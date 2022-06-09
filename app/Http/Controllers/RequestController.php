<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RequestModel;
use Excel;
use App\Exports\ScheduledGroupExport;
use Auth;

class RequestController extends Controller
{
    public function index(Request $request)
    {
        // $requests = RequestModel::select('operation','status','data')->get();
        $requests = RequestModel::select('requests.id','requests.operation','requests.status','requests.data','groups.name as group_name','accounts.username','requests.remarks','requests.created_at','requested_by')
                        ->leftjoin('groups','groups.uuid', 'requests.uuid')
                        ->leftjoin('accounts','accounts.uuid','requests.uuid')
                        ->orderBy('id','desc');
        
        if(Auth::user()->user_type_id == 5){
            $requests = $requests->where('requests.operation', 'groups.update');
        }

        $requests = $requests->paginate(20);

        if ($request->has('download') || $request->has('downloadcurrent')) {

            return Excel::download(
                new ScheduledGroupExport('requests.tables.requestsTable', [
                    'requests' => $requests
                ]),
                'accounts.xlsx'
            );
        }
        return view('requests.index', ['requests' => $requests]);
    }
}
