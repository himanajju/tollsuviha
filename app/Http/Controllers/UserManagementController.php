<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class UserManagementController extends Controller
{
    //Function for creating user
    public function addUser(Request $request){
        //Setting validation rules for all fields
        $validation = Validator::make($request->toArray(),[ 
            'name' => 'required',
            'usergroup' => 'required|digits:1',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'contact_no' => 'required|digits:10'
        ]);

        if($validation->fails()){
            //validation errors
            $response = ['status' => 500,
                         'message'   => 'Validation Failed',
                         'errors'    => $validation->errors()];
        }else{
            //Getting usergroup
            $usergroupOBJ = Usergroup::where('id', '=', $request->input('usergroup'))->get();
            if(!$usergroupOBJ->isEmpty()){
                $usergroupOBJ = $usergroupOBJ->first();
                
                $newUserOBJ = new User;
                
                $newUserOBJ->usergroup()->associate($usergroupOBJ);
                $newUserOBJ->name = $request->input('name');
                $newUserOBJ->email = $request->input('email');
                $newUserOBJ->password = $request->input('password');
                $newUserOBJ->contact_no = $request->input('contact_no');
                $newUserOBJ->wallet_amt = '0.00';
                $newUserOBJ->is_active = '1';

                try{
                    //saving user detail
                    $newUserOBJ->save();
                    // return to client
                    $response = ['status' => 200,
                                 'message'   => 'Registration Successfull.'];
                }catch(\Exception $e){
                    // return to client
                    $response = ['status' => 501,
                                 'message'   => 'Oops!! something went wrong please try again later.'];
                }
            }else{
                // return to client
                $response = ['status' => 501,
                             'message'   => 'Oops!! something went wrong please try again later.'];
            }
        }
        return response()->json($response);
        exit;
    }
    
}
