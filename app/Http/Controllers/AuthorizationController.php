<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\Usergroup;

class AuthorizationController extends Controller
{
    public function login(Request $request){
        $validation = Validator::make($request->toArray(),[ 
            'email' => 'required',
            'password' => 'required'
        ]);

        if($validation->fails()){
            // return to client
            $response = [
                'status' => 500,
                'message'   => 'Validation Failed',
                'errors'    => $validation->errors()
            ];
        }else{
            $email = $request->input('email');
            $password = $request->input('password');
            // Matching Email & Password
            $resultSet = User::where('email', $email)
                ->where('password', $password)->first();

            if($resultSet != null){
                //$resultSet->toArray();
                if($resultSet->is_active == 1){
                    $responseData = array('id' => $resultSet->id,
                                          'name' => $resultSet->name,
                                          'email' => $resultSet->email,
                                          'contact_no' => $resultSet->contact_no,
                                          'usergroup' => $resultSet->usergroup,
                                          'email' => $resultSet->email,
                                          'created_at' => $resultSet->created_at,
                                          'wallet_amt' => $resultSet->wallet_amt);

                    // return to client
                    $response = ['status' => 200,
                                 'message'   => 'Login Successfull.',
                                 'data' => $responseData];
                }else{
                    // return to client
                    $response = ['status' => 501,
                                 'message'   => 'Account Blocked'];
                }
            }else{
                // return to client
                $response = ['status' => 501,
                             'message'   => 'Invalid email/Password.'];
            }
        }
        return response()->json($response);
        exit;
    }

    //fucntion for genrating random string
    private static function getRandomString($length){
        $chars = '0123456789';
        $string = '';
        for ($i = 0; $i < $length; $i++) {
            $string .= $chars[rand(0, strlen($chars) - 1)];
        }
        return $string;
    }


    //Function for creating user
    public function registration(Request $request){
        //Setting validation rules for all fields
        $validation = Validator::make($request->toArray(),[ 
            'name' => 'required',
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
            $usergroupOBJ = Usergroup::where('id', '=', '2')->get();
            if(!$usergroupOBJ->isEmpty()){
                $usergroupOBJ = $usergroupOBJ->first();            
                $newUserOBJ = new User;
                $newUserOBJ->usergroup()->associate($usergroupOBJ);
                $newUserOBJ->name = $request->input('name');
                $newUserOBJ->email = $request->input('email');
                $newUserOBJ->password = $request->input('password');
                $newUserOBJ->contact_no = $request->input('contact_no');
                wallet:{
                        $walletId = $this->getRandomString(7);
                        $walletIdCon="WLT".$walletId."";
                        $walletIdExist = User::where('wallet_id','=',$walletIdCon)->get();
                        if($walletIdExist->isEmpty())
                        {
                            $newUserOBJ->wallet_id=$walletIdCon;     
                        }else
                        {
                            goto wallet;
                        }
                    }
                
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