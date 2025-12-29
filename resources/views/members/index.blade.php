<x-app-layout>
    <div class="p-6 bg-gray-100 min-h-screen">
        <div class="bg-white p-6 shadow rounded-lg">

        @if(auth()->user()->hasRole('SuperAdmin'))
        <button onclick="syncMembers()"
            class="mb-4 px-4 py-2 bg-purple-600 rounded">
            🔄 Sync Members to Machine
        </button>
        @endif

            <h2 class="text-2xl font-semibold mb-4 text-gray-700">👥 Members List From DB</h2>
            @php
                $user = auth()->user();
                $isSuperAdmin = $user->hasRole('SuperAdmin');
            @endphp

            <table id="dbMembersTable" class="w-full border border-gray-200 text-sm">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="px-4 py-2 text-left">#</th>
                        <th class="px-4 py-2 text-left">DB Id</th>
                        <th class="px-4 py-2 text-left">Name</th>
                        <th class="px-4 py-2 text-left">Phone</th>
                        <th class="px-4 py-2 text-left">Gender</th>
                        <th class="px-4 py-2 text-left">Fee</th>
                        <th class="px-4 py-2 text-left">Package Type</th>
                        <th class="px-4 py-2 text-left">Join Date</th>
                        <th class="px-4 py-2 text-left">Last Fee</th>
                        <th class="px-4 py-2 text-left">Next Fee Due</th>
                        <th class="px-4 py-2 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($members as $member)
                        <tr class="border-t hover:bg-gray-50">
                            <td class="px-4 py-2">{{ $loop->iteration }}</td>
                            <td class="px-4 py-2">{{ $member->id }}</td>
                            <td class="px-4 py-2 font-medium">{{ $member->name }}</td>
                            <td class="px-4 py-2">{{ $member->phone }}</td>
                            <td class="px-4 py-2">
                                {{ $member->gender == 1 ? 'Male' : 'Female' }}
                            </td>
                            <td class="px-4 py-2">{{ $member->fee }}</td>
                            <td class="px-4 py-2">{{ $member->membership_type }}</td>
                            <td class="px-4 py-2">{{ $member->join_date }}</td>

                            <!-- Fee Info -->
                            <td class="px-4 py-2">
                                {{ $member->last_fee_date ? \Carbon\Carbon::parse($member->last_fee_date)->format('Y-m-d') : '-' }}
                            </td>
                            <td class="px-4 py-2">
                                {{ $member->next_fee_due ? \Carbon\Carbon::parse($member->next_fee_due)->format('Y-m-d') : '-' }}
                            </td>

                            <!-- Actions -->
                            <td class="px-4 py-2 text-center space-x-2">
                                @if ($isSuperAdmin)
                                <a href="{{ route('members.edit', $member->id) }}" class="px-2 py-1 bg-blue-500 text-white rounded">Edit</a>
                                <form action="{{ route('members.destroy', $member->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            onclick="return confirm('Delete this member?')" 
                                            class="px-2 py-1 bg-red-500 text-white rounded">Delete</button>
                                </form>
                                @endif
                                <!-- View QR Button -->
                                <button type="button" 
                                        class="px-2 py-1 bg-green-600 text-white rounded" 
                                        onclick="showQR('{{ $member->member_code }}')">
                                    View QR
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{-- <div class="mt-4">
   // {{ $members->links() }}
</div> --}}
        </div>
        <br>
        <div class="bg-white p-6 shadow rounded-lg">
            <h2 class="text-2xl font-semibold mb-4 text-gray-700">👥 Members List From Machine</h2>
            @php
                $user = auth()->user();
                $isSuperAdmin = $user->hasRole('SuperAdmin');
            @endphp

            <table id="machineMembersTable" class="w-full border border-gray-200 text-sm">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="px-4 py-2 text-left">#</th>
                        <th class="px-4 py-2 text-left">Name</th>
                        <th class="px-4 py-2 text-left">Person ID</th>
                        <th class="px-4 py-2 text-left">Begin Time</th>
                        <th class="px-4 py-2 text-left">End Time</th>
                        <th class="px-4 py-2 text-left">Next Fee Due</th>
                    </tr>
                </thead>
<tbody>
    @forelse($users as $index => $person)
        <tr class="border-t">
            <td class="px-4 py-2">{{ $index + 1 }}</td>

            <td class="px-4 py-2">
                {{ $person['name'] ?? '-' }}
            </td>

            <td class="px-4 py-2">
                {{ $person['employeeNo'] ?? '-' }}
            </td>

            <td class="px-4 py-2">
                {{ \Carbon\Carbon::parse($person['Valid']['beginTime'] ?? null)->format('d-m-Y') ?? '-' }}
            </td>

            <td class="px-4 py-2">
                {{ \Carbon\Carbon::parse($person['Valid']['endTime'] ?? null)->format('d-m-Y') ?? '-' }}
            </td>

            <td class="px-4 py-2">
                {{-- example: calculate next fee --}}
                {{ \Carbon\Carbon::parse($person['Valid']['endTime'] ?? null)
                    ->addMonth()
                    ->format('Y-m-d') ?? '-' }}
            </td>


        </tr>
    @empty
        <tr>
            <td colspan="7" class="px-4 py-4 text-center text-gray-500">
                No members found or machine is offline
            </td>
        </tr>
    @endforelse
</tbody>
            </table>
        </div>
    </div>

        
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <!-- SweetAlert2 (for nice popup) -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function () {
    $('#dbMembersTable').DataTable({
        paging: true, // Laravel handles paging
        searching: true,
        ordering: true,
        lengthMenu: [10, 25, 50],
        info: true
    });
});
</script>
    <script>
        function showQR(memberCode) {
            const qrUrl = `https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=${memberCode}`;
            
            Swal.fire({
                title: 'Member QR Code',
                html: `
                    <div class="flex justify-center">
                        <img src="${qrUrl}" alt="QR Code" class="mx-auto border p-2 rounded">
                    </div>
                    <p class="mt-2 text-gray-600 text-sm">Code: <strong>${memberCode}</strong></p>
                `,
                confirmButtonText: 'Close',
                confirmButtonColor: '#2563EB'
            });
        }
    </script>
    <script>
$(document).ready(function () {
    $('#machineMembersTable').DataTable({
        paging: true,
        pageLength: 10,
        searching: true,
        ordering: true,
        lengthMenu: [10, 25, 50],
        language: {
            emptyTable: "No members found from machine"
        }
    });
});
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function syncMembers() {

    Swal.fire({
        title: 'Syncing Members',
        text: 'Please wait...',
        allowOutsideClick: false,
        allowEscapeKey: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    fetch("{{ url('/members/sync-machine') }}", {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
            "Accept": "application/json",
        }
    })
    .then(response => response.json())
    .then(data => {

        Swal.fire({
            icon: 'success',
            title: 'Sync Completed',
            text: `${data.synced} members synced successfully`,
            confirmButtonText: 'OK',
            confirmButtonColor: '#2563EB'
        }).then(() => {
            location.reload();
        });

    })
    .catch(error => {

        Swal.fire({
            icon: 'error',
            title: 'Sync Failed',
            text: 'Machine offline or error occurred',
            confirmButtonColor: '#DC2626'
        });

    });
}
</script>

</x-app-layout>
