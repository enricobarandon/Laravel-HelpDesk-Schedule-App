<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RequestModel;
use App\Models\Group;
use App\Models\ActivityLog;
use DB;
use App\Jobs\ProcessRequest;
use Excel;
use App\Exports\ScheduledGroupExport;

class CbandController extends Controller
{
    public function index(Request $request)
    {
        $requests = RequestModel::select('requests.id','requests.operation','requests.status','requests.data','groups.name as group_name','accounts.username','requests.remarks','requests.created_at','requested_by','groups.viewing_status','groups.id as group_id','reference_number','requests.is_processed')
                        ->leftjoin('groups','groups.uuid', 'requests.uuid')
                        ->leftjoin('accounts','accounts.uuid','requests.uuid')
                        ->whereIn('operation', ['groups.create','groups.update'])
                        ->orderBy('id','desc');
                
        $keyword = $request->keyword;
                
        if($request->keyword){
            $requests = $requests->where(DB::raw('CONCAT_WS(requested_by,requests.data,requests.remarks,reference_number)'), 'like', '%' . $keyword . '%');
        }

        $status = $request->status;
        // if($request->status != ""){
        //     $requests = $requests->where('requests.status', $status);
        // }

        $vStatus = $request->vStatus;
        if($vStatus != ""){
            $requests = $requests->where('groups.viewing_status', $vStatus);
        }
        
        $cbandStatus = $request->cbandStatus;
        if($request->cbandStatus != ""){
            $requests = $requests->where('requests.status', 'approved')->where('requests.is_processed', $cbandStatus);
        }

        if($keyword == '' and $status == '' and $cbandStatus == ''){
            $requests = $requests->where('requests.status', 'approved')->where('requests.is_processed', '0');
        }

        $requests = $requests->paginate(20);

        if ($request->has('download')) {
            return Excel::download(
                new ScheduledGroupExport('cband.tables.cRequestsTable', [
                    'requests' => $requests
                ]),
                'cband-requests.xlsx'
            );
        }
        
        return view('cband.index', compact('requests','keyword','cbandStatus','status','vStatus'));
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

                RequestModel::where('id', $requestId)->update(['is_processed' => 1]);

                ProcessRequest::dispatch();

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
