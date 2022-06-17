<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;
use DB;
use Excel;
use App\Exports\ScheduledGroupExport;
use App\Models\Group;
use App\Models\UserType;
use App\Models\ActivityLog;
use App\Models\User;
use Auth;

class AccountController extends Controller
{
    public function index(Request $request) {
        $accounts = Account::select('accounts.id as acc_id','groups.name as group_name','first_name','last_name','accounts.contact','position','username','accounts.is_active','accounts.uuid','password')
                        ->leftjoin('groups','groups.id','accounts.group_id')
                        ->where('accounts.is_active', 1);
        
        $filterStatus = $request->filterStatus;
        
        $filterRole = $request->filterRole;

        if($request->filterGname){
            $accounts = $accounts->where('code', 'like', '%' . $request->filterGname . '%');
        }

        if($request->filterName){
            $accounts = $accounts->where(DB::raw('concat(accounts.first_name,accounts.last_name,accounts.username)'), 'like', '%' . $request->filterName . '%');
        }

        if($request->filterRole != ""){
            $accounts = $accounts->where('accounts.position', $request->filterRole);
        }
        
        $accounts = $accounts->paginate(100);

        if ($request->has('download') || $request->has('downloadcurrent')) {

            return Excel::download(
                new ScheduledGroupExport('accounts.tables.accountsTable', [
                    'accounts' => $accounts
                ]),
                'active-accounts.xlsx'
            );
        }
        
        return view('accounts.index', compact('accounts','filterRole'));
    }

    public function show(Account $account)
    {
        // dd('test');
        $allowedSides = [
            'm' => 'Meron only',
            'w' => 'Wala only',
            'n' => 'None',
            'a' => 'All sides'
        ];

        $positions = [
            'Teller',
            'Cashier',
            'Teller/Cashier',
            'Supervisor',
            'Operator'
        ];

        $groups = Group::select('id','code','name')
                    // ->where('is_active',1)
                    ->orderBy('name','asc')
                    ->get();

        $userTypes = UserType::all();
        
        return view('accounts.edit', compact('account','allowedSides','positions','groups','userTypes'));
    }

    public function create()
    {
        $allowedSides = [
            'm' => 'Meron only',
            'w' => 'Wala only',
            'n' => 'None',
            'a' => 'All sides'
        ];

        $positions = [
            'Teller',
            'Cashier',
            'Teller/Cashier',
            'Supervisor',
            'Operator'
        ];

        $groups = Group::select('code','name')
                    // ->where('is_active',1)
                    ->orderBy('name','asc')
                    ->get();

        return view('accounts.create', compact('allowedSides','positions','groups'));
    }

    public function updatePassword(Request $request){

        $user = Auth::user();

        $id = $request->segment(3);
        
        $accountsInfo = Account::find($id);

        $updatePassword = Account::where('accounts.id', $id)
                                ->update(array('password' => $request->password));
                
        if($updatePassword){

            ActivityLog::create([
                'type' => 'update-account-password',
                'user_id' => $user->id,
                'assets' => json_encode([
                    'action' => 'Update Account Password',
                    'username' => $accountsInfo->username,
                    'position' => $accountsInfo->position
                    ])
            ]);

            return redirect($request->currentURL)->with('success', 'Updated password!');
        }
    }

    public function accountsDeactivated(Request $request) {
        $accounts = Account::select('accounts.id as acc_id','groups.name as group_name','first_name','last_name','accounts.contact','position','username','accounts.is_active','accounts.uuid','password','accounts.status')
                        ->leftjoin('groups','groups.id','accounts.group_id')
                        ->where('accounts.is_active', 0);
        
        $filterStatus = $request->filterStatus;
        
        $filterRole = $request->filterRole;

        if($request->filterGname){
            $accounts = $accounts->where('code', 'like', '%' . $request->filterGname . '%');
        }

        if($request->filterName){
            $accounts = $accounts->where(DB::raw('concat(accounts.first_name,accounts.last_name,accounts.username)'), 'like', '%' . $request->filterName . '%');
        }

        if($request->filterRole != ""){
            $accounts = $accounts->where('accounts.position', $request->filterRole);
        }

        if($request->filterStatus != ""){
            $accounts = $accounts->where('accounts.status', $request->filterStatus);
        }
        
        $accounts = $accounts->paginate(100);

        if ($request->has('download') || $request->has('downloadcurrent')) {

            return Excel::download(
                new ScheduledGroupExport('accounts.tables.deactivatedTable', [
                    'accounts' => $accounts
                ]),
                'deactivated-accounts.xlsx'
            );
        }
        
        return view('accounts.accounts-deactivated', compact('accounts','filterRole'));
    }

    public function updateStatus(Request $request){

        $user = Auth::user();

        $accountsInfo = Account::find($request->id);

        $accounts = Account::where('id',$request->id)
                            ->update(['accounts.status' => $request->status]);
        
        if($accounts){

            ActivityLog::create([
                'type' => 'update-account-status',
                'user_id' => $user->id,
                'assets' => json_encode([
                    'action' => 'Update Account Status',
                    'username' => $accountsInfo->username,
                    'position' => $accountsInfo->position,
                    'status' => $request->status
                    ])
            ]);

            return redirect('accounts/deactivated')->with('success', 'Updated Status!');

        }

    }
}
