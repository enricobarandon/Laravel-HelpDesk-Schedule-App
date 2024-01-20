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

        $actions = [
            'add-group' => 'Added group to schedule',
            'change-user-status' => 'Change user status',
            'confirmed-account' => 'Confirmed all accounts for a scheduled group',
            'confirmed-account' => 'Confirmed an account for a scheduled group',
            'create-archive' => 'Create Archive',
            'create-user' => 'Created a user account',
            'store-schedule' => 'Created a schedule',
            'logout' => 'Logout User',
            'login' => 'Login User',
            'post-request' => 'Posted a request to gaming site application',
            'remove-group' => 'Removed group from a schedule',
            'remove-confirmed-account' => 'Removed account from a scheduled group',
            'gaming-site-manual-update' => 'Received a manual update from gaming site',
            'gaming-site-create-user' => 'Received create user from gaming site',
            'gaming-site-create-group' => 'Received create group from gaming site',
            'gaming-site-update-operators' => 'Received update for operators status',
            'received-update' => 'Received update from a processed request. From gaming site.',
            'update-account-password' =>  'Update Account Password',
            'update-account-status' => 'Update Account Status',
            'update-archive' => 'Update Archive',
            'update-viewing-status' => 'Update Group Viewing Status',
            'update-group-schedule-info' => 'Update group schedule info',
            'update-user' => 'Update User',
            'update-group-fields' => 'Update group local fields',
            'update-schedule' => 'Update schedule',
        ];

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


        $action = $request->action;

        if($request->action){
            $activityLogs = $activityLogs->where('activity_logs.type',$request->action);
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

        return view('logs.index', compact('activityLogs','keyword','datepicker','actions','action'));
    }
}
