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

class ScheduleGroupController extends Controller
{

    public function index()
    {
        $scheduleId = request()->segment(3);

        $scheduledGroups = ScheduledGroup::select('group_id')
                                ->where('schedule_id', $scheduleId)
                                ->get()
                                ->pluck('group_id')
                                ->toArray();

        $groupsForDisplay = Group::select('groups.id','groups.name as group_name','address','provinces.site','province_id','provinces.name as province_name')
                                ->join('provinces','provinces.id', 'groups.province_id')
                                ->whereIn('groups.id', $scheduledGroups)
                                ->get();
        
        $groupsForSelect = Group::select('id','name','address')
                            ->whereNotIn('id', $scheduledGroups)
                            ->get();

        return view('schedules.manage', [
            'scheduleId' => $scheduleId,
            'groupsForDisplay' => $groupsForDisplay,
            'groupsForSelect' => $groupsForSelect
        ]);
        
    }

    public function addGroup()
    {
        $scheduleId = request()->id;
        
        $groupId = request()->group_id;

        $user = Auth::user();

        if ($groupId == 'all') {

            $allGroups = Group::select('id')->where('is_active', 1)->get();

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

            return $insertScheduledGroups;
            
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

        $scheduledGroupInfo = ScheduledGroup::where('schedule_id', $scheduleId)
                            ->where('group_id', $groupId)
                            ->first();

        $deleteScheduledGroup = $scheduledGroupInfo->delete();

        $deleteScheduledAccounts = ScheduledAccount::where('scheduled_group_id', $scheduledGroupInfo->id)
                                        ->delete();

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
            'scheduleId' => encrypt($scheduleId),
            'groupId' => encrypt($groupId),
            'groupInfo' => $groupInfo,
            'scheduledGroupInfo' => $scheduledGroupInfo,
            'groupAccounts' => $groupAccounts
        ]);
    }

    public function deleteScheduledAccount()
    {
        $scheduledGroupId = request()->scheduledGroupId;
        
        $accountId = request()->accountId;

        $deleteAccountFromScheduledGroup = ScheduledAccount::where('scheduled_group_id', $scheduledGroupId)
                                                    ->where('account_id', $accountId)
                                                    ->delete();
                                                    
        $scheduledGroupInfo = ScheduledGroup::find($scheduledGroupId);

        if ($deleteAccountFromScheduledGroup) {
            return redirect("/schedules/$scheduledGroupInfo->schedule_id/groups/$scheduledGroupInfo->group_id");
        }
    }

    public function storeScheduledAccount() 
    {
        $scheduledGroupId = request()->scheduledGroupId;
        
        $accountId = request()->accountId;

        $scheduleId = decrypt(request()->scheduleId);

        $groupId = decrypt(request()->groupId);

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
            return redirect("/schedules/$scheduledGroupInfo->schedule_id/groups/$scheduledGroupInfo->group_id");
        }
    }

    public function view()
    {
        $scheduleId = request()->id;

        $scheduleInfo = Schedule::find($scheduleId, ['name','date_time']);
        
        $scheduledGroups = ScheduledGroup::select('scheduled_groups.schedule_id','scheduled_groups.operation_time','scheduled_groups.group_id','groups.group_type','name','code','owner','contact','address','active_staff','installed_pc','remarks','site')
                            ->join('groups','groups.id', 'scheduled_groups.group_id')
                            ->where('scheduled_groups.schedule_id', $scheduleId)
                            ->get();

        $groupedByAccounts = ScheduledAccount::createAccountsAssocArr($scheduleId);

        return view('schedules.view', [
            'scheduleInfo' => $scheduleInfo,
            'groups' => $scheduledGroups,
            'groupedByAccounts' => $groupedByAccounts
        ]);
    }

}
