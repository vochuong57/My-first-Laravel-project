<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
//chèn thêm thư viện customerInterface tự tạo để lấy thông tin customer từ DB vào form
use App\Services\Interfaces\CustomerServiceInterface as CustomerService;
//chèn thêm tự viện ProvinceServiceInterface tự tạo để lấy thông tin province từ DB vào form
use App\Repositories\Interfaces\ProvinceRepositoryInterface as ProvinceRepository;
//chèn thêm thư viện tự tạo request để kiểm tra dữ liệu đầu vào khi thêm customer
use App\Http\Requests\StoreCustomerRequest;
//chèn thêm viện customerRepositoryInterface để lấy function findById để truy xuất dữ liệu của id vừa nhập
use App\Repositories\Interfaces\CustomerRepositoryInterface as CustomerRepository;
//chèn thêm thư viện tự tạo request để kiểm tra dữ liệu đầu vào khi edit customer
use App\Http\Requests\UpdateCustomerRequest;
//use App\Models\Customer;
//chén thêm thư viện của customerCatalogueRepository để lấy thông tin nhóm thành viên cho form thêm
use App\Repositories\Interfaces\CustomerCatalogueRepositoryInterface as CustomerCatalogueRepository;
use App\Repositories\Interfaces\SourceRepositoryInterface as SourceRepository;



class CustomerController extends Controller
{
    protected $customerService;
    protected $provinceRepository;
    protected $customerRepository;
    protected $customerCatalogueRepository;
    protected $sourceRepository;

    public function __construct(CustomerService $customerService, ProvinceRepository $provinceRepository, CustomerRepository $customerRepository, CustomerCatalogueRepository $customerCatalogueRepository, SourceRepository $sourceRepository){
        $this->customerService=$customerService;//định nghĩa  $this->customerService=$customerService để biến này nó có thể trỏ tới các phương tức của CustomerService
        $this->provinceRepository=$provinceRepository;
        $this->customerRepository=$customerRepository;
        $this->customerCatalogueRepository=$customerCatalogueRepository;
        $this->sourceRepository=$sourceRepository;
    }
    //giao diện tổng
    public function index(Request $request){//Request $request để tiến hành chức năng tìm kiếm
        //$customers=Customer::paginate(20);//từ khóa tìm kiếm eloquent

        //Lấy dữ liệu các mảng là các đường dẫn js và css ở function config phía dưới lưu vào biến $config
        $config = $this->configIndex();

        //biến template là nới lưu đường dẫn main của từng dao diện
        $template='Backend.customer.customer.index';

        //chèn thêm mảng 'seo' vào biến config để mảng 'seo' này lấy toàn bộ giá trị của folder config/apps/customer.php
        $config['seo'] = __('messages.customer');

        //Đổ dữ liệu Customer từ DB vào form theo mô hình service và repository
        $customers = $this->customerService->paginate($request);//$request để tiến hành chức năng tìm kiếm

        // dd($customers->toArray());

        $customerCatalogues=$this->customerCatalogueRepository->all();//sổ select option khi thêm sửa customer

        $sources=$this->sourceRepository->all();
        
        $this->authorize('modules', 'customer.index');//phân quyền

        return view('Backend.dashboard.layout', compact('template','config','customers','customerCatalogues','sources'));
    }

    //giao diện thêm customer
    public function store(){   
        $template='Backend.customer.customer.store';

        $config=$this->configCUD();

        $config['seo']=__('messages.customer.create');

        $config['method']='create';

        // //test nhanh việc lấy được dữ liệu hay không?
        // $location=[
        //     'province'=>$this->provinceRepository->all()
        // ];
        // dd($location);

        //lấy dữ liệu địa chỉ địa lý VN
        $provinces=$this->provinceRepository->all();
        //dd($provinces);

        $customerCatalogues=$this->customerCatalogueRepository->all();

        $sources=$this->sourceRepository->all();

        $this->authorize('modules', 'customer.store');//phân quyền

        return view('Backend.dashboard.layout', compact('template','config','provinces','customerCatalogues', 'sources'));
    }

    //xử lý thêm customer
    public function create(StoreCustomerRequest $request){
        if($this->customerService->createCustomer($request)){
            return redirect()->route('customer.index')->with('success','Thêm mới thành viên thành công');
        }
           return redirect()->route('customer.index')->with('error','Thêm mới thành viên thất bại. Hãy thử lại');
        
    }
    //giao diện sửa customer
    public function edit($id){
        //echo $id;
        $template='Backend.customer.customer.store';

        $config=$this->configCUD();

        $config['seo']=__('messages.customer.edit');

        $config['method']='edit';//kiểm tra metho để thay đổi giao diện cho phù hợp

        $provinces=$this->provinceRepository->all();
        //dd($provinces);

        //truy vấn thông tin
        $customer=$this->customerRepository->findById($id);
        //dd($customer); die();

        $customerCatalogues=$this->customerCatalogueRepository->all();
        
        $sources=$this->sourceRepository->all();

        $this->authorize('modules', 'customer.edit');//phân quyền

        return view('Backend.dashboard.layout', compact('template','config','provinces','customer', 'customerCatalogues', 'sources'));
    }
    //xử lý sửa customer
    public function update($id, UpdateCustomerRequest $request){
        //echo $id; die();
        //dd($request);
        if($this->customerService->updateCustomer($id, $request)){
            return redirect()->route('customer.index')->with('success','Cập nhật thành viên thành công');
        }
           return redirect()->route('customer.index')->with('error','Cập nhật thành viên thất bại. Hãy thử lại');
    }
    //giao diện xóa customer
    public function destroy($id){
        $template='Backend.customer.customer.destroy';

        $config=$this->configCUD();

        $config['seo']=__('messages.customer.delete');

        //truy vấn thông tin
        $customer=$this->customerRepository->findById($id);
        //dd($customer); die();

        $this->authorize('modules', 'customer.destroy');//phân quyền

        return view('Backend.dashboard.layout', compact('template','config','customer'));
    }
    //xử lý xóa customer
    public function delete($id){
        //echo $id;
        if($this->customerService->deleteCustomer($id)){
            return redirect()->route('customer.index')->with('success','Xóa thành viên thành công');
        }
           return redirect()->route('customer.index')->with('error','Xóa thành viên thất bại. Hãy thử lại');
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
            'model'=>'Customer'
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
