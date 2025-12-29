<x-app-layout>
    <div class="p-6 bg-gray-100 min-h-screen">
        <div class="bg-white shadow rounded-lg p-6">

            <!-- Header -->
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-semibold text-gray-700">
                    📂 Expense Categories
                </h2>

                <button onclick="openCategoryModal()"
                        class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                    + Add Category
                </button>
            </div>
            @php
                $user = auth()->user();
                $isSuperAdmin = $user->hasRole('SuperAdmin');
            @endphp
            <!-- Category Table -->
            <div class="overflow-x-auto">
                <table class="w-full border border-gray-200 text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border px-3 py-2 text-left w-16">#</th>
                            <th class="border px-3 py-2 text-left">Category Name</th>
                            <th class="border px-3 py-2 text-center w-40">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $cat)
                            <tr class="hover:bg-gray-50">
                                <td class="border px-3 py-2">
                                    {{ $loop->iteration }}
                                </td>
                                <td class="border px-3 py-2">
                                    {{ $cat->name }}
                                </td>
                                <td class="border px-3 py-2 text-center space-x-1">
                                     @if ($isSuperAdmin)
                                    <button onclick="editCategory({{ $cat->id }}, '{{ $cat->name }}')"
                                            class="bg-blue-500 text-white px-2 py-1 rounded text-xs hover:bg-blue-600">
                                        Edit
                                    </button>

                                    <button onclick="deleteCategory({{ $cat->id }})"
                                            class="bg-red-600 text-white px-2 py-1 rounded text-xs hover:bg-red-700">
                                        Delete
                                    </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3"
                                    class="border px-3 py-4 text-center text-gray-500">
                                    No categories found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
    // ➕ Add Category
    function openCategoryModal() {
        Swal.fire({
            title: '➕ Add Expense Category',
            input: 'text',
            inputLabel: 'Category Name',
            inputPlaceholder: 'e.g. Rent, Electricity',
            showCancelButton: true,
            confirmButtonText: 'Save',
            inputValidator: (value) => {
                if (!value) return 'Category name is required';
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
                .then(res => {
                    if (!res.ok) throw new Error();
                    return res.json();
                })
                .catch(() => {
                    Swal.showValidationMessage('Category already exists');
                });
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire('Saved!', 'Category added successfully.', 'success')
                    .then(() => location.reload());
            }
        });
    }

    // ✏️ Edit Category
    function editCategory(id, currentName) {
        Swal.fire({
            title: '✏️ Edit Category',
            input: 'text',
            inputValue: currentName,
            showCancelButton: true,
            confirmButtonText: 'Update',
            inputValidator: (value) => {
                if (!value) return 'Category name is required';
            },
            preConfirm: (name) => {
                return fetch(`/expense-categories/${id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ name })
                })
                .then(res => {
                    if (!res.ok) throw new Error();
                    return res.json();
                })
                .catch(() => {
                    Swal.showValidationMessage('Category already exists');
                });
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire('Updated!', 'Category updated successfully.', 'success')
                    .then(() => location.reload());
            }
        });
    }

    // 🗑️ Delete Category
    function deleteCategory(id) {
        Swal.fire({
            title: '⚠️ Delete Category?',
            text: 'This action cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/expense-categories/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(res => {
                    if (res.status === 'success') {
                        Swal.fire('Deleted!', 'Category removed.', 'success')
                            .then(() => location.reload());
                    } else {
                        Swal.fire('Error', res.message, 'error');
                    }
                });
            }
        });
    }
    </script>
</x-app-layout>
