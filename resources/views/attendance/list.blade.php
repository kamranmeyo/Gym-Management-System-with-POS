<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Attendance List') }}
        </h2>
    </x-slot>

    <div class="py-6">
       

            {{-- Filter Form --}}
            <div class="bg-white shadow-sm sm:rounded-lg p-4 mb-4">
                <form method="GET" action="{{ route('attendance.list') }}" class="flex gap-4 items-end">
                    <div>
                        <label class="block text-sm">Date From</label>
                        <input type="date" name="date_from"
                               value="{{ request('date_from') }}"
                               class="border rounded px-2 py-1">
                    </div>

                    <div>
                        <label class="block text-sm">Date To</label>
                        <input type="date" name="date_to"
                               value="{{ request('date_to') }}"
                               class="border rounded px-2 py-1">
                    </div>

                    <div>
                        <button type="submit"
                                class="bg-blue-600 text-white mt-4 px-4 py-2 rounded">
                            Filter
                        </button>

                        <a href="{{ route('attendance.list') }}"
                           class="ml-2 text-gray-600 underline">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            {{-- Table --}}
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <table id="attendanceTable" class="min-w-full border">
                    <thead>
                        <tr class="border-b">
                            <th>#</th>
                            <th>Member Name</th>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($attendance as $row)
                            <tr class="border-b">
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $row->member->name ?? 'N/A' }}</td>
                                <td>{{ $row->date }}</td>
                                <td>✅️</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

    </div>

    {{-- DataTables CSS --}}
    <link rel="stylesheet"
          href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    {{-- jQuery --}}
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

    {{-- DataTables JS --}}
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#attendanceTable').DataTable({
                order: [[2, 'desc']]
            });
        });
    </script>
</x-app-layout>
