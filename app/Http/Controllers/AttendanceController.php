<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member;
use Carbon\Carbon;
use App\Models\Attendance;

class AttendanceController extends Controller
{
    public function index()
    {
        return view('attendance.index');
    }

    // When QR code is scanned
    public function scanold(Request $request) // with attandec mark and sound
    {
        $memberCode = $request->member_code;

        $member = Member::where('member_code', $memberCode)->first();

        if (!$member) {
            return response()->json(['status' => 'error', 'message' => 'Member not found']);
        }

        $isExpired = Carbon::parse($member->expiry_date)->isPast();

        return response()->json([
            'status' => 'success',
            'data' => [
                'name' => $member->name,
                'start' => $member->join_date,
                'end' => $member->expiry_date,
                'plan' => $member->membership_type,
                'is_expired' => $isExpired,
            ]
        ]);
    }

public function scan(Request $request)
{
    $member = Member::where('member_code', $request->member_code ?? null)
                    ->orWhere('phone', $request->phone ?? null)
                    ->first();

    if (!$member) {
        return response()->json(['status' => 'error', 'message' => 'Member not found.']);
    }

    $isExpired = now()->greaterThan($member->next_fee_due);

    // ✅ (Optional) Record Attendance
    Attendance::create([
        'member_id' => $member->id,
        'date' => now(),
    ]);

    return response()->json([
        'status' => 'success',
        'data' => [
            'name' => $member->name,
            'plan' => $member->membership_type,
            'start' => $member->join_date,
            'end' => $member->next_fee_due,
            'is_expired' => $isExpired,
        ]
    ]);
}


}
