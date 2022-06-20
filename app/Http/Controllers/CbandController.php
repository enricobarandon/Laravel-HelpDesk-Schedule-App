<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RequestModel;
use App\Models\Group;
use App\Models\ActivityLog;
use DB;

class CbandController extends Controller
{
    public function index(Request $request)
    {
        $requests = RequestModel::select('requests.id','requests.operation','requests.status','requests.data','groups.name as group_name','accounts.username','requests.remarks','requests.created_at','requested_by','groups.viewing_status','groups.id as group_id','reference_number')
                        ->leftjoin('groups','groups.uuid', 'requests.uuid')
                        ->leftjoin('accounts','accounts.uuid','requests.uuid')
                        ->whereIn('operation', ['groups.create','groups.update'])
                        ->orderBy('id','desc');
                
        $keyword = $request->keyword;
                
        if($request->keyword){
            $requests = $requests->where(DB::raw('CONCAT_WS(requested_by,requests.data,requests.remarks,reference_number)'), 'like', '%' . $keyword . '%');
        }

        if($request->status != ""){
            $requests = $requests->where('requests.status', $request->status);
        }

        $requests = $requests->paginate(20);
        
        return view('cband.index', compact('requests','keyword'));
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

                RequestModel::where('id', $requestId)->update(['is_processed',1]);

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
