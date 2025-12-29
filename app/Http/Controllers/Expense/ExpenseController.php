<?php

namespace App\Http\Controllers\Expense;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Member;
use Illuminate\Http\Request;;


class ExpenseController extends Controller
{
    public function index()
{

    $categories = ExpenseCategory::all();
    $expenses = Expense::with('category')->latest()->get();
    return view('expenses.index', compact('expenses', 'categories'));
}

public function create()
{
    $categories = ExpenseCategory::all();
    return view('expenses.create', compact('categories'));
}

public function store(Request $request)
{
    $request->validate([
        'expense_category_id' => 'required',
        'amount' => 'required|numeric',
        'expense_date' => 'required|date',
    ]);

    Expense::create($request->all());

    return response()->json(['success' => true]);
}



public function report(Request $request)
{
        if (!auth()->user()->hasRole('SuperAdmin')) {
        abort(403, 'Unauthorized');
    }
    $from = $request->from;
    $to   = $request->to;

    // Expenses
    $expenses = Expense::with('category')
        ->when($from, fn ($q) => $q->whereDate('expense_date', '>=', $from))
        ->when($to, fn ($q) => $q->whereDate('expense_date', '<=', $to))
        ->get();

    // Total Expense
    $totalExpense = $expenses->sum('amount');

    // Income (Members Fee)
    $income = Member::query()
        ->when($from, fn ($q) => $q->whereDate('created_at', '>=', $from))
        ->when($to, fn ($q) => $q->whereDate('created_at', '<=', $to))
        ->sum('Fee');

    return view('expenses.report', compact(
        'expenses',
        'totalExpense',
        'income'
    ));
}



}
