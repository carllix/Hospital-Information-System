@extends('layouts.dashboard')

@section('title', 'Monitoring Pasien')

@section('content')
<div class="space-y-6">
    
    {{-- Header & Back Button --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ url()->previous() }}" class="p-2 bg-white border border-gray-200 rounded-lg text-gray-600 hover:bg-gray-50 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Monitoring Vital Sign</h1>
                <div class="flex items-center gap-2 mt-1">
                    <span class="text-sm text-gray-500">Pasien:</span>
                    <span class="text-sm font-bold text-gray-900">{{ $pasien->nama_lengkap }}</span>
                    <span class="text-xs text-gray-400">|</span>
                    <span class="text-sm text-gray-500 font-mono">{{ $pasien->no_rm }}</span>
                </div>
            </div>
        </div>

        {{-- Status Connection --}}
        <div class="flex items-center gap-2 bg-white px-4 py-2 rounded-lg border border-gray-200 shadow-sm">
            <div class="relative flex h-3 w-3">
                @if($latestData)
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                @else
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-gray-400"></span>
                @endif
            </div>
            <span class="text-xs font-medium {{ $latestData ? 'text-green-700' : 'text-gray-500' }}">
                {{ $latestData ? 'Device Terhubung' : 'Device Offline' }}
            </span>
        </div>
    </div>

    @if(!$latestData)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900">Tidak Ada Data Wearable</h3>
            <p class="text-gray-500 mt-2">Pasien ini belum menghubungkan wearable device atau belum ada data yang terekam.</p>
        </div>
    @else

        {{-- Real-time Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- Heart Rate --}}
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200 relative overflow-hidden">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Heart Rate</p>
                        <div class="flex items-baseline mt-2">
                            <h3 class="text-4xl font-bold text-gray-900" id="heart-rate-value">{{ $latestData->heart_rate }}</h3>
                            <span class="text-sm text-gray-500 ml-2 font-medium">BPM</span>
                        </div>
                    </div>
                    <div class="p-3 bg-red-50 rounded-lg text-red-500">
                        <svg class="w-6 h-6 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                    </div>
                </div>
                <div class="mt-4 flex items-center">
                    <span id="heart-rate-status" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $latestData->heart_rate >= 60 && $latestData->heart_rate <= 100 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $latestData->heart_rate >= 60 && $latestData->heart_rate <= 100 ? 'Normal' : 'Abnormal' }}
                    </span>
                    <span class="text-xs text-gray-400 ml-auto">Ref: 60-100 BPM</span>
                </div>
            </div>

            {{-- SpO2 --}}
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200 relative overflow-hidden">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Oxygen Saturation</p>
                        <div class="flex items-baseline mt-2">
                            <h3 class="text-4xl font-bold text-gray-900" id="spo2-value">{{ $latestData->oxygen_saturation }}</h3>
                            <span class="text-sm text-gray-500 ml-2 font-medium">%</span>
                        </div>
                    </div>
                    <div class="p-3 bg-blue-50 rounded-lg text-blue-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                    </div>
                </div>
                <div class="mt-4 flex items-center">
                    <span id="spo2-status" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $latestData->oxygen_saturation >= 95 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $latestData->oxygen_saturation >= 95 ? 'Normal' : 'Rendah' }}
                    </span>
                    <span class="text-xs text-gray-400 ml-auto">Ref: ≥95%</span>
                </div>
            </div>

            {{-- Last Updated --}}
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200 relative overflow-hidden">
                <div class="flex items-center gap-3 mb-4">
                    <div class="p-2 bg-gray-100 rounded-lg text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <span class="text-sm font-medium text-gray-500">Last Update</span>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900" id="last-updated">{{ $latestData->timestamp->translatedFormat('j F Y') }}</h3>
                    <p class="text-gray-500 font-mono" id="last-updated-time">{{ $latestData->timestamp->translatedFormat('H:i:s') }} WIB</p>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <p class="text-xs text-gray-400">Data diperbarui secara real-time</p>
                </div>
            </div>
        </div>

        {{-- Charts --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
                <h3 class="text-base font-bold text-gray-900 mb-4">Grafik Heart Rate</h3>
                <div class="h-64">
                    <canvas id="heartRateChart"></canvas>
                </div>
            </div>
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
                <h3 class="text-base font-bold text-gray-900 mb-4">Grafik Oxygen Saturation</h3>
                <div class="h-64">
                    <canvas id="spo2Chart"></canvas>
                </div>
            </div>
        </div>

        {{-- Historical Table --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="text-base font-bold text-gray-900">Riwayat Data Terakhir</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50/50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Waktu</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Heart Rate</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">SpO2</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kondisi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @foreach($historicalData as $data)
                        <tr class="hover:bg-gray-50/50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-mono">
                                {{ $data->timestamp->translatedFormat('j M Y, H:i:s') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="font-medium {{ $data->heart_rate >= 60 && $data->heart_rate <= 100 ? 'text-gray-900' : 'text-red-600' }}">
                                    {{ $data->heart_rate }} BPM
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="font-medium {{ $data->oxygen_saturation >= 95 ? 'text-gray-900' : 'text-red-600' }}">
                                    {{ $data->oxygen_saturation }}%
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($data->heart_rate >= 60 && $data->heart_rate <= 100 && $data->oxygen_saturation >= 95)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                        Normal
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                        Perlu Perhatian
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Scripts --}}
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
        <script>
            // Data from Controller
            const chartData = @json($chartData);

            const timestamps = chartData.map(item => {
                const date = new Date(item.timestamp);
                return date.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
            });
            const heartRates = chartData.map(item => item.heart_rate);
            const oxygenSaturations = chartData.map(item => item.oxygen_saturation);

            // Chart Configs
            const commonOptions = {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: { grid: { display: false } },
                    y: { border: { dash: [4, 4] }, grid: { color: '#f3f4f6' } }
                },
                plugins: { legend: { display: false } },
                interaction: { intersect: false, mode: 'index' },
            };

            // Heart Rate Chart
            new Chart(document.getElementById('heartRateChart').getContext('2d'), {
                type: 'line',
                data: {
                    labels: timestamps,
                    datasets: [{
                        label: 'Heart Rate',
                        data: heartRates,
                        borderColor: '#ef4444', // Red-500
                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 0
                    }]
                },
                options: commonOptions
            });

            // SpO2 Chart
            new Chart(document.getElementById('spo2Chart').getContext('2d'), {
                type: 'line',
                data: {
                    labels: timestamps,
                    datasets: [{
                        label: 'SpO2',
                        data: oxygenSaturations,
                        borderColor: '#3b82f6', // Blue-500
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 0
                    }]
                },
                options: {
                    ...commonOptions,
                    scales: {
                        ...commonOptions.scales,
                        y: { ...commonOptions.scales.y, min: 80, max: 100 } // SpO2 scale tweak
                    }
                }
            });

            // Auto Refresh Logic (Simple version)
            // Function to update current value display via AJAX
            function updateRealtimeData() {
                fetch('{{ route("dokter.monitoring.data", $pasien->pasien_id) }}')
                    .then(response => response.json())
                    .then(data => {
                        if(data.success) {
                            // Update Text
                            document.getElementById('heart-rate-value').innerText = data.data.heart_rate;
                            document.getElementById('spo2-value').innerText = data.data.oxygen_saturation;
                            document.getElementById('last-updated').innerText = new Date(data.data.timestamp).toLocaleDateString('id-ID', {day: 'numeric', month: 'long', year: 'numeric'});
                            document.getElementById('last-updated-time').innerText = new Date(data.data.timestamp).toLocaleTimeString('id-ID') + ' WIB';
                            
                            // Update Styles (Classes) based on thresholds could be done here if needed
                        }
                    });
            }

            // Refresh every 5 seconds
            setInterval(updateRealtimeData, 5000);
        </script>
    @endif
</div>
@endsection