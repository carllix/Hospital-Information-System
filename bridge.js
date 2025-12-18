import { SerialPort } from 'serialport';
import { ReadlineParser } from '@serialport/parser-readline';
import axios from 'axios';
import fs from 'fs';

// --- KONFIGURASI ---
const SERIAL_PATH = 'COM3';
const BAUD_RATE = 115200;
const LARAVEL_URL = 'http://localhost:8000';

const MONITORING_FILES = [
    './monitoring_status.json',
    '../public/monitoring_status.json',
    './public/monitoring_status.json',
    '../monitoring_status.json'
];

// Session-based variables
let isRealTimeMode = false;
let currentPasienId = null;
let sessionStartTime = null;
let sessionTimer = null;
let sessionData = []; // Kumpulkan semua data dalam sesi
let rawDataBuffer = [];
let lastValidBPM = 70;
let lastValidSpO2 = 98;

const port = new SerialPort({ path: SERIAL_PATH, baudRate: BAUD_RATE });
const parser = port.pipe(new ReadlineParser({ delimiter: '\r\n' }));

console.log(`Bridge.js started - Connecting to ${SERIAL_PATH}...`);

setInterval(checkMonitoringStatus, 1000);

parser.on('data', (line) => {
    console.log(`Raw serial data: "${line}"`);
    if (line.includes(',')) {
        const parts = line.split(',');
        if (parts.length >= 2) {
            const red = parseInt(parts[0].trim());
            const ir = parseInt(parts[1].trim());
            
            console.log(`Parsed - RED: ${red}, IR: ${ir}, Mode: ${isRealTimeMode ? 'SESSION' : 'DUMMY'}`);
            
            if (isRealTimeMode && currentPasienId) {
                collectSessionData(red, ir);
            } else {
                console.log(`Not in session mode, ignoring data`);
            }
        }
    }
});

function checkMonitoringStatus() {
    let statusFound = false;
    
    for (const filePath of MONITORING_FILES) {
        try {
            if (fs.existsSync(filePath)) {
                const status = JSON.parse(fs.readFileSync(filePath, 'utf8'));
                statusFound = true;
                
                console.log(`Found monitoring status: ${filePath}`, status);
                
                if (status.active && status.pasien_id) {
                    if (!isRealTimeMode) {
                        startMonitoringSession(status.pasien_id, filePath);
                    }
                } else {
                    if (isRealTimeMode) {
                        endMonitoringSession();
                    }
                }
                break;
            }
        } catch (err) {
            // Continue to next file
        }
    }
    
    if (!statusFound && isRealTimeMode) {
        console.log('No monitoring status file found, ending session');
        endMonitoringSession();
    }
}

function startMonitoringSession(pasienId, statusFile) {
    console.log(`Starting monitoring SESSION for Patient ID: ${pasienId}`);
    isRealTimeMode = true;
    currentPasienId = pasienId;
    sessionData = [];
    rawDataBuffer = [];
    sessionStartTime = Date.now();
    
    // Timer 10 detik - akan process dan kirim hasil akhir
    sessionTimer = setTimeout(() => {
        console.log('10-second session completed, processing final results...');
        
        try {
            fs.unlinkSync(statusFile);
            console.log(`Deleted monitoring status file: ${statusFile}`);
        } catch (err) {
            console.log(`Failed to delete status file: ${err.message}`);
        }
        
        processAndSendSessionResults();
        endMonitoringSession();
    }, 10000);
    
    console.log(`Session will end and process results in 10 seconds`);
}

function endMonitoringSession() {
    console.log('Ending monitoring session');
    isRealTimeMode = false;
    currentPasienId = null;
    sessionData = [];
    rawDataBuffer = [];
    sessionStartTime = null;
    
    if (sessionTimer) {
        clearTimeout(sessionTimer);
        sessionTimer = null;
    }
}

// Collect session data
function collectSessionData(red, ir) {
    const sessionDuration = sessionStartTime ? (Date.now() - sessionStartTime) / 1000 : 0;
    console.log(`Collecting session data [${sessionDuration.toFixed(1)}s] - RED: ${red}, IR: ${ir}`);
    
    // Skip jika sinyal tidak bagus
    if (ir < 30000) {
        console.log(`IR signal too low (${ir}), skipping data point`);
        return;
    }
    
    console.log(`Good signal, adding to session buffer`);
    
    rawDataBuffer.push({ red, ir, timestamp: Date.now() });
    
    if (rawDataBuffer.length > 150) {
        rawDataBuffer = rawDataBuffer.slice(-150);
    }
    
    // Setiap 20 data points, hitung vitals sementara dan simpan ke sessionData
    if (rawDataBuffer.length >= 20 && rawDataBuffer.length % 10 === 0) {
        const vitals = calculateVitals(rawDataBuffer);
        
        // Simpan ke session data dengan timestamp
        sessionData.push({
            timestamp: Date.now(),
            heart_rate: vitals.bpm,
            oxygen_saturation: vitals.spo2,
            raw_data_count: rawDataBuffer.length
        });
        
        console.log(`Session data point added - BPM: ${vitals.bpm}, SpO2: ${vitals.spo2}% (Total points: ${sessionData.length})`);
    }
}

// BARU: Process semua data dalam session dan kirim hasil akhir
function processAndSendSessionResults() {
    console.log(`Processing ${sessionData.length} session data points for final results`);
    
    if (sessionData.length === 0) {
        console.log(`No session data collected, cannot send results`);
        return;
    }
    
    // Analisis semua data dalam session
    const heartRates = sessionData.map(d => d.heart_rate);
    const spO2Values = sessionData.map(d => d.oxygen_saturation);
    
    // Hitung statistik final
    const finalHeartRate = Math.round(calculateAverage(heartRates));
    const finalSpO2 = Math.round(calculateAverage(spO2Values));
    
    // Hitung variabilitas dan confidence
    const hrVariability = calculateStandardDeviation(heartRates);
    const spO2Variability = calculateStandardDeviation(spO2Values);
    
    const sessionDuration = sessionStartTime ? (Date.now() - sessionStartTime) / 1000 : 0;
    
    const finalResults = {
        heart_rate: finalHeartRate,
        oxygen_saturation: finalSpO2,
        device_id: 'MAX30102_SESSION',
        pasien_id: currentPasienId,
        session_info: {
            duration_seconds: Math.round(sessionDuration),
            data_points: sessionData.length,
            hr_min: Math.min(...heartRates),
            hr_max: Math.max(...heartRates),
            hr_variability: Math.round(hrVariability * 100) / 100,
            spo2_min: Math.min(...spO2Values),
            spo2_max: Math.max(...spO2Values),
            spo2_variability: Math.round(spO2Variability * 100) / 100,
            confidence: calculateConfidence(heartRates, spO2Values)
        }
    };
    
    console.log(`Sending FINAL session results:`, finalResults);
    
    axios.post(`${LARAVEL_URL}/pasien/store-session-results`, finalResults, {
        timeout: 10000,
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then((response) => {
        console.log(`Successfully sent final results: BPM=${finalHeartRate}, SpO2=${finalSpO2}% (${sessionData.length} data points processed)`);
        console.log(`Session stats - HR: ${Math.min(...heartRates)}-${Math.max(...heartRates)}, SpO2: ${Math.min(...spO2Values)}-${Math.max(...spO2Values)}`);
    })
    .catch(err => {
        console.error(`Failed to send final results:`, err.response?.data || err.message);
    });
}

// Helper functions untuk statistik
function calculateAverage(values) {
    return values.reduce((sum, val) => sum + val, 0) / values.length;
}

function calculateStandardDeviation(values) {
    const mean = calculateAverage(values);
    const squaredDiffs = values.map(val => Math.pow(val - mean, 2));
    const variance = calculateAverage(squaredDiffs);
    return Math.sqrt(variance);
}

function calculateConfidence(heartRates, spO2Values) {
    const hrStdev = calculateStandardDeviation(heartRates);
    const spO2Stdev = calculateStandardDeviation(spO2Values);
    
    // Confidence berdasarkan konsistensi data
    let confidence = 'High';
    if (hrStdev > 15 || spO2Stdev > 3) {
        confidence = 'Medium';
    }
    if (hrStdev > 25 || spO2Stdev > 5) {
        confidence = 'Low';
    }
    
    return confidence;
}

function calculateVitals(dataBuffer) {
    const recentData = dataBuffer.slice(-50);
    const irValues = recentData.map(d => d.ir);
    const redValues = recentData.map(d => d.red);
    
    const bpm = calculateBPMSimple(irValues);
    const spo2 = calculateSpO2Simple(redValues, irValues);
    
    return { bpm, spo2 };
}

function calculateBPMSimple(irValues) {
    try {
        // Smoothing yang lebih kuat untuk menghilangkan noise frekuensi tinggi
        const smoothed = movingAverage(irValues, 5); 
        
        // Deteksi Dinamis (Adaptive Threshold)
        const maxVal = Math.max(...smoothed);
        const minVal = Math.min(...smoothed);
        const amplitude = maxVal - minVal;
        
        // Jika variasi sinyal terlalu kecil, anggap tidak ada detak valid
        if (amplitude < 300) return lastValidBPM; 

        const threshold = minVal + (amplitude * 0.55); // Threshold di tengah sinyal
        
        const peaks = [];
        const minDistance = 6; // Jeda antar detak (Refractory Period)

        for (let i = 2; i < smoothed.length - 2; i++) {
            if (smoothed[i] > smoothed[i - 1] && 
                smoothed[i] > smoothed[i + 1] && 
                smoothed[i] > threshold) {
                
                // Pastikan jarak antar puncak tidak terlalu rapat (mencegah BPM > 200)
                if (peaks.length === 0 || (i - peaks[peaks.length - 1]) > minDistance) {
                    peaks.push(i);
                }
            }
        }
        
        if (peaks.length < 2) return lastValidBPM;
        
        // Menghitung interval antar puncak (dalam jumlah sampel)
        const intervals = [];
        for (let i = 1; i < peaks.length; i++) {
            intervals.push(peaks[i] - peaks[i - 1]);
        }
        
        // Menggunakan Median (bukan rata-rata)
        intervals.sort((a, b) => a - b);
        const medianInterval = intervals[Math.floor(intervals.length / 2)];
        
        // Rumus: (60 detik * 100 Hz sampel rate) / interval sampel
        const bpm = Math.round((60 * 100) / medianInterval);
        
        // Batasan hasil yang wajar (60 - 100 BPM)
        if (bpm >= 50 && bpm <= 110) {
            lastValidBPM = Math.round((lastValidBPM * 0.7) + (bpm * 0.3));
            return lastValidBPM;
        } else {
            return lastValidBPM;
        }
    } catch (error) {
        return lastValidBPM;
    }
}

function calculateSpO2Simple(redValues, irValues) {
    try {
        const redAC = Math.sqrt(calculateVariance(redValues));
        const redDC = redValues.reduce((a, b) => a + b, 0) / redValues.length;
        const irAC = Math.sqrt(calculateVariance(irValues));
        const irDC = irValues.reduce((a, b) => a + b, 0) / irValues.length;
        
        if (redDC === 0 || irDC === 0 || irAC === 0 || redAC === 0) {
            return lastValidSpO2;
        }
        
        const R = (redAC / redDC) / (irAC / irDC);
        let spo2 = Math.round(104 - 17 * R);
        
        if (spo2 >= 80 && spo2 <= 100) {
            lastValidSpO2 = spo2;
            return spo2;
        } else {
            return lastValidSpO2;
        }
    } catch (error) {
        return lastValidSpO2;
    }
}

function movingAverage(data, windowSize) {
    const result = [];
    for (let i = 0; i < data.length; i++) {
        const start = Math.max(0, i - Math.floor(windowSize / 2));
        const end = Math.min(data.length - 1, i + Math.floor(windowSize / 2));
        const sum = data.slice(start, end + 1).reduce((a, b) => a + b, 0);
        result.push(sum / (end - start + 1));
    }
    return result;
}

function calculateVariance(values) {
    const mean = values.reduce((a, b) => a + b, 0) / values.length;
    return values.reduce((sum, val) => sum + Math.pow(val - mean, 2), 0) / values.length;
}

// Error handling
port.on('error', (err) => {
    console.error(`Serial port error: ${err.message}`);
});

parser.on('error', (err) => {
    console.error(`Parser error: ${err.message}`);
});

// Graceful shutdown
process.on('SIGINT', () => {
    console.log('\nBridge shutting down...');
    
    for (const filePath of MONITORING_FILES) {
        try {
            if (fs.existsSync(filePath)) {
                fs.unlinkSync(filePath);
                console.log(`Cleaned up: ${filePath}`);
            }
        } catch (err) {
        }
    }
    
    if (sessionTimer) {
        clearTimeout(sessionTimer);
    }
    
    port.close();
    process.exit(0);
});