<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\ActivityLog;
use App\Models\Account;

class OcbsController extends Controller
{
    public function update(Request $request)
    {
        $uuid = $request->uuid;

        $table = $request->table;

        if ($table == 'groups') {

            $update = Group::where('uuid', $uuid)->update($request->except(['table']));

        } else if ($table == 'users') {
            
            $updateAccountForm = $request->except(['table']);

            $findGroup = Group::select('id')->where('uuid', $request->group_uuid)->first();

            $updateAccountForm['group_id'] = $findGroup->id;

            unset($updateAccountForm['group_uuid']);

            $update = Account::where('uuid', $uuid)->update($updateAccountForm);

        }
        

        if ($update) {

            ActivityLog::create([
                'type' => 'ocbs-manual-update',
                'user_id' => 0,
                'assets' => json_encode(array_merge([
                    'action' => 'Received a manual update from ocbs',
                    'uuid' => $uuid,
                    'target_table' => $table
                ], $request->except(['table'])))
            ]);

            return response(json_encode([
                'status' => 'ok',
                'message' => 'Updated'
            ]), 200);

        } else {

            return response(json_encode([
                'status' => 'error',
                'message' => 'Something went wrong'
            ]), 200);

        }
    }

    public function create(Request $request)
    {
        $table = $request->table;

        if ($table == 'groups') {

            $create = Group::create($request->except(['table']));

        } else if ($table == 'users') {

            // $request->group_uuid convert to group id
            $groupInfo = Group::select('id')->where('uuid', $request->group_uuid)->first();
            
            if ($groupInfo) {

                $request->merge(['group_id' => $groupInfo->id]);

                $create = Account::create($request->except(['table','group_uuid']));
            }

        }

        if ($create) {

            ActivityLog::create([
                'type' => 'ocbs-create-group',
                'user_id' => 0,
                'assets' => json_encode(array_merge([
                    'action' => 'Received create from ocbs',
                    'target_table' => $table
                ], $request->except(['table','group_uuid'])))
            ]);

            return response(json_encode([
                'status' => 'ok',
                'message' => 'Created'
            ]), 200);
        } else {
            return response(json_encode([
                'status' => 'error',
                'message' => 'Something went wrong'
            ]), 200);
        }

    }
}
