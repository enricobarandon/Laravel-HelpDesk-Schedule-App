<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Account;
use App\Models\Group;
use App\Models\Province;

class DataController extends Controller
{
    public function index()
    {
        return view('syncdata.index');
    }

    public function initialGroupsTransfer()
    {
        $apiURL = 'https://development.wpc2040.live/api/v4/groups';

        $postInput = [
            'api_key' => '4856dff1c620f02368c744f8eb6d4617a29f12fb'
        ];
  
        $headers = [
            'X-header' => 'value',
            'Content-Type' => 'application/json',
            'accepts' => 'application/json'
        ];
  
        $response = Http::withHeaders($headers)->get($apiURL, $postInput);
  
        $statusCode = $response->status();

        $responseBody = json_decode($response->getBody(), true);
        
        if ($responseBody['status'] == 'ok') {

            foreach($responseBody['data'] as $data){

                $provinceInfo = Province::where('name', $data['province'])->first();

                $newGroupArr = [
                    'uuid' => $data['uuid'],
                    'name' => $data['group_name'],
                    'owner' => $data['owner'],
                    'contact' => $data['contact'],
                    'is_active' => $data['is_active'],
                    'province_id' => $provinceInfo->id,
                    'group_type' => $data['type'],
                    'code' => '--'
                ];

                Group::create($newGroupArr);

            }

            return 'end';

        }
    }

    public function initialUsersTransfer()
    {
        $apiURL = 'https://development.wpc2040.live/api/v4/users';

        $postInput = [
            'api_key' => '4856dff1c620f02368c744f8eb6d4617a29f12fb'
        ];
  
        $headers = [
            'X-header' => 'value',
            'Content-Type' => 'application/json',
            'accepts' => 'application/json'
        ];
  
        $response = Http::withHeaders($headers)->get($apiURL, $postInput);
  
        $statusCode = $response->status();

        $responseBody = json_decode($response->getBody(), true);
        
        if ($responseBody['status'] == 'ok') {

            $groups = collect(Group::all()->toArray());
            // dd($groups->where('name','LUCKY 8 STAR QUEST INC. OFFICE - B'));

            foreach($responseBody['data'] as $data){

                // $groupInfo = Group::where('name', $data['group_name'])->first();
                $groupInfo = $groups->where('name', '=', $data['group_name']);

                $newAccountArr = [
                    'uuid' => $data['uuid'],
                    'first_name' => $data['firstname'] ? $data['firstname'] : '--',
                    'last_name' => $data['lastname'] ? $data['lastname'] : '--',
                    'username' => $data['username'],
                    'is_active' => $data['is_active'],
                    'group_id' => $groupInfo ? $groupInfo[0]->id : 0,
                    'allowed_sides' => $data['allowed_sides'],
                    'position' => $data['user_type'],
                    'contact' => $data['contact']
                ];

                // Account::create($newAccountArr);

            }

            return 'end';

        }
    }

}
