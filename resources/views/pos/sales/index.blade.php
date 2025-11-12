<x-app-layout>
    <div class="p-6 bg-gray-100 min-h-screen">
        <div class="max-w-5xl mx-auto bg-white p-6 shadow rounded-lg">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-semibold text-gray-700">🧾 Sales</h2>
                <a href="{{ route('pos.sales.create') }}" class="bg-green-600 text-white px-4 py-2 rounded">+ New Sale</a>
            </div>

            @if(session('success'))
                <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">{{ session('success') }}</div>
            @endif

            <div class="overflow-x-auto">
                <table id="salesTable" class="min-w-full border text-sm table-auto">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="border px-3 py-2 text-left">#</th>
                            <th class="border px-3 py-2 text-left">Products</th>
                            <th class="border px-3 py-2 text-left">Qty</th>
                            <th class="border px-3 py-2 text-left">Total</th>
                            <th class="border px-3 py-2 text-left">Method</th>
                            <th class="border px-3 py-2 text-left">Date</th>
                            <th class="border px-3 py-2 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sales as $sale)
                            <tr class="border-t hover:bg-gray-50 align-top">
                                <td class="px-3 py-2">{{ $loop->iteration }}</td>

                                {{-- Show all products in this sale --}}
                                <td class="px-3 py-2">
                                    @foreach($sale->items as $item)
                                        <div>{{ $item->product->name ?? '—' }}</div>
                                    @endforeach
                                </td>

                                {{-- Show corresponding quantities --}}
                                <td class="px-3 py-2 text-left">
                                    @foreach($sale->items as $item)
                                        <div>{{ $item->quantity }}</div>
                                    @endforeach
                                </td>

                                <td class="px-3 py-2 text-left">Rs. {{ number_format($sale->total, 2) }}</td>
                                <td class="px-3 py-2">{{ $sale->payment_method }}</td>
                                <td class="px-3 py-2">{{ $sale->created_at->format('Y-m-d H:i') }}</td>

                                {{-- Actions: Re-print & Delete --}}
                                <td class="px-3 py-2 text-center space-x-2">
                                    <a href="{{ route('pos.sales.print', $sale->id) }}" target="_blank" class="text-blue-500">Re-print</a>

                                    <form action="{{ route('pos.sales.destroy', $sale->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500">Delete</button>
                                </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">No sales recorded yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- DataTables JS --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize DataTable
            $('#salesTable').DataTable({
                responsive: true,
                columnDefs: [
                    { orderable: false, targets: 6 } // Disable sorting on actions column
                ]
            });

            // Re-print button click
            $('.reprint-btn').on('click', function() {
                const saleId = $(this).data('sale-id');
                // Open re-print window
                window.open(`/pos/sales/${saleId}/print`, '_blank', 'width=400,height=600');
            });

            // Delete confirmation
            $('.delete-form').on('submit', function(e){
                if(!confirm('Are you sure you want to delete this sale?')) {
                    e.preventDefault();
                }
            });
        });
    </script>
</x-app-layout>
