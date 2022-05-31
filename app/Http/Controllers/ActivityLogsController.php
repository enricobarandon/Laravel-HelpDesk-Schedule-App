<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ActivityLog;
use DB;

class ActivityLogsController extends Controller
{
    public function index(Request $request)
    {
        $activityLogs = ActivityLog::select('activity_logs.id as id','type','users.name as name','assets','activity_logs.created_at as created_at')
                        ->leftjoin('users','users.id','activity_logs.user_id')
                        ->orderBy('id','desc');
                        // ->paginate(30);
        
        $keyword = $request->keyword;
        
        if($request->keyword){
            $activityLogs = $activityLogs->where(DB::raw('concat(activity_logs.type,activity_logs.assets)'), 'like', '%' . $request->keyword . '%');
        }
        
        $activityLogs = $activityLogs->paginate(30);

        return view('logs.index', compact('activityLogs','keyword'));
    }
}
