<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
//chèn thêm thư viện customerInterface tự tạo để lấy thông tin customer từ DB vào form
use App\Services\Interfaces\CustomerCatalogueServiceInterface as CustomerCatalogueService;
//chèn thêm thư viện tự tạo request để kiểm tra dữ liệu đầu vào khi thêm customer
use App\Http\Requests\StoreCustomerCatalogueRequest;
//chèn thêm viện customerRepositoryInterface để lấy function findById để truy xuất dữ liệu của id vừa nhập
use App\Repositories\Interfaces\CustomerCatalogueRepositoryInterface as CustomerCatalogueRepository;
//chèn thêm thư viện tự tạo request để kiểm tra dữ liệu đầu vào khi edit customer
use App\Http\Requests\UpdateCustomerCatalogueRequest;
//use App\Models\Customer;


class CustomerCatalogueController extends Controller
{
    protected $customerCatalogueService;
    protected $customerCatalogueRepository;

    public function __construct(CustomerCatalogueService $customerCatalogueService, CustomerCatalogueRepository $customerCatalogueRepository){
        $this->customerCatalogueService=$customerCatalogueService;//định nghĩa  $this->customerService=$customerCatalogueService để biến này nó có thể trỏ tới các phương tức của CustomerCatalogueService
        $this->customerCatalogueRepository=$customerCatalogueRepository;
    }
    //giao diện tổng
    public function index(Request $request){//Request $request để tiến hành chức năng tìm kiếm
        //$customers=Customer::paginate(20);//từ khóa tìm kiếm eloquent

        // echo 123; die();

        //Lấy dữ liệu các mảng là các đường dẫn js và css ở function config phía dưới lưu vào biến $config
        $config=$this->configIndex();

        //biến template là nới lưu đường dẫn main của từng dao diện
        $template='Backend.customer.catalogue.index';

        //chèn thêm mảng 'seo' vào biến config để mảng 'seo' này lấy toàn bộ giá trị của folder config/apps/customer.php
        $config['seo']=__('messages.customerCatalogue');

        //Đổ dữ liệu Customer từ DB vào form theo mô hình service và repository
        $customerCatalogues = $this->customerCatalogueService->paginate($request);//$request để tiến hành chức năng tìm kiếm
        //dd($customerCatalogues);

        $this->authorize('modules', 'customer.catalogue.index');//phân quyền

        return view('Backend.dashboard.layout', compact('template','config','customerCatalogues'));
    }

    //giao diện thêm customer
    public function store(){   
        $template='Backend.customer.catalogue.store';

        $config=$this->configCUD();

        $config['seo']=__('messages.customerCatalogue.create');

        $config['method']='create';

        // //test nhanh việc lấy được dữ liệu hay không?
        // $location=[
        //     'province'=>$this->provinceRepository->all()
        // ];
        // dd($location);

       
        //dd($provinces);

        $this->authorize('modules', 'customer.catalogue.store');//phân quyền

        return view('Backend.dashboard.layout', compact('template','config'));
    }

    //xử lý thêm customer
    public function create(StoreCustomerCatalogueRequest $request){
        if($this->customerCatalogueService->createCustomerCatalogue($request)){
            return redirect()->route('customer.catalogue.index')->with('success','Thêm mới nhóm thành viên thành công');
        }
           return redirect()->route('customer.catalogue.index')->with('error','Thêm mới nhóm thành viên thất bại. Hãy thử lại');
        
    }
    //giao diện sửa customer
    public function edit($id){
        //echo $id;
        $template='Backend.customer.catalogue.store';

        $config=$this->configCUD();

        $config['seo']=__('messages.customerCatalogue.edit');

        $config['method']='edit';//kiểm tra metho để thay đổi giao diện cho phù hợp

        //truy vấn thông tin
        $customerCatalogue=$this->customerCatalogueRepository->findById($id);
        //dd($customer); die();

        $this->authorize('modules', 'customer.catalogue.edit');//phân quyền

        return view('Backend.dashboard.layout', compact('template','config','customerCatalogue'));
    }
    //xử lý sửa customer
    public function update($id, UpdateCustomerCatalogueRequest $request){
        //echo $id; die();
        //dd($request);
        if($this->customerCatalogueService->updateCustomerCatalogue($id, $request)){
            return redirect()->route('customer.catalogue.index')->with('success','Cập nhật nhóm thành viên thành công');
        }
           return redirect()->route('customer.catalogue.index')->with('error','Cập nhật nhóm thành viên thất bại. Hãy thử lại');
    }
    //giao diện xóa customer
    public function destroy($id){
        $template='Backend.customer.catalogue.destroy';

        $config=$this->configCUD();

        $config['seo']=__('messages.customerCatalogue.delete');

        //truy vấn thông tin
        $customerCatalogue=$this->customerCatalogueRepository->findById($id);
        //dd($customer); die();

        $this->authorize('modules', 'customer.catalogue.destroy');//phân quyền

        return view('Backend.dashboard.layout', compact('template','config','customerCatalogue'));
    }
    //xử lý xóa customer
    public function delete($id){
        //echo $id;
        if($this->customerCatalogueService->deleteCustomerCatalogue($id)){
            return redirect()->route('customer.catalogue.index')->with('success','Xóa nhóm thành viên thành công');
        }
           return redirect()->route('customer.catalogue.index')->with('error','Xóa nhóm thành viên thất bại. Hãy thử lại');
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
            'model'=>'CustomerCatalogue'
        ];
    }

    private function configCUD(){
        return[
            'js'=>[
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
                'Backend/libary/location.js',
                'Backend/plugins/ckfinder/ckfinder.js',
                'Backend/libary/finder.js'
            ],
            'css'=>[
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
            ]
        ];
    }

}
