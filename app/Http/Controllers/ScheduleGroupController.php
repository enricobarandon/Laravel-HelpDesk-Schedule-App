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

class ScheduleGroupController extends Controller
{

    public function index(Request $request)
    {
        $scheduleId = request()->segment(3);

        $scheduledGroups = ScheduledGroup::select('group_id')
                                ->where('schedule_id', $scheduleId)
                                ->get()
                                ->pluck('group_id')
                                ->toArray();
                                
        // $groupsForDisplay = Group::select('groups.id','groups.name as group_name','address','provinces.site','province_id','provinces.name as province_name')
        //                         ->join('provinces','provinces.id', 'groups.province_id')
        //                         ->whereIn('groups.id', $scheduledGroups)
        //                         ->get();
        $arrToStr = implode(",", $scheduledGroups);
        
        $groupsForDisplay = DB::select(DB::raw(
            "
            select 
                `groups`.`id`, `groups`.`name` as `group_name`, `address`, `provinces`.`site`, `province_id`, `provinces`.`name` as `province_name` 
            from `groups` 
            inner join `provinces` on `provinces`.`id` = `groups`.`province_id` 
                where `groups`.`id` in ($arrToStr)
            "
        ));
                                
        $groupsForSelect = Group::select('id','name','address')
                            ->where('is_active', 1)
                            ->whereNotIn('id', $scheduledGroups)
                            ->orderBy('name', 'asc')
                            ->get();

        // $groupsForDisplay = ScheduledGroup::select('groups.id')
        //                         ->join('groups')
        //                         ->join('provinces','provinces.id', 'groups.province_id')
        //                         ->where('schedule_id', $scheduleId)
        
        $provinces = Province::select('id','name','site')
                            ->get();
        
        
        // if($request->filterGroup){
        //     $groupsForDisplay = $groupsForDisplay->where('groups.name', 'like', '%' . $request->filterGroup . '%');
        // }

        // if($request->selectProvince){
        //     $groupsForDisplay = $groupsForDisplay->where('provinces.id', $request->selectProvince);
        // }

        // if($request->selectType){
        //     $groupsForDisplay = $groupsForDisplay->where('groups.group_type', $request->selectType);
        // }

        // if($request->siteID){
        //     $groupsForDisplay = $groupsForDisplay->where('provinces.site', $request->siteID);
        // }

        // $groupsForDisplay = $groupsForDisplay->get();

        return view('schedules.manage', [
            'scheduleId' => $scheduleId,
            'groupsForDisplay' => $groupsForDisplay,
            'groupsForSelect' => $groupsForSelect,
            'provinces' => $provinces
        ]);
        
    }

    public function addGroup()
    {
        $scheduleId = request()->id;
        
        $groupId = request()->group_id;

        $user = Auth::user();

        ActivityLog::create([
            'type' => 'add-group',
            'user_id' => $user->id,
            'assets' => json_encode([
                'action' => 'added group to schedule',
                'group_id' => $groupId,
                'schedule_id' => $scheduleId
            ])
        ]);

        if ($groupId == 'all') {

            $allGroups = Group::select('id')->where('is_active', 1)->orderBy('name','asc')->get();

            $groupArray = [];

            foreach($allGroups as $index => $group) {
                $groupArray[$index] = [
                    'schedule_id' => $scheduleId,
                    'group_id' => $group->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                    'user_id' => $user->id
                ];
            }
            
            $insertScheduledGroups = ScheduledGroup::insert($groupArray);

            if ($insertScheduledGroups) {
                return redirect('/schedules/manage/' . $scheduleId)->with('success', 'All active groups added!');
            } else {
                return redirect('/schedules/manage/' . $scheduleId)->with('error', 'Something went wrong!');
            }
            
        } else if ($groupId > 0)  {

            $scheduledGroup = ScheduledGroup::create([
                'schedule_id' => $scheduleId,
                'group_id' => $groupId,
                'user_id' => $user->id
            ]);

            if ($scheduledGroup) {
                return redirect('/schedules/manage/' . $scheduleId)->with('success', 'Group added!');
            } else {
                return redirect('/schedules/manage/' . $scheduleId)->with('error', 'Something went wrong!');
            }

        } else {
            return redirect('/schedules/manage/' . $scheduleId)->with('error', 'Something went wrong!');
        }
    }

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

        ActivityLog::create([
            'type' => 'remove-group',
            'user_id' => $user->id,
            'assets' => json_encode([
                'action' => 'removed group from a schedule',
                'group_id' => $groupId,
                'schedule_id' => $scheduleId
            ])
        ]);

        return redirect('/schedules/manage/' . $scheduleId);
    }

    public function manageGroup()
    {
        $scheduleId = request()->scheduleId;
        
        $groupId = request()->groupId;

        $groupInfo = Group::select('groups.name as group_name','address', 'groups.group_type','owner','contact','code','provinces.site','installed_pc','active_staff','provinces.name as province_name')
                            ->where('groups.id', $groupId)
                            ->join('provinces', 'provinces.id', 'groups.province_id')
                            ->first();

        $scheduledGroupInfo = ScheduledGroup::select('id','operation_time')
                                ->where('schedule_id', $scheduleId)
                                ->where('group_id', $groupId)
                                ->first();

        $groupAccounts = Account::select('accounts.id as acc_id','scheduled_accounts.id as sched_id','first_name','last_name','username','contact','position','scheduled_group_id')
                            ->leftJoin('scheduled_accounts','scheduled_accounts.account_id', 'accounts.id')
                            ->where('accounts.group_id', $groupId)
                            ->get();

        return view('schedules.groups.index', [
            'scheduleId' => $scheduleId,
            'groupId' => $groupId,
            'groupInfo' => $groupInfo,
            'scheduledGroupInfo' => $scheduledGroupInfo,
            'groupAccounts' => $groupAccounts
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

            ActivityLog::create([
                'type' => 'remove-confirmed-account',
                'user_id' => $user->id,
                'assets' => json_encode([
                    'action' => 'Removed account from a scheduled group',
                    'account_id' => $accountId,
                    'schedule_group_id' => $scheduledGroupId
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
        
        $user = Auth::user();

        $storeAccountFromScheduledGroup = ScheduledAccount::insert([
            'scheduled_group_id' => $scheduledGroupId,
            'schedule_id' => $scheduleId,
            'group_id' => $groupId,
            'account_id' => $accountId,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        $scheduledGroupInfo = ScheduledGroup::find($scheduledGroupId);

        if ($storeAccountFromScheduledGroup) {

            ActivityLog::create([
                'type' => 'confirmed-account',
                'user_id' => $user->id,
                'assets' => json_encode([
                    'action' => 'Confirmed an account for a scheduled group',
                    'account_id' => $accountId,
                    'schedule_group_id' => $scheduledGroupId,
                    'scheduleId' => $scheduleId,
                    'groupId' => $groupId
                ])
            ]);

            return redirect("/schedules/$scheduledGroupInfo->schedule_id/groups/$scheduledGroupInfo->group_id");
        }
    }

    public function view(Request $request)
    {
        $scheduleId = request()->id;

        $scheduleInfo = Schedule::find($scheduleId, ['name','date_time']);
        
        $scheduledGroups = ScheduledGroup::select('scheduled_groups.schedule_id','scheduled_groups.operation_time','scheduled_groups.group_id','groups.group_type','name','code','owner','contact','address','active_staff','installed_pc','remarks','site')
                            ->join('groups','groups.id', 'scheduled_groups.group_id')
                            ->where('scheduled_groups.schedule_id', $scheduleId)
                            ->get();

        $groupedByAccounts = ScheduledAccount::createAccountsAssocArr($scheduleId);
        if ($request->has('download') || $request->has('downloadcurrent')) {

            return Excel::download(
                new ScheduledGroupExport('schedules.tables.fullview', [
                    'scheduleInfo' => $scheduleInfo,
                    'groups' => $scheduledGroups,
                    'groupedByAccounts' => $groupedByAccounts
                ]),
                'schedule.xlsx'
            );
        }

        return view('schedules.view', [
            'scheduleInfo' => $scheduleInfo,
            'groups' => $scheduledGroups,
            'groupedByAccounts' => $groupedByAccounts
        ]);
    }

    public function updateGroup()
    {
        $scheduleId = request()->scheduleId;

        $groupId = request()->groupId;
        
        $user = Auth::user();

        $newOperationTime = date('H:i:s',strtotime(request()->operation_time));
        
        $updateOperationTime = ScheduledGroup::where('schedule_id', $scheduleId)->where('group_id', $groupId)->update(['operation_time' => $newOperationTime]);

        if ($updateOperationTime) {

            ActivityLog::create([
                'type' => 'update-operation-time',
                'user_id' => $user->id,
                'assets' => json_encode([
                    'action' => 'Updated operation time',
                    'scheduleId' => $scheduleId,
                    'groupId' => $groupId,
                    'newOperationTime' => $newOperationTime
                ])
            ]);

            return redirect("/schedules/$scheduleId/groups/$groupId")->with('success', 'Group details updated!');
        } else {
            return redirect("/schedules/$scheduleId/groups/$groupId")->with('error', 'Something went wrong!');
        }

    }

}
