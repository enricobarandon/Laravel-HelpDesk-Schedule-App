<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserType;
use Illuminate\Support\Facades\Validator;
use Hash;
use App\Models\ActivityLog;
use Auth;
use DB;
use Excel;
use App\Exports\ScheduledGroupExport;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $userTypes = UserType::get();

        $users = User::select('users.id','name','email','user_types.role as role','users.created_at as created_at')
                    ->join('user_types', 'user_types.id','users.user_type_id');

        $keyword = $request->keyword;

        if($request->keyword){
            $users = $users->where(DB::raw('concat(users.name,users.email)'), 'like', '%' . $request->keyword . '%');
        }

        $userType = $request->userType;

        if($request->userType){
            $users = $users->where('users.user_type_id', $request->userType);
        }

        $users = $users->get();

        if ($request->has('download')) {
            return Excel::download(
                new ScheduledGroupExport('users.tables.usersTable', [
                    'users' => $users
                ]),
                'users.xlsx'
            );
        }

        return view('users.index', compact('users','userTypes','keyword','userType'));
    }
    public function updateUser(Request $request){
        $userTypes = UserType::get();

        $userId = $request->segment(3);

        $operation =  $request->segment(4);

        $usersInfo = User::select('id','name','email','user_type_id')
                    ->where('users.id', $userId)
                    ->first();
        return view('users.updateUsers', compact('usersInfo','userTypes','operation'));
    }

    public function submitUser(Request $request){

        $user = Auth::user();
        
        $updateUsers = '';

        $logs = '';

        if($request->operation == 'info'){
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|email|unique:users,email,'.$request->id,
                'user_type_id' => 'required|numeric'
            ]);
            if ($validator->fails()) {
                return redirect('/users/update/'.$request->id.'/info')
                    ->withErrors($validator)
                    ->withInput();
            }else{
                $updateUsers = User::where('users.id', $request->id)->update(
                    array(
                        'name' => $request->name,
                        'email' => $request->email,
                        'user_type_id' => $request->user_type_id
                    )
                );
                $logs = array(
                    'action' => 'Update Users Details',
                    'name' => $request->name,
                    'email' => $request->email,
                    'user_role' => UserType::getUserRole($request->user_type_id)->role
                );
            }
        }else{
            $validator = Validator::make($request->all(), [
                'cpassword' => 'required',
                'ccpassword' => 'required',
            ]);
            if ($validator->fails()) {
                return redirect('/users/update/'.$request->id.'/password')
                    ->withErrors($validator)
                    ->withInput();
            }else{
                if($request->cpassword == $request->ccpassword){

                    $newPassword = Hash::make($request->cpassword);

                    $updateUsers = User::where('users.id', $request->id)->update(
                        array(
                            'password' => $newPassword
                        )
                    );
                    $logs = array(
                        'action' => 'Update password',
                        'email' => User::getUserInfo($request->id)->email,
                    );
                }else{
                    return redirect('/users/update/'.$request->id.'/password')
                    ->withErrors('Password and Confirm password not match!');
                }
            }

        }
        
        if($updateUsers){
            ActivityLog::create([
                'type' => 'update-user',
                'user_id' => $user->id,
                'assets' => json_encode($logs)
            ]);
            return redirect('/users')->with('success','User successfully updated.');
        }
    }
    
}
