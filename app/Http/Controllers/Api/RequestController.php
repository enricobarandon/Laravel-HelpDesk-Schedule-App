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

class RequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
            $checkInRequests = RequestModel::where('operation', $requestName)
                                ->where('uuid', $uuid)
                                ->where('status','pending')
                                ->first();
        }


        if (!$checkInRequests) {

            if ($operation == 'create') {

                $validator = Validator::make(request()->all(), [
                    'group-name' => 'required',
                    'group-address' => 'required',
                    'group-type' => 'required|max:20',
                    'group-code' => 'required',
                    'group-operator' => 'required',
                    'province-id' => 'required|numeric',
                    'group-contact' => 'required',
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
                    'requested_by' => $user->name . '('. UserType::find($user->user_type_id)->role .')'
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

                $form['requested_by'] = $user->name . '('. UserType::find($user->user_type_id)->role .')';

                $apiRequest = RequestModel::create($form);
            }

            if ($apiRequest) {
                

                $logs = ActivityLog::create([
                    'type' => 'post-request',
                    'user_id' => $user->id,
                    'assets' => json_encode(array_merge([
                        'action' => 'Posted a request to OCBS application',
                        'request-type' => $requestName
                    ],$request->except(['api_key'])))
                ]);

                // dd($logs);

                if ($operation == 'update') {
                    
                    $this->postRequestToKiosk($request->all());

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
                return response([
                    'result' => 0,
                    'message' => 'Request already exists.'
                ], 200);
            }

            return redirect()->back()->with('error', 'Request already exists'); 

        }
        // return new ApiRequestResource($apiRequest);
    }

    public function postRequestToKiosk($postInput)
    {
        $user = auth()->user();

        $apiURL = 'https://development.wpc2040.live/api/v4/requests';

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
                                
            if ($acceptChanges) {

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

    
                    if ($operation == 'update') {
    
                        $result = Account::where('uuid', $request->uuid)->update($data);
    
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
        $requestName = request()->operation;

        $table = '';
        $operation = '';
        list($table, $operation) = explode('.', $requestName);

        if ($operation == 'create') {
            $uuid = (string) Str::uuid();
            $checkInRequests = false;
        } else {
            $uuid = request()->uuid;
            $checkInRequests = RequestModel::where('operation', $requestName)
                                ->where('uuid', $uuid)
                                ->where('status','pending')
                                ->first();
        }

        
        if($user->user_type_id == 1) {
            $validator = Validator::make(request()->all(), [
                'operation' => 'required',
                'first-name' => 'required|string|max:50',
                'last-name' => 'required|string|max:50',
                'username' => 'required|string|max:50',
                'contact' => 'required|max:50',
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
            return redirect()->back()->with('errors', $validator->messages());
        }

        $form = [
            'api_key' => '4e829e510539afcc43365a18acc91ede41fb555e',
            'uuid' => $uuid,
            'operation' => $requestName,
            'status' => 'pending',
            'data' => json_encode($formData),
            'remarks' => $request->remarks,
            'requested_by' => $user->name . '('. UserType::find($user->user_type_id)->role .')'
        ];

        if (!$checkInRequests) {
            $apiRequest = RequestModel::create($form);
            if ($apiRequest) {
                
                $this->postRequestToKiosk($form);

                $logs = ActivityLog::create([
                    'type' => 'post-request',
                    'user_id' => $user->id,
                    'assets' => json_encode(array_merge([
                        'action' => 'Posted a request to OCBS application',
                        'request-type' => $requestName
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

}
