<?php

namespace App\Http\Controllers\Api\V1;

use App\CentralLogics\CustomerLogic;
use App\CentralLogics\Helpers;
use App\Http\Controllers\Admin\CustomerWalletController;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function transactions(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'limit' => 'required',
            'offset' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $paginator = WalletTransaction::where('user_id', $request->user()->id)->latest()->paginate($request->limit, ['*'], 'page', $request->offset);

        $data = [
            'total_size' => $paginator->total(),
            'limit' => $request->limit,
            'offset' => $request->offset,
            'data' => $paginator->items()
        ];
        return response()->json($data, 200);
    }

    public static function add_fund($customer_id, $amount, $reference)
    {
        $validator = Validator::make([
            'customer_id' => $customer_id,
            'amount' => $amount,
        ], [
            'customer_id' => 'exists:users,id',
            'amount' => 'numeric|min:10',
        ]);

        if ($validator->fails()) {
            return false;
        }

        $wallet_transaction = CustomerLogic::create_wallet_transaction($customer_id, $amount, 'add_fund', $reference);

        if($wallet_transaction)
        {
            try{
                if(config('mail.status')) {
                    Mail::to($wallet_transaction->user->email)->send(new \App\Mail\AddFundToWallet($wallet_transaction));
                }
            }catch(\Exception $ex)
            {
                info($ex);
            }

            return true;
        }

        return false;
    }

    public function fund_transfer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone'=>'exists:users,phone',
            'amount'=>'numeric|min:10',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }

        $from_user = $request->user()->id;
        $to_user = User::where('phone', $request->phone)->first()->id;

        $from_reference = $from_user->f_name.' '.$from_user->l_name . '('.$from_user->phone.')';
        $to_reference = $to_user->f_name.' '.$to_user->l_name . '('.$to_user->phone.')';

        $wallet_transaction_from = CustomerLogic::create_wallet_transaction($from_user, $request->amount, 'fund_transfer',$to_reference);
        $wallet_transaction_to = CustomerLogic::create_wallet_transaction($to_user, $request->amount, 'add_fund_by_transfer',$from_reference);

        if($wallet_transaction_from && $wallet_transaction_to)
        {
            try{
                if(config('mail.status')) {
                    Mail::to($wallet_transaction_from->user->email)->send(new \App\Mail\AddFundToWallet($wallet_transaction_from));
                    Mail::to($wallet_transaction_to->user->email)->send(new \App\Mail\AddFundToWallet($wallet_transaction_to));
                }
            }catch(\Exception $ex)
            {
                info($ex);
            }

            return response()->json([
                'message' => 'Fund transferred successfully',
            ], 200);
        }

        return response()->json(['errors'=>[
            'message'=> 'Failed to transfer fund'
        ]], 200);
    }

}
