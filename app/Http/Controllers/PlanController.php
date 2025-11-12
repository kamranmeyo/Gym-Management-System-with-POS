<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Plan;

class PlanController extends Controller
{
    public function index()
    {
        $plans = Plan::latest()->get();
        return view('plans.index', compact('plans'));
    }

    public function create()
    {
        return view('plans.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:plans,name',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        Plan::create($request->all());
        return redirect()->route('plans.index')->with('success', 'Plan created successfully!');
    }

    public function edit(Plan $plan)
    {
        return view('plans.edit', compact('plan'));
    }

    public function update(Request $request, Plan $plan)
    {
        $request->validate([
            'name' => 'required|unique:plans,name,' . $plan->id,
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $plan->update($request->all());
        return redirect()->route('plans.index')->with('success', 'Plan updated successfully!');
    }

    public function destroy(Plan $plan)
    {
        $plan->delete();
        return redirect()->route('plans.index')->with('success', 'Plan deleted successfully!');
    }
}
