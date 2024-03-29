<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\V1\WalletController;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Str;

class BkashPaymentController extends Controller
{
    private $base_url;
    private $app_key;
    private $app_secret;
    private $username;
    private $password;

    public function __construct()
    {
        $config=\App\CentralLogics\Helpers::get_business_settings('bkash');

        if((env('APP_MODE') == 'live') && $config) {
            $this->app_key = $config['api_key']; // bKash Merchant API APP KEY
            $this->app_secret = $config['api_secret']; // bKash Merchant API APP SECRET
            $this->username = $config['username']; // bKash Merchant API USERNAME
            $this->password = $config['password']; // bKash Merchant API PASSWORD
            $this->base_url = 'https://tokenized.pay.bka.sh/v1.2.0-beta';
        } else {
            $this->app_key = '4f6o0cjiki2rfm34kfdadl1eqq';
            $this->app_secret = '2is7hdktrekvrbljjh44ll3d9l1dtjo4pasmjvs5vl5qr3fug4b';
            $this->username = 'sandboxTokenizedUser02';
            $this->password = 'sandboxTokenizedUser02@12345';
            $this->base_url = 'https://tokenized.sandbox.bka.sh/v1.2.0-beta';

//            $this->app_key = '5nej5keguopj928ekcj3dne8p';
//            $this->app_secret = '1honf6u1c56mqcivtc9ffl960slp4v2756jle5925nbooa46ch62';
//            $this->username = 'testdemo';
//            $this->password = 'test%#de23@msdao';
//            $this->base_url = 'https://tokenized.sandbox.bka.sh/v1.2.0-beta';
        }

    }

    public function getToken()
    {
        session()->forget('bkash_token');

        $request_data = array(
            'app_key' => $this->app_key,
            'app_secret' => $this->app_secret
        );

        $url = curl_init($this->base_url . '/tokenized/checkout/token/grant');
        $request_data_json = json_encode($request_data);
        $header = array(
            'Content-Type:application/json',
            'username:'.$this->username,
            'password:'.$this->password
        );
        curl_setopt($url, CURLOPT_HTTPHEADER, $header);
        curl_setopt($url, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($url, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($url, CURLOPT_POSTFIELDS, $request_data_json);
        curl_setopt($url, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($url, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);

        $resultdata = curl_exec($url);
        curl_close($url);

        $response = json_decode($resultdata, true);

        if (array_key_exists('msg', $response)) {
            return $response;
        }

        session()->put('bkash_token', $response['id_token']);

        return $response;
    }

    public function make_tokenize_payment(Request $request)
    {
        $user_data = User::find($request->customer_id);
        $response = self::getToken();
        $auth = $response['id_token'];
        session()->put('token', $auth);

        if ($request->has('walletPayment') && $request->walletPayment == 'true') {
            $amount = $request->amount;
            $callbackURL = route('bkash-callback', ['customer_id' => $request->customer_id, 'amount' => $amount, 'token' => $auth]);
        } else {
            $order = Order::with(['details','customer'])->where(['id' => $request->order_id])->first();
            $amount = $order->order_amount;
            $callbackURL = route('bkash-callback', ['order_id' => $request->order_id, 'token' => $auth]);
        }

        $requestbody = array(
            'mode' => '0011',
            'amount' => (string)$amount,
            'currency' => 'BDT',
            'intent' => 'sale',
            'payerReference' => $user_data->phone,
            'merchantInvoiceNumber' => 'invoice_' . Str::random('15'),
            'callbackURL' => $callbackURL,
        );

        $url = curl_init($this->base_url . '/tokenized/checkout/create');
        $requestbodyJson = json_encode($requestbody);

        $header = array(
            'Content-Type:application/json',
            'Authorization:' . $auth,
            'X-APP-Key:' . $this->app_key
        );

        curl_setopt($url, CURLOPT_HTTPHEADER, $header);
        curl_setopt($url, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($url, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($url, CURLOPT_POSTFIELDS, $requestbodyJson);
        curl_setopt($url, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($url, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        $resultdata = curl_exec($url);
        curl_close($url);

        $obj = json_decode($resultdata);
        return redirect()->away($obj->{'bkashURL'});
    }

    public function callback(Request $request)
    {
        $paymentID = $_GET['paymentID'];
        $auth = $_GET['token'];

        $request_body = array(
            'paymentID' => $paymentID
        );
        $url = curl_init($this->base_url . '/tokenized/checkout/execute');

        $request_body_json = json_encode($request_body);

        $header = array(
            'Content-Type:application/json',
            'Authorization:' . $auth,
            'X-APP-Key:' . $this->app_key
        );
        curl_setopt($url, CURLOPT_HTTPHEADER, $header);
        curl_setopt($url, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($url, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($url, CURLOPT_POSTFIELDS, $request_body_json);
        curl_setopt($url, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($url, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        $resultdata = curl_exec($url);
        info($resultdata);
        curl_close($url);
        $obj = json_decode($resultdata);

        if($request->has('customer_id') && $request->has('amount')) {
            if ($obj->statusCode == '0000') {

                $result = WalletController::add_fund($request->input('customer_id'), $request->input('amount'), $obj->trxID);

                if ($result) {
                    return \redirect()->route('payment-success');
                } else {
                    return \redirect()->route('payment-fail');
                }

            } else {

                return \redirect()->route('payment-fail');
            }
        }


        $order = Order::find($request['order_id']);

        if ($obj->statusCode == '0000') {
            $order->payment_method = 'bkash';
            $order->order_status = 'confirmed';
            $order->payment_status = 'paid';
            $order->transaction_reference = $obj->trxID ?? null;
            $order->save();
            if ($order->callback != null) {
                return redirect($order->callback . '&status=success');
            }else{
                return \redirect()->route('payment-success');
            }
        } else {
            if ($order->callback != null) {
                return redirect($order->callback . '&status=fail');
            }else{
                return \redirect()->route('payment-fail');
            }
        }
    }
}

