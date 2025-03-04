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
        // dd($this->language);
        $languageId = $this->getLanguage();
        $config = $this->config();

        // $product = ['id' => '293ad', 'name' => 'Product 1', 'qty' => 1, 'price' => 9.99, 'options' => ['size' => 'large']];

        // Cart::add($product);

        // $cart = Cart::content();
        // dd($cart);

        // Cart::instance('shopping')->destroy();
        $cart = Cart::instance('shopping')->content();
        dd($cart);
        // die();
        
        // return view('frontend.homepage.home.index', compact('config', 'languageId'));
    }


    private function config(){
        return [];
    }
}
