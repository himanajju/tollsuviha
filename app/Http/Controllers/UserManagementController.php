<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\Usergroup;
use App\TollDetail;
use App\VipUser;
use App\Vehicle;
use DB;

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
    
    //fucntion for get user details
    public function getUserDetails($id){
     //Getting user details
        $userOBJ=User::where('id','=',$id)->get();
        if(!$userOBJ->isEmpty()){
            $userOBJ=$userOBJ->first();
                // if($userOBJ->is_active==1)
                // {
                //print_r($userOBJ->id);
                 $responseData= array(
                    'id'=>$userOBJ->id,
                    'name'=>$userOBJ->name,
                    'email'=>$userOBJ->email,
                    'contact_no'=>$userOBJ->contact_no,
                    'wallet_amt'=>$userOBJ->wallet_amt,
                    'usergroup'=>$userOBJ->usergroup
                    );
                //return to client
                $response = [ 'status'=>200,
                                'message'=>'user details fetched Successfully',
                                'data'=>$responseData ];
            
        }else{
            $response = [
                        'status'=> 501,
                        'message'=>'user does not exists'
                ];               
        }
        return response()->json($response);
        exit;
    
    }

    //fucntion for update user
    public function userUpdate(Request $request){
        //setting validator rules for all fields
        $validation=Validator::make($request->toArray(),[
                'id'=>'required',
                'name'=>'required',
                'email'=>'required|email',
                'password'=>'required|min:6',
                'contact_no'=>'required|digits:10'
            ]);
        if($validation->fails()){
            //validation errors
            $response=[
                'status'=>500,
                'message'=>'Validation failed',
                'errors'=>$validation->errors()
            ];
        }else
        {
            //getting user is exist
            $userExistObj=User::where('id','=',$request->input('id'))->get();
            if(!$userExistObj->isEmpty()){
                DB::beginTransaction();
                
                try{
                    //updating data
                    User::where('id',$request->input('id'))
                    ->update([
                        'name'=>$request->input('name'),
                        'email'=>$request->input('email'),
                        'password'=>$request->input('password'),
                        'contact_no'=>$request->input('contact_no')
                        ]);
                    DB::commit();
                    $response=[
                        'status'=>200,
                        'message'=>'Updated Successfully.'
                    ];
                }catch(\Exception $e){
                    //return to client
                    DB::rollBack();
                    $response = ['status'=> 501,
                        'message'=>'Oops!! something went wrong please try again later.'
                    ];
                }
            }else
            {
                //return to client
                    $response = ['status'=> 501,
                        'message'=>'invalid user.'
                    ];
            }
        }
        return response()->json($response);
        exit;

    }

    // //registration of toll and filling tolldetails
    // public function addTollDetails(Request $request){
    //     //setting validation rules for all fields
    //     $validation=Validator::make($request->toArray(),[
    //             'toll_id'=>'required',
    //             'toll_name'=>'required',
    //             'city'=>'required',
    //             'state'=>'required',
    //             'car_jeep_van_price'=>'required',
    //             'lcv_price' => 'required',
    //             'bus_truck_price'=>'required',
    //             'upto_3_axle_vehicle_price'=>'required',
    //             'axle_4_to_6_vehicle_price'=>'required',
    //             'axle_7_or_more_vehicle_price'=>'required',
    //             'hcm_eme_price'=>'required',
    //             'highway'=>'required|digits:1'
    //         ]);
    //         if($validation->fails()){
    //             //validation errors
    //             $response=['status'=>500,
    //                 'message'=>'validation failed',
    //                 'errors'=>$validation->errors()
    //             ];
    //         }else
    //         {
    //             //fetching toll details
    //             $tollOBJ=new TollDetail;
    //             $tollOBJ->toll_id=$request->input('toll_id');
    //             $tollOBJ->toll_name=$request->input('toll_name');
    //             $tollOBJ->city=$request->input('city');
    //             $tollOBJ->state=$request->input('state');
    //             $tollOBJ->car_jeep_van_price=$request->input('car_jeep_van_price');
    //             $tollOBJ->lcv_price=$request->input('lcv_price');
    //             $tollOBJ->bus_truck_price=$request->input('bus_truck_price');
    //             $tollOBJ->upto_3_axle_vehicle_price=$request->input('upto_3_axle_vehicle_price');
    //             $tollOBJ->axle_4_to_6_vehicle_price=$request->input('axle_4_to_6_vehicle_price');
    //             $tollOBJ->axle_7_or_more_vehicle_price=$request->input('axle_7_or_more_vehicle_price');
    //             $tollOBJ->hcm_eme_price=$request->input('hcm_eme_price');
    //             $tollOBJ->highway=$request->input('highway');
    //             try{
    //                     //saving toll details
    //                 $tollOBJ->save();
    //                 //return to client
    //                 $response=[ 'status'=>200,
    //                 'message'=>'toll details are inserted'
    //                 ];

    //                 }catch(\Exception $e){
    //                     //return to client
    //                     $response = ['status'=>501,
    //                                 'message'=>'Oops!! something went wrong please try again later.'];
    //                 }
                       
    //         }
    //         return response()->json($response);
    //         exit;
    // }


    //function for getting TOLL details
    public function getAllTollDetails()
    {
        //getting toll details
        $tollOBJ=TollDetail::all(['toll_id','toll_name','city','state','latitude','longitude','car_jeep_van_price','lcv_price','bus_truck_price','upto_3_axle_vehicle_price','axle_4_to_6_vehicle_price','axle_7_or_more_vehicle_price','hcm_eme_price','highway']);
        $response=['status'=>200,
            'message'=>'all toll details have Successfully fetched.',
            'data'=>$tollOBJ
        ];
        //return to client
        return response()->json($response);
        exit;
        
    }

    //function for inserting VIP_users
    public function addVIPusers(Request $request)
    {
        //setting all validattion rules and fields
        $validation=Validator::make($request->toArray(),[
                'designation'=>'required',
                'vehicle_no'=>'required'
            ]);
        if($validation->fails()){
            //validation errors
            $response=[
                'status'=>500,
                'message'=>'Validation Failed',
                'errors'=>$validation->errors()
            ];
        }else{
            $vip_usersOBJ=new VipUser;
            $vip_usersOBJ->designation = $request->input('designation');
            $vip_usersOBJ->vehicle_no=$request->input('vehicle_no');
            try{
                //saving vip user detail
                $vip_usersOBJ->save();
                //return to client
                $response=[
                    'status'=>200,
                    'message'=>'VIP user registrated Successfully.'];
            }catch(\Exception $e){
                //return to client
                $response = [
                        'status'=>501,
                        'message'=>'Oops!! something went wrong please try again later.'
                ];
            }
        }
        return response()->json($response);
        exit;
    }

    //function for getting details of vechile

    public function getVechileDetails($vehicle_no,$userId){
     //Getting user details
        $userOBJ=User::where('id','=',$userId)->where('usergroup_id','=',3)->where('is_active','=',1)->get();
        if(!$userOBJ->isEmpty()){
        $VehicleOBJ=Vehicle::where('vehicle_no','=',$vehicle_no)->get();
        if(!$VehicleOBJ->isEmpty()){
            $VehicleOBJ=$VehicleOBJ->first();
            $responseData=array(
                'vechile_no'=>$VehicleOBJ->vehicle_no,
                'vechile_type'=>$VehicleOBJ->vehicle_type
                );
                //return to client
            $response=[
                    'status'=>200,
                    'message'=>'vechile data is fetched Successfully.',
                    'data'=>$responseData
            ];

        }else{
            $response=[
                'status'=>501,
                'message'=>'vechile not registrated.'
            ];
        }
    }else{
        $response=[
                'status'=>501,
                'message'=>'unauthorized user'
            ];
      
    }
        return response()->json($response);
        exit;
    
    }

}
