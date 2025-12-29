<x-app-layout>
    <div class="p-6 bg-gray-100 min-h-screen">
        <div class="max-w-5xl mx-auto bg-white p-6 shadow rounded-lg">
            <h2 class="text-2xl font-semibold mb-4 text-gray-700">💰 Date-wise Fee Report</h2>

            @php
                $user = auth()->user();
                $isSuperAdmin = $user->hasRole('SuperAdmin');
            @endphp

            <!-- 🔹 Filter by Date -->
            @if ($isSuperAdmin)
                <form method="GET" class="mb-4 flex space-x-2">
                    <input type="date" name="from" value="{{ $from }}" class="border rounded p-2">
                    <input type="date" name="to" value="{{ $to }}" class="border rounded p-2">
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">Filter</button>
                </form>
            @else
                <div class="mb-4 text-sm text-gray-600">
                    📅 Showing report for <strong>today ({{ now()->toDateString() }})</strong>
                </div>
            @endif

            <!-- 🔹 Table -->
            {{-- <table class="w-full border border-gray-200">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="px-4 py-2 text-left">Date</th>
                        <th class="px-4 py-2 text-left">Total Fee Received</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($fees as $fee)
                        <tr class="border-t">
                            <td class="px-4 py-2">{{ $fee->fee_date }}</td>
                            <td class="px-4 py-2">Rs. {{ number_format($fee->total_fee, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="px-4 py-2 text-center text-gray-500">No fees found for this range.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table> --}}

            <table class="w-full border border-gray-200">
    <thead class="bg-gray-200">
        <tr>
            <th class="px-4 py-2 text-left">Date</th>
            <th class="px-4 py-2 text-left">Fee Received</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($fees as $fee)
            <tr class="border-t">
                <td class="px-4 py-2">{{ $fee->last_fee_date }}</td>
                <td class="px-4 py-2">Rs. {{ number_format($fee->fee, 2) }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="2" class="px-4 py-2 text-center text-gray-500">No fees found for this range.</td>
            </tr>
        @endforelse
    </tbody>
</table>



            <!-- 🔹 Total Income -->
            @if ($fees->count() > 0)
                <div class="mt-4 text-right font-semibold text-gray-700 text-lg">
                    Total Income: <span class="text-green-600">Rs. {{ number_format($totalIncome, 2) }}</span>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
