<x-app-layout>
    <div class="p-6 bg-gray-100 min-h-screen">
        <div class="max-w-6xl mx-auto bg-white p-6 shadow rounded-lg">
            <h2 class="text-2xl font-semibold mb-4 text-gray-700">👥 Members List</h2>
            @php
                $user = auth()->user();
                $isSuperAdmin = $user->hasRole('SuperAdmin');
            @endphp

            <table class="w-full border border-gray-200 text-sm">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="px-4 py-2 text-left">#</th>
                        <th class="px-4 py-2 text-left">Name</th>
                        <th class="px-4 py-2 text-left">Phone</th>
                        <th class="px-4 py-2 text-left">Gender</th>
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
                            <td class="px-4 py-2 font-medium">{{ $member->name }}</td>
                            <td class="px-4 py-2">{{ $member->phone }}</td>
                            <td class="px-4 py-2">
                                {{ $member->gender == 1 ? 'Male' : 'Female' }}
                            </td>
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
        </div>
    </div>

    <!-- SweetAlert2 (for nice popup) -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
</x-app-layout>
