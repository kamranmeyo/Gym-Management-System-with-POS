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



public function scan(Request $request)
{
    $member = Member::where('member_code', $request->member_code ?? null)
                    ->orWhere('phone', $request->phone ?? null)
                    ->orWhere('id', $request->phone ?? null)
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


public function list(Request $request)
{
    $query = Attendance::with('member');

    // Date filters
    if ($request->filled('date_from')) {
        $query->whereDate('date', '>=', $request->date_from);
    }
    if ($request->filled('date_to')) {
        $query->whereDate('date', '<=', $request->date_to);
    }

    // Role-based member gender filter
    if (auth()->user()->hasRole('MaleUser')) {
        $query->whereHas('member', function($q) {
            $q->where('gender', 1); // 1 = Male
        });
    } elseif (auth()->user()->hasRole('FemaleUser')) {
        $query->whereHas('member', function($q) {
            $q->where('gender', 2); // 2 = Female
        });
    }
    // SuperAdmin can see all, no filter needed

    $attendance = $query->get();

    return view('attendance.list', compact('attendance'));
}


}
