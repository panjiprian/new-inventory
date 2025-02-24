@extends('layouts.main')

@section('container')
    @if (session('message'))
        <div id="toast-container"
            class="hidden fixed z-50 items-center w-full max-w-xs p-4 space-x-4 text-gray-500 bg-white divide-x divide-gray-200 rounded border-l-2 border-green-400 shadow top-5 right-5 dark:text-gray-400 dark:divide-gray-700 space-x dark:bg-gray-800"
            role="alert">
            <div class="text-green-400 text-sm font-bold capitalize">{{ session()->get('message') }}</div>
        </div>
    @endif
    <div class="w-full flex-wrap gap-4">
        <div class="bg-white mt-5 p-5 rounded-lg shadow-lg">
            <!-- Header Section -->
            <div class="flex justify-between items-center">
                <div class="text-left">
                    <h2 class="text-gray-800 font-bold text-lg">Officer Data</h2>
                    <a href="/input-petugas"
                        class="text-sm bg-blue-600 text-white inline-block mt-2 px-4 py-2 rounded-md hover:bg-blue-700">
                        Input New Officer
                    </a>
                </div>
            </div>

            <!-- Table Section -->
            <div class="containerTabelOfficer mt-5 overflow-x-auto">
                <table id="officerTabel" class="min-w-full text-sm text-left text-gray-700 border-collapse border border-gray-200">
                    <thead>
                        <tr class="font-bold bg-gray-100 text-gray-700 border-b-2 border-gray-300">
                            <th class="p-2 border border-gray-300">No</th>
                            <th class="p-2 border border-gray-300">Officer Name</th>
                            <th class="p-2 border border-gray-300">Email</th>
                            <th class="p-2 border border-gray-300">Phone</th>
                            <th class="p-2 border border-gray-300">Role</th>
                            <th class="p-2 border border-gray-300">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($officers as $officer)
                            <tr class="border-b border-gray-300">
                                <td class="p-2 border border-gray-300">{{ $loop->iteration }}</td>
                                <td class="p-2 border border-gray-300">{{ $officer->name }}</td>
                                <td class="p-2 border border-gray-300">{{ $officer->email }}</td>
                                <td class="p-2 border border-gray-300">{{ $officer->phone ?? '-' }}</td>
                                <td class="p-2 border border-gray-300">{{ ucfirst($officer->role) }}</td>
                                <td class="p-2 flex justify-center items-center gap-2 border border-gray-300">
                                    <button data-id="{{$officer->id}}"
                                        class="btn-delete-officer bg-red-600 py-1 px-4 rounded text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-300">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                    <a href="/ubah-petugas/{{$officer->id}}"
                                        class="bg-yellow-500 py-1 px-4 rounded text-white hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-300">
                                        <i class="ri-edit-box-line"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#officerTabel').DataTable({
                "paging": true,
                "searching": true,
                "lengthChange": false,
                "pageLength": 10,
                "info": false,
                "responsive": true,
                "order": [[0, "asc"]],
                "language": {
                    "emptyTable": "No officer available",
                    "search": "Search Officer:"
                }
            });
        });
    </script>
@endsection
