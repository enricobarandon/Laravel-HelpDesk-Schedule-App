<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;

class AccountController extends Controller
{
    public function index() {
        $accounts = Account::select('accounts.id as acc_id','groups.name as group_name','first_name','last_name','accounts.contact','position','username','accounts.is_active')
                        ->leftjoin('groups','groups.id','accounts.group_id')
                        ->get();
                        
        return view('accounts.index', compact('accounts'));
    }
}
