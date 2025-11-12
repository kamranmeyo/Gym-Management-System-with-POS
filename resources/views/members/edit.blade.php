<x-app-layout>
    <div class="p-6 bg-gray-100 min-h-screen">
        <div class="max-w-3xl mx-auto bg-white p-6 shadow rounded-lg">
            <h2 class="text-2xl font-semibold mb-4 text-gray-700">✏️ Edit Member</h2>

            <form method="POST" action="{{ route('members.update', $member->id) }}">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Name -->
                    <div>
                        <label class="block text-gray-600 text-sm">Full Name</label>
                        <input type="text" name="name" value="{{ $member->name }}" class="w-full border-gray-300 rounded-md" required>
                    </div>

                    <!-- Phone -->
                    <div>
                        <label class="block text-gray-600 text-sm">Phone</label>
                        <input type="text" name="phone" value="{{ $member->phone }}" class="w-full border-gray-300 rounded-md" required>
                    </div>

                    <!-- Gender -->
                    <div>
                        <label class="block text-gray-600 text-sm">Gender</label>
                        <select name="gender" class="w-full border-gray-300 rounded-md" required>
                            <option value="">-- Select Gender --</option>
                            <option value="1" {{ $member->gender == 1 ? 'selected' : '' }}>Male</option>
                            <option value="2" {{ $member->gender == 2 ? 'selected' : '' }}>Female</option>
                        </select>
                    </div>

                    <!-- Plan Dropdown -->
                    <div>
                        <label class="block text-gray-600 text-sm">Plan</label>
                        <select name="membership_type" id="plan" class="w-full border-gray-300 rounded-md" required>
                            <option value="">-- Select Plan --</option>
                            <option value="200" {{ $member->membership_type == '200' ? 'selected' : '' }}>Gym (Rs. 200)</option>
                            <option value="2500" {{ $member->membership_type == '2500' ? 'selected' : '' }}>Cardio (Rs. 2500)</option>
                            <option value="2700" {{ $member->membership_type == '2700' ? 'selected' : '' }}>Gym + Cardio (Rs. 2700)</option>
                        </select>
                    </div>

                    <!-- Fee -->
                    <div>
                        <label class="block text-gray-600 text-sm">Fee</label>
                        <input type="number" name="fee" id="fee" step="0.01" value="{{ $member->fee }}" class="w-full border-gray-300 rounded-md">
                    </div>

                    <!-- Fee Method -->
                    <div>
                        <label class="block text-gray-600 text-sm">Fee Method</label>
                        <select name="fee_method" class="w-full border-gray-300 rounded-md" required>
                            <option value="">-- Select Method --</option>
                            <option value="1" {{ $member->fee_method == 1 ? 'selected' : '' }}>EasyPaisa</option>
                            <option value="2" {{ $member->fee_method == 2 ? 'selected' : '' }}>JazzCash</option>
                            <option value="3" {{ $member->fee_method == 3 ? 'selected' : '' }}>Bank</option>
                            <option value="4" {{ $member->fee_method == 4 ? 'selected' : '' }}>Cash</option>
                        </select>
                    </div>

                    <!-- Join Date -->
                    <div>
                        <label class="block text-gray-600 text-sm">Join Date</label>
                        <input type="date" name="join_date" id="join_date" value="{{ $member->join_date }}" class="w-full border-gray-300 rounded-md" required>
                    </div>

                    <!-- Expiry Date -->
                    <div>
                        <label class="block text-gray-600 text-sm">Expiry Date</label>
                        <input type="date" name="expiry_date" id="expiry_date" value="{{ $member->expiry_date }}" class="w-full border-gray-300 rounded-md bg-gray-100" readonly>
                    </div>

                    <!-- Comment -->
                    <div class="md:col-span-2">
                        <label class="block text-gray-600 text-sm">Comment</label>
                        <textarea name="comment" rows="3" class="w-full border-gray-300 rounded-md">{{ $member->comment }}</textarea>
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                        Update Member
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- JS for Fee update when plan changes -->
    <script>
        const planSelect = document.getElementById('plan');
        const feeInput = document.getElementById('fee');

        planSelect.addEventListener('change', function() {
            feeInput.value = this.value;
        });
    </script>
</x-app-layout>
