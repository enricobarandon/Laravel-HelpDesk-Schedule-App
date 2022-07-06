<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\ScheduledGroup;
use Carbon\Carbon;
use Auth;
use App\Models\Account;
use App\Models\ScheduledAccount;
use App\Models\Schedule;
use App\Models\ActivityLog;
use App\Models\Province;
use Excel;
use App\Exports\ScheduledGroupExport;
use DB;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;

class ScheduleGroupController extends Controller
{

    public function index(Request $request)
    {
        $scheduleId = request()->segment(3);

        // $groupCode = $request->input('filter-code');
        // $province = $request->input('select-province');
        // $type = $request->input('select-type');

        // if ($groupCode) {
        //     $groupCode = " and groups.code = '$groupCode'";
        // }
        // if ($province) {
        //     $province = " and groups.province_id = $province";
        // }
        // if (!empty($type)) {
        //     $count = count($type);
        //     if ($count > 1) {
        //         array_shift($type);
        //         $type = "'" . implode("','",$type) . "'";
        //         $type = " and groups.group_type in ($type)";
        //     } else if($count == 1 && $type[0] == '') {
        //         $type = '';
        //     }
        // }

        $scheduleInfo = Schedule::find($scheduleId);

        if($scheduleInfo->status == 'finished'){
            return redirect('/schedules-past')->with('error', 'Schedule is already Finished!');
        }

        $scheduledGroups = ScheduledGroup::select('group_id','operation_time')
                                ->where('schedule_id', $scheduleId)
                                ->orderBy('id','desc')
                                ->get();
        
        $scheduledGroupsIds = $scheduledGroups->pluck('group_id')->toArray();
        $scheduledGroupsOperationHours = $scheduledGroups->keyBy('group_id')->toArray();

        $arrToStr = implode(",", $scheduledGroupsIds);

        $groupsForDisplay = [];
        $groupsForSelect = [];
        
        if ($arrToStr != "") {

            $groupsForDisplay = DB::select(DB::raw(
                "
                select 
                    `groups`.`id`, 
                    `groups`.`name` as `group_name`, 
                    `address`, 
                    `provinces`.`site`, 
                    `province_id`, 
                    `provinces`.`name` as `province_name`,
                    code,
                    groups.group_type
                from `groups` 
                inner join `provinces` on `provinces`.`id` = `groups`.`province_id` 
                    where `groups`.`id` in ($arrToStr)
                    ORDER BY field(`groups`.`id`, $arrToStr)
                "
            ));

            $groupsForSelect = DB::select(
                DB::raw(
                    "
                    select 
                        `groups`.`id`, 
                        `groups`.`name`, 
                        `groups`.`address`,
                        `groups`.`guarantor`,
                        `provinces`.`name` as `province`
                    from `groups`
                    left join `provinces` on `groups`.`province_id` = `provinces`.`id`
                    where `is_active` = 1 and `groups`.`id` not in ($arrToStr)  
                    order by `groups`.`name` asc
                    "
                )
            );

            // Collection::macro('toUpper', function () {
            //     return $this->map(function ($key, $value) {
            //         return Str::upper($value);
            //     });
            // });

            $groupsForDisplay = collect($groupsForDisplay);
            $groupsForSelect = collect($groupsForSelect);

                            
            if($request->filterGroup){
                // $groupsForDisplay = $groupsForDisplay->where('code', $request->filterGroup);
                $groupsForDisplay = $groupsForDisplay->filter(function ($item) use ($request) {
                    return strtolower($item->code) == strtolower($request->filterGroup);
                    // dd(strtolower($item->code), strtolower($request->filterGroup));
                });
            }

            if($request->selectProvince){
                $groupsForDisplay = $groupsForDisplay->where('province_id', $request->selectProvince);
            }

            if($request->selectType){
                if(!empty(array_filter($request->selectType,'strlen'))){
                    $groupsForDisplay = $groupsForDisplay->whereIn('group_type', $request->selectType);
                }
            }

            if($request->siteID){
                $groupsForDisplay = $groupsForDisplay->where('site', $request->siteID);
            }
                
        } else {
            $groupsForSelect = DB::select(
                DB::raw(
                    "
                    select 
                        `groups`.`id`, 
                        `groups`.`name`, 
                        `groups`.`address`, 
                        `groups`.`guarantor`, 
                        `provinces`.`name` as `province`
                    from `groups`
                    left join `provinces` on `groups`.`province_id` = `provinces`.`id`
                    where `is_active` = 1 and `groups`.`id` not in (0)  
                    order by `groups`.`name` asc
                    "
                )
            );
        }
        if ($request->has('download') || $request->has('downloadcurrent')) {

            return Excel::download(
                new ScheduledGroupExport('schedules.tables.manageTable', [
                    'groupsForDisplay' => $groupsForDisplay,
                    'scheduledGroupsOperationHours' => $scheduledGroupsOperationHours
                ]),
                'schedule.xlsx'
            );
        }


        // $groupsForDisplay = ScheduledGroup::select('groups.id')
        //                         ->join('groups')
        //                         ->join('provinces','provinces.id', 'groups.province_id')
        //                         ->where('schedule_id', $scheduleId)
        
        $provinces = Province::select('id','name','site')
                            ->get();
                            // dd($groupsForDisplay);
            

        // $groupsForDisplay = $groupsForDisplay->all();

        return view('schedules.manage', [
            'scheduleId' => $scheduleId,
            'scheduleInfo' => $scheduleInfo,
            'groupsForDisplay' => $groupsForDisplay,
            'groupsForSelect' => $groupsForSelect,
            'provinces' => $provinces,
            'scheduledGroupsOperationHours' => $scheduledGroupsOperationHours,
            'siteID' => $request->siteID,
            'selectType' => $request->selectType,
            'selectProvince' => $request->selectProvince,
            
        ]);
        
    }

    // public function addGroup()
    // {
    //     $scheduleId = request()->id;
        
    //     $groupId = request()->group_id;

    //     $user = Auth::user();

    //     if ($groupId == 'all') {

    //         $allGroups = Group::select('groups.id','scheduled_groups.id as scheduled_groups_id','groups.remarks')
    //                         ->leftjoin('scheduled_groups', function($join) use ($scheduleId){
    //                             $join->on('scheduled_groups.group_id', 'groups.id')
    //                                 ->where('scheduled_groups.schedule_id','=', $scheduleId);
    //                         })
    //                         ->where('is_active', 1)
    //                         ->orderBy('name','asc')
    //                         ->get();
                            
    //         $groupArray = [];

    //         foreach($allGroups as $index => $group) {
    //             if (!$group->scheduled_groups_id) {
    //                 $groupArray[$index] = [
    //                     'schedule_id' => $scheduleId,
    //                     'group_id' => $group->id,
    //                     'created_at' => Carbon::now(),
    //                     'updated_at' => Carbon::now(),
    //                     'user_id' => $user->id,
    //                     'remarks' => $group->remarks
    //                 ];
    //             }
    //         }
            
    //         $insertScheduledGroups = ScheduledGroup::insert($groupArray);

    //         if ($insertScheduledGroups) {
    //             ActivityLog::create([
    //                 'type' => 'add-group',
    //                 'user_id' => $user->id,
    //                 'assets' => json_encode([
    //                     'action' => 'added group to schedule',
    //                     'group_id' => $groupId,
    //                     'schedule_id' => $scheduleId,
    //                     'group_code' => 'All Active Groups'
    //                 ])
    //             ]);
    //             return redirect('/schedules/manage/' . $scheduleId)->with('success', 'All active groups added!');
    //         } else {
    //             return redirect('/schedules/manage/' . $scheduleId)->with('error', 'Something went wrong!');
    //         }
            
    //     } else if ($groupId > 0)  {

    //         $groupInfo = Group::select('code','remarks')->where('groups.id',$groupId)->first();

    //         ActivityLog::create([
    //             'type' => 'add-group',
    //             'user_id' => $user->id,
    //             'assets' => json_encode([
    //                 'action' => 'added group to schedule',
    //                 'group_id' => $groupId,
    //                 'schedule_id' => $scheduleId,
    //                 'group_code' => $groupInfo->code
    //             ])
    //         ]);
            
    //         $scheduledGroup = ScheduledGroup::create([
    //             'schedule_id' => $scheduleId,
    //             'group_id' => $groupId,
    //             'user_id' => $user->id,
    //             'remarks' => $groupInfo->remarks
    //         ]);

    //         if ($scheduledGroup) {
    //             return redirect('/schedules/manage/' . $scheduleId)->with('success', 'Group added!');
    //         } else {
    //             return redirect('/schedules/manage/' . $scheduleId)->with('error', 'Something went wrong!');
    //         }

    //     } else {
    //         return redirect('/schedules/manage/' . $scheduleId)->with('error', 'Something went wrong!');
    //     }
        
    // }

    public function removeGroup()
    {
        $scheduleId = request()->scheduleId;
        
        $groupId = request()->groupId;
        
        $user = Auth::user();

        $scheduledGroupInfo = ScheduledGroup::where('schedule_id', $scheduleId)
                            ->where('group_id', $groupId)
                            ->first();

        $deleteScheduledGroup = $scheduledGroupInfo->delete();

        $deleteScheduledAccounts = ScheduledAccount::where('scheduled_group_id', $scheduledGroupInfo->id)
                                        ->delete();


        $groupCode = Group::select('code')->where('groups.id',$groupId)->first();
        
        ActivityLog::create([
            'type' => 'remove-group',
            'user_id' => $user->id,
            'assets' => json_encode([
                'action' => 'removed group from a schedule',
                'group_id' => $groupId,
                'schedule_id' => $scheduleId,
                'group_code' => $groupCode->code
            ])
        ]);

        return redirect('/schedules/manage/' . $scheduleId);
    }

    public function manageGroup()
    {
        $scheduleId = request()->scheduleId;
        
        $groupId = request()->groupId;

        $groupInfo = Group::select('groups.name as group_name','address', 'groups.group_type','owner','contact','code','provinces.site','installed_pc','active_staff','provinces.name as province_name','guarantor')
                            ->where('groups.id', $groupId)
                            ->join('provinces', 'provinces.id', 'groups.province_id')
                            ->first();

        $scheduledGroupInfo = ScheduledGroup::select('id','operation_time','remarks')
                                ->where('schedule_id', $scheduleId)
                                ->where('group_id', $groupId)
                                ->first();
                                // dd($scheduledGroupInfo);

        $groupAccounts = Account::select('accounts.id as acc_id','scheduled_accounts.id as sched_id','first_name','last_name','username','contact','position','scheduled_group_id','allowed_sides','is_active','password','status','account_allowed_sides','account_password','account_position')
                            // ->leftJoin('scheduled_accounts','scheduled_accounts.account_id', 'accounts.id')
                            ->leftjoin('scheduled_accounts', function($join) use ($scheduleId){
                                $join->on('scheduled_accounts.account_id', 'accounts.id')
                                    ->where('scheduled_accounts.schedule_id','=', $scheduleId);
                            })
                            ->where('accounts.group_id', $groupId)
                            // ->where('scheduled_accounts.schedule_id', $scheduleId)
                            ->get();
                            // dd($groupAccounts);
        
        $scheduleInfo = Schedule::find($scheduleId);                            

        return view('schedules.groups.index', [
            'scheduleId' => $scheduleId,
            'groupId' => $groupId,
            'groupInfo' => $groupInfo,
            'scheduledGroupInfo' => $scheduledGroupInfo,
            'groupAccounts' => $groupAccounts,
            'scheduleInfo' => $scheduleInfo
        ]);
    }

    public function deleteScheduledAccount()
    {
        $scheduledGroupId = request()->scheduledGroupId;
        
        $accountId = request()->accountId;
        
        $user = Auth::user();

        $deleteAccountFromScheduledGroup = ScheduledAccount::where('scheduled_group_id', $scheduledGroupId)
                                                    ->where('account_id', $accountId)
                                                    ->delete();
                                                    
        $scheduledGroupInfo = ScheduledGroup::find($scheduledGroupId);

        if ($deleteAccountFromScheduledGroup) {

            $groupCode = Group::select('code')->where('groups.id',$scheduledGroupInfo->group_id)->first();

            $accountInfo = Account::find($accountId);

            ActivityLog::create([
                'type' => 'remove-confirmed-account',
                'user_id' => $user->id,
                'assets' => json_encode([
                    'action' => 'Removed account from a scheduled group',
                    'account_id' => $accountId,
                    'schedule_group_id' => $scheduledGroupId,
                    'account_username' => $accountInfo->username,
                    'group_code' => $groupCode->code
                ])
            ]);

            return redirect("/schedules/$scheduledGroupInfo->schedule_id/groups/$scheduledGroupInfo->group_id");
        }
    }

    public function storeScheduledAccount() 
    {
        $scheduledGroupId = request()->scheduledGroupId;
        
        $accountId = request()->accountId;

        $scheduleId = request()->scheduleId;

        $groupId = request()->groupId;
        // dd(request()->accountId);
        // dd($accountId,$scheduleId,$groupId);
        
        $user = Auth::user();

        $checkIfExisting = ScheduledAccount::select('id')
                            ->where('scheduled_group_id', $scheduledGroupId)
                            ->where('account_id', $accountId)
                            ->first();

        if ($checkIfExisting) {
            return back()->with('error','Account already confirmed');
        }

        $accountInfo = Account::find($accountId);

        $storeAccountFromScheduledGroup = ScheduledAccount::insert([
            'scheduled_group_id' => $scheduledGroupId,
            'schedule_id' => $scheduleId,
            'group_id' => $groupId,
            'account_id' => $accountId,
            'account_allowed_sides' => $accountInfo->allowed_sides,
            'account_password' => $accountInfo->password,
            'account_position' => $accountInfo->position,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        $scheduledGroupInfo = ScheduledGroup::find($scheduledGroupId);

        if ($storeAccountFromScheduledGroup) {

            $groupCode = Group::select('code')->where('groups.id',$scheduledGroupInfo->group_id)->first();

            $accountInfo = Account::find($accountId);

            ActivityLog::create([
                'type' => 'confirmed-account',
                'user_id' => $user->id,
                'assets' => json_encode([
                    'action' => 'Confirmed an account for a scheduled group',
                    'account_id' => $accountId,
                    'schedule_group_id' => $scheduledGroupId,
                    'scheduleId' => $scheduleId,
                    'groupId' => $groupId,
                    'account_username' => $accountInfo->username,
                    'group_code' => $groupCode->code
                ])
            ]);

            return redirect("/schedules/$scheduledGroupInfo->schedule_id/groups/$scheduledGroupInfo->group_id");
        }
    }

    public function view(Request $request)
    {
        $scheduleId = request()->id;

        $scheduleInfo = Schedule::find($scheduleId, ['name','date_time','status']);
        
        $scheduledGroups = ScheduledGroup::select('scheduled_groups.schedule_id','scheduled_groups.operation_time','scheduled_groups.group_id','groups.group_type','groups.name','code','owner','contact','address','active_staff','installed_pc','scheduled_groups.remarks','provinces.site')
                            ->join('groups','groups.id', 'scheduled_groups.group_id')
                            ->join('provinces','provinces.id', 'groups.province_id')
                            ->where('scheduled_groups.schedule_id', $scheduleId);
        
        $groupedByAccounts = ScheduledAccount::createAccountsAssocArr($scheduleId);

        if($request->groupCode){
            // $scheduledGroups = $scheduledGroups->where('groups.code', '=', $request->groupCode);
            $scheduledGroups = $scheduledGroups->where(function($query) use ($request) {
                $query->where('groups.code', '=', $request->groupCode)
                            ->orWhere('groups.name','like',"%$request->groupCode%")
                            ->orWhere('groups.address','like',"%$request->groupCode%");
            });
        }

        if($request->selectType){
            if(!empty(array_filter($request->selectType,'strlen'))){
                $scheduledGroups = $scheduledGroups->whereIn('groups.group_type', $request->selectType);
            }
        }

        if($request->selectSite){
            $scheduledGroups = $scheduledGroups->where('provinces.site', $request->selectSite);
        }

        if($request->pagination){
            if($request->pagination == 'all'){
                $scheduledGroups = $scheduledGroups->paginate(999999);
            }else{
                $scheduledGroups = $scheduledGroups->paginate(100);
            }
        }else{
            $scheduledGroups = $scheduledGroups->paginate(100);
        }

        $groupedByAccounts = ScheduledAccount::createAccountsAssocArr($scheduleId);
        // dd($groupedByAccounts);
        $tbody = '';
        $groupCount = ($scheduledGroups->currentpage()-1)* $scheduledGroups->perpage() + 1;;

        foreach($scheduledGroups as $group) {

            $accounts = '';

            if (isset($groupedByAccounts[$group->group_id])) {
                $accounts = $this->concatAccounts($groupedByAccounts, $group->group_id);
            } else {
                $accounts = '<tr><td colspan="8" style="text-align: center;">No confirmed staff</td></tr>';
            }

            $siteColor = '';

            if ($group->site == 'wpc2040') {
                $siteColor = 'background-color: #007bff;color: #ffffff;';
            } else if ($group->site == 'wpc2040aa') {
                $siteColor = 'background-color: #dc3545;color: #ffffff;';
            }

            $tbody .= '<tbody>';
            $tbody .= '<tr colspan="1">';
            $tbody .=   '<td><h3>' . $groupCount++ .'</h3></td>';
            $tbody .=   '<td>';
            $tbody .=       '<table class="table table-bordered full-sched-table" cellspacing="0">';
            $tbody .=           '<thead>';
            $tbody .=               '<tr>';
            $tbody .=                   '<td style="text-align: center;">ARENA NAME</td>';
            $tbody .=                   '<td colspan="3" style="background-color: darkgreen; color: white; font-weight: bold; width: 500px;" >'. htmlspecialchars($group->name) .'</td>';
            $tbody .=                   '<td colspan="4" style="background-color: darkgreen; color: white; text-align: center; width: 450px;">'. $group->remarks .'</td>';
            $tbody .=               '</tr>';
            $tbody .=           '</thead>';
            $tbody .=           '<tbody>';
            $tbody .=               '<tr>';
            $tbody .=                   '<td style="text-align: center;">Address</td>';
            $tbody .=                   '<td colspan="3" style="text-align: center;">'. $group->address .'</td>';
            $tbody .=                   '<td colspan="2" style="background-color: darkgreen; color: white; text-align: center; font-weight: bold;">'. $group->code .'</td>';
            $tbody .=                   '<td colspan="2" style="text-align: center; font-weight: bold; '. $siteColor .'">'. $group->site .'</td>';
            $tbody .=               '</tr>';
            $tbody .=               '<tr>';
            $tbody .=                   '<td style="text-align: center;">Site</td>';
            $tbody .=                   '<td colspan="3" style="text-align: center;">'. $group->group_type .'</td>';
            $tbody .=                   '<td colspan="2" style="text-align: center;">Date</td>';
            $tbody .=                   '<td colspan="2" style="text-align: center;">Time</td>';
            $tbody .=               '</tr>';
            $tbody .=               '<tr>';
            $tbody .=                   '<td style="text-align: center;">OPERATOR NAME</td>';
            $tbody .=                   '<td colspan="3" style="text-align: center;">'. strtoupper(htmlspecialchars($group->owner)) .'</td>';
            $tbody .=                   '<td colspan="2" style="text-align: center;">'. date('l, M d Y', strtotime($scheduleInfo->date_time)) .'</td>';
            $tbody .=                   '<td colspan="2" style="text-align: center;">'. date('h:i A', strtotime($group->operation_time)) .'</td>';
            $tbody .=               '</tr>';
            $tbody .=               '<tr>';
            $tbody .=                   '<td style="text-align: center;">CONTACT DETAILS</td>';
            $tbody .=                   '<td colspan="3" style="text-align: center;">'. $group->contact .'</td>';
            $tbody .=               '</tr>';
            $tbody .=               '<tr>';
            $tbody .=                   '<td style="text-align: center;"># OF PC INSTALLED</td>';
            $tbody .=                   '<td colspan="3" style="text-align: center;">'. $group->installed_pc .'</td>';
            $tbody .=               '</tr>';
            $tbody .=               '<tr>';
            $tbody .=                   '<td style="text-align: center;"># OF ACTIVE STAFFS</td>';
            $tbody .=                   '<td colspan="3" style="text-align: center;">'. $group->active_staff .'</td>';
            $tbody .=               '</tr>';
            if(Auth::User()->user_type_id != 5){
            $tbody .=               '<tr>';
            $tbody .=                   '<th style="background-color: black; color: yellow; text-align: center;">NAMES</th>';
            $tbody .=                   '<th style="background-color: black; color: yellow; text-align: center;">CONTACT</th>';
            $tbody .=                   '<th style="background-color: black; color: yellow; text-align: center;">POSITION</th>';
            $tbody .=                   '<th style="background-color: black; color: yellow; text-align: center;">REMARKS</th>';
            $tbody .=                   '<th colspan="2" style="background-color: black; color: yellow; text-align: center;">USERNAME</th>';
            $tbody .=                   '<th style="background-color: black; color: yellow; text-align: center;">PASSWORD</th>';
            $tbody .=                   '<th style="background-color: black; color: yellow; text-align: center;">STATUS</th>';
            $tbody .=               '</tr>';
            $tbody .=               $accounts;
            }
            $tbody .=           '</tbody>';
            $tbody .=       '</table>';
            $tbody .=   '<td>';
            $tbody .= '</tr>';
            $tbody .= '</tbody>';
        }

        if ($request->has('download') || $request->has('downloadcurrent')) {

            return Excel::download(
                new ScheduledGroupExport('schedules.tables.fullview', [
                    'tbody' => $tbody
                ]),
                'schedule.xlsx'
            );
        }

        // return view('schedules.view', [
        //     'scheduleInfo' => $scheduleInfo,
        //     'groups' => $scheduledGroups,
        //     'groupedByAccounts' => $groupedByAccounts
        // ]);

        return view('schedules.view', [
            'tbody' => $tbody,
            'scheduledGroups' => $scheduledGroups,
            'scheduleId' => $scheduleId,
            'scheduleInfo' => $scheduleInfo,
            'selectSite' => $request->selectSite,
            'selectType' => $request->selectType
        ]);
    }

    public function updateGroup()
    {
        $scheduleId = request()->scheduleId;

        $groupId = request()->groupId;
        
        $user = Auth::user();

        $optime = date('H:i:s',strtotime(request()->operation_time));
    
        $remarks = request()->gRemarks;

        $updateScheduledGroupInfo = ScheduledGroup::where('schedule_id', $scheduleId)
                                                ->where('group_id', $groupId)
                                                ->update(['remarks' => $remarks, 'operation_time' => $optime]);


        if ($updateScheduledGroupInfo) {

            $groupCode = Group::select('code')->where('groups.id',$groupId)->first();

            ActivityLog::create([
                'type' => 'update-group-schedule-info',
                'user_id' => $user->id,
                'assets' => json_encode([
                    'action' => 'update group schedule info',
                    'operation_time' => $optime,
                    'remarks' => $remarks,
                    'scheduleId' => $scheduleId,
                    'groupId' => $groupId,
                    'group_code' => $groupCode->code
                ])
            ]);

            return redirect("/schedules/$scheduleId/groups/$groupId")->with('success', 'Group details updated!');
        } else {
            return redirect("/schedules/$scheduleId/groups/$groupId")->with('error', 'Something went wrong!');
        }

    }

    public function concatAccounts($accountsArr, $groupId)
    {
        $trClass = '';
        $tableRow = '';
        foreach($accountsArr[$groupId] as $account) {

            if($account['status'] == 'temporarydeactivated' and $account['is_active'] == 0){
                $trClass = 'background: yellow;';
            }elseif($account['status'] == 'permanentdeactivated' and $account['is_active'] == 0){
                $trClass = 'background: #dc3545; color: white;';
            }else{
                $trClass = '';
            }

            $tableRow .= '<tr>';
            $tableRow .=    '<td style="text-align: center;' . $trClass . '">'. strtoupper($account['first_name']) .' '. strtoupper($account['last_name']) .'</td>';
            $tableRow .=    '<td style="text-align: center;' . $trClass . '">'. $account['contact'] .'</td>';
            $tableRow .=    '<td style="text-align: center;' . $trClass . '">'. strtoupper($account['account_position']) .'</td>';
            $tableRow .=    '<td style="text-align: center;' . $trClass . '">'. $account['account_allowed_sides'] .'</td>';
            $tableRow .=    '<td colspan="2" style="text-align: center;' . $trClass . '">'. $account['username'] .'</td>';
            $tableRow .=    '<td style="text-align: center;' . $trClass . '">'. $account['account_password'] .'</td>';
            $tableRow .=    '<td style="background-color: lightskyblue; text-align: center;">ACCOUNT CONFIRMED</td>';
            $tableRow .= '</tr>';
        }
        return $tableRow;
    }

    public function addGroups()
    {
        $scheduleId = request()->id;
        
        $groupId = request()->group_id;

        $user = Auth::user();

        // dd(in_array('ALL', $groupId));

        // dd($scheduleId, $groupId);

        if(empty($groupId)){
            return redirect('/schedules/manage/' . $scheduleId)->with('error', 'No selected group!');
        }

        if (in_array('ALL', $groupId)) {

            $allGroups = Group::select('groups.id','scheduled_groups.id as scheduled_groups_id','groups.remarks')
                            ->leftjoin('scheduled_groups', function($join) use ($scheduleId){
                                $join->on('scheduled_groups.group_id', 'groups.id')
                                    ->where('scheduled_groups.schedule_id','=', $scheduleId);
                            })
                            ->where('is_active', 1)
                            ->orderBy('name','asc')
                            ->get();
                            
            $groupArray = [];

            foreach($allGroups as $index => $group) {
                if (!$group->scheduled_groups_id) {
                    $groupArray[$index] = [
                        'schedule_id' => $scheduleId,
                        'group_id' => $group->id,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                        'user_id' => $user->id,
                        'remarks' => $group->remarks
                    ];
                }
            }
            
            $insertScheduledGroups = ScheduledGroup::insert($groupArray);
            
            if ($insertScheduledGroups) {
                ActivityLog::create([
                    'type' => 'add-group',
                    'user_id' => $user->id,
                    'assets' => json_encode([
                        'action' => 'added group to schedule',
                        'group_id' => $groupId,
                        'schedule_id' => $scheduleId,
                        'group_code' => 'All Active Groups'
                    ])
                ]);
                return redirect('/schedules/manage/' . $scheduleId)->with('success', 'All active groups added!');
            } else {
                return redirect('/schedules/manage/' . $scheduleId)->with('error', 'Something went wrong!');
            }

        }

        $types = [
            'ARENA',
            'OCBS-LOTTO',
            'OCBS-OTB',
            'OCBS-RESTOBAR',
            'OCBS-STORE',
            'OCBS-MALL',
            'OCBS',
            'OCBS-EGAMES',
            'OCBS-CASINO'
        ];

        $insert = false;

        foreach($groupId as $group){

            if (in_array($group, $types)) {

                // if group type is selected

                $groups = Group::select('groups.id','code','groups.remarks','scheduled_groups.id as scheduled_groups_id')
                            ->leftjoin('scheduled_groups', function($join) use ($scheduleId){
                                $join->on('scheduled_groups.group_id', 'groups.id')
                                    ->where('scheduled_groups.schedule_id','=', $scheduleId);
                            })
                            ->where('group_type', $group)
                            ->where('is_active',1)
                            ->get();
                            
                $groupArray = [];

                foreach($groups as $i => $v) {
                    if (!$v->scheduled_groups_id) {
                        $groupArray[$i] = [
                            'schedule_id' => $scheduleId,
                            'group_id' => $v->id,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                            'user_id' => $user->id,
                            'remarks' => $v->remarks
                        ];
                    }
                }
                
                $insert = ScheduledGroup::insert($groupArray);

                if ($insert) {
                    ActivityLog::create([
                        'type' => 'add-group',
                        'user_id' => $user->id,
                        'assets' => json_encode([
                            'action' => 'added groups to schedule',
                            'group_id' => $groupId,
                            'schedule_id' => $scheduleId,
                            'group_code' => "All Active '$group' Groups"
                        ])
                    ]);
                }

            } else {

                $groupInfo = Group::select('code','remarks')->where('groups.id',$group)->first();

                ActivityLog::create([
                    'type' => 'add-group',
                    'user_id' => $user->id,
                    'assets' => json_encode([
                        'action' => 'added group to schedule',
                        'group_id' => $group,
                        'schedule_id' => $scheduleId,
                        'group_code' => $groupInfo->code
                    ])
                ]);
                
                $insert = ScheduledGroup::create([
                    'schedule_id' => $scheduleId,
                    'group_id' => $group,
                    'user_id' => $user->id,
                    'remarks' => $groupInfo->remarks
                ]);
            }
        }

        if ($insert) {
            return redirect('/schedules/manage/' . $scheduleId)->with('success', 'Groups added!');
        } else {
            return redirect('/schedules/manage/' . $scheduleId)->with('error', 'Something went wrong!');
        }
    }


    public function storeAllScheduledAccount() 
    {
        $scheduledGroupId = request()->scheduledGroupId;
        
        // $accountId = request()->accountId;

        $scheduleId = request()->scheduleId;

        $groupId = request()->groupId;

        // dd($groupId);
        
        $user = Auth::user();

        // $checkIfExisting = ScheduledAccount::select('id')
        //                     ->where('scheduled_group_id', $scheduledGroupId)
        //                     ->where('account_id', $accountId)
        //                     ->first();

        // if ($checkIfExisting) {
        //     return back()->with('error','Account already confirmed');
        // }

        $allAccounts = Group::select('groups.id','accounts.id as acc_id','accounts.allowed_sides','accounts.password','accounts.position')
                        ->join('accounts','accounts.group_id','groups.id')
                        ->leftjoin('scheduled_accounts','scheduled_accounts.account_id','accounts.id')
                        ->where('groups.id', $groupId)
                        ->whereNull('scheduled_accounts.id')
                        ->get();
                        // dd($allAccounts);

        if ($allAccounts) {
            $insertArr = [];
            foreach($allAccounts as $account) {
                array_push($insertArr, [
                    'scheduled_group_id' => $scheduledGroupId,
                    'schedule_id' => $scheduleId,
                    'group_id' => $groupId,
                    'account_id' => $account->acc_id,
                    'account_allowed_sides' => $account->allowed_sides,
                    'account_password' => $account->password,
                    'account_position' => $account->position,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);
            }
            // dd($insertArr);
        }

        $storeAll = ScheduledAccount::insert($insertArr);
        

        // $accountInfo = Account::find($accountId);

        // $storeAccountFromScheduledGroup = ScheduledAccount::insert([
        //     'scheduled_group_id' => $scheduledGroupId,
        //     'schedule_id' => $scheduleId,
        //     'group_id' => $groupId,
        //     'account_id' => $accountId,
        //     'account_allowed_sides' => $accountInfo->allowed_sides,
        //     'account_password' => $accountInfo->password,
        //     'account_position' => $accountInfo->position,
        //     'created_at' => Carbon::now(),
        //     'updated_at' => Carbon::now()
        // ]);

        // $scheduledGroupInfo = ScheduledGroup::find($scheduledGroupId);

        // if ($storeAccountFromScheduledGroup) {

        //     $groupCode = Group::select('code')->where('groups.id',$scheduledGroupInfo->group_id)->first();

        //     $accountInfo = Account::find($accountId);

            $groupInfo = Group::find($groupId, ['code']);

            ActivityLog::create([
                'type' => 'confirmed-account',
                'user_id' => $user->id,
                'assets' => json_encode([
                    'action' => 'Confirmed all accounts for a scheduled group',
                    'account_id' => 'all accounts',
                    'schedule_group_id' => $scheduledGroupId,
                    'scheduleId' => $scheduleId,
                    'groupId' => $groupId,
                    'group_code' => $groupInfo->code
                ])
            ]);

            return redirect("/schedules/$scheduleId/groups/$groupId");
        // }
    }

}
