<?php
namespace App\Classes;

class Momo{

    public function __construct(){

    }

    public function payment($order){
        // dd($order);

        $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";

        $configMomo = momoConfig();

        $partnerCode = $configMomo['partnerCode'];
        $accessKey = $configMomo['accessKey'];
        $secretKey = $configMomo['secretKey'];
        $orderInfo = "Thanh toán qua MoMo";
        $amount = !is_null($order->promotion) ? (string) ($order->cart['cartTotal'] - $order->promotion['discount']) : (string) $order->cart['cartTotal'];
        $redirectUrl = $configMomo['redirectUrl'];
        $ipnUrl = $configMomo['ipnUrl'];
        // Lưu ý: link notifyUrl không phải là dạng localhost
        $bankCode = "";

        if (!empty($_POST)) {
            $orderId = $order->code;
            $orderInfo = (!is_null($order->description)) ? $order->description : 'Thanh toán đơn hàng mã: #' . $order->code . ' qua Momo';
            $requestId = time()."";
            $requestType = "payWithATM";
            $extraData = "";
            //before sign HMAC SHA256 signature
            $rawHash = "accessKey=" . $accessKey . "&amount=" . $amount . "&extraData=" . $extraData . "&ipnUrl=" . $ipnUrl . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo . "&partnerCode=" . $partnerCode . "&redirectUrl=" . $redirectUrl . "&requestId=" . $requestId . "&requestType=" . $requestType;
            $signature = hash_hmac("sha256", $rawHash, $secretKey);
            $data = array('partnerCode' => $partnerCode,
                'partnerName' => "Test",
                "storeId" => "MomoTestStore",
                'requestId' => $requestId,
                'amount' => $amount,
                'orderId' => $orderId,
                'orderInfo' => $orderInfo,
                'redirectUrl' => $redirectUrl,
                'ipnUrl' => $ipnUrl,
                'lang' => 'vi',
                'extraData' => $extraData,
                'requestType' => $requestType,
                'signature' => $signature
            );
            $result = execPostRequest($endpoint, json_encode($data));
            $jsonResult = json_decode($result, true);  // decode json
            // dd($jsonResult);
            return $jsonResult;
            // error_log( print_r( $jsonResult, true ) );
        }
    }
}