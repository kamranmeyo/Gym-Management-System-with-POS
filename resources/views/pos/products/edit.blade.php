<x-app-layout>
    <div class="p-6 bg-gray-100 min-h-screen">
        <div class="max-w-md mx-auto bg-white p-6 shadow rounded-lg">
            <h2 class="text-2xl font-semibold mb-4 text-gray-700">✏️ Edit Product</h2>

            @if ($errors->any())
                <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('pos.products.update', $product) }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="block text-sm text-gray-600">Product Name</label>
                    <input type="text" name="name" value="{{ old('name', $product->name) }}" class="w-full border rounded p-2" required>
                </div>

                <div class="mb-3">
                    <label class="block text-sm text-gray-600">Price</label>
                    <input type="number" name="price" value="{{ old('price', $product->price) }}" step="0.01" class="w-full border rounded p-2" required>
                </div>

                <div class="mb-3">
                    <label class="block text-sm text-gray-600">Stock</label>
                    <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" class="w-full border rounded p-2" min="0" required>
                </div>

                <div class="mb-3">
                    <label class="block text-sm text-gray-600">Description</label>
                    <textarea name="description" rows="3" class="w-full border rounded p-2">{{ old('description', $product->description) }}</textarea>
                </div>

                <div class="flex justify-end">
                    <a href="{{ route('pos.products.index') }}" class="mr-2 px-4 py-2 border rounded">Cancel</a>
                    <button class="bg-indigo-600 text-white px-4 py-2 rounded">Update Product</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
