<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member;
use Carbon\Carbon;
use DB;

class FeeReportController extends Controller
{
public function index(Request $request)
{
    $user = auth()->user();

    // Default date filter
    $from = Carbon::today()->toDateString();
    $to = Carbon::today()->toDateString();

    if ($user->hasRole('SuperAdmin')) {
        $from = $request->input('from', Carbon::today()->startOfMonth()->toDateString());
        $to = $request->input('to', Carbon::today()->toDateString());
    }

    // 🔹 Fetch individual fee records
    $query = Member::query()
        ->select('FeeSubmitDate', 'fee','name','updated_at')
        ->whereBetween('FeeSubmitDate', [$from, $to]);

    if ($user->hasRole('MaleUser')) {
        $query->where('gender', 1);
    } elseif ($user->hasRole('FemaleUser')) {
        $query->where('gender', 2);
    }

    $fees = $query->orderBy('FeeSubmitDate', 'desc')->get();

    // Total income
    $totalIncome = $fees->sum('fee');

    return view('fee.fee_report', compact('fees', 'from', 'to','totalIncome'));
}

}
