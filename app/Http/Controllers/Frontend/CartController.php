<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\FrontendController;
use Illuminate\Http\Request;
use Cart;
use App\Repositories\Interfaces\ProvinceRepositoryInterface as ProvinceRepository;
use App\Services\Interfaces\CartServiceInterface as CartService;
use App\Http\Requests\StoreCartRequest;
use App\Repositories\Interfaces\OrderRepositoryInterface as OrderRepository;
use App\Classes\Vnpay;
use App\Classes\Momo;
use App\Classes\Paypal;

class CartController extends FrontendController
{
    protected $provinceRepository;
    protected $cartService;
    protected $orderRepository;
    protected $vnpay;
    protected $momo;
    protected $paypal;

    public function __construct(ProvinceRepository $provinceRepository, CartService $cartService, OrderRepository $orderRepository, Vnpay $vnpay, Momo $momo, Paypal $paypal){
        parent::__construct();
        $this->provinceRepository = $provinceRepository;
        $this->cartService = $cartService;
        $this->orderRepository = $orderRepository;
        $this->vnpay = $vnpay;
        $this->momo = $momo;
        $this->paypal = $paypal;
    }

    public function checkout(){
        // echo '123'; die();
        $languageId = $this->getLanguage();
        $config = $this->config();

        // Cart::instance('shopping')->destroy();
        $carts = Cart::instance('shopping')->content(); // V151
        // dd($carts);
        $carts = $this->cartService->remakeCart($carts, $languageId);
        // dd($carts);

        $cartCaculate = $this->cartService->reCaculateCart();
        // dd($cartCaculate);
        $cartPromotion = $this->cartService->cartPromotion($cartCaculate['cartTotal']);
        // dd($cartPromotion);

        $systems = $this->getSystem();
        $seo = [
            'meta_title' => $systems['checkout_meta_title'],
            'meta_keyword' => $systems['checkout_meta_keyword'],
            'meta_description' => $systems['checkout_meta_description'],
            'canonical' => write_url('checkout'),
            'meta_images' => $systems['checkout_meta_images'],
        ];
        // dd($seo);

        $provinces=$this->provinceRepository->all();
        // dd($provinces);
        
        return view('frontend.cart.index', compact('config', 'languageId', 'seo', 'provinces', 'carts', 'cartPromotion', 'cartCaculate'));
    }

    public function create(StoreCartRequest $request){
        $systems = $this->getSystem();
        $order = $this->cartService->createDB($request, $this->language, $systems);
        // dd($order);
        if($order['flag']){
            if($order['order']->method != 'cod'){
                $response = $this->paymentMethod($order);
                if($response['resultCode'] == '0'){
                    return redirect()->away($response['payUrl']);
                }
            }
            Cart::instance('shopping')->destroy();
            return redirect()->route('cart.success', ['code' => $order['order']->code])->with('success','Đặt hàng thành công');
        }
           return redirect()->route('cart.checkout')->with('error','Đặt hàng không thành công. Hãy thử lại');
    }

    private function paymentMethod($order){
        $class = $order['order']->method;
        $response = $this->{$class}->payment($order['order']);

        // switch ($order['order']->method) {
        //     case 'vnpay':
        //         $response = $this->vnpay->payment($order['order']);
        //         break;
        //     case 'momo':
        //         $response = $this->momo->payment($order['order']);
        //         break;
        // }
        return $response;
    }

    public function success($code){
        // dd($code);
        $condition = [
            ['code', '=', $code],
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

        return view('frontend.cart.success', compact('config', 'languageId', 'seo', 'order'));
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
