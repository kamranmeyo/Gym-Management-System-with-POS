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

        // Base Query
        $membersQuery = Member::query();
        $attendanceQuery = Attendance::query();

        // 🎯 Role-based filter
        if ($user->hasRole('MaleUser')) {
            $membersQuery->where('gender', '1');
            $attendanceQuery->whereHas('member', function ($q) {
                $q->where('gender', '1');
            });
        } elseif ($user->hasRole('FemaleUser')) {
            $membersQuery->where('gender', '2');
            $attendanceQuery->whereHas('member', function ($q) {
                $q->where('gender', '2');
            });
        }
        // SuperAdmin sees all → no filter applied

        // ✅ Active Members (based on expiry)
        $activeMembers = $membersQuery->count();

        // ✅ Pending Renewals (next fee due < today)
        $pendingRenewals = (clone $membersQuery)
            ->whereDate('next_fee_due', '<', Carbon::today())
            ->count();

        // ✅ Attendance marked today
        $attendanceToday = $attendanceQuery
            ->whereDate('created_at', Carbon::today())
            ->count();

        return view('dashboard', compact('activeMembers', 'pendingRenewals', 'attendanceToday'));
    }
}
