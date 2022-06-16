<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RequestModel;
use App\Models\Group;
use App\Models\ActivityLog;

class CbandController extends Controller
{
    public function index()
    {
        $requests = RequestModel::select('requests.id','requests.operation','requests.status','requests.data','groups.name as group_name','accounts.username','requests.remarks','requests.created_at','requested_by','groups.viewing_status','groups.id as group_id')
                        ->leftjoin('groups','groups.uuid', 'requests.uuid')
                        ->leftjoin('accounts','accounts.uuid','requests.uuid')
                        ->whereIn('operation', ['groups.create','groups.update'])
                        ->orderBy('id','desc');

        $requests = $requests->paginate(20);
        $keyword = '';
        $status = '';
        return view('cband.index', compact('requests','keyword','status'));
    }
    public function changeViewingStatus(Request $request)
    {
        $user = auth()->user();
        $groupId = $request->group_id;
        $requestId = $request->request_id;
        $action = $request->request_action;
        if($action == 'activate' || $action == 'deactivate') {

            $groupInfo = Group::find($groupId);

            $newStatus = $action == 'activate' ? 1 : 0;

            $update = Group::where('id', $groupId)->update(['viewing_status' => $newStatus]);

            if ($update) {
                ActivityLog::create([
                    'type' => 'update-viewing-status',
                    'user_id' => $user->id,
                    'assets' => json_encode([
                        'action' => 'Update Group Viewing Status',
                        'new viewing status' => $action,
                        'group code' => $groupInfo->code,
                        'request ID' => $requestId
                        ])
                ]);
    
                return back()->with('success', 'Updated viewing status!');
            } else {
                return back()->with('error', 'Something went wrong!');
            }


        }
    }
}
