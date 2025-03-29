<?php

namespace App\Services;

use App\Services\Interfaces\OrderServiceInterface;
use App\Repositories\Interfaces\OrderRepositoryInterface as OrderRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;


/**
 * Class OrderService
 * @package App\Services
 */
class OrderService implements OrderServiceInterface
{
    protected $orderRepository;

    public function __construct(OrderRepository $orderRepository){
        $this->orderRepository=$orderRepository;
    }

    public function paginate($request){
        $condition['keyword']=addslashes($request->input('keyword'));
        //dd($condition);
        $perpage=$request->integer('perpage', 20);
        $orders=$this->orderRepository->pagination(
            $this->paginateSelect(), 
            $condition, 
            $perpage, 
            ['path'=> 'order/index'],
            ['orders.id', 'DESC'],
            [],
            ['provinces', 'districts', 'wards'],
        );
        // dd($orders);
        return $orders;
    }
    
    private function paginateSelect(){
        return [
            'code',
            'fullname',
            'phone',
            'email',
            'province_id',
            'district_id',
            'ward_id',
            'address',
            'description',
            'promotion',
            'cart',
            'customer_id',
            'guest_cookie',
            'method',
            'confirm',
            'payment',
            'delivery',
            'shipping',
            'created_at',
            'updated_at',
            'deleted_at',
        ];
    }
}
