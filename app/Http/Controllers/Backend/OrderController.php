<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Interfaces\OrderServiceInterface as OrderService;
use App\Repositories\Interfaces\OrderRepositoryInterface as OrderRepository;
use App\Repositories\Interfaces\ProvinceRepositoryInterface as ProvinceRepository;

class OrderController extends Controller
{
    protected $orderService;
    protected $orderRepository;
    protected $provinceRepository;

    public function __construct(OrderService $orderService, OrderRepository $orderRepository, ProvinceRepository $provinceRepository){
        $this->orderService=$orderService;
        $this->orderRepository=$orderRepository;
        $this->provinceRepository=$provinceRepository;
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

    public function detail($id, Request $request){
        // echo $id; die();

        $order = $this->orderRepository->findById($id, ['*'], ['products']);
        // dd($order);

        if(!$order) {
            return redirect()->route('order.index')->with('error', __('messages.detail.not_found'));
        }

        $config=$this->configIndex();

        $template='Backend.order.order.detail';

        $config['seo']=__('messages.order');

        // $orders = $this->orderService->paginate($request);
        // dd($orders);

        $provinces=$this->provinceRepository->all();

        $this->authorize('modules', 'order.detail');

        return view('Backend.dashboard.layout', compact('template','config', 'order', 'provinces'));
    }

    private function configIndex(){
        return[
            'js'=>[
                'Backend/js/plugins/switchery/switchery.js',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
                'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js',
                'Backend/js/plugins/daterangepicker/daterangepicker.js',
                'Backend/libary/order.js',
                'Backend/js/plugins/toastr/toastr.min.js',

            ],
            'css'=>[
                'Backend/css/plugins/switchery/switchery.css',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
                'Backend/css/plugins/daterangepicker/daterangepicker-bs3.css',
                'Backend/css/plugins/toastr/toastr.min.css',
            ],
            'model'=>'Order'
        ];
    }

}
