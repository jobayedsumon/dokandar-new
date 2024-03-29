<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\CustomerLogic;
use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\BusinessSetting;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Mail;


class CustomerWalletController extends Controller
{
    public function add_fund_view()
    {
        if (BusinessSetting::where('key', 'wallet_status')->first()->value != 1) {
            Toastr::error(trans('messages.customer_wallet_disable_warning_admin'));
            return back();
        }
        return view('admin-views.customer.wallet.add_fund');
    }

    public function add_fund(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_id'=>'exists:users,id',
            'amount'=>'numeric|min:.01',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }

        $wallet_transaction = CustomerLogic::create_wallet_transaction($request->customer_id, $request->amount, 'add_fund_by_admin',$request->referance);

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

            return response()->json([
                'message' => 'Fund added to wallet successfully',
            ], 200);
        }

        return response()->json(['errors'=>[
            'message'=>trans('messages.failed_to_create_transaction')
        ]], 200);
    }

    public function report(Request $request)
    {
        $data = WalletTransaction::selectRaw('sum(credit) as total_credit, sum(debit) as total_debit')
        ->when(($request->from && $request->to),function($query)use($request){
            $query->whereBetween('created_at', [$request->from.' 00:00:00', $request->to.' 23:59:59']);
        })
        ->when($request->transaction_type, function($query)use($request){
            $query->where('transaction_type',$request->transaction_type);
        })
        ->when($request->customer_id, function($query)use($request){
            $query->where('user_id',$request->customer_id);
        })
        ->get();

        $transactions = WalletTransaction::
        when(($request->from && $request->to),function($query)use($request){
            $query->whereBetween('created_at', [$request->from.' 00:00:00', $request->to.' 23:59:59']);
        })
        ->when($request->transaction_type, function($query)use($request){
            $query->where('transaction_type',$request->transaction_type);
        })
        ->when($request->customer_id, function($query)use($request){
            $query->where('user_id',$request->customer_id);
        })
        ->latest()
        ->paginate(config('default_pagination'));

        return view('admin-views.customer.wallet.report', compact('data','transactions'));
    }

    public function admin_bonus_status(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'wallet_transaction_id'=>'exists:wallet_transactions,id',
            'admin_bonus_status' => 'required|in:approved,pending',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }

        $wallet_transaction = WalletTransaction::find($request->wallet_transaction_id);
        $user = $wallet_transaction->user;

        if ($wallet_transaction && $user) {
            if ($request->admin_bonus_status == 'approved') {
                $wallet_transaction->balance += $wallet_transaction->admin_bonus;
                $user->wallet_balance += $wallet_transaction->admin_bonus;
            } else {
                $wallet_transaction->balance -= $wallet_transaction->admin_bonus;
                $user->wallet_balance -= $wallet_transaction->admin_bonus;
            }

            $wallet_transaction->admin_bonus_status = $request->admin_bonus_status;

            $wallet_transaction->save();
            $user->save();

            return response()->json([
                'message' => 'Admin bonus status updated successfully',
                'balance' => $wallet_transaction->balance,
            ], 200);
        }

        return response()->json([
            'message' => 'Failed to update admin bonus status',
        ], 400);

    }

}
