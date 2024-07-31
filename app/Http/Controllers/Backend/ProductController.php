<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
//chèn thêm thư viện userInterface tự tạo để lấy thông tin user từ DB vào form
use App\Services\Interfaces\ProductServiceInterface as ProductService;
//chèn thêm thư viện tự tạo request để kiểm tra dữ liệu đầu vào khi thêm user
use App\Http\Requests\StoreProductRequest;
//chèn thêm viện userRepositoryInterface để lấy function findById để truy xuất dữ liệu của id vừa nhập
use App\Repositories\Interfaces\ProductRepositoryInterface as ProductRepository;
//chèn thêm thư viện tự tạo request để kiểm tra dữ liệu đầu vào khi edit user
use App\Http\Requests\UpdateProductRequest;
//use App\Models\User;
use App\Classes\Nestedsetbie;
use App\Models\Language;
use App\Repositories\Interfaces\AttributeCatalogueRepositoryInterface as AttributeCatalogueRepository;

class ProductController extends Controller
{
    protected $productService;
    protected $productRepository;
    protected $nestedset;
    protected $language;//được lấy từ extends Controller
    protected $attributeCatalogueRepository;

    public function __construct(ProductService $productService, ProductRepository $productRepository, AttributeCatalogueRepository $attributeCatalogueRepository)
    {
        $this->productService = $productService; // định nghĩa $this->userService=$userCatalogueService để biến này nó có thể trỏ tới các phương tức của UserCatalogueService
        $this->productRepository = $productRepository;
        $this->attributeCatalogueRepository = $attributeCatalogueRepository;

        $this->middleware(function($request, $next) {
            try {
                $locale = app()->getLocale(); // vn cn en
                $language = Language::where('canonical', $locale)->first();

                if (!$language) {
                    throw new \Exception('Vui lòng chọn ngôn ngữ trước khi truy cập bài viết.');
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
        //$users=User::paginate(20);//từ khóa tìm kiếm eloquent

        //echo 123; die();

        //Lấy dữ liệu các mảng là các đường dẫn js và css ở function config phía dưới lưu vào biến $config
        $config=$this->configIndex();

        //biến template là nới lưu đường dẫn main của từng dao diện
        $template='Backend.product.product.index';

        //chèn thêm mảng 'seo' vào biến config để mảng 'seo' này lấy toàn bộ giá trị của folder config/apps/user.php
        $config['seo']=__('messages.product');

        //Đổ dữ liệu User từ DB vào form theo mô hình service và repository
        $products = $this->productService->paginate($request, $this->language);//$request để tiến hành chức năng tìm kiếm
        //dd($products);

        $dropdown= $this->nestedset->Dropdown();

        //dd($languages);

        $this->authorize('modules', 'product.index');//phân quyền

        return view('Backend.dashboard.layout', compact('template','config','products','dropdown'));
    }

    //giao diện thêm user
    public function store(){   
        $template='Backend.product.product.store';

        $config=$this->configCUD();

        $config['seo']=__('messages.product.create');

        $config['method']='create';

        // //test nhanh việc lấy được dữ liệu hay không?
        // $location=[
        //     'province'=>$this->provinceRepository->all()
        // ];
        // dd($location);

       
        //dd($provinces);

        $dropdown= $this->nestedset->Dropdown();
        //dd($dropdown);

        $attributeCatalogues =  $this->attributeCatalogueRepository->getAll($this->language);//nằm ở phần relations khi dd
        // dd($attributeCatalogues);

        $this->authorize('modules', 'product.store');//phân quyền

        return view('Backend.dashboard.layout', compact('template','config','dropdown','attributeCatalogues'));
    }

    //xử lý thêm user
    public function create(StoreProductRequest $request){
        if($this->productService->createProduct($request, $this->language)){
            return redirect()->route('product.index')->with('success','Thêm mới bào viết thành công');
        }
           return redirect()->route('product.index')->with('error','Thêm mới bài viết thất bại. Hãy thử lại');
        
    }
    //giao diện sửa user
    public function edit($id){
        //echo $id;
        $template='Backend.product.product.store';

        $config=$this->configCUD();

        $config['seo']=__('messages.product.edit');

        $config['method']='edit';//kiểm tra metho để thay đổi giao diện cho phù hợp

        //truy vấn thông tin
        $product=$this->productRepository->getProductById($id,$this->language);
        // dd($product);

        if(!$product){
            return redirect()->route('product.index')->with('error', 'Bài viết này chưa có bản dịch của ngôn ngữ được chọn');
        }
        
        //dd($product->product_catalogues);

        $dropdown= $this->nestedset->Dropdown();

        $album = json_decode($product->album);

        $attributeCatalogues =  $this->attributeCatalogueRepository->getAll($this->language);

        $this->authorize('modules', 'product.edit');//phân quyền

        return view('Backend.dashboard.layout', compact('template','config','product','dropdown','album','attributeCatalogues'));
    }
    //xử lý sửa user
    public function update($id, UpdateProductRequest $request){
        //echo $id; die();
        //dd($request);
        if($this->productService->updateProduct($id, $request, $this->language)){
            return redirect()->route('product.index')->with('success','Cập nhật bài viết thành công');
        }
           return redirect()->route('product.index')->with('error','Cập nhật bài viết thất bại. Hãy thử lại');
    }
    //giao diện xóa user
    public function destroy($id){
        $template='Backend.product.product.destroy';

        $config=$this->configCUD();

        $config['seo']=__('messages.product.delete');

        //truy vấn thông tin
        $product=$this->productRepository->getProductById($id,$this->language);
        
        //dd($productCatalogue);

        $dropdown= $this->nestedset->Dropdown();

        $this->authorize('modules', 'product.destroy');//phân quyền

        return view('Backend.dashboard.layout', compact('template','config','product'));
    }
    //xử lý xóa user
    public function delete($id){
        //echo $id;
        //echo 123; die();
        if($this->productService->deleteProduct($id, $this->language)){
            return redirect()->route('product.index')->with('success','Xóa bài viết thành công');
        }
           return redirect()->route('product.index')->with('error','Xóa bài viết thất bại. Hãy thử lại');
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
            'model'=>'Product'
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
                'Backend/plugins/nice-select/js/jquery.nice-select.min.js',
                'Backend/libary/variant.js',
                'Backend/js/plugins/switchery/switchery.js',
            ],
            'css'=>[
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
                'Backend/plugins/nice-select/css/nice-select.css',
                'Backend/css/plugins/switchery/switchery.css',
            ]
        ];
    }

}
