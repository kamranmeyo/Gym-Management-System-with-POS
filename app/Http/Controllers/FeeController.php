<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class FeeController extends Controller
{
    public function index()
    {
        return view('fee.index');
    }

    // Search by QR or Phone
    public function search(Request $request)
    {
        $request->validate([
            'member_code' => 'nullable|string',
            'phone' => 'nullable|string',
        ]);

        $member = null;

        if ($request->member_code) {
            $member = Member::where('member_code', $request->member_code)->first();
        } elseif ($request->phone) {
            $member = Member::where('phone', $request->phone)->orWhere('id', $request->phone ?? null)->first();
        }

        if (!$member) {
            return response()->json(['status' => 'error', 'message' => 'Member not found']);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $member->id,
                'name' => $member->name,
                'phone' => $member->phone,
                'plan' => $member->membership_type,
                'last_fee_date' => $member->last_fee_date,
                'next_fee_due' => $member->next_fee_due,
            ],
        ]);
    }



    public function update(Request $request)
{
    // Validate inputs
    $request->validate([
        'member_id' => 'required|integer|exists:members,id',
        'fee_date' => 'required|date',
    ]);

    // Find the member in the database
    $member = Member::findOrFail($request->member_id);

    // Update fee in MySQL
    $member->last_fee_date = $request->fee_date;
    $member->next_fee_due = Carbon::parse($request->fee_date)->addMonth();
    $member->save();

    // Now update the Hikvision device's `endTime` for the member
    //$baseUrl = env('HIKVISION_BASE_URL');
    //$requestUrl = $baseUrl . '/ISAPI/AccessControl/UserInfo/Modify?format=json';

    // Prepare the data to update the member on the device
    // $payload = [
    //     'UserInfo' => [
    //         'employeeNo' => (string)$member->id,  // Use the member ID as employee number
    //         'Valid' => [
    //             'enable' => true,
    //             'timeType' => 'UTC',
    //             'beginTime' => $member->last_fee_date . 'T08:00:00+08:00', // Begin time (last fee date)
    //             'endTime' => $request->fee_date . 'T23:59:59+08:00', // End time (fee date)
    //         ],
    //     ]
    // ];

    // Make the request to Hikvision API
    // $response = Http::withOptions([
    //     'auth' => ['admin', '1122@Abc', 'digest'],  // Use your Hikvision API credentials
    //     'timeout' => 15,
    // ])->withHeaders([
    //     'Content-Type' => 'application/json',
    // ])->put($requestUrl, $payload);

    // Check if the device update was successful
    // if (!$response->successful()) {
    //     return response()->json([
    //         'status' => 'error',
    //         'message' => 'Failed to update fee on the Hikvision device.',
    //         'data' => [],
    //     ], 500);
    // }
    return response()->json([
    'status' => 'success',
    'message' => 'Fee updated successfully',
    'data' => [
        'last_fee_date' => $member->last_fee_date,
        'next_fee_due' => $member->next_fee_due,
    ],
    'print_url' => route('fee.print', $member->id)
]);
}


public function print(Member $member)
{
    return view('fee.print', compact('member'));
}


}
