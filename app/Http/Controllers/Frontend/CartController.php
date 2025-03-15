<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\FrontendController;
use Illuminate\Http\Request;
use Cart;
use App\Repositories\Interfaces\ProvinceRepositoryInterface as ProvinceRepository;

class CartController extends FrontendController
{
    protected $provinceRepository;

    public function __construct(ProvinceRepository $provinceRepository){
        parent::__construct();
        $this->provinceRepository = $provinceRepository;
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

        $provinces=$this->provinceRepository->all();
        // dd($provinces);
        
        return view('frontend.cart.index', compact('config', 'languageId', 'seo', 'provinces'));
    }


    private function config(){
        return [
            'js'=>[
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
                'Backend/libary/location.js',
            ],
            'css'=>[
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
            ]
        ];
    }
}
