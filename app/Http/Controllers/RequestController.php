<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RequestModel;
use Excel;
use App\Exports\ScheduledGroupExport;

class RequestController extends Controller
{
    public function index(Request $request)
    {
        // $requests = RequestModel::select('operation','status','data')->get();
        $requests = RequestModel::select('requests.id','requests.operation','requests.status','requests.data','groups.name as group_name','accounts.username','requests.remarks')
                        ->leftjoin('groups','groups.uuid', 'requests.uuid')
                        ->leftjoin('accounts','accounts.uuid','requests.uuid')
                        ->orderBy('id','desc')
                        ->paginate(100);

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
