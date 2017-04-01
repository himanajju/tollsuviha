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
use App\TollUser;
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
                DB::beginTransaction();

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

                    if($usergroupOBJ->group_title == "MANAGER" || $usergroupOBJ->group_title == "BOOTH_OPERATOR"){
                        $tollOBJ = TollDetail::where('id', '=', 1)->get();
                        if(!$tollOBJ->isEmpty()){   
                            $tollUserOBJ = new TollUser();
                            
                            $tollUserOBJ->user()->associate($newUserOBJ);
                            $tollUserOBJ->toll()->associate($tollOBJ->first());

                            //saving user detail
                            $tollUserOBJ->save();
                            DB::commit();
                            // return to client
                            $response = ['status' => 200,
                                         'message'   => 'Registration Successfull.'];
                        }else{
                            DB::rollback();
                            $response = ['status' => 501,
                                         'message'   => 'Oops!! something went wrong please try again later.'];
                        }
                    }
                }catch(\Exception $e){
                    DB::rollback();
                    // return to client
                    $response = ['status' => 501,
                                 'message'   => 'Oops!! something went wrong please try again later.'];
                }
            }else{
                DB::rollback();
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
                'message'=>'    Validation failed',
                'errors'=>$validation->errors()
            ];
        }else
        {
            //getting user is exist
            $userExistObj=User::where('id','=',$request->input('id'))->get();
            if(!$userExistObj->isEmpty()){
                
                try{
                    //updating data
                    User::where('id',$request->input('id'))
                    ->update([
                        'name'=>$request->input('name'),
                        'email'=>$requestd->input('email'),
                        'password'=>$request->input('password'),
                        'contact_no'=>$request->input('contact_no')
                        ]);
                    $response=[
                        'status'=>200,
                        'message'=>'Updated Successfully.'
                    ];
                }catch(\Exception $e){
                    //return to client
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

    // //registration of toll and filling tolldetailsOBJ
    // public function addtolldetailsOBJ(Request $request){
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
    //             //validation  
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
    public function getAlltolldetailsOBJ()
    {
        //getting toll details
        $tollOBJ=TollDetail::all(['id','toll_id','toll_name','city','state','latitude','longitude','car_jeep_van_price','lcv_price','bus_truck_price','upto_3_axle_vehicle_price','axle_4_to_6_vehicle_price','axle_7_or_more_vehicle_price','hcm_eme_price','highway']);
        $response=['status'=>200,
            'message'=>'all toll details have Successfully fetched.',
            'data'=>$tollOBJ
        ];
        //return to client
        return response()->json($response);
        exit;
        
    }


    //function for blocking users
    public function blockUser($userEmail,$contactNo,$adminId)
    {
        $isAdminId=User::where('id','=',$adminId)->where('is_active','=',1)->where('usergroup_id','=',1)->get();
        if(!$isAdminId->isEmpty())
        {
                $userOBJ=User::where('email','=',$userEmail)->where('contact_no','=',$contactNo)->where('is_active','=',1)->get();
                if(!$userOBJ->isEmpty())
                {
                    $userOBJ=$userOBJ->first();
                    if($userOBJ->is_blocked==0)
                    {

                       try{
                            User::where('email','=',$userEmail)->update(['is_blocked'=>1,'update_by'=>$adminId]);
                            $response=[
                                'status'=>200,
                                'message'=>'user is blocked Successfully.'
                            ];
                        }catch(\Exception $e)
                        {
                            //retuen to client
                            $response=[
                                'status'=>501,
                                'message'=>'Oops!! something went wrong please try again later.'
                            ];
                        }
                    }else
                    {
                        //retuen to client
                            $response=[
                                'status'=>501,
                                'message'=>'user is alredy blocked.'
                            ];
                    }

                }
                else
                {
                    //return to client
                    $response=[
                            'status'=>501,
                            'message'=>'user does not exist or is not active.'
                        ];
                }
        }else
        {
            //return to client
                $response=[
                        'status'=>501,
                        'message'=>'user is not admin.'
                    ];
        }
        return response()->json($response);
        exit;
    }

//function for unblocking users
    public function unblockUser($userEmail,$contactNo,$adminId)
    {
        $isAdminId=User::where('id','=',$adminId)->where('is_active','=',1)->where('usergroup_id','=',1)->get();
        if(!$isAdminId->isEmpty())
        {
                $userOBJ=User::where('email','=',$userEmail)->where('contact_no','=',$contactNo)->where('is_active','=',1)->get();
                if(!$userOBJ->isEmpty())
                {
                    $userOBJ=$userOBJ->first();
                    if($userOBJ->is_blocked==1)
                    {

                       try{
                            User::where('email','=',$userEmail)->update(['is_blocked'=>0,'update_by'=>$adminId]);
                            $response=[
                                'status'=>200,
                                'message'=>'user is unblocked Successfully.'
                            ];
                        }catch(\Exception $e)
                        {
                            //retuen to client
                            $response=[
                                'status'=>501,
                                'message'=>'Oops!! something went wrong please try again later.'
                            ];
                        }
                    }else
                    {
                        //retuen to client
                            $response=[
                                'status'=>501,
                                'message'=>'user is alredy unblocked.'
                            ];
                    }

                }
                else
                {
                    //return to client
                    $response=[
                            'status'=>501,
                            'message'=>'user does not exist or is not active.'
                        ];
                }
        }else
        {
            //return to client
                $response=[
                        'status'=>501,
                        'message'=>'user is not admin.'
                    ];
        }
        return response()->json($response);
        exit;
    }



    //function for getting all users details
    public function getAllUsers($userId)
    {
        $userOBJ=User::where('id','=',$userId)->where('is_active','=',1)->whereIn('usergroup_id',[1,4])->get();
        if(!$userOBJ->isEmpty())
        {
            $allUserOBJ=User::whereNotIn('usergroup_id',[1,4])->get();
            //return to client
            $response=[
            'status'=>200,
            'message'=>'all users details fetched Successfully.',
            'data'=>$allUserOBJ
            ];
        }else{
            //return to client
            $response=[
                'status'=>501,
                'message'=>'user is not admin or manager.'
            ];
        }
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

    //function for getting details of vechile and toll price

    public function getVechileDetails($vehicleNo,$userId,$tollId){
     //Getting user details
        $userOBJ=User::where('id','=',$userId)->where('usergroup_id','=',3)->where('is_active','=',1)->get();
        $price="";
        if(!$userOBJ->isEmpty()){
            $VehicleOBJ=Vehicle::where('vehicle_no','=',$vehicleNo)->get();
            if(!$VehicleOBJ->isEmpty()){
                $VehicleOBJ=$VehicleOBJ->first();
                $tolldetailsOBJ=TollDetail::where('id','=',$tollId)->get()->first();
                if($VehicleOBJ->vehicle_type=="car_jeep_van"){
                    $price=$tolldetailsOBJ->car_jeep_van_price;
                }elseif ($VehicleOBJ->vehicle_type=="bus_truck") {
                    $price=$tolldetailsOBJ->bus_truck_price;
                }elseif ($VehicleOBJ->vehicle_type=="lcv") {
                    $price=$tolldetailsOBJ->lcv_price;
                }elseif ($VehicleOBJ->vehicle_type=="upto_3_axle_vehicle") {
                    $price=$tolldetailsOBJ->upto_3_axle_vehicle_price;
                }elseif ($VehicleOBJ->vehicle_type=="axle_4_to_6_vehicle") {
                    $price=$tolldetailsOBJ->axle_4_to_6_vehicle_price;
                }elseif ($VehicleOBJ->vehicle_type=="axle_7_or_more_vehicle") {
                    $price=$tolldetailsOBJ->axle_7_or_more_vehicle_price;
                }elseif ($VehicleOBJ->vehicle_type=="hcm_eme") {
                    $price=$tolldetailsOBJ->hcm_eme_price;                
                }
                $responseData=array(
                    'vehicle_no'=>$VehicleOBJ->vehicle_no,
                    'vehicle_type'=>$VehicleOBJ->vehicle_type,
                    'amount'=>$price
                    );
                    //return to client
                $response=[
                        'status'=>200,
                        'message'=>'vehicle data is fetched Successfully.',
                        'data'=>$responseData
                ];

            }else{
                $response=[
                    'status'=>501,
                    'message'=>'vehicle not registrated.'
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

    //genrate random string
    // private function genrateRndomString()
    // {
    //     return
    // }

    //function for chnage password of user

    public function chnagePassword(Request $request)
    {
        //setting validation 
        $validation=Validator::make($request->toArray(),[
            'user_id'=>'required',
            'oldPassword'=>'required|min:6',
            'newPassword'=>'required|min:6'
            ]);
        if($validation->fails())
        {
            //return to client
            $response=[
                'status'=>500,
                'message'=>'validation errors.',
                'errors'=>$validation->errors()
            ];
        }else
        {
            $userExistOBJ=User::where('id','=',$request->input('user_id'))->where('is_active','=',1)->where('is_blocked','=',0)->get();
            if(!$userExistOBJ->isEmpty())
            {
                $userExistOBJ=$userExistOBJ->first();
                if($userExistOBJ->password==$request->input('oldPassword'))
                {
                    User::where('id','=',$request->input('user_id'))->where('password','=',$request->input('oldPassword'))
                    ->update(['password'=>$request->input('newPassword'),'update_by'=>$request->input('user_id')]);

                    //return to client
                    $response=[
                        'status'=>200,
                        'message'=>'password is Successfully updated.'
                    ];
                }else
                {
                    //return to client
                    $response=[
                        'status'=>501,
                        'message'=>'old password is wrong.'
                    ];
                }
            }else
            {
                //return to client
                $response=[
                    'status'=>501,
                    'message'=>'user does not exist or is not active or blocked.'
                ];
            }
        }
        return response()->json($response);
        exit;
    }

    //function for toll list in between to stations
    public function tollList(Request $request)
    {

        $validation=validator::make($request->toArray(),[
                'source'=>'required',
                'destination'=>'required',
                'vehicle_type'=>'required'
            ]);

        // print_r("hh");die;
        if($validation->fails())
        {
            //return to client
            $response=[
                'status'=>500,
                'message'=>'validation errors',
                'errors'=>$validation->errors()
            ];

        }else
        {
            
            $src=$request->input('source');
            $destination=$request->input('destination');
            $vehicle_type=$request->input('vehicle_type');
            $longitudeId=0;
            $totalPrice=0;
            $price=0;
            if($src=="raipur" && $destination=="nagpur")
            {
                $longitudeId="1111";
            }elseif ($src=="nagpur" && $destination=="delhi") {
                $longitudeId="2222";
            }
            $tollListOBJ=TollDetail::where('longitude','like',$longitudeId.'%')
                        ->select('id','toll_id','toll_name','city','state',$vehicle_type."_price")->get();
               // print_r($longitudeId);die;
            if(!$tollListOBJ->isEmpty())
            {
                $response=array('status'=>200,
                                'message'=>'list of toll are fetched Successfully.',
                                'data'=>$tollListOBJ,
                                'totalPrice'=>0);

                    
                foreach ($tollListOBJ as $tollListOBJ) {
                    
                    if($vehicle_type=="car_jeep_van"){
                        $price=$tollListOBJ->car_jeep_van_price;    
                    }elseif ($vehicle_type=="bus_truck") {
                        $price=$tollListOBJ->bus_truck_price;
                    }elseif ($vehicle_type=="lcv") {
                        $price=$tollListOBJ->lcv_price;
                    }elseif ($vehicle_type=="upto_3_axle_vehicle") {
                        $price=$tollListOBJ->upto_3_axle_vehicle_price;
                    }elseif ($vehicle_type=="axle_4_to_6_vehicle") {
                        $price=$tollListOBJ->axle_4_to_6_vehicle_price;
                    }elseif ($vehicle_type=="axle_7_or_more_vehicle") {
                        $price=$tollListOBJ->axle_7_or_more_vehicle_price;
                    }elseif ($vehicle_type=="hcm_eme") {
                        $price=$tollListOBJ->hcm_eme_price;              
                    }
                    $totalPrice+=$price;
                }
                $response['totalPrice']=$totalPrice;
            }else
            {
                $response=[
                    'status'=>501,
                    'message'=>'Oops!! something went wrong please try again later.'
                ];
            }
        }
        return response()->json($response);
        exit;
    }

}

