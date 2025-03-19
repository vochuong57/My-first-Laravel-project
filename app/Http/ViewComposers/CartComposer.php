<?php
namespace App\Http\ViewComposers;

use Illuminate\View\View;
use App\Models\Language;
use Cart;

class CartComposer
{
    protected $language;

    public function __construct()
    {
        $this->language = app()->getLocale();
    }

    public function compose(View $view)
    {
        // dd($this->language);
        // $language = Language::where('canonical', $this->language)->first();
        $carts = Cart::instance('shopping')->content();

        $cartTotalItems = 0;
        if(!is_null($carts) && count($carts) > 0){
            foreach($carts as $cart){
                $cartTotalItems += $cart->qty;
            }
        }

        $view->with('cartTotalItems', $cartTotalItems);
    }
}