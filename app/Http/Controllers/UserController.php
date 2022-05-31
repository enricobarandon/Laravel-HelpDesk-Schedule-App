<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserType;
use Illuminate\Support\Facades\Validator;
use Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::select('users.id','name','email','user_types.role as role')
                    ->join('user_types', 'user_types.id','users.user_type_id')
                    ->get();
        return view('users.index', compact('users'));
    }
    public function updateUser(Request $request){
        $userTypes = UserType::get();

        $userId = $request->segment(3);

        $operation =  $request->segment(4);

        $usersInfo = User::select('id','name','email','user_type_id')
                    ->where('users.id', $userId)
                    ->first();
        return view('users.UpdateUsers', compact('usersInfo','userTypes','operation'));
    }

    public function submitUser(Request $request){

        $updateUsers = '';

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
                }else{
                    return redirect('/users/update/'.$request->id.'/password')
                    ->withErrors('Password and Confirm password not match!');
                }
            }

        }
        
        if($updateUsers){
            return redirect('/users')->with('success','User successfully updated.');
        }
    }
    
}
