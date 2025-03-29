<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Interfaces\OrderServiceInterface as OrderService;
use App\Repositories\Interfaces\OrderRepositoryInterface as OrderRepository;

class OrderController extends Controller
{
    protected $orderService;
    protected $orderRepository;

    public function __construct(OrderService $orderService, OrderRepository $orderRepository){
        $this->orderService=$orderService;
        $this->orderRepository=$orderRepository;
    }
    public function index(Request $request){
        $config=$this->configIndex();

        $template='Backend.order.order.index';

        $config['seo']=__('messages.order');

        $orders = $this->orderService->paginate($request);
        // dd($orders);

        $this->authorize('modules', 'order.index');

        return view('Backend.dashboard.layout', compact('template','config','orders'));
    }

    private function configIndex(){
        return[
            'js'=>[
                'Backend/js/plugins/switchery/switchery.js',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js'
            ],
            'css'=>[
                'Backend/css/plugins/switchery/switchery.css',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
            ],
            'model'=>'Order'
        ];
    }

}
