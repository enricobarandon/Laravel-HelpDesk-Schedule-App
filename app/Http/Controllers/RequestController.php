<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RequestModel;
use Excel;
use App\Exports\ScheduledGroupExport;
use Auth;
use DB;

class RequestController extends Controller
{
    public function index(Request $request)
    {
        // $requests = RequestModel::select('operation','status','data')->get();
        $requests = RequestModel::select('requests.id','requests.operation','requests.status','requests.data','groups.name as group_name','accounts.username','requests.remarks','requests.created_at','requested_by','reference_number')
                        ->leftjoin('groups','groups.uuid', 'requests.uuid')
                        ->leftjoin('accounts','accounts.uuid','requests.uuid')
                        ->orderBy('id','desc');
        
        if(in_array(Auth::user()->user_type_id, [4,5])){
            $requests = $requests->where('requests.operation', 'groups.update');
        }
        
        $keyword = $request->keyword;

        if($request->keyword){
            $requests = $requests->where(DB::raw('CONCAT_WS(requests.operation,requests.data,requests.remarks,reference_number,requested_by)'), 'like', '%' . $request->keyword . '%');
        }

        $status = $request->status;

        if($request->status != ""){
            $requests = $requests->where('requests.status', $request->status);
        }

        $requests = $requests->paginate(20);

        if ($request->has('download') || $request->has('downloadcurrent')) {

            return Excel::download(
                new ScheduledGroupExport('requests.tables.requestsTable', [
                    'requests' => $requests
                ]),
                'requests.xlsx'
            );
        }
        return view('requests.index', ['requests' => $requests, 'keyword' => $keyword, 'status' => $status]);
    }
}
