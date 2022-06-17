<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ActivityLog;
use DB;
use Excel;
use App\Exports\ScheduledGroupExport;

class ActivityLogsController extends Controller
{
    public function index(Request $request)
    {
        $activityLogs = ActivityLog::select('activity_logs.id as id','type','users.name as name','assets','activity_logs.created_at as created_at','user_types.role')
                        ->leftjoin('users','users.id','activity_logs.user_id')
                        ->leftjoin('user_types','user_types.id','users.user_type_id')
                        ->orderBy('id','desc');
                        // ->paginate(30);

        $datepicker = $request->datepicker;
        
        if($request->datepicker){
            $activityLogs = $activityLogs->whereBetween('activity_logs.created_at', [
                                            $datepicker . ' 00:00:00',
                                            $datepicker . ' 23:59:59',
                                        ]);
        }

        $keyword = $request->keyword;

        if($request->keyword){
            $activityLogs = $activityLogs->where('activity_logs.type', 'like', '%' . $request->keyword . '%')
                                        ->orWhere('activity_logs.assets', 'like', '%' . $request->keyword . '%')
                                        ->orWhere('users.name', 'like', '%' . $request->keyword . '%');
        }
        
        $activityLogs = $activityLogs->paginate(100);
        if ($request->has('download')) {
            return Excel::download(
                new ScheduledGroupExport('logs.tables.logs-table', [
                    'activityLogs' => $activityLogs
                ]),
                'activityLogs.xlsx'
            );
        }

        return view('logs.index', compact('activityLogs','keyword','datepicker'));
    }
}
