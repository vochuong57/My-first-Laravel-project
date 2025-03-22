<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\FrontendController;
use Illuminate\Http\Request;
use Cart;
use App\Repositories\Interfaces\ProvinceRepositoryInterface as ProvinceRepository;
use App\Services\Interfaces\CartServiceInterface as CartService;
use App\Http\Requests\StoreCartRequest;

class CartController extends FrontendController
{
    protected $provinceRepository;
    protected $cartService;

    public function __construct(ProvinceRepository $provinceRepository, CartService $cartService){
        parent::__construct();
        $this->provinceRepository = $provinceRepository;
        $this->cartService = $cartService;
    }

    public function checkout(){
        // echo '123'; die();
        $languageId = $this->getLanguage();
        $config = $this->config();

        // Cart::instance('shopping')->destroy();
        $carts = Cart::instance('shopping')->content(); // V151
        // dd($carts);
        $carts = $this->cartService->remakeCart($carts);
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
        if($this->cartService->createDB($request)){
            return redirect()->route('cart.success')->with('success','Đặt hàng thành công');
        }
           return redirect()->route('cart.checkout')->with('error','Đặt hàng không thành công. Hãy thử lại');
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
