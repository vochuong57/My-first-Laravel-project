<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\FrontendController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Repositories\Interfaces\OrderRepositoryInterface as OrderRepository;
use App\Services\Interfaces\OrderServiceInterface as OrderService;
use App\Services\Interfaces\CartServiceInterface as CartService;

class MomoController extends FrontendController
{
    protected $orderRepository;
    protected $cartService;
    protected $orderService;
    public function __construct(OrderRepository $orderRepository, CartService $cartService, OrderService $orderService){
        $this->orderRepository = $orderRepository;
        $this->cartService = $cartService;
        $this->orderService = $orderService;
        parent::__construct();
    }

    public function momo_return(Request $request){
        // echo 123; die();
        $configMomo = momoConfig();
        $secretKey = $configMomo['secretKey']; //Put your secret key in there
        $accessKey = $configMomo['accessKey'];

        if (!empty($_GET)) {
            $accessKey = $accessKey;
            $amount = $_GET["amount"];
            $extraData = $_GET["extraData"];
            $message = $_GET["message"];
            $orderId = $_GET["orderId"];
            $orderInfo = $_GET["orderInfo"];
            $orderType = $_GET["orderType"];
            $partnerCode = $_GET["partnerCode"];
            $payType = $_GET["payType"];
            $requestId = $_GET["requestId"];
            $responseTime = $_GET["responseTime"];
            $resultCode = $_GET["resultCode"];
            $transId = $_GET["transId"];

            $m2signature = $_GET["signature"]; //MoMo signature
            // $localMessage = $_GET["localMessage"];

            //Checksum
            $rawHash = "accessKey=" . $accessKey .
            "&amount=" . $amount .
            "&extraData=" . $extraData .
            "&message=" . $message .
            "&orderId=" . $orderId .
            "&orderInfo=" . $orderInfo .
            "&orderType=" . $orderType .
            "&partnerCode=" . $partnerCode .
            "&payType=" . $payType .
            "&requestId=" . $requestId .
            "&responseTime=" . $responseTime .
            "&resultCode=" . $resultCode .
            "&transId=" . $transId;

            $partnerSignature = hash_hmac("sha256", $rawHash, $secretKey);
            $condition = [
                ['code', '=', $orderId],
            ];
            $order = $this->orderRepository->findByCondition($condition);
            // dd($order);
            $order = $this->cartService->getFullAddress($order);
            if(!$order){
                return redirect()->route('home.index')->with('error','Đơn hàng không tồn tại');
            }
            $languageId = $this->getLanguage();
            $config = $this->config();
            $systems = $this->getSystem();
            $seo = [
                'meta_title' => $systems['success_meta_title'],
                'meta_keyword' => $systems['success_meta_keyword'],
                'meta_description' => $systems['success_meta_description'],
                'canonical' => write_url('cart/yourCode/success'),
                'meta_images' => $systems['success_meta_images'],
            ];
            // dd($seo);
            $template = 'frontend.cart.component.momo';

            $momo = [
                'partnerSignature' => $partnerSignature,
                'm2signature' => $m2signature,
                'message' => $message,
                // 'localMessage' => $localMessage,
                'resultCode' => $resultCode,
            ];
            // dd($momo);

            return view('frontend.cart.success', compact('config', 'languageId', 'seo', 'order', 'template', 'momo'));

        }
    }

    public function momo_ipn(){
        $configMomo = momoConfig();
        $secretKey = $configMomo['secretKey']; //Put your secret key in there
        $accessKey = $configMomo['accessKey'];
        if (!empty($_GET)) {
            $response = array();
            try {
                $accessKey = $accessKey;
                $amount = $_GET["amount"];
                $extraData = $_GET["extraData"];
                $message = $_GET["message"];
                $orderId = $_GET["orderId"];
                $orderInfo = $_GET["orderInfo"];
                $orderType = $_GET["orderType"];
                $partnerCode = $_GET["partnerCode"];
                $payType = $_GET["payType"];
                $requestId = $_GET["requestId"];
                $responseTime = $_GET["responseTime"];
                $resultCode = $_GET["resultCode"];
                $transId = $_GET["transId"];

                $m2signature = $_GET["signature"]; //MoMo signature
                // $localMessage = $_GET["localMessage"];
        
        
                //Checksum
                $rawHash = "accessKey=" . $accessKey .
                "&amount=" . $amount .
                "&extraData=" . $extraData .
                "&message=" . $message .
                "&orderId=" . $orderId .
                "&orderInfo=" . $orderInfo .
                "&orderType=" . $orderType .
                "&partnerCode=" . $partnerCode .
                "&payType=" . $payType .
                "&requestId=" . $requestId .
                "&responseTime=" . $responseTime .
                "&resultCode=" . $resultCode .
                "&transId=" . $transId;

                $partnerSignature = hash_hmac("sha256", $rawHash, $secretKey);
            
                $condition = [
                    ['code', '=', $orderId],
                ];
                $order = $this->orderRepository->findByCondition($condition);
                $post['id'] = $order->id;
                if ($m2signature == $partnerSignature) {
                    if ($resultCode == '0') {
                        // Trạng thái thanh toán thành công
                        $post['payload']['confirm'] = 'confirm';
                        $post['payload']['payment'] = 'paid';
                    } else {
                        // Trạng thái thanh toán thất bại / lỗi
                        $post['payload']['confirm'] = 'confirm';
                        $post['payload']['paymnent'] = 'failed';
                    }
                } else {
                    // Trạng thái thanh toán thất bại / lỗi
                    $post['payload']['confirm'] = 'confirm';
                    $post['payload']['paymnent'] = 'failed';
                }
                $flag = $this->orderService->update($post);
            } catch (Exception $e) {
                echo $response['message'] = $e;
            }
        
            $debugger = array();
            $debugger['rawData'] = $rawHash;
            $debugger['momoSignature'] = $m2signature;
            $debugger['partnerSignature'] = $partnerSignature;
        
            if ($m2signature == $partnerSignature) {
                $response['message'] = "Received payment result success";
            } else {
                $response['message'] = "ERROR! Fail checksum";
            }
            $response['debugger'] = $debugger;
            echo json_encode($response);
        }
    }

    private function config(){
        return [
            'js'=>[
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
                'Backend/libary/location.js',
                'Frontend/resources/cart.js',
            ],
            'css'=>[
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
            ]
        ];
    }
}
