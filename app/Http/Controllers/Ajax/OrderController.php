<?php

namespace App\Http\Controllers\Ajax;

//chèn thêm thư viện có sẵn Request để lấy thông tin value của input mà ajax đã thiết lập
use Illuminate\Http\Request;
//thêm thư viện tự tạo
use App\Http\Controllers\FrontendController;
use App\Services\Interfaces\OrderServiceInterface as OrderService;
use App\Repositories\Interfaces\OrderRepositoryInterface as OrderRepository;

class OrderController extends FrontendController
{
    protected $orderService;
    protected $orderRepository;

    public function __construct(OrderService $orderService, OrderRepository $orderRepository){
        parent::__construct();
        $this->orderService=$orderService;
        $this->orderRepository=$orderRepository;
    }

    public function update(Request $request){
        $post = $request->except('_token');
        // dd($post);
        if($this->orderService->update($request)){
            $order = $this->orderRepository->findById($post['id'], ['*'], ['provinces', 'districts', 'wards']);
            return response()->json([
                'message' => 'Cập nhật dữ liệu thông tin đơn hàng thành công!',
                'code' => 200,
                'order' => $order,
            ]);
        }

        return response()->json([
            'message' => 'Cập nhật dữ liệu thông tin đơn hàng thất bại! Hãy thử lại',
            'code' => 500,
        ]);
    }
    
}
