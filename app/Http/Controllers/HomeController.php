<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\Group;
use App\Models\Account;
use App\Models\RequestModel;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if (Auth::check()) {

            $allGroups = Group::count();
            $activeGroups = Group::where('is_active', 1)->count();
            $deactivatedGroups = Group::where('is_active', 0)->whereIn('status',['onhold','forpullout','temporarydeactivated','permanentdeactivated'])->count();
            $deactivatedGroupsNull = Group::where('is_active', 0)->where('status',null)->count();
            $fulloutGroups = Group::where('is_active', 0)->where('status','pullout')->count();

            $allAccounts = Account::count();
            $activeAccounts = Account::where('is_active',1)->count();
            $deactivatedAccounts = Account::where('is_active',0)->count();

            $pendingAccount = RequestModel::where('status','pending')->count();
            $approvedAccount = RequestModel::where('status','approved')->count();
            $rejectedAccount = RequestModel::where('status','rejected')->count();

            return view('home', compact(
                'activeGroups',
                'deactivatedGroups',
                'activeAccounts',
                'deactivatedAccounts',
                'pendingAccount',
                'approvedAccount',
                'rejectedAccount',
                'fulloutGroups',
                'deactivatedGroupsNull',
                'allGroups',
                'allAccounts'
            ));
        } else {
            return view('auth.login');
        }
    }
}
