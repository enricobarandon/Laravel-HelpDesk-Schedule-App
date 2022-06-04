<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;
use DB;
use Excel;
use App\Exports\ScheduledGroupExport;

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
        // dd($account);
        $allowedSides = [
            'm' => 'Meron only',
            'w' => 'Wala only',
            'n' => 'None',
            'a' => 'All sides'
        ];
        
        return view('accounts.edit', compact('account','allowedSides'));
    }
}
