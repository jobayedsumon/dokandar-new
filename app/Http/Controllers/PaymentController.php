<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function payment(Request $request)
    {
        if ($request->has('walletPayment') && $request->walletPayment == 'true') {

            session()->put('walletPayment', true);
            session()->put('customer_id', $request['customer_id']);
            $customer = User::find($request['customer_id']);

            if (isset($customer)) {
                $data = [
                    'name' => $customer['f_name'],
                    'email' => $customer['email'],
                    'phone' => $customer['phone'],
                ];
                session()->put('data', $data);
                return view('payment-view');
            }

            return response()->json(['errors' => ['code' => 'order-payment', 'message' => 'Data not found']], 403);
        }

        if ($request->has('callback')) {
            Order::where(['id' => $request->order_id])->update(['callback' => $request['callback']]);
        }

        session()->put('customer_id', $request['customer_id']);
        session()->put('order_id', $request->order_id);

        $customer = User::find($request['customer_id']);

        $order = Order::where(['id' => $request->order_id, 'user_id' => $request['customer_id']])->first();

        if (isset($customer) && isset($order)) {
            $data = [
                'name' => $customer['f_name'],
                'email' => $customer['email'],
                'phone' => $customer['phone'],
            ];
            session()->put('data', $data);
            return view('payment-view');
        }

        return response()->json(['errors' => ['code' => 'order-payment', 'message' => 'Data not found']], 403);
    }

    public function success()
    {
        if (session('walletPayment')) {
            session()->forget('walletPayment');
            return response()->json(['message' => 'Payment succeeded'], 200);
        }

        $order = Order::where(['id' => session('order_id'), 'user_id'=>session('customer_id')])->first();
        if (isset($order) && $order->callback != null) {
            return redirect($order->callback . '&status=success');
        }
        return response()->json(['message' => 'Payment succeeded'], 200);
    }

    public function fail()
    {
        if (session('walletPayment')) {
            session()->forget('walletPayment');
            return response()->json(['message' => 'Payment failed'], 403);
        }

        $order = Order::where(['id' => session('order_id'), 'user_id'=>session('customer_id')])->first();
        if (isset($order) && $order->callback != null) {
            return redirect($order->callback . '&status=fail');
        }
        return response()->json(['message' => 'Payment failed'], 403);
    }

    public function cancel()
    {
        if (session('walletPayment')) {
            session()->forget('walletPayment');
            return response()->json(['message' => 'Payment canceled'], 403);
        }

        $order = Order::where(['id' => session('order_id'), 'user_id'=>session('customer_id')])->first();
        if (isset($order) && $order->callback != null) {
            return redirect($order->callback . '&status=cancel');
        }
        return response()->json(['message' => 'Payment canceled'], 403);
    }

}
