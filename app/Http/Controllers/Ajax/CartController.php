<?php

namespace App\Http\Controllers\Ajax;

//chèn thêm thư viện có sẵn Request để lấy thông tin value của input mà ajax đã thiết lập
use Illuminate\Http\Request;
//thêm thư viện tự tạo
use App\Http\Controllers\FrontendController;
use App\Services\Interfaces\CartServiceInterface as CartService;
use Cart;

class CartController extends FrontendController
{
    protected $cartService;

    public function __construct(CartService $cartService){
        parent::__construct();
        $this->cartService=$cartService;
    }

    public function create(Request $request){
        // dd($this->language);
        $get = $request->input();
        // dd($get);
        $flag = $this->cartService->create($request, $this->language);
        
        $cart = Cart::instance('shopping')->content();
        return response()->json([
            'cart' => $cart, 
            'message' => 'Thêm vào giỏ hàng thành công!',
            'code' => ($flag) ? 200 : 500
        ]);
    }

    public function update(Request $request){
        $flag = $this->cartService->update($request);

        return response()->json([
            'response' => $flag,
            'message' => 'Cập nhật số lượng thành công!',
            'code' => (!$flag) ? 500 : 200
        ]);
    }
    
}
