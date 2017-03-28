<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\UserWallet;
use App\UserTollTxn;
use App\Vehicle;
use App\TollDetail;
use DB;

class TxnManagementController extends Controller
{
    //function for payment txn details in user wallet
    public function addTxnDetails(Request $request)
    {
    	//setting validation rules
    	$validation=validator::make($request->toArray(),[
    		'user_id'=>'required',
    		'json_data'=>'required'
    		]);
    	if($validation->fails())
    	{
    		//validation error
    		$response=[
    			'status'=>500,
    			'message'=>'validation error',
    			'errors'=>$validation->errors()
    		];
    	}else
    	{
    		$userExistOBJ=User::where('id','=',$request->input('user_id'))->where('usergroup_id','=','2')->where('is_active','=','1')->get();
    		if(!$userExistOBJ->isEmpty())
    		{
    			DB::beginTransaction();
    			$txnOBJ=new UserWallet;
    			$txnOBJ->user_id=$request->input('user_id');
    			$json=json_decode($request->input('json_data'),true);
    			
    			$txnOBJ->status = $json['status'];
    			$txnOBJ->txn_id = strtoupper($json['txnid']);
    			$txnOBJ->amount=$json['amount'];
    			$txnOBJ->json_data=$request->input('json_data');
    			$Wallet=User::where('id','=',$request->input('user_id'))->first();
    			if($Wallet->wallet_amt>=0)
    			{
    				$Wallet->wallet_amt+=$json['amount'];
    			}

    			try{
    				//saving txn details in user wallet
    				User::where('id','=',$request->input('user_id'))->update(['wallet_amt'=>$Wallet->wallet_amt]);
    				$txnOBJ->save();
    				//return to client
    				DB::commit();
    				$response=[
    					'status'=>200,
    					'message'=>'txn details inserted successfully and amount added to users wallet.'
    				];
    			}catch(\Excpetion $e){
    				//return to client
    				DB::rollback();
    				$response=[
    					'status'=>501,
    					'message'=>'Oops!! something went wrong please try again later.'
    				];
    			}
    		}else
    		{
    			//return to client
    			$response=[
    				'status'=>501,
    				'message'=>'user does not exist'
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

    //toll payment txn
    public function tollPayment(Request $request)
    {
    	//setting validation rules
    	$validation=Validator::make($request->toArray(),[
    		'user_id'=>'required',
            'wallet_id' => 'required',
    		'vehicle_no'=>'required',
    		'toll_id'=>'required'
    		]);
    	if($validation->fails())
    	{
    		//error
    		$response=[
    			'status'=>500,
    			'message'=>'validation errors',
    			'errors'=>$validation->errors()
      		];
    	}else
    	{
    		
    		$userExistOBJ=User::where('id','=',$request->input('user_id'))->where('wallet_id','=',$request->input('wallet_id'))->where('usergroup_id','=','2')->where('is_active','=',1)->get();
    		if(!$userExistOBJ->isEmpty())
    		{
    			$userExistOBJ=$userExistOBJ->first();
    			$price="";
    			$vehicleOBJ=Vehicle::where('vehicle_no','=',$request->input('vehicle_no'))->get();
    			if(!$vehicleOBJ->isEmpty())
    			{
    				$vehicleOBJ=$vehicleOBJ->first();
    				$tollOBJ=TollDetail::where('id','=',$request->input('toll_id'))->get();
    				if(!$tollOBJ->isEmpty())
    				{
    					$tollOBJ=$tollOBJ->first();

    					$userTollTxnOBJ= new UserTollTxn;
    					if($userExistOBJ->wallet_amt>0)
    					{
    						DB::beginTransaction();
    						if($vehicleOBJ->vehicle_type=="car_jeep_van"){
			                    $price=$tollOBJ->car_jeep_van_price;
			                    $userTollTxnOBJ->vehicle_type="car_jeep_van";
			                }elseif ($vehicleOBJ->vehicle_type=="bus_truck") {
			                    $price=$tollOBJ->bus_truck_price;
			                    $userTollTxnOBJ->vehicle_type="bus_truck";
			                }elseif ($vehicleOBJ->vehicle_type=="lcv") {
			                    $price=$tollOBJ->lcv_price;
			                    $userTollTxnOBJ->vehicle_type="lcv";
			                }elseif ($vehicleOBJ->vehicle_type=="upto_3_axle_vehicle") {
			                    $price=$tollOBJ->upto_3_axle_vehicle_price;
			                    $userTollTxnOBJ->vehicle_type="upto_3_axle_vehicle";
			                }elseif ($vehicleOBJ->vehicle_type=="axle_4_to_6_vehicle") {
			                    $price=$tollOBJ->axle_4_to_6_vehicle_price;
			                    $userTollTxnOBJ->vehicle_type="axle_4_to_6_vehicle";
			                }elseif ($vehicleOBJ->vehicle_type=="axle_7_or_more_vehicle") {
			                    $price=$tollOBJ->axle_7_or_more_vehicle_price;
			                    $userTollTxnOBJ->vehicle_type="axle_7_or_more_vehicle";
			                }elseif ($vehicleOBJ->vehicle_type=="hcm_eme") {
			                    $price=$tollOBJ->hcm_eme_price;              
			                    $userTollTxnOBJ->vehicle_type="hcm_eme";  
			                }
			                $userTollTxnOBJ->user_id=$request->input('user_id');
			                $userTollTxnOBJ->vehicle_no=$request->input('vehicle_no');
			                $userTollTxnOBJ->toll_amount=$price;
			                $userExistOBJ->wallet_amt-=$price;
			                $userTollTxnOBJ->wallet_id=$userExistOBJ->wallet_id;
			                User::where('id','=',$request->input('user_id'))->update(['wallet_amt'=>$userExistOBJ->wallet_amt]);
			                $userTollTxnOBJ->toll_id=$request->input('toll_id');
			                txn:{
			                        $txnId = $this->getRandomString(7);
			                        $txnIdCon="TNX".$txnId."";
			                        $txnIdExist = UserTollTxn::where('wallet_id','=',$txnIdCon)->get();
			                        if($txnIdExist->isEmpty())
			                        {
			                            $userTollTxnOBJ->txn_id=$txnIdCon;     
			                        }else
			                        {
			                            goto txn;
			                        }
		                    	}
		                    $userTollTxnOBJ->json_data=json_encode($request->input());
		                    try{
		                    	$userTollTxnOBJ->save();
		                    	DB::commit();
		                    	$response=[
		                    		'status'=>200,
		                    		'message'=>'txn is successfully completed.'
		                    	];
		                    }catch(\Excpetion $e)
		                    {
		                    	//return to client
		                    	DB::rollback();
		                    	$respons=[
		                    		'status'=>501,
		                    		'message'=>'Oops!! something went wrong please try again later.'
		                    	];
		                    }

    					}else
    					{
    						//return to client
    						$response=[
    							'status'=>501,
    							'message'=>'insufficient balance in wallet.'
    						];
    					}

    					
    				}else
    				{
    					//return to client
    					$response=[
    						'status'=>501,
    						'message'=>'toll does not exist.'
    					];
    				}
    			}else
    			{
    				//return to client
    				$response =[
    					'status'=>501,
    					'message'=>'vehicle no. does not exist.'
    				];
    			}

    		}else
    		{
    			//return to client
    			$response = [
    				"status" => 501,
    				"message" => "user does not exist"
    			];
    		}
    	}
    	return response()->json($response);
    	exit;
    }

    //function for fetching users payment history
    public function payHistory($userId)
    {
    	$userOBJ=User::where('id','=',$userId)->where('is_active','=',1)->where('usergroup_id','=',2)->get();
    	if(!$userOBJ->isEmpty())
    	{
    		$userOBJ=$userOBJ->first();
    		$historyOBJ=DB::select('SELECT ut.vehicle_no,ut.vehicle_type,ut.created_at,ut.toll_amount,td.toll_name FROM `user_toll_txns` ut,`toll_details` td WHERE ut.`toll_id` = td.`id` AND ut.`user_id` =:user_Id',['user_Id'=>$userId]);

    		
    		//return to client
    		$response=[
    			'status'=>200,
    			'message'=>'history is successfully fetched.',
    			'data'=>$historyOBJ
    		];
    	}else{
    		//return to client
    		$response=[
    			'status'=>501,
    			'message'=>'user does not exist.'
    		];
    	}
    	return response()->json($response);
    	exit;
    }
}
