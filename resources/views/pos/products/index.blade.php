<x-app-layout>
    <div class="p-6 bg-gray-100 min-h-screen">
        <div class="max-w-4xl mx-auto bg-white p-6 shadow rounded-lg">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-semibold text-gray-700">🛍️ Product List</h2>
                <a href="{{ route('pos.products.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded">+ Add Product</a>
            </div>

            @if(session('success'))
                <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">{{ session('success') }}</div>
            @endif
                        @php
                $user = auth()->user();
                $isSuperAdmin = $user->hasRole('SuperAdmin');
            @endphp
            <div class="overflow-x-auto">
                <table class="w-full border">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="border px-3 py-2 text-left">#</th>
                            <th class="border px-3 py-2 text-left">Name</th>
                            <th class="border px-3 py-2 text-left">Price</th>
                            <th class="border px-3 py-2 text-left">Stock</th>
                            <th class="border px-3 py-2 text-left">Description</th>
                            <th class="border px-3 py-2 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $product)
                            <tr class="border-t hover:bg-gray-50">
                                <td class="px-3 py-2">{{ $loop->iteration }}</td>
                                <td class="px-3 py-2 font-medium">{{ $product->name }}</td>
                                <td class="px-3 py-2">Rs. {{ number_format($product->price, 2) }}</td>
                                <td class="px-3 py-2">{{ $product->stock }}</td>
                                <td class="px-3 py-2">{{ $product->description }}</td>
                                <td class="px-3 py-2 text-center">
                                     @if ($isSuperAdmin)
                                    <a href="{{ route('pos.products.edit', $product) }}" class="px-2 py-1 bg-blue-500 text-white rounded text-sm">Edit</a>
                                    <form action="{{ route('pos.products.destroy', $product) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button onclick="return confirm('Delete this product?')" class="px-2 py-1 bg-red-500 text-white rounded text-sm">Delete</button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">No products found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
