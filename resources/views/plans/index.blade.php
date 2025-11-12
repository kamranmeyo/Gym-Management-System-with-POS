<x-app-layout>
    <div class="p-6 bg-gray-100 min-h-screen">
        <div class="max-w-4xl mx-auto bg-white p-6 shadow rounded-lg">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-semibold text-gray-700">🏋️ Plans</h2>
                <a href="{{ route('plans.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded">Add Plan</a>
            </div>

            @if(session('success'))
                <div class="mb-4 text-green-600">{{ session('success') }}</div>
            @endif

            <table class="w-full border">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="border px-3 py-2">#</th>
                        <th class="border px-3 py-2">Name</th>
                        <th class="border px-3 py-2">Price</th>
                        <th class="border px-3 py-2">Description</th>
                        <th class="border px-3 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($plans as $plan)
                        <tr>
                            <td class="border px-3 py-2">{{ $loop->iteration }}</td>
                            <td class="border px-3 py-2">{{ $plan->name }}</td>
                            <td class="border px-3 py-2">Rs. {{ number_format($plan->price, 0) }}</td>
                            <td class="border px-3 py-2">{{ $plan->description }}</td>
                            <td class="border px-3 py-2 text-center">
                                <a href="{{ route('plans.edit', $plan) }}" class="text-blue-600">Edit</a> |
                                <form action="{{ route('plans.destroy', $plan) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button onclick="return confirm('Delete this plan?')" class="text-red-600">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center py-3">No plans found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
