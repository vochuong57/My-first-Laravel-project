<?php

namespace App\Http\Controllers\Ajax;

//chèn thêm thư viện có sẵn Request để lấy thông tin value của input mà ajax đã thiết lập
use Illuminate\Http\Request;
//thêm thư viện tự tạo
use App\Http\Controllers\FrontendController;
use App\Services\Interfaces\OrderServiceInterface as OrderService;

class OrderController extends FrontendController
{
    protected $orderService;

    public function __construct(OrderService $orderService){
        parent::__construct();
        $this->orderService=$orderService;
    }

    public function update(Request $request){
        $post = $request->except('_token');
        // dd($post);
        if($this->orderService->update($request)){
            return response()->json([
                'message' => 'Cập nhật dữ liệu thông tin đơn hàng thành công!',
                'code' => 200,
            ]);
        }

        return response()->json([
            'message' => 'Cập nhật dữ liệu thông tin đơn hàng thất bại! Hãy thử lại',
            'code' => 500,
        ]);
    }
    
}
