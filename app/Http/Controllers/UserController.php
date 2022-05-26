<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::select('users.id','name','email','user_types.role as role')
                    ->join('user_types', 'user_types.id','users.user_type_id')
                    ->get();
        return view('users.index', compact('users'));
    }
}
