@extends('layouts.main')

@section('container')
@if (session('message'))
<div id="toast-container"
    class="hidden fixed z-50 items-center w-full max-w-xs p-4 space-x-4 text-gray-500 bg-white divide-x divide-gray-200 rounded border-l-2 border-green-400 shadow top-5 right-5 dark:text-gray-400 dark:divide-gray-700 space-x dark:bg-gray-800"
    role="alert">
    <div class=" text-green-400 text-sm font-bold capitalize">{{ session()->get('message') }}</div>
</div>
@endif
    <div class="w-full flex-wrap gap-4">
        <div class="bg-white mt-5 p-5 rounded-lg shadow-lg">
            <!-- Header Section -->
            <div class="flex justify-between items-center">
                <div class="text-left">
                    <h2 class="text-gray-800 font-bold text-lg">Report</h2>

                    {{-- <button href="/excel/variants"
                        class="text-sm bg-green-600 text-white inline-block mt-2 px-4 py-2 rounded-lg hover:bg-green-700 focus:outline-none focus:ring-4 focus:ring-green-300 transition">
                        Export Excel
                    </button> --}}

                    <button id="downloadReportBtn"
                        class="text-sm bg-blue-600 text-white inline-block mt-2 px-4 py-2 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300 transition">
                        Export PDF
                    </button>

                    <button id="viewReportBtn"
                        class="text-sm bg-purple-600 text-white inline-block mt-2 px-4 py-2 rounded-lg hover:bg-purple-700 focus:outline-none focus:ring-4 focus:ring-purple-300 transition">
                        View Report
                    </button>
                </div>
            </div>
            <!-- Table Section -->
            <div class="containerTabelVariant mt-5 overflow-x-auto">

                <table id="reportTable"
                    class="min-w-full text-sm text-left text-gray-700 border-collapse border border-gray-200">
                    <thead>
                        <tr class="font-bold bg-gray-100 text-gray-700 border-b-2 border-gray-300">
                            <th class="p-2 border border-gray-300">No</th>
                            <th class="p-2 border border-gray-300">Code Product</th>
                            <th class="p-2 border border-gray-300">Product Name</th>
                            <th class="p-2 border border-gray-300">Supplier Name</th>
                            <th class="p-2 border border-gray-300">Quantity</th>
                            <th class="p-2 border border-gray-300">Price</th>
                            <th class="p-2 border border-gray-300">Type</th>
                            <th class="p-2 border border-gray-300">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($report as $item)
                            <tr class="border-b border-gray-300 hover:bg-gray-50">
                                <td class="p-2 border border-gray-300">{{ $loop->iteration }}</td>
                                <td class="p-2 border border-gray-300">{{ $item->product->code ?? '-' }}</td>
                                <td class="p-2 border border-gray-300">{{ $item->product->name ?? '-' }}</td>
                                <td class="p-2 border border-gray-300">{{ $item->supplier->name ?? '-' }}</td>
                                <td class="p-2 border border-gray-300">{{ $item->quantity }}</td>
                                <td class="p-2 border border-gray-300">Rp
                                    {{ number_format($item->product->price, 2, ',', '.') }}</td>
                                <td class="p-2 border border-gray-300">{{ ucfirst($item->type) }}</td>
                                <td class="p-2 border border-gray-300">
                                    {{ $item->date ? \Carbon\Carbon::parse($item->date)->format('d M Y') : '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    </div>
    <script>
        $(document).ready(function() {
            // Ketika tombol dengan ID 'downloadReportBtn' diklik
            $('#downloadReportBtn').on('click', function() {
                // Trigger action untuk mendownload laporan
                window.location.href = "{{ route('report.download') }}";
            });
            $('#viewReportBtn').on('click', function() {
                // Trigger action untuk mendownload laporan
                window.open("{{ route('report.view') }}", '_blank');
            });
        });
    </script>
@endsection
