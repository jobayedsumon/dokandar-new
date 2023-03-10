<?php

namespace App\Http\Controllers;

use App\CentralLogics\Helpers;
use App\Library\SslCommerz\SslCommerzNotification;
use App\Models\BusinessSetting;
use App\Models\Order;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AamarpayController extends Controller
{
    public function index(Request $request)
    {
        $order = Order::with(['details'])->where(['id' => $request->order_id])->first();
        $tr_ref = Str::random(6) . '-' . rand(1, 1000);
        $config = \App\CentralLogics\Helpers::get_business_settings('aamarpay');

        $url = env('APP_MODE') == 'demo' ? 'https://sandbox.aamarpay.com/request.php' : 'https://secure.aamarpay.com/request.php';
        $fields = array(
            'store_id' => env('APP_MODE') == 'demo' ? 'aamarpay' : $config['store_id'],
            'amount' => $order->order_amount, //transaction amount
            'payment_type' => 'VISA', //no need to change
            'currency' => Helpers::currency_code(),  //currenct will be USD/BDT
            'tran_id' => $tr_ref, //transaction id must be unique from your end
            'cus_name' => $order->customer['f_name'],  //customer name
            'cus_email' => $order->customer['email'] == null ? "example@example.com" : $order->customer['email'], //customer email address
            'cus_add1' => 'Savar',  //customer address
            'cus_add2' => 'Savar', //customer address
            'cus_city' => 'Savar',  //customer city
            'cus_state' => 'Savar',  //state
            'cus_postcode' => '1340', //postcode or zipcode
            'cus_country' => 'Bangladesh',  //country
            'cus_phone' => $order->customer['phone'] == null ? '0000000000' : $order->customer['phone'], //customer phone number
            'cus_fax' => 'Not¬Applicable',  //fax
            'ship_name' => $order->customer['f_name'], //ship name
            'ship_add1' => 'Savar',  //ship address
            'ship_add2' => 'Savar',
            'ship_city' => 'Savar',
            'ship_state' => 'Savar',
            'ship_postcode' => '1340',
            'ship_country' => 'Bangladesh',
            'desc' => 'payment description',
            'success_url' => route('aamarpay-success'), //your success route
            'fail_url' => route('aamarpay-fail'), //your fail route
            'cancel_url' => route('aamarpay-cancel'), //your cancel url
            'opt_a' => 'A',  //optional paramter
            'opt_b' => 'B',
            'opt_c' => 'C',
            'opt_d' => 'D',
            'signature_key' => env('APP_MODE') == 'demo' ? '28c78bb1f45112f5d40b956fe104645a': $config['signature_key']
        );

        DB::table('orders')
            ->where('id', $order['id'])
            ->update([
                'transaction_reference' => $tr_ref,
                'payment_method' => 'aamarpay',
                'order_status' => 'failed',
                'failed' => now(),
                'updated_at' => now(),
            ]);


        $fields_string = http_build_query($fields);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $url_forward = str_replace('"', '', stripslashes(curl_exec($ch)));
        curl_close($ch);

        $this->redirect_to_merchant($url_forward);
    }

    function redirect_to_merchant($url) {

        ?>
        <html xmlns="http://www.w3.org/1999/xhtml">
        <head><script type="text/javascript">
                function closethisasap() { document.forms["redirectpost"].submit(); }
            </script></head>
        <body onLoad="closethisasap();">

        <form name="redirectpost" method="post" action="<?php echo (env('APP_MODE') == 'demo' ? 'https://sandbox.aamarpay.com/' : 'https://secure.aamarpay.com/') . $url; ?>"></form>
        <!-- for live url https://secure.aamarpay.com -->
        </body>
        </html>
        <?php
        exit;
    }


    public function success(Request $request)
    {
        $tran_id = $request->input('mer_txnid');
        $order = Order::where('transaction_reference', $tran_id)->first();

        if ($request->input('pay_status') == 'Successful') {
            $order->order_status='confirmed';
            $order->payment_method='aamarpay';
            $order->transaction_reference=$tran_id;
            $order->payment_status='paid';
            $order->confirmed=now();
            $order->save();
            try {
                Helpers::send_order_notification($order);
            } catch (\Exception $e) {
            }

            if ($order->callback != null) {
                return redirect($order->callback . '&status=success');
            }

            return \redirect()->route('payment-success');

        } else {
            DB::table('orders')
                ->where('transaction_reference', $tran_id)
                ->update(['order_status' => 'failed', 'payment_status' => 'unpaid', 'failed'=>now()]);
            if ($order->callback != null) {
                return redirect($order->callback . '&status=fail');
            }
            return \redirect()->route('payment-fail');
        }
    }

    public function fail(Request $request)
    {
        $tran_id = $request->input('mer_txnid');

        DB::table('orders')
            ->where('transaction_reference', $tran_id)
            ->update(['order_status' => 'failed', 'payment_status' => 'unpaid', 'failed'=>now()]);

        $order_detials = DB::table('orders')
            ->where('transaction_reference', $tran_id)
            ->select('id', 'transaction_reference', 'order_status', 'order_amount', 'callback')->first();

        if ($order_detials->callback != null) {
            return redirect($order_detials->callback . '&status=fail');
        }
        return \redirect()->route('payment-fail');
    }

    public function cancel()
    {
        return \redirect()->route('payment-cancel');
    }
}
