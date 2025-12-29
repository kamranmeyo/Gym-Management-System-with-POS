<x-app-layout>
    <div class="p-6 bg-gray-100 min-h-screen">
        <div class="max-w-5xl mx-auto bg-white p-6 shadow rounded-lg">
            <h2 class="text-2xl font-semibold mb-4 text-gray-700">📊 Expense Report</h2>

            {{-- Filter Form --}}

                <form method="GET" class="mb-4 flex space-x-2">
                    <input type="date" name="from" class="border rounded p-2">
                    <input type="date" name="to"  class="border rounded p-2">
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">Filter</button>
                </form>

            {{-- Expenses Table --}}
            <div class="overflow-x-auto">
                <table id="expensesTable" class="w-full border border-gray-200 text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border px-3 py-2 text-left">Date</th>
                            <th class="border px-3 py-2 text-left">Category</th>
                            <th class="border px-3 py-2 text-left">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($expenses as $exp)
                            <tr>
                                <td class="border px-3 py-2">{{ $exp->expense_date }}</td>
                                <td class="border px-3 py-2">{{ $exp->category->name ?? '-' }}</td>
                                <td class="border px-3 py-2">{{ number_format($exp->amount, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="border px-3 py-4 text-center text-gray-500">
                                    No expenses found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

            </div>

            {{-- Total --}}
            <div class="mt-4 text-right">
                <h4 class="text-lg font-semibold text-red-600">
                    Total Expense: {{ number_format($totalExpense, 2) }} ,  <span class="text-green-600">Total Income: {{ number_format($income, 2) }}</span>
                </h4>
                <h4 class="text-lg font-semibold">Profit: {{$income - $totalExpense }}  </h4>
            </div>

        </div>
    </div>
    {{-- DataTables CSS --}}
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">

{{-- jQuery --}}
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

{{-- DataTables --}}
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

{{-- Export Buttons --}}
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>


    <script>
$(document).ready(function () {
    $('#expensesTable').DataTable({
        paging: true,
        searching: true,
        ordering: false,
        pageLength: 10,
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excelHtml5',
                title: 'Expense_Report'
            },
            {
                extend: 'pdfHtml5',
                title: 'Expense_Report',
                orientation: 'portrait',
                pageSize: 'A4'
            }
        ]
    });
});
</script>

</x-app-layout>
