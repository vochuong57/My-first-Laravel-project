<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
//chèn thêm thư viện userInterface tự tạo để lấy thông tin user từ DB vào form
use App\Services\Interfaces\ProductCatalogueServiceInterface as ProductCatalogueService;
//chèn thêm thư viện tự tạo request để kiểm tra dữ liệu đầu vào khi thêm user
use App\Http\Requests\StoreProductCatalogueRequest;
//chèn thêm viện userRepositoryInterface để lấy function findById để truy xuất dữ liệu của id vừa nhập
use App\Repositories\Interfaces\ProductCatalogueRepositoryInterface as ProductCatalogueRepository;
//chèn thêm thư viện tự tạo request để kiểm tra dữ liệu đầu vào khi edit user
use App\Http\Requests\UpdateProductCatalogueRequest;
//use App\Models\User;
use App\Classes\Nestedsetbie;
use App\Http\Requests\DeleteProductCatalogueRequest;
use App\Models\Language;


class ProductCatalogueController extends Controller
{
    protected $productCatalogueService;
    protected $productCatalogueRepository;
    protected $nestedset;
    protected $language;//được lấy từ extends Controller

    public function __construct(ProductCatalogueService $productCatalogueService, ProductCatalogueRepository $productCatalogueRepository){
        $this->productCatalogueService=$productCatalogueService;//định nghĩa  $this->userService=$userCatalogueService để biến này nó có thể trỏ tới các phương tức của UserCatalogueService
        $this->productCatalogueRepository=$productCatalogueRepository;
        
        $this->middleware(function($request, $next) {
            try {
                $locale = app()->getLocale(); // vn cn en
                $language = Language::where('canonical', $locale)->first();

                if (!$language) {
                    throw new \Exception('Vui lòng chọn ngôn ngữ trước khi truy cập.');
                }

                $this->language = $language->id;
                $this->initialize();
            } catch (\Exception $e) {
                return redirect()->route('dashboard.index')->with('error', $e->getMessage());
            }
            return $next($request);
        });
    }

    private function initialize(){
        $this->nestedset=new Nestedsetbie([
            'table'=>'product_catalogues',
            'foreignkey'=>'product_catalogue_id',
            'language_id'=>$this->language,
        ]);
    }

    //giao diện tổng
    public function index(Request $request){//Request $request để tiến hành chức năng tìm kiếm
        //dd(session('app_locale'));

        //echo 123; die();

        //Lấy dữ liệu các mảng là các đường dẫn js và css ở function config phía dưới lưu vào biến $config
        $config=$this->configIndex();

        //biến template là nới lưu đường dẫn main của từng dao diện
        $template='Backend.product.catalogue.index';

        //chèn thêm mảng 'seo' vào biến config để mảng 'seo' này lấy toàn bộ giá trị của folder config/apps/user.php
        $config['seo']=__('messages.productCatalogue');
        //dd($config['seo']);
        //Đổ dữ liệu User từ DB vào form theo mô hình service và repository
        $productCatalogues = $this->productCatalogueService->paginate($request, $this->language);//$request để tiến hành chức năng tìm kiếm
        //dd($productCatalogues);

        $this->authorize('modules', 'product.catalogue.index');//phân quyền

        return view('Backend.dashboard.layout', compact('template','config','productCatalogues'));
    }

    //giao diện thêm user
    public function store(){   
        $template='Backend.product.catalogue.store';

        $config=$this->configCUD();

        $config['seo']=__('messages.productCatalogue.create');

        $config['method']='create';

        // //test nhanh việc lấy được dữ liệu hay không?
        // $location=[
        //     'province'=>$this->provinceRepository->all()
        // ];
        // dd($location);

       
        //dd($provinces);

        $dropdown= $this->nestedset->Dropdown();
        //dd($dropdown);

        $this->authorize('modules', 'product.catalogue.store');//phân quyền

        return view('Backend.dashboard.layout', compact('template','config','dropdown'));
    }

    //xử lý thêm user
    public function create(StoreProductCatalogueRequest $request){
        if($this->productCatalogueService->createProductCatalogue($request, $this->language)){
            return redirect()->route('product.catalogue.index')->with('success','Thêm mới nhóm bào viết thành công');
        }
           return redirect()->route('product.catalogue.index')->with('error','Thêm mới nhóm bài viết thất bại. Hãy thử lại');
        
    }
    //giao diện sửa user
    public function edit($id){
        //echo $id;
        $template='Backend.product.catalogue.store';

        $config=$this->configCUD();

        $config['seo']=__('messages.productCatalogue.edit');//config('apps.productCatalogue.edit');

        $config['method']='edit';//kiểm tra metho để thay đổi giao diện cho phù hợp

        //truy vấn thông tin
        $productCatalogue=$this->productCatalogueRepository->getProductCatalogueById($id,$this->language);

        if(!$productCatalogue){
            return redirect()->route('product.catalogue.index')->with('error', 'Nhóm bài viết này chưa có bản dịch của ngôn ngữ được chọn');
        }
        
        //dd($productCatalogue);

        $dropdown= $this->nestedset->Dropdown();

        $album = json_decode($productCatalogue->album);

        $this->authorize('modules', 'product.catalogue.edit');//phân quyền

        return view('Backend.dashboard.layout', compact('template','config','productCatalogue','dropdown','album'));
    }
    //xử lý sửa user
    public function update($id, UpdateProductCatalogueRequest $request){
        //echo $id; die();
        //dd($request);
        if($this->productCatalogueService->updateProductCatalogue($id, $request, $this->language)){
            return redirect()->route('product.catalogue.index')->with('success','Cập nhật nhóm bài viết thành công');
        }
           return redirect()->route('product.catalogue.index')->with('error','Cập nhật nhóm bài viết thất bại. Hãy thử lại');
    }
    //giao diện xóa user
    public function destroy($id){
        $template='Backend.product.catalogue.destroy';

        $config=$this->configCUD();

        $config['seo']=__('messages.productCatalogue.delete');

        //truy vấn thông tin
        $productCatalogue=$this->productCatalogueRepository->getProductCatalogueById($id,$this->language);
        
        //dd($productCatalogue);

        $dropdown= $this->nestedset->Dropdown();

        $this->authorize('modules', 'product.catalogue.destroy');//phân quyền

        return view('Backend.dashboard.layout', compact('template','config','productCatalogue'));
    }
    //xử lý xóa user
    public function delete($id, DeleteProductCatalogueRequest $request){
        //echo $id;
        //echo 123; die();
        if($this->productCatalogueService->deleteProductCatalogue($id, $this->language)){
            return redirect()->route('product.catalogue.index')->with('success','Xóa nhóm bài viết thành công');
        }
           return redirect()->route('product.catalogue.index')->with('error','Xóa nhóm bài viết thất bại. Hãy thử lại');
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
            'model'=>'ProductCatalogue'
        ];
    }

    private function configCUD(){
        return[
            'js'=>[
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
                'Backend/plugins/ckfinder/ckfinder.js',
                'Backend/libary/finder.js',
                'Backend/plugins/ckeditor/ckeditor.js',
                'Backend/libary/seo.js',
            ],
            'css'=>[
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
            ]
        ];
    }

}
