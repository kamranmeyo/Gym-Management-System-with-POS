<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Member;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
public function index()
{
    $user = auth()->user();
    $membersQuery = Member::query();
        $attendanceQuery = Attendance::query();
    // 🔒 Male & Female users see only 0
    if ($user->hasRole('MaleUser') || $user->hasRole('FemaleUser')) {
        $activeMembers     = 0;
            $pendingRenewals = (clone $membersQuery)
        ->whereDate('next_fee_due', '<', Carbon::today())
        ->count();
            $attendanceToday = $attendanceQuery
        ->whereDate('created_at', Carbon::today())
        ->count();

        return view('dashboard', compact(
            'activeMembers',
            'pendingRenewals',
            'attendanceToday'
        ));
    }


    // ✅ Active Members
    $activeMembers = $membersQuery->count();

    // ✅ Pending Renewals
    $pendingRenewals = (clone $membersQuery)
        ->whereDate('next_fee_due', '<', Carbon::today())
        ->count();

    // ✅ Attendance Today
    $attendanceToday = $attendanceQuery
        ->whereDate('created_at', Carbon::today())
        ->count();

    return view('dashboard', compact(
        'activeMembers',
        'pendingRenewals',
        'attendanceToday'
    ));
}

}
