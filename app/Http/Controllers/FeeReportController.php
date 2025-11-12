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

        // Default values (for Male/Female users)
        $from = Carbon::today()->toDateString();
        $to = Carbon::today()->toDateString();

        // 🔹 Allow SuperAdmin to filter custom range
        if ($user->hasRole('SuperAdmin')) {
            $from = $request->input('from', Carbon::today()->startOfMonth()->toDateString());
            $to = $request->input('to', Carbon::today()->toDateString());
        }

        // 🔹 Base query
        $query = Member::select(
                DB::raw('DATE(last_fee_date) as fee_date'),
                DB::raw('SUM(fee) as total_fee')
            )
            ->whereBetween('last_fee_date', [$from, $to]);

        // 🔹 Apply gender-based restriction
        if ($user->hasRole('MaleUser')) {
            $query->where('gender', 1); // assuming 1 = male
        } elseif ($user->hasRole('FemaleUser')) {
            $query->where('gender', 2); // assuming 2 = female
        }

        $fees = $query->groupBy('fee_date')
                      ->orderBy('fee_date', 'desc')
                      ->get();


        $totalIncome = $fees->sum('total_fee');

        return view('fee.fee_report', compact('fees', 'from', 'to','totalIncome'));
    }
}
