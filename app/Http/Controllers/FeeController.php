<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class FeeController extends Controller
{
    public function index()
    {
        return view('fee.index');
    }

    // Search by QR or Phone
    public function search(Request $request)
    {
        $request->validate([
            'member_code' => 'nullable|string',
            'phone' => 'nullable|string',
        ]);

        $member = null;

        if ($request->member_code) {
            $member = Member::where('member_code', $request->member_code)->first();
        } elseif ($request->phone) {
            $member = Member::where('phone', $request->phone)->orWhere('id', $request->phone ?? null)->first();
        }

        if (!$member) {
            return response()->json(['status' => 'error', 'message' => 'Member not found']);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $member->id,
                'name' => $member->name,
                'phone' => $member->phone,
                'plan' => $member->membership_type,
                'last_fee_date' => $member->last_fee_date,
                'next_fee_due' => $member->next_fee_due,
            ],
        ]);
    }



    public function update(Request $request)
{
    // Validate inputs
    $request->validate([
        'member_id' => 'required|integer|exists:members,id',
        'fee_date' => 'required|date',
    ]);

    // Find the member in the database
    $member = Member::findOrFail($request->member_id);

    // Update fee in MySQL
    $member->last_fee_date = $request->fee_date;
    $member->FeeSubmitDate = now()->toDateString();
    // $paidDate = Carbon::parse($request->fee_date);
    $paidDate = Carbon::today();

    $member->last_fee_date = $paidDate;
    $member->FeeSubmitDate = now()->toDateString();
    $member->next_fee_due = $paidDate->copy()->addMonth();
    $member->save();

    return response()->json([
    'status' => 'success',
    'message' => 'Fee updated successfully',
    'data' => [
        'last_fee_date' => $member->last_fee_date,
        'next_fee_due' => $member->next_fee_due,
    ],
    'print_url' => route('fee.print', $member->id)
]);
}


public function print(Member $member)
{
    return view('fee.print', compact('member'));
}


}
