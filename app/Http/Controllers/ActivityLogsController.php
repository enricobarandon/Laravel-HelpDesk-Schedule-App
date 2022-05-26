<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ActivityLog;

class ActivityLogsController extends Controller
{
    public function index()
    {
        $activityLogs = ActivityLog::select('activity_logs.id as id','type','users.name as name','assets','activity_logs.created_at as created_at')
                        ->leftjoin('users','users.id','activity_logs.user_id')
                        ->paginate(30);

        return view('logs.index', compact('activityLogs'));
    }
}
