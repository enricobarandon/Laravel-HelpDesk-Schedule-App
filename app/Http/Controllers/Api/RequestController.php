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
use App\Models\User;

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

    public function storeGroupRequest(ApiRequests $request)
    {
        $uuid = request()->uuid;
        $requestName = request()->operation;
        $checkInRequests = RequestModel::where('operation', $requestName)
                            ->where('uuid', $uuid)
                            ->where('status','pending')
                            ->first();

        if (!$checkInRequests) {
            $apiRequest = RequestModel::create($request->validated());
            if ($apiRequest) {
                
                $this->postRequestToKiosk($request->all());

                return response([
                    'result' => 1,
                    'message' => 'Please monitor the status of your requests on the Requests tab!'
                ], 201);
            } else {
                return response([
                    'result' => 0,
                    'message' => 'Something went wrong! Please try again.'
                ], 200);
            }
        } else {
            return response([
                'result' => 0,
                'message' => 'Request already exists.'
            ], 200);
        }
        // return new ApiRequestResource($apiRequest);
    }

    public function postRequestToKiosk($postInput)
    {

        $apiURL = 'https://development.wpc2040.live/api/v4/requests';

        $apiKey = env('KIOSK_API_KEY');
  
        $headers = [
            'X-header' => 'value',
            'Content-Type' => 'application/json',
            'accept' => 'application/json'
        ];
        
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

                    if ($operation == 'update') {
    
                        $result = Group::where('uuid', $request->uuid)->update($data);
    
                    }
    
                } else if ($table == 'users') {
    
                    if ($operation == 'update') {
    
                        $result = User::where('uuid', $request->uuid)->update($data);
    
                    }
                }

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
}
