<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;
use App\Models\ActivityLog;
use App\Models\UserType;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    // protected $redirectTo = RouteServiceProvider::HOME;
    public function redirectTo() 
    {
        $user = Auth::user();
        ActivityLog::create([
            'type' => 'login',
            'user_id' => $user->id,
            'assets' => json_encode([
                'email' => $user->email,
                'user_role' => UserType::getUserRole($user->user_type_id)->role
            ])
        ]);
        // return redirect('/home');
    }

    public function logout() {
        $user = Auth::user();
        ActivityLog::create([
            'type' => 'logout',
            'user_id' => $user->id,
            'assets' => json_encode(['
                email' => $user->email,
                'user_role' => UserType::getUserRole($user->user_type_id)->role
            ])
        ]);
        Auth::logout();
        return redirect('/');
    }
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    
}
