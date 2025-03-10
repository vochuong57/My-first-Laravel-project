<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\FrontendController;
use Illuminate\Http\Request;
use Cart;
class CartController extends FrontendController
{


    public function __construct(){
        parent::__construct();
    }

    public function checkout(){
        // echo '123'; die();
        $languageId = $this->getLanguage();
        $config = $this->config();

        // Cart::instance('shopping')->destroy();
        $cart = Cart::instance('shopping')->content();
        // dd($cart);
        $systems = $this->getSystem();
        $seo = [
            'meta_title' => $systems['checkout_meta_title'],
            'meta_keyword' => $systems['checkout_meta_keyword'],
            'meta_description' => $systems['checkout_meta_description'],
            'canonical' => write_url('checkout'),
            'meta_images' => $systems['checkout_meta_images'],
        ];
        // dd($seo);
        
        return view('frontend.cart.index', compact('config', 'languageId', 'seo'));
    }


    private function config(){
        return [];
    }
}
