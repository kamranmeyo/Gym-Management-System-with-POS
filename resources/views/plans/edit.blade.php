<x-app-layout>
    <div class="p-6 bg-gray-100 min-h-screen">
        <div class="max-w-md mx-auto bg-white p-6 shadow rounded-lg">
            <h2 class="text-2xl font-semibold mb-4 text-gray-700">✏️ Edit Plan</h2>

            <form method="POST" action="{{ route('plans.update', $plan) }}">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="block text-sm text-gray-600">Plan Name</label>
                    <input type="text" name="name" value="{{ $plan->name }}" class="w-full border rounded p-2" required>
                </div>

                <div class="mb-3">
                    <label class="block text-sm text-gray-600">Price</label>
                    <input type="number" name="price" value="{{ $plan->price }}" step="0.01" class="w-full border rounded p-2" required>
                </div>

                <div class="mb-3">
                    <label class="block text-sm text-gray-600">Description</label>
                    <textarea name="description" rows="3" class="w-full border rounded p-2">{{ $plan->description }}</textarea>
                </div>

                <button class="bg-indigo-600 text-white px-4 py-2 rounded">Update</button>
            </form>
        </div>
    </div>
</x-app-layout>
