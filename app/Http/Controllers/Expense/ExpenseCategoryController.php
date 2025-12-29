<?php

namespace App\Http\Controllers\Expense;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ExpenseCategory;

class ExpenseCategoryController extends Controller
{
    public function index()
{
    $categories = ExpenseCategory::latest()->get();
    return view('expense-categories.index', compact('categories'));
}
    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:100|unique:expense_categories,name',
    ]);

    $category = ExpenseCategory::create([
        'name' => $request->name
    ]);

    return response()->json([
        'status' => 'success',
        'data' => $category
    ]);
}


public function update(Request $request, ExpenseCategory $category)
{
    $request->validate([
        'name' => 'required|string|max:100|unique:expense_categories,name,' . $category->id,
    ]);

    $category->update([
        'name' => $request->name
    ]);

    return response()->json([
        'status' => 'success',
        'data' => $category
    ]);
}


public function destroy(ExpenseCategory $category)
{
    // Prevent delete if category is used
    if ($category->expenses()->exists()) {
        return response()->json([
            'status' => 'error',
            'message' => 'Category is used in expenses and cannot be deleted'
        ], 422);
    }

    $category->delete();

    return response()->json([
        'status' => 'success'
    ]);
}



}