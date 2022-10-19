<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RequestModel;
use App\Http\Requests\ApiRequests;
use App\Http\Resources\ApiRequestResource;
use Illuminate\Support\Facades\Http;
use DB;
use App\Models\Group;
use App\Models\Account;
use App\Models\ActivityLog;
// use Auth;
use Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use App\Models\UserType;
use App\Jobs\ProcessRequest;
use App\Models\Schedule;
use App\Models\ScheduledAccount;

class RequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pendingRequests = RequestModel::where('status','pending')->count();
        $approvedGroupRequests = RequestModel::where('status','approved')
                                        ->where('is_processed',0)
                                        ->whereIn('operation', ['groups.create','groups.update'])
                                        // ->get()
                                        ->count();

        return json_encode([
            'pendingRequests' => $pendingRequests,
            'approvedGroupRequests' => $approvedGroupRequests
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function storeGroupRequest(Request $request)
    {
        $user = auth()->user();
        $uuid = request()->uuid;
        $requestName = request()->operation;

        $table = '';
        $operation = '';
        list($table, $operation) = explode('.', $requestName);

        if ($operation == 'create') {
            $uuid = (string) Str::uuid();
            $checkInRequests = false;
        } else if ($operation == 'update') {
            // $checkInRequests = RequestModel::where('operation', $requestName)
            //                     ->where('uuid', $uuid)
            //                     ->where('status','pending')
            //                     ->first();
            $checkInRequests = RequestModel::whereraw("operation = '$requestName'
                                            and (status = 'approved' AND is_processed = 0  and uuid = '$uuid')
                                            or (status = 'pending' and uuid = '$uuid')")
                                            ->first();
        }


        if (!$checkInRequests) {

            $form = [];

            if ($operation == 'create') {

                $messages = [
                    'digits_between' => 'Mobile Number must be 10 to 15 digits',
                ];
                $validator = Validator::make(request()->all(), [
                    'group-name' => 'required|unique:groups,name',
                    'group-address' => 'required',
                    'group-type' => 'required|max:20',
                    'group-code' => 'required|unique:groups,code',
                    'group-operator' => 'required|string',
                    'province-id' => 'required|numeric',
                    'group-contact' => 'required|numeric|digits_between:10,15',
                    'group-guarantor' => 'required',
                    'is_active' => 'required|boolean',
                    'remarks' => 'nullable|string'
                ]);
        
                if ($validator->fails()) {
                    return redirect()->back()->with('errors', $validator->messages());
                }

                $type = [
                    0 => 'foo',
                    1 => 'ARENA',
                    2 => 'OCBS-LOTTO',
                    3 => 'OCBS-OTB',
                    4 => 'OCBS-RESTOBAR',
                    5 => 'OCBS-STORE',
                    6 => 'OCBS-MALL',
                    7 => 'OCBS',
                    8 => 'OCBS-EGAMES',
                    9 => 'OCBS-CASINO'
                ];
                
                $form = [
                    'api_key' => '4e829e510539afcc43365a18acc91ede41fb555e',
                    'uuid' => $uuid,
                    'operation' => $requestName,
                    'status' => 'pending',
                    'data' => json_encode([
                        'uuid' => $uuid,
                        'name' => $request->input('group-name'),
                        'code' => $request->input('group-code'),
                        'owner' => $request->input('group-operator'),
                        'contact' => $request->input('group-contact'),
                        'province_id' => $request->input('province-id'),
                        'address' => $request->input('group-address'),
                        'is_active' => $request->input('is_active'),
                        'type' => $type[$request->input('group-type')],
                        'guarantor' => $request->input('group-guarantor')
                    ]),
                    'remarks' => $request->remarks,
                    'requested_by' => $user->name . '('. UserType::find($user->user_type_id)->role .')',
                    'reference_number' => 'KRM' . $user->id . '-' . time()
                ];
                
                $apiRequest = RequestModel::create($form);

            } else if($operation == 'update') {

                $validator = Validator::make(request()->all(), [
                    'uuid' => 'required',
                    'operation' => 'required',
                    'status' => 'required',
                    'data' => 'required',
                    'remarks' => 'nullable|string'
                ]);
        
                if ($validator->fails()) {
                    return redirect()->back()->with('errors', $validator->messages());
                }

                $form = $request->all();

                $noChanges = $this->checkChanges('groups',$uuid, $form['data']);

                if($noChanges) {
                    return response([
                        'result' => 0,
                        'message' => 'No changes in the form detected.'
                    ], 200);
                }

                $form['requested_by'] = $user->name . '('. UserType::find($user->user_type_id)->role .')';
                $form['reference_number'] = 'KRM' . $user->id . '-' . time();

                $apiRequest = RequestModel::create($form);
            }

            if ($apiRequest) {

                ProcessRequest::dispatch();

                $assests = $form;
                
                unset($assests['api_key']);

                $logs = ActivityLog::create([
                    'type' => 'post-request',
                    'user_id' => $user->id,
                    'assets' => json_encode(array_merge([
                        'action' => 'Posted a request to OCBS application',
                        'request-type' => $requestName
                    ],$assests))
                ]);

                // dd($logs);

                if ($operation == 'update') {
                    // dd($request->all());
                    // $this->postRequestToKiosk($request->all());
                    $this->postRequestToKiosk($form);
                    
                    return response([
                        'result' => 1,
                        'message' => 'Please monitor the status of your requests on the Requests tab!'
                    ], 201);
                }

                
                $this->postRequestToKiosk($form);
                return redirect()->route('requests.index')->with('success', 'Please monitor the status of your requests on the Requests tab!'); 

            } else {

                return response([
                    'result' => 0,
                    'message' => 'Something went wrong! Please try again.'
                ], 200);

            }
        } else {
            if ($operation == 'update') {
                if($checkInRequests->status == 'pending'){
                    return response([
                        'result' => 0,
                        'message' => 'Request already exists.'
                    ], 200);
                }else{
                    return response([
                        'result' => 0,
                        'message' => 'Request already approved but not yet processed by C-Band.'
                    ], 200);
                }
            }

            return redirect()->back()->with('error', 'Request already exists'); 

        }
        // return new ApiRequestResource($apiRequest);
    }

    public function postRequestToKiosk($postInput)
    {
        $user = auth()->user();

        $environment = env('APP_ENV');

        if ($environment == 'production') {
            // temporary
            // direct to site A
            // follow up condition to detect if site is for site A or B
            $host = request()->getHost();
            if ($host == 'schedule.wpc2040.live') {
                $apiURL = 'https://admin.wpc2040.live/api/v4/requests';
            } else if ($host == 'schedule.wpc2040aa.live') {
                $apiURL = 'https://admin.wpc2040aa.live/api/v4/requests';
            }
        } else {
            // $apiURL = 'https://development.wpc2040.live/api/v4/requests';
            $devHost = request()->getHost();
            if ($devHost == 'devsched.wpc2040.live') {
                // for develop server
                // BMM server
                $apiURL = 'https://develop.wpc2040.live//api/v4/requests';
            } else if ($devHost == 'devschedule.wpc2040.live') {
                // for official dev server
                // dev2
                $apiURL = 'https://development.wpc2040.live/api/v4/requests';
            }
        }

        $apiKey = env('KIOSK_API_KEY');
  
        $headers = [
            'X-header' => 'value',
            'Content-Type' => 'application/json',
            'accept' => 'application/json'
        ];

        $postInput['requested_by'] = '.' . $user->name . '('. UserType::find($user->user_type_id)->role .')';
        
        $response = Http::withHeaders($headers)->post($apiURL, $postInput);
        
        $statusCode = $response->status();
        
        $responseBody = json_decode($response->getBody(), true);
        
        return $responseBody;
    }

    public function updateRequest(ApiRequests $request)
    {
        // update local requests 
        // post from kiosk
        
        $checkInRequests = RequestModel::where('uuid', $request->uuid)
                                ->where('operation', $request->operation)
                                ->where('status', 'pending')
                                ->first();

        if (!$checkInRequests) {

            return response([
                'status' => 'error',
                'message' => 'Request not found. No row updated.'
            ], 200);

        } else {
            // update request

            $acceptChanges = RequestModel::where('uuid', $request->uuid)
                                ->where('operation', $request->operation)
                                ->where('status', 'pending')
                                ->update([
                                    'status' => $request->status,
                                    // 'remarks' => DB::raw('remarks') . ' ; ' . $request->remarks
                                ]);
                
            ProcessRequest::dispatch();
                                
            if ($acceptChanges && $request->status == 'approved') {

                // Update Helpdesk groups and users table

                $table = '';
                $operation = '';
                list($table, $operation) = explode('.', $request->operation);

                $data = json_decode($request->data, true);
                
                if ($table == 'groups') {

                    // dd($data);
                    if (isset($data['type'])) {
                        $data['group_type'] = $data['type'];
                        unset($data['type']);
                    }

                    if ($request->remarks) {
                        $data['remarks'] = $request->remarks;
                    }

                    if ($operation == 'update') {
    
                        $result = Group::where('uuid', $request->uuid)->update($data);
    
                    } else if($operation == 'create') {

                        $result = Group::create($data);

                    }
    
                } else if ($table == 'users') {

                    if (isset($data['firstname'])) {
                        $data['first_name'] = $data['firstname'];
                        unset($data['firstname']);
                    }

                    if (isset($data['lastname'])) {
                        $data['last_name'] = $data['lastname'];
                        unset($data['lastname']);
                    }

                    if (isset($data['allowed_sides'])) {
                        $allowedSides = [
                            'm' => 'Meron only',
                            'w' => 'Wala only',
                            'n' => 'None',
                            'a' => 'All sides'
                        ];
                        $data['allowed_sides'] = $allowedSides[$data['allowed_sides']];
                    }

                    if (isset($data['group_code'])) {
                        $groupInfo = Group::select('id')->where('code', $data['group_code'])->first();
                        $data['group_id'] = $groupInfo->id;
                        unset($data['group_code']);
                    }

                    if ($request->remarks) {
                        $data['remarks'] = $request->remarks;
                    }
    
                    if ($operation == 'update') {
    
                        $result = Account::where('uuid', $request->uuid)->update($data);

                        // check if there is an ongoing event;
                        // update user allowed_sides and position in scheduled_accounts for that user;
                        $checkOngoingEvent = Schedule::select('id')->where('status','active')->orderBy('id','desc')->first();
                        if ($checkOngoingEvent) {
                            $accountInfo = Account::where('uuid', $request->uuid)->first();
                            ScheduledAccount::updateUserCurEvent($checkOngoingEvent->id, $accountInfo->id, $data);
                        }
    
                    } else if($operation == 'create') {

                        $result = Account::create($data);

                    }
                }

                
                ActivityLog::create([
                    'type' => 'received-update',
                    'user_id' => 0,
                    'assets' => json_encode(array_merge([
                        'action' => 'Received update from a processed request. From ocbs.',
                        'uuid' => $request->uuid,
                        'target_table' => $table
                    ], $request->except(['table'])))
                ]);

                return response([
                    'status' => 'ok',
                    'message' => 'Row updated.'
                ], 200);

            } else if ($acceptChanges && $request->status == 'rejected') {

                $table = '';
                $operation = '';
                list($table, $operation) = explode('.', $request->operation);

                ActivityLog::create([
                    'type' => 'received-update',
                    'user_id' => 0,
                    'assets' => json_encode([
                        'action' => 'Received a rejected request. From ocbs.',
                        'uuid' => $request->uuid,
                        'target_table' => $table
                    ])
                ]);

                return response([
                    'status' => 'ok',
                    'message' => 'Row updated. Request rejected.'
                ], 200);
                
            } else {
                return response([
                    'status' => 'error',
                    'message' => 'Failed. No row updated.'
                ], 200);
            }

        }

    }

    public function storeAccountRequest(Request $request)
    {
        $user = auth()->user();
        $allowedUsersToCreate = [1,2,3];
        $requestName = request()->operation;

        $table = '';
        $operation = '';
        list($table, $operation) = explode('.', $requestName);
        
        $unique = '';

        if ($operation == 'create') {
            $uuid = (string) Str::uuid();
            $checkInRequests = false;
            $unique = '|unique:accounts';
        } else {
            $uuid = request()->uuid;
            $checkInRequests = RequestModel::where('operation', $requestName)
                                ->where('uuid', $uuid)
                                ->where('status','pending')
                                ->first();
        }

        
        if(in_array($user->user_type_id, $allowedUsersToCreate)) {
            $messages = [
                'digits_between' => 'Mobile Number must be 10 to 15 digits',
            ];
            $validator = Validator::make(request()->all(), [
                'operation' => 'required',
                'first-name' => 'required|string|max:50',
                'last-name' => 'required|string|max:50',
                'username' => 'required|string|max:50'.$unique,
                'contact' => 'required|digits_between:10,15|numeric',
                'position' => ['required', Rule::in(['Cashier','Teller','Teller/Cashier','Supervisor','Operator'])],
                'allowed-sides' => ['required', Rule::in(['m','w','n','a'])],
                'is-active' => 'required|boolean',
                'remarks' => 'nullable|string|max:300',
                'group-code' => 'required|max:10'
            ]);

            $formData = [
                'uuid' => $uuid,
                'firstname' => $request->input('first-name'),
                'lastname' => $request->input('last-name'),
                'username' => $request->username,
                'contact' => $request->contact,
                'position' => $request->position,
                'allowed_sides' => $request->input('allowed-sides'),
                'is_active' => $request->input('is-active'),
                'group_code' => $request->input('group-code')
            ];

        } else {

            $validator = Validator::make(request()->all(), [
                'operation' => 'required',
                'is-active' => 'required|boolean',
                'remarks' => 'nullable|string|max:300'
            ]);

            $formData = [
                'uuid' => $uuid,
                'is_active' => $request->input('is-active'),
            ];

        }

        if ($validator->fails()) {
            return redirect()->back()->withInput()->with('errors', $validator->messages());
        }

        $form = [
            'api_key' => '4e829e510539afcc43365a18acc91ede41fb555e',
            'uuid' => $uuid,
            'operation' => $requestName,
            'status' => 'pending',
            'data' => json_encode($formData),
            'remarks' => $request->remarks,
            'requested_by' => $user->name . '('. UserType::find($user->user_type_id)->role .')',
            'reference_number' => 'KRM' . $user->id . '-' . time()
        ];

        if ($operation == 'update') {
            $noChanges = $this->checkChanges('users',$uuid, $form['data']);

            if($noChanges) {
                return redirect()->route('accounts.index')->with('error', 'No changes in the form detected.');
            }
        }

        if (!$checkInRequests) {
            $apiRequest = RequestModel::create($form);
            if ($apiRequest) {
                
                ProcessRequest::dispatch();
                
                $this->postRequestToKiosk($form);
                $forReferenceNumber = extract($form);
                $logs = ActivityLog::create([
                    'type' => 'post-request',
                    'user_id' => $user->id,
                    'assets' => json_encode(array_merge([
                        'action' => 'Posted a request to OCBS application',
                        'request-type' => $requestName,
                        'reference_number' => $reference_number
                    ],$request->except(['api_key','_token'])))
                ]);

                return redirect()->route('accounts.index')->with('success', 'Request posted! Please monitor the status of your requests on the Requests tab!'); 

            } else {
                return redirect()->route('accounts.index')->with('error', 'Something went wrong! Please try again.'); 
            }
        } else {
            return redirect()->route('accounts.index')->with('error', 'Request already exists.'); 
        }
    }

    public function checkChanges($table, $uuid, $newData)
    {
        $diff = [];
        $newData = json_decode($newData, true);

        if ($table == 'groups') {
            $oldData = Group::where('uuid', $uuid)->first()->toArray();
            foreach($newData as $index => $value) {
                if ($oldData[$index] != $newData[$index]) {
                    array_push($diff, $index);
                }
            }
        } else if($table == 'users') {
            $oldData = Account::where('uuid', $uuid)->first()->toArray();

            // dd($oldData, $newData);

            $allowedSides = [
                'm' => 'Meron only',
                'w' => 'Wala only',
                'n' => 'None',
                'a' => 'All sides'
            ];

            if(isset($newData['firstname'])) {
                $newData['first_name'] = $newData['firstname'];
                unset($newData['firstname']);
            }
            if(isset($newData['lastname'])) {
                $newData['last_name'] = $newData['lastname'];
                unset($newData['lastname']);
            }
            if(isset($newData['group_code'])) {
                unset($newData['group_code']);
            }
            // if(isset($newData['allowed_sides'])) {
                
            // }
            foreach($newData as $index => $value) {
                if ($index == 'allowed_sides') {
                    if ($allowedSides[$newData[$index]] != $oldData[$index]) {
                        array_push($diff, $index);
                    }
                } else if ($oldData[$index] != $newData[$index]) {
                    array_push($diff, $index);
                }
                
            }
        }
        // dd($diff);
        return empty($diff);
    }

}
