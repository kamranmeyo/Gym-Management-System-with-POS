<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
 use Carbon\Carbon;

class MemberController extends Controller
{
    // Show list of members
    public function index()
    {
       // $members = Member::latest()->paginate(10);

            $user = auth()->user();

    if ($user->hasRole('SuperAdmin')) {
        $members = Member::all();
    } elseif ($user->hasRole('MaleUser')) {
        $members = Member::where('gender', '1')->get();
    } elseif ($user->hasRole('FemaleUser')) {
        $members = Member::where('gender', '2')->get();
    }
        return view('members.index', compact('members'));
    }

    // Show add new member form
    public function create()
    {
        return view('members.create');
    }

  

public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'phone' => 'required|string|max:15|unique:members,phone',
        'gender' => 'required',
        'membership_type' => 'required|string|max:100',
        'join_date' => 'required|date',
        'fee' => 'nullable|numeric',
        'fee_method' => 'nullable|integer',
        'comment' => 'nullable|string|max:500',
        'last_fee_date' => 'nullable|date',
    ]);

    // ✅ Safe fee date handling
    $lastFeeDate =  $validated['join_date'];
    $nextFeeDue = Carbon::parse($lastFeeDate)->addMonth();


    $memberData = array_merge($validated, [
        'last_fee_date' => $lastFeeDate,
        'next_fee_due' => $nextFeeDue,
    ]);

    Member::create($memberData);

    return redirect()->route('members.index')->with('success', 'Member added successfully!');
}


    // Edit member
    public function edit(Member $member)
    {
        return view('members.edit', compact('member'));
    }

    // Update member
public function update(Request $request, Member $member)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'phone' => 'required|string|max:20',
        'gender' => 'required',
        'membership_type' => 'required|string|max:100',
        'join_date' => 'required|date',
        'expiry_date' => 'required|date|after_or_equal:join_date',
        'fee' => 'nullable|numeric',
        'fee_method' => 'nullable|integer',
        'comment' => 'nullable|string|max:500',
        'last_fee_date' => 'nullable|date',
    ]);

    // ✅ Handle fee due logic
    $lastFeeDate = $validated['last_fee_date'] ?? $member->last_fee_date ?? $member->join_date;
    
    // Agar last_fee_date change hui ho to next fee due update karo
    if ($member->last_fee_date != $lastFeeDate) {
        $nextFeeDue = Carbon::parse($lastFeeDate)->addMonth();
    } else {
        $nextFeeDue = $member->next_fee_due;
    }

    // ✅ Merge all data safely
    $updateData = array_merge($validated, [
        'last_fee_date' => $lastFeeDate,
        'next_fee_due' => $nextFeeDue,
    ]);

    $member->update($updateData);

    return redirect()->route('members.index')->with('success', 'Member updated successfully!');
}

    // Delete member
    public function destroy(Member $member)
    {
        $member->delete();
        return redirect()->route('members.index')->with('success', 'Member deleted successfully!');
    }
}
