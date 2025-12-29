<x-app-layout>
    <div class="p-6 bg-gray-100 min-h-screen">
        <div class="max-w-3xl mx-auto bg-white p-6 shadow rounded-lg">
            <h2 class="text-2xl font-semibold mb-4 text-gray-700">➕ Add New Member</h2>

            <form method="POST" action="{{ route('members.store') }}">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Name -->
                    <div>
                        <label class="block text-gray-600 text-sm">Full Name</label>
                        <input type="text" name="name" class="w-full border-gray-300 rounded-md" required>
                    </div>

                    <!-- Phone -->
                    <div>
                        <label class="block text-gray-600 text-sm">Phone</label>
                        <input type="text" name="phone" class="w-full border-gray-300 rounded-md" required>
                    </div>

                    <!-- Gender -->
                    <div>
                        <label class="block text-gray-600 text-sm">Gender</label>
                        <select name="gender" class="w-full border-gray-300 rounded-md" required>
                            <option value="">-- Select Gender --</option>
                            <option value="1">Male</option>
                            <option value="2">Female</option>
                        </select>
                    </div>

                    <!-- Plan Dropdown -->
                    <div>
                        <label class="block text-gray-600 text-sm">Plan</label>
                        <select name="membership_type" id="plan" class="w-full border-gray-300 rounded-md" required>
                            <option value="">-- Select Plan --</option>
                            @foreach(\App\Models\Plan::all() as $plan)
                                <option value="{{ $plan->name }}" data-price="{{ $plan->price }}">{{ $plan->name }} (Rs. {{ $plan->price }})</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Fee -->
                    <div>
                        <label class="block text-gray-600 text-sm">Fee</label>
                        <input type="number" name="fee" id="fee" step="0.01" class="w-full border-gray-300 rounded-md" readonly>
                    </div>

                    <!-- Fee Method -->
                    <div>
                        <label class="block text-gray-600 text-sm">Fee Method</label>
                        <select name="fee_method" class="w-full border-gray-300 rounded-md" required>
                            <option value="">-- Select Method --</option>
                            <option value="1">EasyPaisa</option>
                            <option value="2">JazzCash</option>
                            <option value="3">Bank</option>
                            <option value="4">Cash</option>
                        </select>
                    </div>

                    <!-- Join Date -->
                    <div>
                        <label class="block text-gray-600 text-sm">Join Date</label>
                        <input type="date" name="join_date" id="join_date" class="w-full border-gray-300 rounded-md" value="{{ \Carbon\Carbon::now()->toDateString() }}" required readonly>
                    </div>

                    <!-- Expiry Date (auto 1 month later) -->
                    <div>
                        <label class="block text-gray-600 text-sm">Expiry Date</label>
                        <input type="date" name="expiry_date" id="expiry_date" class="w-full border-gray-300 rounded-md bg-gray-100" readonly>
                    </div>

                    <!-- Comment -->
                    <div class="md:col-span-2">
                        <label class="block text-gray-600 text-sm">Comment</label>
                        <textarea name="comment" rows="3" class="w-full border-gray-300 rounded-md"></textarea>
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                        Save Member
                    </button>
                </div>
            </form>
        </div>
    </div>

   
    <script>
         //<!-- JS for Expiry & Plan Fee -->
        const planSelect = document.getElementById('plan');
        const feeInput = document.getElementById('fee');
        // Auto set fee when plan selected
        // Get the plan dropdown and fee input elements

    planSelect.addEventListener('change', function() {
    // Get the selected option
    const selectedOption = planSelect.options[planSelect.selectedIndex];

    // Get the price from the data-price attribute
    const price = selectedOption.getAttribute('data-price');

    // Set the fee input's value to the price
    feeInput.value = price;
});
    
document.addEventListener('DOMContentLoaded', function() {
    // Get the Join Date and Expiry Date input elements
    const joinDateInput = document.getElementById('join_date');
    const expiryDateInput = document.getElementById('expiry_date');

    // Set the Expiry Date (1 month after Join Date)
    const joinDate = new Date(joinDateInput.value);
    joinDate.setMonth(joinDate.getMonth() + 1);

    // Format the expiry date to YYYY-MM-DD
    const year = joinDate.getFullYear();
    const month = (joinDate.getMonth() + 1).toString().padStart(2, '0');  // month is zero-based, so we add 1
    const day = joinDate.getDate().toString().padStart(2, '0');

    // Set the expiry date in the input field
    expiryDateInput.value = `${year}-${month}-${day}`;
});
</script>

</x-app-layout>
