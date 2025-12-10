@extends('layouts.dashboard')

@section('title', 'Health Monitoring | Ganesha Hospital')

@section('content')
<div class="max-w-7xl">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Health Monitoring</h2>
        <p class="text-gray-600 mt-1">Pantau kondisi kesehatan Anda secara real-time</p>
    </div>

    @if(!$latestData)
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
        <div class="flex items-center">
            <svg class="w-6 h-6 text-yellow-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
            <div>
                <h3 class="text-yellow-800 font-semibold">Tidak Ada Data Wearable Device</h3>
                <p class="text-yellow-700 text-sm mt-1">Hubungkan wearable device Anda untuk mulai memantau kesehatan secara real-time.</p>
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
                    <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse mr-2"></div>
                    <p class="text-xs text-green-600 font-medium ml-2">Live Data</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Heart Rate Chart -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Heart Rate Trend (Recent)</h3>
            <div style="height: 180px;">
                <canvas id="heartRateChart"></canvas>
            </div>
        </div>

        <!-- SpO2 Chart -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Oxygen Saturation Trend (Recent)</h3>
            <div style="height: 180px;">
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
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($historicalData as $data)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $data->timestamp->translatedFormat('j M Y, H:i') }}
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
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
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
    // Prepare chart data from Laravel
    const chartData = @json($chartData);

    const timestamps = chartData.map(item => {
        const date = new Date(item.timestamp);
        return date.toLocaleTimeString('id-ID', {
            hour: '2-digit',
            minute: '2-digit'
        });
    });

    const heartRates = chartData.map(item => item.heart_rate);
    const oxygenSaturations = chartData.map(item => item.oxygen_saturation);

    // Heart Rate Chart
    const heartRateCtx = document.getElementById('heartRateChart').getContext('2d');
    const heartRateChart = new Chart(heartRateCtx, {
        type: 'line',
        data: {
            labels: timestamps,
            datasets: [{
                label: 'Heart Rate (BPM)',
                data: heartRates,
                borderColor: '#dc2626',
                backgroundColor: 'rgba(220, 38, 38, 0.1)',
                borderWidth: 2,
                tension: 0.4,
                fill: true,
                pointRadius: 2,
                pointHoverRadius: 4,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            animation: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                },
                decimation: {
                    enabled: true,
                    algorithm: 'lttb'
                }
            },
            scales: {
                y: {
                    beginAtZero: false,
                    min: 40,
                    max: 120,
                    ticks: {
                        callback: function(value) {
                            return value;
                        },
                        maxTicksLimit: 6
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    ticks: {
                        maxTicksLimit: 8,
                        maxRotation: 0
                    },
                    grid: {
                        display: false
                    }
                }
            },
            interaction: {
                mode: 'nearest',
                axis: 'x',
                intersect: false
            }
        }
    });

    // SpO2 Chart
    const spo2Ctx = document.getElementById('spo2Chart').getContext('2d');
    const spo2Chart = new Chart(spo2Ctx, {
        type: 'line',
        data: {
            labels: timestamps,
            datasets: [{
                label: 'Oxygen Saturation (%)',
                data: oxygenSaturations,
                borderColor: '#2563eb',
                backgroundColor: 'rgba(37, 99, 235, 0.1)',
                borderWidth: 2,
                tension: 0.4,
                fill: true,
                pointRadius: 2,
                pointHoverRadius: 4,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            animation: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                },
                decimation: {
                    enabled: true,
                    algorithm: 'lttb'
                }
            },
            scales: {
                y: {
                    beginAtZero: false,
                    min: 85,
                    max: 100,
                    ticks: {
                        callback: function(value) {
                            return value;
                        },
                        maxTicksLimit: 6
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    ticks: {
                        maxTicksLimit: 8,
                        maxRotation: 0
                    },
                    grid: {
                        display: false
                    }
                }
            },
            interaction: {
                mode: 'nearest',
                axis: 'x',
                intersect: false
            }
        }
    });

    // Auto-refresh data every 30 seconds
    setInterval(function() {
        fetch('{{ route("pasien.wearable-data") }}')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update metrics cards
                    document.getElementById('heart-rate-value').textContent = data.data.heart_rate;
                    document.getElementById('spo2-value').textContent = data.data.oxygen_saturation;
                    document.getElementById('last-updated-time').textContent = new Date(data.data.timestamp).toLocaleTimeString('id-ID', {
                        hour: '2-digit',
                        minute: '2-digit'
                    }) + ' WIB';

                    // Update status badges
                    const heartRateStatus = document.getElementById('heart-rate-status');
                    if (data.data.heart_rate >= 60 && data.data.heart_rate <= 100) {
                        heartRateStatus.textContent = 'Normal';
                        heartRateStatus.className = 'text-sm font-medium text-green-600';
                    } else {
                        heartRateStatus.textContent = 'Abnormal';
                        heartRateStatus.className = 'text-sm font-medium text-red-600';
                    }

                    const spo2Status = document.getElementById('spo2-status');
                    if (data.data.oxygen_saturation >= 95) {
                        spo2Status.textContent = 'Normal';
                        spo2Status.className = 'text-sm font-medium text-green-600';
                    } else {
                        spo2Status.textContent = 'Low';
                        spo2Status.className = 'text-sm font-medium text-red-600';
                    }
                }
            })
            .catch(error => console.error('Error fetching latest data:', error));
    }, 30000); // 30 seconds
</script>
@endif
@endsection