@extends('layouts.dashboard')

@section('title', 'Health Monitoring | Ganesha Hospital')

@section('content')
<div class="max-w-7xl">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Health Monitoring</h2>
        <p class="text-gray-600 mt-1">Pantau kondisi kesehatan Anda secara real-time</p>
    </div>

    <!-- Real-time Monitoring Controls -->
    <div class="flex justify-between items-center mb-4">
        <div class="flex items-center space-x-4">
            <!-- Connection Status -->
            <div id="connection-status" class="flex items-center">
                <div class="w-3 h-3 bg-gray-400 rounded-full mr-2"></div>
                <span class="text-sm text-gray-600">Disconnected</span>
            </div>
            
            <!-- Session Countdown -->
            <div id="countdown-info" class="text-sm font-mono hidden">
                <span class="text-blue-600" id="countdown-timer">10</span><span class="text-gray-600">s remaining</span>
            </div>
        </div>

        <div class="flex space-x-2">
            <!-- Real-time Monitor Button (10 detik) -->
            <button id="realtime-monitor-btn" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg flex items-center transition duration-300">
                <svg id="monitor-icon" class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                </svg>
                <span id="monitor-text">Real-time Monitor (10s)</span>
            </button>
        </div>
    </div>

    <!-- Real-time Status Alert dengan Countdown -->
    <div id="realtime-alert" class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6 hidden">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <svg class="w-6 h-6 text-blue-600 mr-3 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
                <div>
                    <h3 class="text-blue-800 font-semibold">Real-time Monitoring Active</h3>
                    <p class="text-blue-700 text-sm mt-1">Mengambil data langsung dari sensor selama <span id="session-countdown" class="font-bold">10</span> detik</p>
                </div>
            </div>
            <!-- Progress Bar -->
            <div class="w-24 bg-blue-200 rounded-full h-2">
                <div id="progress-bar" class="bg-blue-600 h-2 rounded-full transition-all duration-1000" style="width: 100%"></div>
            </div>
        </div>
    </div>

    <!-- Debug Info Panel (Hidden by default) -->
    <div id="debug-panel" class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-6 hidden">
        <div class="flex justify-between items-center mb-2">
            <h3 class="text-gray-800 font-semibold">Debug Information</h3>
            <button id="close-debug" class="text-gray-500 hover:text-gray-700">✕</button>
        </div>
        <pre id="debug-content" class="text-xs text-gray-700 bg-white p-3 rounded border overflow-auto max-h-48">Loading...</pre>
    </div>

    @if(!$latestData)
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
        <div class="flex items-center">
            <svg class="w-6 h-6 text-yellow-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
            <div>
                <h3 class="text-yellow-800 font-semibold">Tidak Ada Data Wearable Device</h3>
                <p class="text-yellow-700 text-sm mt-1">Hubungkan wearable device dan jalankan Bridge.js untuk mulai real-time monitoring.</p>
            </div>
        </div>
    </div>
    @else
    <!-- Real-time Metrics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <!-- Heart Rate Card -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="p-3 bg-red-100 rounded-lg">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                    </div>
                </div>
                <span id="heart-rate-status" class="text-sm font-medium {{ $latestData->heart_rate >= 60 && $latestData->heart_rate <= 100 ? 'text-green-600' : 'text-red-600' }}">
                    {{ $latestData->heart_rate >= 60 && $latestData->heart_rate <= 100 ? 'Normal' : 'Abnormal' }}
                </span>
            </div>
            <div>
                <p class="text-gray-600 text-sm mb-1">Heart Rate</p>
                <div class="flex items-baseline">
                    <h3 id="heart-rate-value" class="text-4xl font-bold text-gray-800">{{ $latestData->heart_rate }}</h3>
                    <span class="text-gray-600 ml-2">BPM</span>
                </div>
                <p class="text-xs text-gray-500 mt-2">Normal: 60-100 BPM</p>
            </div>
        </div>

        <!-- Oxygen Saturation Card -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m2-1l-2-1m2 1v2.5M14 4l-2-1-2 1M4 7l2-1M4 7l2 1M4 7v2.5M12 21l-2-1m2 1l2-1m-2 1v-2.5M6 18l-2-1v-2.5M18 18l2-1v-2.5"></path>
                        </svg>
                    </div>
                </div>
                <span id="spo2-status" class="text-sm font-medium {{ $latestData->oxygen_saturation >= 95 ? 'text-green-600' : 'text-red-600' }}">
                    {{ $latestData->oxygen_saturation >= 95 ? 'Normal' : 'Low' }}
                </span>
            </div>
            <div>
                <p class="text-gray-600 text-sm mb-1">Oxygen Saturation</p>
                <div class="flex items-baseline">
                    <h3 id="spo2-value" class="text-4xl font-bold text-gray-800">{{ $latestData->oxygen_saturation }}</h3>
                    <span class="text-gray-600 ml-2">%</span>
                </div>
                <p class="text-xs text-gray-500 mt-2">Normal: ≥95%</p>
            </div>
        </div>

        <!-- Last Updated Card -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center mb-4">
                <div class="p-3 bg-gray-100 rounded-lg">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <div>
                <p class="text-gray-600 text-sm mb-1">Last Updated</p>
                <h3 id="last-updated" class="text-lg font-semibold text-gray-800">{{ $latestData->timestamp->translatedFormat('j F Y') }}</h3>
                <p class="text-sm text-gray-600 mt-1" id="last-updated-time">{{ $latestData->timestamp->translatedFormat('H:i') }} WIB</p>
                <div class="flex items-center mt-2">
                    <div id="live-indicator" class="w-2 h-2 bg-gray-400 rounded-full mr-2"></div>
                    <p id="live-status" class="text-xs text-gray-600 font-medium">Offline</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Heart Rate Chart -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Heart Rate Trend</h3>
            <div style="height: 200px;">
                <canvas id="heartRateChart"></canvas>
            </div>
        </div>

        <!-- SpO2 Chart -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Oxygen Saturation Trend</h3>
            <div style="height: 200px;">
                <canvas id="spo2Chart"></canvas>
            </div>
        </div>
    </div>

    <!-- Historical Data Table -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Riwayat Pembacaan</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Heart Rate</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SpO2</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                    </tr>
                </thead>
                <tbody id="history-table-body" class="bg-white divide-y divide-gray-200">
                    @forelse($historicalData as $data)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $data->timestamp->translatedFormat('j M Y, H:i:s') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-medium {{ $data->heart_rate >= 60 && $data->heart_rate <= 100 ? 'text-gray-900' : 'text-red-600' }}">
                                {{ $data->heart_rate }} BPM
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-medium {{ $data->oxygen_saturation >= 95 ? 'text-gray-900' : 'text-red-600' }}">
                                {{ $data->oxygen_saturation }}%
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($data->heart_rate >= 60 && $data->heart_rate <= 100 && $data->oxygen_saturation >= 95)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Normal
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    Perlu Perhatian
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-medium rounded-full bg-gray-100 text-gray-800">
                            @if(strpos($data->device_id, 'REALTIME') !== false || strpos($data->device_id, 'SESSION') !== false)
                                <span class="px-2 inline-flex text-xs leading-5 font-medium rounded-full bg-blue-100 text-blue-800">
                                    Real-time Session
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-medium rounded-full bg-gray-100 text-gray-800">
                                    Quick Exam
                                </span>
                            @endif
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                            Tidak ada data historis
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>

@if($latestData)
<!-- Chart.js Library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
    // Updated JavaScript untuk session-based monitoring
    let isSessionActive = false;
    let sessionProgressInterval;
    let heartRateChart, spo2Chart;
    let countdownInterval;
    let remainingTime = 10;

    // Prepare initial chart data
    const chartData = @json($chartData);
    let chartTimestamps = chartData.map(item => {
        const date = new Date(item.timestamp);
        return date.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
    });
    let chartHeartRates = chartData.map(item => item.heart_rate);
    let chartOxygenSaturations = chartData.map(item => item.oxygen_saturation);

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        initializeCharts();
        
        // Event listeners
        document.getElementById('realtime-monitor-btn').addEventListener('click', toggleSessionMonitoring);
        document.getElementById('start-exam').addEventListener('click', startQuickExam);
        document.getElementById('debug-btn').addEventListener('click', showDebugInfo);
        document.getElementById('close-debug').addEventListener('click', hideDebugInfo);
    });

    function initializeCharts() {
        // Heart Rate Chart
        const heartRateCtx = document.getElementById('heartRateChart').getContext('2d');
        heartRateChart = new Chart(heartRateCtx, {
            type: 'line',
            data: {
                labels: chartTimestamps,
                datasets: [{
                    label: 'Heart Rate (BPM)',
                    data: chartHeartRates,
                    borderColor: '#dc2626',
                    backgroundColor: 'rgba(220, 38, 38, 0.1)',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true,
                    pointRadius: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                animation: { duration: 0 },
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: false, min: 40, max: 120 },
                    x: { ticks: { maxTicksLimit: 8 } }
                }
            }
        });

        // SpO2 Chart
        const spo2Ctx = document.getElementById('spo2Chart').getContext('2d');
        spo2Chart = new Chart(spo2Ctx, {
            type: 'line',
            data: {
                labels: chartTimestamps,
                datasets: [{
                    label: 'Oxygen Saturation (%)',
                    data: chartOxygenSaturations,
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37, 99, 235, 0.1)',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true,
                    pointRadius: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                animation: { duration: 0 },
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: false, min: 85, max: 100 },
                    x: { ticks: { maxTicksLimit: 8 } }
                }
            }
        });
    }

    async function toggleSessionMonitoring() {
        const btn = document.getElementById('realtime-monitor-btn');
        const text = document.getElementById('monitor-text');
        
        if (!isSessionActive) {
            // Start session
            btn.disabled = true;
            text.textContent = 'Starting Session...';
            
            try {
                const response = await fetch('{{ route("pasien.monitoring.start") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });
                
                const result = await response.json();
                console.log('Start session response:', result);
                
                if (result.success) {
                    startSessionUI();
                    showNotification('Monitoring session started - Collecting data for 10 seconds...', 'info');
                } else {
                    throw new Error(result.message || 'Unknown error');
                }
            } catch (error) {
                console.error('Start session error:', error);
                showNotification('Failed to start session: ' + error.message, 'error');
                btn.disabled = false;
                text.textContent = 'Real-time Monitor (10s)';
            }
        } else {
            // Stop session manually
            try {
                const response = await fetch('{{ route("pasien.monitoring.stop") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });
                
                stopSessionUI();
                showNotification('Session stopped manually', 'info');
            } catch (error) {
                console.error('Stop session error:', error);
                showNotification('Error stopping session', 'error');
            }
        }
    }

    function startSessionUI() {
        isSessionActive = true;
        remainingTime = 10;
        
        const btn = document.getElementById('realtime-monitor-btn');
        const text = document.getElementById('monitor-text');
        const alert = document.getElementById('realtime-alert');
        const countdownInfo = document.getElementById('countdown-info');
        
        // Update button
        btn.className = 'bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded-lg flex items-center transition duration-300';
        text.textContent = 'Collecting Data...';
        btn.disabled = false;
        
        // Show alert and countdown
        alert.classList.remove('hidden');
        countdownInfo.classList.remove('hidden');
        
        // Update connection status
        updateConnectionStatus('session-active');
        
        // Start session progress monitoring
        startSessionProgress();
        
        // Start countdown
        startCountdown();
    }

    function stopSessionUI() {
        isSessionActive = false;
        
        const btn = document.getElementById('realtime-monitor-btn');
        const text = document.getElementById('monitor-text');
        const alert = document.getElementById('realtime-alert');
        const countdownInfo = document.getElementById('countdown-info');
        
        // Update button
        btn.className = 'bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg flex items-center transition duration-300';
        text.textContent = 'Real-time Monitor (10s)';
        btn.disabled = false;
        
        // Hide alert and countdown
        alert.classList.add('hidden');
        countdownInfo.classList.add('hidden');
        
        // Update connection status
        updateConnectionStatus('processing');
        
        // Stop intervals
        if (sessionProgressInterval) {
            clearInterval(sessionProgressInterval);
        }
        if (countdownInterval) {
            clearInterval(countdownInterval);
        }
        
        // Wait for final results and refresh
        showProcessingMessage();
        setTimeout(checkSessionResults, 2000);
    }

    function startSessionProgress() {
        sessionProgressInterval = setInterval(async () => {
            try {
                const response = await fetch('{{ route("pasien.monitoring.progress") }}');
                const result = await response.json();
                
                if (result.success) {
                    if (result.active && result.status === 'collecting') {
                        updateConnectionStatus('collecting');
                        console.log(`Session progress: ${result.progress_percentage.toFixed(1)}% - ${result.remaining_seconds}s remaining`);
                    } else if (result.status === 'processing') {
                        updateConnectionStatus('processing');
                        // Session ended, results being processed
                        setTimeout(() => {
                            stopSessionUI();
                        }, 1000);
                    }
                }
            } catch (error) {
                console.error('Error checking session progress:', error);
            }
        }, 1000);
    }

    function startCountdown() {
        const countdownTimer = document.getElementById('countdown-timer');
        const sessionCountdown = document.getElementById('session-countdown');
        const progressBar = document.getElementById('progress-bar');
        
        countdownInterval = setInterval(() => {
            remainingTime--;
            
            // Update countdown displays
            countdownTimer.textContent = remainingTime;
            sessionCountdown.textContent = remainingTime;
            
            // Update progress bar
            const progress = (remainingTime / 10) * 100;
            progressBar.style.width = progress + '%';
            
            // Change colors as time runs out
            if (remainingTime <= 3) {
                progressBar.className = 'bg-red-600 h-2 rounded-full transition-all duration-1000';
                countdownTimer.className = 'text-red-600';
            } else if (remainingTime <= 5) {
                progressBar.className = 'bg-yellow-600 h-2 rounded-full transition-all duration-1000';
                countdownTimer.className = 'text-yellow-600';
            }
            
            if (remainingTime <= 0) {
                clearInterval(countdownInterval);
                
                // Update UI to show processing
                countdownTimer.textContent = 'Processing...';
                sessionCountdown.textContent = 'Processing results';
                progressBar.style.width = '100%';
                progressBar.className = 'bg-blue-600 h-2 rounded-full transition-all duration-1000 animate-pulse';
                
                updateConnectionStatus('processing');
                
                // Auto stop session UI after processing
                setTimeout(() => {
                    stopSessionUI();
                    showNotification('10-second data collection completed! Processing results...', 'success');
                }, 2000);
            }
        }, 1000);
    }

    function showProcessingMessage() {
        const alert = document.getElementById('realtime-alert');
        const alertContent = alert.querySelector('div').querySelector('div');
        
        alert.className = 'bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6';
        alertContent.innerHTML = `
            <div class="flex items-center">
                <svg class="w-6 h-6 text-yellow-600 mr-3 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                <div>
                    <h3 class="text-yellow-800 font-semibold">Processing Session Results</h3>
                    <p class="text-yellow-700 text-sm mt-1">Menganalisis semua data dari sesi 10 detik dan menghitung hasil akhir...</p>
                </div>
            </div>
        `;
        
        alert.classList.remove('hidden');
    }

    async function checkSessionResults() {
        try {
            const response = await fetch('{{ route("pasien.monitoring.result") }}');
            const result = await response.json();
            
            if (result.success && result.data) {
                displaySessionResults(result.data);
                showNotification('Session results ready! Check the detailed analysis below.', 'success');
            } else {
                showNotification('Session completed - Refresh page to see results', 'info');
                setTimeout(() => location.reload(), 3000);
            }
        } catch (error) {
            console.error('Error getting session results:', error);
            showNotification('Session completed - Refresh page to see results', 'info');
            setTimeout(() => location.reload(), 3000);
        }
    }

    function displaySessionResults(sessionData) {
        // Update vital cards dengan hasil akhir
        updateVitalCards(sessionData.heart_rate, sessionData.oxygen_saturation);
        
        // Display session info
        const sessionInfo = sessionData.session_info;
        if (sessionInfo) {
            // Show detailed session analysis
            showSessionAnalysis(sessionInfo);
            
            // Add result to charts
            addSessionResultToCharts(sessionData);
        }
        
        // Hide processing alert
        const alert = document.getElementById('realtime-alert');
        alert.classList.add('hidden');
        
        // Update connection status
        updateConnectionStatus('completed');
        
        // Auto-refresh in 5 seconds to show in history table
        setTimeout(() => location.reload(), 5000);
    }

    function showSessionAnalysis(sessionInfo) {
        // Create or update session analysis panel
        let analysisPanel = document.getElementById('session-analysis');
        if (!analysisPanel) {
            analysisPanel = document.createElement('div');
            analysisPanel.id = 'session-analysis';
            analysisPanel.className = 'bg-green-50 border border-green-200 rounded-lg p-6 mb-6';
            
            // Insert before charts
            const chartsContainer = document.querySelector('.grid.grid-cols-1.lg\\:grid-cols-2');
            chartsContainer.parentNode.insertBefore(analysisPanel, chartsContainer);
        }
        
        const hrRange = sessionInfo.heart_rate_range;
        const spo2Range = sessionInfo.spo2_range;
        
        analysisPanel.innerHTML = `
            <div class="flex items-start justify-between mb-4">
                <div class="flex items-center">
                    <svg class="w-8 h-8 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <h3 class="text-green-800 font-bold text-lg">Session Analysis Complete</h3>
                        <p class="text-green-700 text-sm">Processed ${sessionInfo.data_points_processed} data points over ${sessionInfo.duration_seconds} seconds</p>
                    </div>
                </div>
                <div class="text-right">
                    <span class="px-3 py-1 text-sm font-medium rounded-full ${
                        sessionInfo.confidence_level === 'High' ? 'bg-green-100 text-green-800' :
                        sessionInfo.confidence_level === 'Medium' ? 'bg-yellow-100 text-yellow-800' :
                        'bg-red-100 text-red-800'
                    }">
                        ${sessionInfo.confidence_level} Confidence
                    </span>
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-6">
                <div class="bg-white rounded-lg p-4 border border-green-100">
                    <h4 class="text-green-800 font-semibold mb-2">Heart Rate Analysis</h4>
                    <div class="space-y-1 text-sm">
                        <div class="flex justify-between"><span class="text-gray-600">Average:</span><span class="font-medium">${hrRange.avg} BPM</span></div>
                        <div class="flex justify-between"><span class="text-gray-600">Range:</span><span class="font-medium">${hrRange.min} - ${hrRange.max} BPM</span></div>
                        <div class="flex justify-between"><span class="text-gray-600">Variability:</span><span class="font-medium">±${hrRange.variability.toFixed(1)} BPM</span></div>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg p-4 border border-green-100">
                    <h4 class="text-green-800 font-semibold mb-2">SpO2 Analysis</h4>
                    <div class="space-y-1 text-sm">
                        <div class="flex justify-between"><span class="text-gray-600">Average:</span><span class="font-medium">${spo2Range.avg}%</span></div>
                        <div class="flex justify-between"><span class="text-gray-600">Range:</span><span class="font-medium">${spo2Range.min}% - ${spo2Range.max}%</span></div>
                        <div class="flex justify-between"><span class="text-gray-600">Variability:</span><span class="font-medium">±${spo2Range.variability.toFixed(1)}%</span></div>
                    </div>
                </div>
            </div>
        `;
        
        analysisPanel.classList.remove('hidden');
    }

    function addSessionResultToCharts(sessionData) {
        const timeLabel = new Date().toLocaleTimeString('id-ID', { 
            hour: '2-digit', 
            minute: '2-digit', 
            second: '2-digit' 
        });
        
        // Add new data
        heartRateChart.data.labels.push(timeLabel);
        heartRateChart.data.datasets[0].data.push(sessionData.heart_rate);
        
        spo2Chart.data.labels.push(timeLabel);
        spo2Chart.data.datasets[0].data.push(sessionData.oxygen_saturation);
        
        // Keep only last 20 points for real-time view
        if (heartRateChart.data.labels.length > 20) {
            heartRateChart.data.labels.shift();
            heartRateChart.data.datasets[0].data.shift();
            spo2Chart.data.labels.shift();
            spo2Chart.data.datasets[0].data.shift();
        }
        
        // Update charts
        heartRateChart.update('none');
        spo2Chart.update('none');
    }

    function updateConnectionStatus(status) {
        const statusDiv = document.getElementById('connection-status');
        const indicator = statusDiv.querySelector('div');
        const text = statusDiv.querySelector('span');
        const liveIndicator = document.getElementById('live-indicator');
        const liveStatus = document.getElementById('live-status');
        
        switch (status) {
            case 'session-active':
                indicator.className = 'w-3 h-3 bg-blue-500 rounded-full mr-2 animate-pulse';
                text.textContent = 'Session Active';
                liveIndicator.className = 'w-2 h-2 bg-blue-500 rounded-full mr-2 animate-pulse';
                liveStatus.textContent = 'Session Active';
                liveStatus.className = 'text-xs text-blue-600 font-medium';
                break;
            case 'collecting':
                indicator.className = 'w-3 h-3 bg-green-500 rounded-full mr-2 animate-pulse';
                text.textContent = 'Collecting Data';
                liveIndicator.className = 'w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse';
                liveStatus.textContent = 'Collecting';
                liveStatus.className = 'text-xs text-green-600 font-medium';
                break;
            case 'processing':
                indicator.className = 'w-3 h-3 bg-yellow-500 rounded-full mr-2 animate-pulse';
                text.textContent = 'Processing Results';
                liveIndicator.className = 'w-2 h-2 bg-yellow-500 rounded-full mr-2 animate-pulse';
                liveStatus.textContent = 'Processing';
                liveStatus.className = 'text-xs text-yellow-600 font-medium';
                break;
            case 'completed':
                indicator.className = 'w-3 h-3 bg-green-600 rounded-full mr-2';
                text.textContent = 'Session Complete';
                liveIndicator.className = 'w-2 h-2 bg-green-600 rounded-full mr-2';
                liveStatus.textContent = 'Complete';
                liveStatus.className = 'text-xs text-green-600 font-medium';
                break;
            default:
                indicator.className = 'w-3 h-3 bg-gray-400 rounded-full mr-2';
                text.textContent = 'Disconnected';
                liveIndicator.className = 'w-2 h-2 bg-gray-400 rounded-full mr-2';
                liveStatus.textContent = 'Offline';
                liveStatus.className = 'text-xs text-gray-600 font-medium';
        }
    }

    function updateVitalCards(heartRate, spO2) {
        document.getElementById('heart-rate-value').textContent = heartRate;
        document.getElementById('spo2-value').textContent = spO2;
        document.getElementById('last-updated-time').textContent = new Date().toLocaleTimeString('id-ID') + ' WIB';
        
        const hrStatus = document.getElementById('heart-rate-status');
        const spo2Status = document.getElementById('spo2-status');
        
        if (heartRate >= 60 && heartRate <= 100) {
            hrStatus.textContent = 'Normal';
            hrStatus.className = 'text-sm font-medium text-green-600';
        } else {
            hrStatus.textContent = 'Abnormal';
            hrStatus.className = 'text-sm font-medium text-red-600';
        }
        
        if (spO2 >= 95) {
            spo2Status.textContent = 'Normal';
            spo2Status.className = 'text-sm font-medium text-green-600';
        } else {
            spo2Status.textContent = 'Low';
            spo2Status.className = 'text-sm font-medium text-red-600';
        }
    }

    // Debug functions
    async function showDebugInfo() {
        const debugPanel = document.getElementById('debug-panel');
        const debugContent = document.getElementById('debug-content');
        
        debugPanel.classList.remove('hidden');
        debugContent.textContent = 'Loading debug info...';
        
        try {
            const response = await fetch('{{ route("pasien.monitoring.debug") }}');
            const result = await response.json();
            debugContent.textContent = JSON.stringify(result, null, 2);
        } catch (error) {
            debugContent.textContent = 'Error loading debug info: ' + error.message;
        }
    }

    function hideDebugInfo() {
        document.getElementById('debug-panel').classList.add('hidden');
    }

    // Quick Exam functionality (existing)
    function startQuickExam() {
        let countdown = 10;
        const startBtn = document.getElementById('start-exam');
        const examText = document.getElementById('exam-text');
        
        startBtn.disabled = true;
        startBtn.classList.add('opacity-50', 'cursor-not-allowed');
        
        const examInterval = setInterval(function() {
            if (countdown <= 0) {
                clearInterval(examInterval);
                examText.textContent = "Pemeriksaan Selesai";
                setTimeout(() => location.reload(), 1000);
                return;
            }
            
            examText.textContent = `Mengambil Data... (${countdown}s)`;
            countdown--;
        }, 1000);
    }

    function showNotification(message, type = 'info') {
        const colors = {
            success: 'bg-green-100 border-green-500 text-green-700',
            error: 'bg-red-100 border-red-500 text-red-700',
            info: 'bg-blue-100 border-blue-500 text-blue-700'
        };
        
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 ${colors[type]} border px-4 py-2 rounded-lg shadow-lg z-50`;
        notification.textContent = message;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.remove();
        }, 5000);
    }
</script>
@endif
@endsection