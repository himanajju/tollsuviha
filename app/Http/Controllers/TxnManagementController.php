<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\UserWallet;
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
    					'message'=>'txn details inserted successfully.'
    				];
    			}catch(\Excpetion $e){
    				//return to client
    				DB::	rollback();
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
}
