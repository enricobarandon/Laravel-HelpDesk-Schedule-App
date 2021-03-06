<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Auth;
use App\Models\UserType;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

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
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'user_type_id' => ['required','numeric']
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'user_type_id' => $data['user_type_id']
        ]);
    }

    public function register(Request $request){
        $this->validator($request->all())->validate();
        event(new Registered($user = $this->create($request->all())));
        // $this->guard()->login($user);
        ActivityLog::create([
            'type' => 'create-user',
            'user_id' => Auth::id(),
            'assets' => json_encode([
                'action' => 'created a user account',
                'name' => $request->name,
                'email' => $request->email,
                'role' => UserType::getUserRole($request->user_type_id)->role
            ])
        ]);

        return $this->registered($request, $user)
                            ?: redirect('/users')->with('success','User successfully created.');

    }

    public function showRegistrationForm() {
        $user = Auth::user();
        $userType = UserType::get();
        if ($user->user_type_id != '1') {
            return redirect('/users')->withError('Permission denied.');
        }
        return view('auth.register', [
            'userTypes' => $userType
        ]);
    }

}
