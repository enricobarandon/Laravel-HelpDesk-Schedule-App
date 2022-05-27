<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;
use DB;

class AccountController extends Controller
{
    public function index(Request $request) {
        $accounts = Account::select('accounts.id as acc_id','groups.name as group_name','first_name','last_name','accounts.contact','position','username','accounts.is_active')
                        ->leftjoin('groups','groups.id','accounts.group_id');
        
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
        return view('accounts.index', compact('accounts'));
    }

    public function show(Account $account)
    {
        dd($account);
    }
}
