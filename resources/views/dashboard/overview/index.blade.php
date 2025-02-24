@extends('layouts.main')

@section('container')
    <div class="flex flex-wrap gap-4 justify-start">
        <div class="p-5 mt-5 rounded-lg">
            <div class="text-left">
                <h1 class="text-gray-600 font-semibold">Overview</h1>
            </div>
            <div class="flex gap-4 mt-5">
                <!-- Statistic Cards -->
                <div class="bg-white rounded w-1/3 text-center hover:border-blue-500 p-10 shadow-lg">
                    <h2 class="font-bold text-4xl">{{ $countProducts }}</h2>
                    <p class="text-sm mt-2 text-gray-600">Product Data</p>
                </div>
                <div class="bg-white rounded w-1/3 text-center hover:border-blue-500 p-10 shadow-lg">
                    <h2 class="font-bold text-4xl">{{ $countProductIncome }}</h2>
                    <p class="text-sm mt-2 text-gray-600">Incoming Goods</p>
                </div>
                <div class="bg-white rounded text-center hover:border-blue-500 w-1/3 p-10 shadow-lg">
                    <h2 class="font-bold text-4xl">{{ $countProductOutcome }}</h2>
                    <p class="text-sm text-gray-600 mt-2">Outcoming Goods</p>
                </div>
            </div>

            <!-- Chart Section -->
            <div class="mt-10">
                <h2 class="text-gray-600 font-semibold mb-4">Inventory Overview</h2>
                <div class="bg-white rounded-lg p-5 shadow-lg">
                    <canvas id="inventoryChart" class="w-full h-96"></canvas>
                </div>
            </div>

            <!-- Stock Trend Section (Last 7 Days) -->
            <div class="mt-10">
                <h2 class="text-gray-600 font-semibold mb-4">Stock Trends (Last 7 Days)</h2>
                <div class="bg-white rounded-lg p-5 shadow-lg">
                    <canvas id="stockTrendChart" class="w-full h-96"></canvas>
                </div>
            </div>


        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('inventoryChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Product Data', 'Incoming Goods', 'Outcoming Goods'],
                    datasets: [{
                        label: 'Inventory Data',
                        data: [{{ $countProducts }}, {{ $countProductIncome }},
                            {{ $countProductOutcome }}
                        ],
                        backgroundColor: ['#3B82F6', '#10B981', '#EF4444'],
                        borderColor: ['#2563EB', '#059669', '#DC2626'],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const stockTrendCtx = document.getElementById('stockTrendChart').getContext('2d');
            new Chart(stockTrendCtx, {
                type: 'line',
                data: {
                    labels: @json($stockTrends->pluck('date')),
                    datasets: [{
                            label: 'Incoming Goods',
                            data: @json($stockTrends->pluck('total_in')),
                            borderColor: 'green',
                            backgroundColor: 'rgba(0, 128, 0, 0.2)',
                            fill: true
                        },
                        {
                            label: 'Outgoing Goods',
                            data: @json($stockTrends->pluck('total_out')),
                            borderColor: 'red',
                            backgroundColor: 'rgba(255, 0, 0, 0.2)',
                            fill: true
                        }
                    ]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>
@endsection
