<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;
use App\Models\Member;
use Illuminate\Http\Request;
 use Carbon\Carbon;
 use Illuminate\Http\Client\ConnectionException;
 use Illuminate\Support\Facades\DB;

class MemberController extends Controller
{
    // Show list of members
   public function index()
{
      if (!auth()->user()->hasRole('SuperAdmin')) {
        abort(403, 'Unauthorized');
    }
    $user = auth()->user();

    if ($user->hasRole('SuperAdmin')) {
        $members = Member::all();
    } elseif ($user->hasRole('MaleUser')) {
        $members = Member::where('gender', '1')->get();
    } elseif ($user->hasRole('FemaleUser')) {
        $members = Member::where('gender', '2')->get();
    }

    // Default empty users array
    $users = [];

    //------------------------ Code for HIKVision Machine--------------------
    try {
        $requestUrl = env('HIKVISION_BASE_URL')
            . '/ISAPI/AccessControl/UserInfo/Search?format=json';

        $payload = [
            'UserInfoSearchCond' => [
                'searchID' => (string) time(),
                'searchResultPosition' => 0,
                'maxResults' => 30
            ]
        ];

        $response = Http::withOptions([
            'auth' => ['admin', '1122@Abc', 'digest'],
            'timeout' => 5, // reduce timeout so page loads faster
        ])->withHeaders([
            'Content-Type' => 'application/json'
        ])->post($requestUrl, $payload);

        if ($response->successful()) {
            $data = $response->json();
            $users = $data['UserInfoSearch']['UserInfo'] ?? [];
        }

    } catch (ConnectionException $e) {
        // Device offline → silently ignore
        $users = [];
    }
    //------------------------ End HIKVision Code--------------------

    return view('members.index', compact('members', 'users'));
}



public function syncMembersToMachine()
{
    $members = Member::all();
    $machineUsers = [];

    try {
        $response = Http::withOptions([
            'auth' => ['admin', '1122@Abc', 'digest'],
            'timeout' => 5,
        ])->post(
            env('HIKVISION_BASE_URL') . '/ISAPI/AccessControl/UserInfo/Search?format=json',
            [
                'UserInfoSearchCond' => [
                    'searchID' => (string) time(),
                    'searchResultPosition' => 0,
                    'maxResults' => 500
                ]
            ]
        );

        if ($response->successful()) {
            $machineUsers = collect(
                $response->json()['UserInfoSearch']['UserInfo'] ?? []
            )->pluck('employeeNo')->toArray();
        }

    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Machine offline'
        ], 500);
    }

    $synced = 0;

    foreach ($members as $member) {

        if (in_array((string)$member->id, $machineUsers)) {
            continue;
        }

        $payload = [
            'UserInfo' => [
                'employeeNo' => (string) $member->id,
                'name' => $member->name,
                'userType' => 'normal',
                'Valid' => [
                    'enable' => true,
                    'beginTime' => now()->format('Y-m-d\TH:i:s'),
                    'endTime' => now()->addYear()->format('Y-m-d\TH:i:s'),
                ]
            ]
        ];

        try {
            $create = Http::withOptions([
                'auth' => ['admin', '1122@Abc', 'digest'],
                'timeout' => 5,
            ])->post(
                env('HIKVISION_BASE_URL') . '/ISAPI/AccessControl/UserInfo/Record?format=json',
                $payload
            );

            if ($create->successful()) {
                $synced++;
            }

        } catch (\Exception $e) {
            continue;
        }
    }

    return response()->json([
        'status' => 'success',
        'synced' => $synced
    ]);
}










    // Show add new member form
    public function create()
    {
        return view('members.create');
    }



    public function store_OLD(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'phone' => 'required|string|max:15|unique:members,phone',
        'gender' => 'required',
        'membership_type' => 'required|string|max:100',
        'join_date' => 'required|date',
        'expiry_date' => 'required|date',
        'fee' => 'nullable|numeric',
        'fee_method' => 'nullable|integer',
        'comment' => 'nullable|string|max:500',
    ]);

    DB::beginTransaction();

    try {
        // 1️⃣ Save in MySQL
        $member = Member::create([
            ...$validated,
            'last_fee_date' => $validated['join_date'],
            'next_fee_due' => Carbon::parse($validated['join_date'])->addMonth(),
        ]);

        
        // 2️⃣ Prepare Hikvision payload
        $payload = [
            'UserInfo' => [
                'employeeNo' => (string) $member->id, // 👈 use DB ID
                'name' => $member->name,
                'userType' => 'normal',
                'doorRight' => '1',
                'RightPlan' => [
                    [
                        'doorNo' => 1,
                        'planTemplateNo' => '1'
                    ]
                ],
                'Valid' => [
                    'enable' => true,
                    'timeType' => 'UTC',
                    'beginTime' => Carbon::parse($validated['join_date'])
                        ->format('Y-m-d\TH:i:s+08:00'),
                    'endTime' => Carbon::parse($validated['expiry_date'])
                        ->format('Y-m-d\TH:i:s+08:00'),
                ]
            ]
        ];

        // 3️⃣ Send to Hikvision machine
        $hikResponse = Http::withOptions([
            'auth' => ['admin', '1122@Abc', 'digest'],
            'timeout' => 15,
        ])->withHeaders([
            'Content-Type' => 'application/json'
        ])->post(
            env('HIKVISION_BASE_URL') . '/ISAPI/AccessControl/UserInfo/Record?format=json',
            $payload
        );

        // 4️⃣ Check machine response
        if (!$hikResponse->successful()) {
            throw new \Exception('Hikvision error: ' . $hikResponse->body());
        }

        DB::commit();

        return redirect()
            ->route('members.index')
            ->with('success', 'Member added to system and machine successfully!');

    } catch (\Exception $e) {
        DB::rollBack();

        \Log::error('Hikvision Add Person Failed', [
            'error' => $e->getMessage()
        ]);

        return back()->with(
            'error',
            'Member saved failed on machine. Please try again.'
        );
    }
}


public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'phone' => 'required|string|max:15|unique:members,phone',
        'gender' => 'required',
        'membership_type' => 'required|string|max:100',
        'join_date' => 'required|date',
        'expiry_date' => 'required|date',
        'fee' => 'nullable|numeric',
        'fee_method' => 'nullable|integer',
        'comment' => 'nullable|string|max:500',
    ]);

    // ✅ Save member FIRST (no transaction rollback)
    $member = Member::create([
        ...$validated,
        'last_fee_date' => $validated['join_date'],
        'next_fee_due' => Carbon::parse($validated['join_date'])->addMonth(),
        'is_synced' => false,
    ]);

    // 🔄 Try to sync with machine
    try {

        $payload = [
            'UserInfo' => [
                'employeeNo' => (string) $member->id,
                'name' => $member->name,
                'userType' => 'normal',
                'doorRight' => '1',
                'RightPlan' => [
                    [
                        'doorNo' => 1,
                        'planTemplateNo' => '1'
                    ]
                ],
                'Valid' => [
                    'enable' => true,
                    'timeType' => 'UTC',
                    'beginTime' => Carbon::parse($validated['join_date'])
                        ->format('Y-m-d\TH:i:s+08:00'),
                    'endTime' => Carbon::parse($validated['expiry_date'])
                        ->format('Y-m-d\TH:i:s+08:00'),
                ]
            ]
        ];

        $response = Http::withOptions([
            'auth' => ['admin', '1122@Abc', 'digest'],
            'timeout' => 5,
        ])->post(
            env('HIKVISION_BASE_URL') . '/ISAPI/AccessControl/UserInfo/Record?format=json',
            $payload
        );

        if ($response->successful()) {
            $member->update(['is_synced' => true]);
        } else {
            throw new \Exception($response->body());
        }

    } catch (\Exception $e) {

        // ❗ Keep member saved, mark sync failed
        $member->update([
            'sync_error' => $e->getMessage()
        ]);

        \Log::warning('Machine offline, member pending sync', [
            'member_id' => $member->id,
            'error' => $e->getMessage()
        ]);
    }

    return redirect()
        ->route('members.index')
        ->with(
            'success',
            'Member saved successfully. Machine sync will happen automatically.'
        );
}




    // Edit member
    public function edit(Member $member)
    {
        return view('members.edit', compact('member'));
    }

// Update member (DB + Hikvision)
public function update(Request $request, Member $member)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'phone' => 'required|string|max:20',
        'gender' => 'required',
        'membership_type' => 'required|string|max:100',
        'join_date' => 'required|date',
        'expiry_date' => 'required|date',
        'fee' => 'nullable|numeric',
        'fee_method' => 'nullable|integer',
        'comment' => 'nullable|string|max:500',
        'last_fee_date' => 'nullable|date',
    ]);

        // Convert to Hikvision datetime format
$beginTime = Carbon::parse($member->join_date)
    ->setTime(8, 0, 0)      // 08:00:00
    ->format('Y-m-d\TH:i:s+08:00');

$endTime = Carbon::parse($request->expiry_date)
    ->setTime(23, 59, 59)   // 23:59:59
    ->format('Y-m-d\TH:i:s+08:00');

   
    DB::beginTransaction();

    try {
        // 1️⃣ Fee logic
        $lastFeeDate = $validated['last_fee_date']
            ?? $member->last_fee_date
            ?? $member->join_date;

        $nextFeeDue = $member->last_fee_date != $lastFeeDate
            ? Carbon::parse($lastFeeDate)->addMonth()
            : $member->next_fee_due;

        // 2️⃣ Update MySQL
        $member->update([
            ...$validated,
            'last_fee_date' => $lastFeeDate,
            'next_fee_due' => $nextFeeDue,
        ]);


        // 3️⃣ Prepare Hikvision payload
        $payload = [
            'UserInfo' => [
                'employeeNo' => (string) $member->id, // 👈 SAME ID
                'name' => $member->name,
                'userType' => 'normal',

                'doorRight' => '1',
                'RightPlan' => [
                    [
                        'doorNo' => 1,
                        'planTemplateNo' => '1'
                    ]
                ],

                'Valid' => [
                    'enable' => true,
                    'timeType' => 'UTC',
                    'beginTime' => $beginTime,
                    'endTime' => $endTime,
                ]
            ]
        ];

        // 4️⃣ Update on Hikvision machine
        $hikResponse = Http::withOptions([
            'auth' => ['admin', '1122@Abc', 'digest'],
            'timeout' => 15,
        ])->withHeaders([
            'Content-Type' => 'application/json'
        ])->put(
            env('HIKVISION_BASE_URL')
                . '/ISAPI/AccessControl/UserInfo/Modify?format=json',
            $payload
        );

        if (!$hikResponse->successful()) {
            throw new \Exception('Hikvision update failed: ' . $hikResponse->body());
        }

        DB::commit();

        return redirect()
            ->route('members.index')
            ->with('success', 'Member updated in system and machine successfully!');

    } catch (\Exception $e) {
        DB::rollBack();

        \Log::error('Hikvision Update Failed', [
            'member_id' => $member->id,
            'error' => $e->getMessage()
        ]);

        return back()->with(
            'error',
            'Member updated in system, but failed on machine.'
        );
    }
}






public function destroy(Member $member)
{
    set_time_limit(120); // Allow the script to run for 120 seconds
    $baseUrl = env('HIKVISION_BASE_URL'); 

    try {
        // Start a database transaction to ensure consistency
        DB::beginTransaction();

        // 1️⃣ Start device deletion
        $deleteUrl = $baseUrl . '/ISAPI/AccessControl/UserInfoDetail/Delete?format=json';

        // Correct JSON body
        $payload = [
            'UserInfoDetail' => [
                'mode' => 'byEmployeeNo',  // Specify deletion mode as 'byEmployeeNo'
                'EmployeeNoList' => [
                    [
                        'employeeNo' => (string) $member->id  // The employee number (user code)
                    ]
                ]
            ]
        ];

        // Send DELETE request to the device
        $response = Http::withOptions([
            'auth' => ['admin', '1122@Abc', 'digest'],
            'timeout' => 15,
        ])->withHeaders([
            'Content-Type' => 'application/json'
        ])->put($deleteUrl, $payload);

        // Check if the device responded successfully
        if (!$response->successful()) {
            // Log the error for further debugging
            Log::error('Failed to delete user from device: ' . $response->body());
            return response($response->body(), $response->status())
                ->header('Content-Type', 'application/json');
        }

        // 2️⃣ Poll DeleteProcess endpoint until progress = 100
        // $progress = 0;
        // $progressUrl = $baseUrl . '/ISAPI/AccessControl/UserInfoDetail/DeleteProcess?format=json';

        // do {
        //     sleep(5); // wait 5 second
        //     $progressResponse = Http::withOptions([
        //         'auth' => ['admin', '1122@Abc', 'digest'],
        //         'timeout' => 10,
        //     ])->get($progressUrl);

        //     if (!$progressResponse->successful()) {
        //         // Log progress check error
        //         Log::error('Failed to check deletion progress on the device: ' . $progressResponse->body());
        //         return redirect()->route('members.index')
        //             ->with('error', 'Failed to check deletion progress on the device.');
        //     }

        //     $progressData = $progressResponse->json();
        //     $progress = $progressData['progress'] ?? 0;

        // } while ($progress < 100); // Poll until the progress reaches 100%

        // // 3️⃣ Delete from the database after device deletion completes
        // // Log to track DB deletion
        // Log::info("Deleting member with ID: {$member->id} from the database.");
        $member->delete();

        // Commit the transaction
        DB::commit();

        return redirect()->route('members.index')
            ->with('success', 'Member deleted successfully from both device and database!');
        
    } catch (\Exception $e) {
        // Rollback the transaction in case of error
        DB::rollBack();

        // Log the error for debugging
        Log::error('Error during member deletion: ' . $e->getMessage());

        return redirect()->route('members.index')
            ->with('error', 'Error: ' . $e->getMessage());
    }
}

}
