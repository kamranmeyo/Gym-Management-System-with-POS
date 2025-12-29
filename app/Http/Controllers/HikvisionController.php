<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class HikvisionController extends Controller
{
    public function deviceInfo()
    {

        
        $requestUrl = env('HIKVISION_BASE_URL') . '/ISAPI/System/deviceInfo?format=json';

        $response = Http::withOptions([
            'auth' => ['admin', '1122@Abc', 'digest'],
            'timeout' => 10,
        ])->get($requestUrl);

        return response($response->body(), 200)
            ->header('Content-Type', 'application/xml');
    }

    public function checkcapability(){
         $requestUrl = env('HIKVISION_BASE_URL') . '/ISAPI/AccessControl/capabilities?format=json';
        $response = Http::withOptions([
            'auth' => ['admin', '1122@Abc', 'digest'],
            'timeout' => 10,
        ])->get($requestUrl);

                return response($response->body(), 200)
            ->header('Content-Type', 'application/xml');

    }    
    public function systemIOpability(){
         $requestUrl = env('HIKVISION_BASE_URL') . '/ISAPI/System/IO/capabilities?format=json';
        $response = Http::withOptions([
            'auth' => ['admin', '1122@Abc', 'digest'],
            'timeout' => 10,
        ])->get($requestUrl);

                return response($response->body(), 200)
            ->header('Content-Type', 'application/json');

    }
        public function UserInfoCount(){ // get the count of total numer of user in machine just show number
         $requestUrl = env('HIKVISION_BASE_URL') . '/ISAPI/AccessControl/UserInfo/Count?format=json';

        $response = Http::withOptions([
            'auth' => ['admin', '1122@Abc', 'digest'],
            'timeout' => 10,
        ])->get($requestUrl);

                return response($response->body(), 200)
            ->header('Content-Type', 'application/json');

    }       
     public function UserInfoCapabilities(){
         $requestUrl = env('HIKVISION_BASE_URL') . '/ISAPI/AccessControl/UserInfo/capabilities?format=json';
        $response = Http::withOptions([
            'auth' => ['admin', '1122@Abc', 'digest'],
            'timeout' => 10,
        ])->get($requestUrl);

                return response($response->body(), 200)
            ->header('Content-Type', 'application/json');

    }     public function UserInfoDeleteCapabilities(){
         $requestUrl = env('HIKVISION_BASE_URL') . '/ISAPI/AccessControl/UserInfoDetail/Delete/capabilities?format=json';
        $response = Http::withOptions([
            'auth' => ['admin', '1122@Abc', 'digest'],
            'timeout' => 10,
        ])->get($requestUrl);

                return response($response->body(), 200)
            ->header('Content-Type', 'application/json');

    }
    public function ListAllPersons(Request $request)
{
    $requestUrl = env('HIKVISION_BASE_URL') . '/ISAPI/AccessControl/UserInfo/Search?format=json';

    // Pagination (optional, defaults)
    $pageNo   = $request->input('pageNo', 1);
    $pageSize = $request->input('pageSize', 30);

    // Build the search payload
 $payload = [
    'UserInfoSearchCond' => [
        'searchID' => (string) time(),
        'searchResultPosition' => 0,
        'maxResults' => 30
    ]
];

   
    $response = Http::withOptions([
        'auth' => ['admin', '1122@Abc', 'digest'],
        'timeout' => 15,
    ])->withHeaders([
        'Content-Type' => 'application/json'
    ])->post($requestUrl, $payload);

    return response($response->body(), $response->status())
        ->header('Content-Type', 'application/json');
}

public function searchPersonByEmployeeNo(Request $request)
{
    $requestUrl = env('HIKVISION_BASE_URL') . '/ISAPI/AccessControl/UserInfo/Search?format=json';

    // Example: employeeNo from query param or default to "6"
    $employeeNo = $request->input('employeeNo', '2');

    // Build the search payload
    $payload = [
        'UserInfoSearchCond' => [
            'searchID' => '1',             // unique search ID
            'searchResultPosition' => 0,   // start position
            'maxResults' => 10,            // number of results
            'EmployeeNoList' => [
                ['employeeNo' => $employeeNo]
            ]
        ]
    ];

    $response = Http::withOptions([
        'auth' => ['admin', '1122@Abc', 'digest'],
        'timeout' => 15,
    ])->withHeaders([
        'Content-Type' => 'application/json'
    ])->post($requestUrl, $payload);

    return response($response->body(), $response->status())
        ->header('Content-Type', 'application/json');
}
public function searchPersonByEmployeeNoAllDetails(Request $request)
{
    $requestUrl = env('HIKVISION_BASE_URL') . '/ISAPI/AccessControl/UserInfo/Get?format=json';

    // Example: employeeNo from query param or default to "6"
    $employeeNo = $request->input('employeeNo', '2');

    // Build the search payload
    $payload = [
        'UserInfoCond' => [
            'employeeNo' => '1'
        ]
    ];

    $response = Http::withOptions([
        'auth' => ['admin', '1122@Abc', 'digest'],
        'timeout' => 15,
    ])->withHeaders([
        'Content-Type' => 'application/json'
    ])->post($requestUrl, $payload);

    return response($response->body(), $response->status())
        ->header('Content-Type', 'application/json');
}

public function editPerson(Request $request)
{
    $requestUrl = env('HIKVISION_BASE_URL') . '/ISAPI/AccessControl/UserInfo/Modify?format=json';

    // Required inputs
    $employeeNo = "2";  // e.g., "2"
    $beginTime  =  "2025-12-12T08:00:00+08:00";
    $endTime    = "2030-12-30T23:59:59+08:00";

    if (!$employeeNo || !$beginTime || !$endTime) {
        return response()->json(['error' => 'employeeNo, beginTime, and endTime are required'], 400);
    }

    $payload = [
        'UserInfo' => [
            'employeeNo' => $employeeNo,
            'Gender' => "Female",
            'Valid' => [
                'enable' => true,
                'timeType' => 'UTC',
                'beginTime' => $beginTime,
                'endTime' => $endTime,
                'Gender' => "Female"
            ]
        ]
    ];

    $response = Http::withOptions([
        'auth' => ['admin', '1122@Abc', 'digest'],
        'timeout' => 15,
    ])->withHeaders([
        'Content-Type' => 'application/json'
    ])->put($requestUrl, $payload);

    return response($response->body(), $response->status())
        ->header('Content-Type', 'application/json');
}

public function addPerson(Request $request)
{
    $requestUrl = env('HIKVISION_BASE_URL')
        . '/ISAPI/AccessControl/UserInfo/Record?format=json';

    // Required fields
    $employeeNo = $request->input('employeeNo', '4');
    $name = $request->input('name', 'Rohail');

    // Optional fields
    $beginTime  = $request->input('beginTime', '2025-12-17T08:00:00+08:00');
    $endTime    = $request->input('endTime', '2026-01-17T23:59:59+08:00');
    $userType   = $request->input('userType', 'normal');

    if (!$employeeNo || !$name) {
        return response()->json([
            'error' => 'employeeNo and name are required'
        ], 400);
    }

$payload = [
    'UserInfo' => [
        'employeeNo' => (string) $employeeNo,
        'name'       => $name,
        'userType'   => $userType,

        // Door permission
        'doorRight' => '1',

        // Access plan
        'RightPlan' => [
            [
                'doorNo' => 1,
                'planTemplateNo' => '1'
            ]
        ],

        // Valid time
        'Valid' => [
            'enable'    => true,
            'timeType'  => 'UTC',
            'beginTime' => $beginTime,
            'endTime'   => $endTime
        ]
    ]
];


    $response = Http::withOptions([
        'auth' => ['admin', '1122@Abc', 'digest'],
        'timeout' => 15,
    ])->withHeaders([
        'Content-Type' => 'application/json'
    ])->post($requestUrl, $payload);

    return response($response->body(), $response->status())
        ->header('Content-Type', 'application/json');
}

public function Eventcapabilities()
{
    // Define the device's base URL
    $baseUrl = env('HIKVISION_BASE_URL'); // Example: http://192.168.1.100

    // Prepare the URL for fetching attendance records
    $requestUrl = $baseUrl . '/ISAPI/AccessControl/AcsEventTotalNum/capabilities?format=json';

    // Make the request to the Hikvision device
    $response = Http::withOptions([
        'auth' => ['admin', '1122@Abc', 'digest'], // Device credentials
        'timeout' => 15,
    ])->withHeaders([
        'Content-Type' => 'application/xml'
    ])->get($requestUrl);

    // Check if the request was successful
    if ($response->successful()) {
        // Parse and return the response as JSON
      return response($response->body(), $response->status())
        ->header('Content-Type', 'application/xml');
    } else {
        // Handle failure (e.g., device is unreachable, bad request, etc.)
    return response($response->body(), $response->status())
        ->header('Content-Type', 'application/xml');
    }
}
public function EventSearch(Request $request)
{
    // Define the device's base URL
    $baseUrl = env('HIKVISION_BASE_URL'); // Example: http://192.168.1.100

    // Prepare the URL for posting to search for access control events
    $requestUrl = $baseUrl . '/ISAPI/AccessControl/AcsEvent?format=json';

    // Prepare the payload with search criteria
    $payload = [
        'AcsEventSearch' => [
            'searchID' => '1',         // A unique search ID for the query
            'employeeNo' => '24' // Optional: Employee number (e.g., "2")
           // 'eventType' => '', // Optional: Event type (e.g., "entry", "exit")
           // 'startTime' => $request->start_time, // Optional: Start time (e.g., "2025-12-01T00:00:00+08:00")
           // 'endTime' => $request->end_time, // Optional: End time (e.g., "2025-12-31T23:59:59+08:00")
        ]
    ];

    // Make the POST request to search for events
    $response = Http::withOptions([
        'auth' => ['admin', '1122@Abc', 'digest'], // Device credentials
        'timeout' => 15,
    ])->withHeaders([
        'Content-Type' => 'application/json' // Content type is JSON since we expect JSON response
    ])->post($requestUrl, $payload);

    // Check if the request was successful
    if ($response->successful()) {
        // Parse and return the response as JSON
        return response($response->body(), $response->status())
            ->header('Content-Type', 'application/json');
    } else {
        // Handle failure (e.g., device is unreachable, bad request, etc.)
        return response($response->body(), $response->status())
            ->header('Content-Type', 'application/json');
    }
}

public function getAttendanceEvents()
{
    // Define the device's base URL (replace with your Hikvision device's IP)
    $baseUrl = env('HIKVISION_BASE_URL'); // Example: http://192.168.1.100

    // Prepare the URL for retrieving the attendance events
    $requestUrl = $baseUrl . '/ISAPI/AccessControl/AcsEvent?format=json';

    // Prepare the payload for querying attendance-related events
    $payload = [
        'AcsEventCond' => [
            'searchID' => '1',  // Unique search ID for this request
            'searchResultPosition' => 0,  // Start position for results
            'maxResults' => 30,  // Limit the results to 30
            'major' => 1,  // Major event type (1 = access granted)
            'minor' => 'all',  // Minor event type (1024 = specific event)

        
            
          
           
            'beginSerialNo' => 1,  // Start serial number for the events
            'endSerialNo' => 30,  // End serial number for the events
            'employeeNoString' => '24',  // Employee number (optional)
           
            
     
            'isAttendanceInfo' => true,  // Only include attendance events
            'hasRecordInfo' => true  // Include record info with the event
        ]
    ];

    // Make the POST request to the Hikvision device
    $response = Http::withOptions([
        'auth' => ['admin', '1122@Abc', 'digest'],  // Replace with your actual device credentials
        'timeout' => 15,  // Timeout in seconds
    ])->withHeaders([
        'Content-Type' => 'application/json',  // Specify content type as JSON
    ])->post($requestUrl, $payload);  // POST request to fetch the attendance events

    // Check if the request was successful
    if ($response->successful()) {
        // Return the response containing the attendance events
        return response($response->body(), $response->status())
            ->header('Content-Type', 'application/json');
    } else {
        // Handle failure if the device returns an error
        return response($response->body(), $response->status())
            ->header('Content-Type', 'application/json');
    }
}


public function ArmingwithoutSubscription()
{
    $client = new Client([
        'base_uri' => env('HIKVISION_BASE_URL'),
        'auth'     => ['admin', '1122@Abc', 'digest'],
        'timeout'  => 0,           // IMPORTANT: no timeout
        'read_timeout' => 0,
    ]);

    $response = $client->request('GET', '/ISAPI/Event/notification/alertStream', [
        'headers' => [
            'Connection' => 'keep-alive',
            'Accept'     => 'multipart/x-mixed-replace',
        ],
        'stream' => true,          // IMPORTANT
    ]);

    $body = $response->getBody();
dd($body);
    while (!$body->eof()) {
        $chunk = $body->read(1024);

        if (!empty(trim($chunk))) {
            // Each chunk may contain part of an event
            // You must parse by boundary (e.g. --boundary)
            logger($chunk);

            // Example: forward to frontend, websocket, queue, etc.
        }
    }
}


}
