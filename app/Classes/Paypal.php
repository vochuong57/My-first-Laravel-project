<?php
namespace App\Classes;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class Paypal{

    public function __construct(){

    }

    public function payment($order){
        // dd($order);
        $provider = new PayPalClient;
        // dd($provider);
        $provider = \PayPal::setProvider();
        $accessToken = $provider->getAccessToken();
        // dd($accessToken);

        $cartTotal = !is_null($order->promotion) ? ($order->cart['cartTotal'] - $order->promotion['discount']) : $order->cart['cartTotal'];
        $paymentValue = convertVndToUsd($cartTotal);
        // dd($paymentValue);

        $data = [
            "intent" => "CAPTURE",
            "application_context" => [
                "return_url" => route('paypal.success', ['orderId' => $order->id]),
                "cancel_url" => route('paypal.cancel', ['orderId' => $order->id]),
            ],
            "purchase_units" => [
                [
                    "amount" => [
                        "currency_code" => "USD",
                        "value" => $paymentValue,
                    ],
                ],
            ],
        ];
        $response = $provider->createOrder($data);
        // dd($response);
        $res['payUrl'] = '';
        if(!empty($response['id']) && $response['id'] != ''){
            foreach($response['links'] as $key => $val){
                if($val['rel'] == 'approve'){
                    $res['payUrl'] = $val['href'];
                    $res['resultCode'] = 0;
                }
            }
        }
        // dd($res);
        return $res;
    }
}