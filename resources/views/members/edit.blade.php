<x-app-layout>
    <div class="p-6 bg-gray-100 min-h-screen">
        <div class="max-w-3xl mx-auto bg-white p-6 shadow rounded-lg">

@if (session('error'))
    <div
        x-data="{ show: true }"
        x-show="show"
        x-init="setTimeout(() => show = false, 4000)"
        x-transition
        class="fixed top-5 right-16 bg-red-600 text-white px-4 py-3 rounded shadow-lg z-50"
    >
        {{ session('error') }}
    </div>
@endif


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
                        @foreach(\App\Models\Plan::all() as $plan)
                            <option value="{{ $plan->name }}" 
                                    data-price="{{ $plan->price }}" 
                                    @if(old('membership_type', $member->membership_type) == $plan->name) selected @endif>
                                {{ $plan->name }} (Rs. {{ $plan->price }})
                            </option>
                        @endforeach
                    </select>
                    </div>

                    <!-- Fee -->
                    <div>
                        <label class="block text-gray-600 text-sm">Fee</label>
                        <input type="number" name="fee" id="fee" value="{{ old('fee', $member->fee) }}" class="mt-2 p-2 border rounded-md w-full" required readonly />
                        {{-- <input type="number" name="fee" id="fee" step="0.01" value="{{ $member->fee }}" class="mt-2 p-2 border rounded-md w-full"> --}}
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
                        <input type="date" name="join_date" id="join_date" value="{{ $member->join_date }}" class="w-full border-gray-300 rounded-md" style="background-color: #F3F4F6;"  disabled>
                    </div>

                    <!-- Expiry Date -->
                    <div>
                        <label class="block text-gray-600 text-sm">Expiry Date</label>
                        <input type="date" name="expiry_date" id="expiry_date" value="{{ $member->next_fee_due }}" class="w-full border-gray-300 rounded-md" style="background-color: #F3F4F6;">
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
        // const feeInput = document.getElementById('fee');

        // planSelect.addEventListener('change', function() {
        //     feeInput.value = this.value;
        // });
        document.addEventListener('DOMContentLoaded', function() {
    // Get the plan dropdown and fee input elements
    const planSelect = document.getElementById('plan');
    const feeInput = document.getElementById('fee');

    // Check if the plan is already selected and update fee
    updateFeeFromSelectedPlan();

    // Listen for change in plan selection
    planSelect.addEventListener('change', function() {
        // Update fee when the plan is changed
        updateFeeFromSelectedPlan();
    });

    function updateFeeFromSelectedPlan() {
        // Get the selected option
        const selectedOption = planSelect.options[planSelect.selectedIndex];

        // Get the price from the data-price attribute
        const price = selectedOption.getAttribute('data-price');

        // Set the fee input's value to the price
        feeInput.value = price;
    }
});

    </script>
</x-app-layout>
