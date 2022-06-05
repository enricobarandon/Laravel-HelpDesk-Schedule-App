<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;
use DB;
use Excel;
use App\Exports\ScheduledGroupExport;
use App\Models\Group;

class AccountController extends Controller
{
    public function index(Request $request) {
        $accounts = Account::select('accounts.id as acc_id','groups.name as group_name','first_name','last_name','accounts.contact','position','username','accounts.is_active','accounts.uuid')
                        ->leftjoin('groups','groups.id','accounts.group_id');
        
        $filterStatus = $request->filterStatus;

        if($request->filterGname){
            $accounts = $accounts->where('code', 'like', '%' . $request->filterGname . '%');
        }

        if($request->filterName){
            $accounts = $accounts->where(DB::raw('concat(accounts.first_name,accounts.last_name,accounts.username)'), 'like', '%' . $request->filterName . '%');
        }

        if($request->filterStatus != ""){
            $accounts = $accounts->where('accounts.is_active', 'like', $request->filterStatus);
        }
        
        $accounts = $accounts->paginate(100);

        if ($request->has('download') || $request->has('downloadcurrent')) {

            return Excel::download(
                new ScheduledGroupExport('accounts.tables.accountsTable', [
                    'accounts' => $accounts
                ]),
                'accounts.xlsx'
            );
        }
        
        return view('accounts.index', compact('accounts','filterStatus'));
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
                    ->where('is_active',1)
                    ->orderBy('name','asc')
                    ->get();
        
        return view('accounts.edit', compact('account','allowedSides','positions','groups'));
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
                    ->where('is_active',1)
                    ->orderBy('name','asc')
                    ->get();

        return view('accounts.create', compact('allowedSides','positions','groups'));
    }
}
