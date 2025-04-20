<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\FrontendController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Repositories\Interfaces\OrderRepositoryInterface as OrderRepository;
use App\Services\Interfaces\OrderServiceInterface as OrderService;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PaypalController extends FrontendController
{
    protected $orderRepository;
    protected $orderService;
    public function __construct(OrderRepository $orderRepository, OrderService $orderService){
        $this->orderRepository = $orderRepository;
        $this->orderService = $orderService;
        parent::__construct();
    }

    public function success(Request $request){
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $paypalToken = $provider->getAccessToken();
        $response = $provider->capturePaymentOrder($request->token);
        // dd($response);
        if(isset($response['status']) && $response['status'] = 'COMPLETED'){
            $orderId = $request->input('orderId');
            $condition = [
                ['id', '=', $orderId],
            ];
            $order = $this->orderRepository->findByCondition($condition);
            $post['id'] = $orderId;
            $post['payload']['confirm'] = 'confirm';
            $post['payload']['payment'] = 'paid';
            $flag = $this->orderService->update($post);

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
            $template = 'frontend.cart.component.paypal';
            return view('frontend.cart.success', compact('config', 'languageId', 'seo', 'order', 'template', 'response'));
        }
    }

    public function cancel(Request $request){
        $orderId = $request->input('orderId');
        $condition = [
            ['id', '=', $orderId],
        ];
        $order = $this->orderRepository->findByCondition($condition);
        $post['id'] = $orderId;
        $post['payload']['confirm'] = 'confirm';
        $post['payload']['paymnent'] = 'failed';
        $flag = $this->orderService->update($post);
        echo "Thanh toán thất bại! vui lòng thử lại!"; die();
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
