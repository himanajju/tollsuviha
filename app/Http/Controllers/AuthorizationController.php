<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use App\User;
use DB;
use App\Usergroup;
use App\UserDevice;

class AuthorizationController extends Controller
{
    public function androidLogin(Request $request){
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
                                          'usergroup' => $resultSet->usergroup()->first()->group_title,
                                          'email' => $resultSet->email,
                                          'created_at' => $resultSet->created_at,
                                          'wallet_id' => $resultSet->wallet_id,
                                          'device_id' => $resultSet->device_id,
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

    public function webLogin(Request $request){
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
                                          'usergroup' => $resultSet->usergroup()->first()->group_title,
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

    //function for police login
    public function policeLogin(Request $request)
    {
        $validation = Validator::make($request->toArray(),[ 
            'email' => 'required',
            'password' => 'required'
        ]);

        if($validation->fails())
        {
            $response=[
                'status'=>500,
                'message'=>'validation errors',
                'errors'=>$validation->errors()
            ];
        }else
        {
            $userExistOBJ=User::where('email','=',$request->input('email'))->where('is_active','=',1)->get();
            if(!$userExistOBJ->isEmpty())
            {
                $userExistOBJ=$userExistOBJ->first();
                if($userExistOBJ->password==$request->input('password'))
                {
                    $responseData=array(
                        'id'=>$userExistOBJ->id,
                        'name'=>$userExistOBJ->name,
                        'email'=>$userExistOBJ->email,
                        'contact_no'=>$userExistOBJ->contact_no,
                        'created_at'=>$userExistOBJ->created_at
                        );
                    $response=[
                        'status'=>200,
                        'message'=>'access granted.',
                        'data'=>$responseData
                        ];
                }else
                {
                    $response=[
                        'status'=>501,
                        'message'=>'user password incorrect.'
                    ];
                }
            }
            else
            {
                $response=[
                    'status'=>501,
                    'message'=>'user does not exist.'
                ];
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
            'contact_no' => 'required|digits:10',
            'device_id' => 'required'
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
                DB::beginTransaction();
                
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

                    $userDeviceOBJ = new UserDevice();
                    $userDeviceOBJ->user()->associate($newUserOBJ);
                    $userDeviceOBJ->device_id = $request->input('device_id');
                    $userDeviceOBJ->save();

                    DB::commit();

                    // return to client
                    $response = ['status' => 200,
                                 'message'   => 'Registration Successfull.',
                                 'data' => array('id'=>$newUserOBJ->id, 'email' => $newUserOBJ->email, 'wallet_id' => $newUserOBJ->wallet_id, 'wallet_amount' => $newUserOBJ->wallet_amt, 'contact_no' => $newUserOBJ->contact_no, 'created_at' => $newUserOBJ->created_at, 'device_id' => $userDeviceOBJ->device_id)];
                }catch(\Exception $e){
                    DB::rollback();

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