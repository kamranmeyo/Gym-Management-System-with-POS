<x-app-layout>
    <div class="p-6 bg-gray-100 min-h-screen">
        <div class="bg-white shadow rounded-lg p-6">

            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-semibold text-gray-700">
                    💰 Expense List
                </h2>
                   <div class="space-x-2">
        <button onclick="openExpenseModal()"
                class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
            + Add Expense
        </button>
                <button onclick="openCategoryModal()"
                class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
            + Add Category
        </button>
    </div>
            </div>
            @php
                $user = auth()->user();
                $isSuperAdmin = $user->hasRole('SuperAdmin');
            @endphp
            <div class="overflow-x-auto">
                <table class="w-full border border-gray-200 text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border px-3 py-2 text-left">Date</th>
                            <th class="border px-3 py-2 text-left">Category</th>
                            <th class="border px-3 py-2 text-left">Amount</th>
                            <th class="border px-3 py-2 text-left">Payment</th>
                            <th class="border px-3 py-2 text-left">Note</th>
                            <th class="border px-3 py-2 text-center w-32">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($expenses as $exp)
                            <tr class="hover:bg-gray-50">
                                <td class="border px-3 py-2">{{ $exp->expense_date }}</td>
                                <td class="border px-3 py-2">
                                    {{ $exp->category->name ?? '-' }}
                                </td>
                                <td class="border px-3 py-2">
                                    {{ number_format($exp->amount, 2) }}
                                </td>
                                <td class="border px-3 py-2">
                                    {{ $exp->payment_method }}
                                </td>
                                <td class="border px-3 py-2">
                                    {{ $exp->note }}
                                </td>
                                <td class="border px-3 py-2 text-center space-x-1">

                                     @if ($isSuperAdmin)

                                    <a href="{{ route('expenses.edit', $exp->id) }}"
                                       class="bg-blue-500 text-white px-2 py-1 rounded text-xs hover:bg-blue-600">
                                        Edit
                                    </a>

                                    <form method="POST"
                                          action="{{ route('expenses.destroy', $exp->id) }}"
                                          class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                onclick="return confirm('Delete this expense?')"
                                                class="bg-red-600 text-white px-2 py-1 rounded text-xs hover:bg-red-700">
                                            Delete
                                        </button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6"
                                    class="border px-3 py-4 text-center text-gray-500">
                                    No expenses found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
   <script>
function openExpenseModal() {
    Swal.fire({
        title: '➕ Add Expense',
        width: 700,
        showCancelButton: true,
        confirmButtonText: 'Save',
        cancelButtonText: 'Cancel',
        focusConfirm: false,
        html: `
        <form id="expenseForm" class="expense-form">
  <div class="form-group">
    <label for="expense_category_id">Category</label>
                <select name="expense_category_id" class="swal2-input">
                    <option value="">Select</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
  </div>

  <div class="form-group">
    <label for="amount">Amount</label>
    <input type="number" name="amount" id="amount" step="0.01">
  </div>

  <div class="form-group">
    <label for="expense_date">Date</label>
    <input type="date" name="expense_date" id="expense_date">
  </div>

  <div class="form-group">
    <label for="payment_method">Payment</label>
    <select name="payment_method" id="payment_method">
      <option value="">Select</option>
      <option value="1">JazzCash/EasyPaisa</option>
      <option value="2">Bank</option>
      <option value="3">Cash</option>
    </select>
  </div>

  <div class="form-group">
    <label for="note">Note</label>
    <textarea name="note" id="note" rows="3"></textarea>
  </div>
</form>

<style>
  .expense-form {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 16px;
    max-width: 600px;
  }

  .expense-form .form-group {
    display: flex;
    flex-direction: column;
  }

  .expense-form .form-group textarea {
    resize: vertical;
  }

  /* Make note field span 2 columns */
  .expense-form .form-group:last-child {
    grid-column: span 2;
  }

  label {
    margin-bottom: 4px;
    display: flex;
    font-size: 0.9rem;
  }

  input, select, textarea {
    padding: 8px;
    font-size: 1rem;
    border: 1px solid #ccc;
    border-radius: 4px;
  }
</style>

        `,
        preConfirm: () => {
            const form = document.getElementById('expenseForm');
            const formData = new FormData(form);

            return fetch("{{ route('expenses.store') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Validation failed');
                }
                return response.json();
            })
            .catch(() => {
                Swal.showValidationMessage('Please fill all required fields correctly');
            });
        }
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire('Saved!', 'Expense added successfully.', 'success')
                .then(() => location.reload());
        }
    });
}
</script>
<script>
function openCategoryModal() {
    Swal.fire({
        title: '➕ Add Expense Category',
        input: 'text',
        inputLabel: 'Category Name',
        inputPlaceholder: 'e.g. Electricity, Rent',
        showCancelButton: true,
        confirmButtonText: 'Save',
        cancelButtonText: 'Cancel',
        inputValidator: (value) => {
            if (!value) {
                return 'Category name is required';
            }
        },
        preConfirm: (name) => {
            return fetch("{{ route('expense-categories.store') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ name })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Validation error');
                }
                return response.json();
            })
            .catch(() => {
                Swal.showValidationMessage('Category already exists or invalid');
            });
        }
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire('Saved!', 'Category added successfully.', 'success')
                .then(() => location.reload());
        }
    });
}
</script>


</x-app-layout>
