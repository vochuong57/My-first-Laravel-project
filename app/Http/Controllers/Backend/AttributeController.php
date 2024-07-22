<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
//chèn thêm thư viện userInterface tự tạo để lấy thông tin user từ DB vào form
use App\Services\Interfaces\AttributeServiceInterface as AttributeService;
//chèn thêm thư viện tự tạo request để kiểm tra dữ liệu đầu vào khi thêm user
use App\Http\Requests\StoreAttributeRequest;
//chèn thêm viện userRepositoryInterface để lấy function findById để truy xuất dữ liệu của id vừa nhập
use App\Repositories\Interfaces\AttributeRepositoryInterface as AttributeRepository;
//chèn thêm thư viện tự tạo request để kiểm tra dữ liệu đầu vào khi edit user
use App\Http\Requests\UpdateAttributeRequest;
//use App\Models\User;
use App\Classes\Nestedsetbie;
use App\Models\Language;

class AttributeController extends Controller
{
    protected $attributeService;
    protected $attributeRepository;
    protected $nestedset;
    protected $language;//được lấy từ extends Controller

    public function __construct(AttributeService $attributeService, AttributeRepository $attributeRepository)
    {
        $this->attributeService = $attributeService; // định nghĩa $this->userService=$userCatalogueService để biến này nó có thể trỏ tới các phương tức của UserCatalogueService
        $this->attributeRepository = $attributeRepository;

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
            'table'=>'attribute_catalogues',
            'foreignkey'=>'attribute_catalogue_id',
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
        $template='Backend.attribute.attribute.index';

        //chèn thêm mảng 'seo' vào biến config để mảng 'seo' này lấy toàn bộ giá trị của folder config/apps/user.php
        $config['seo']=__('messages.attribute');

        //Đổ dữ liệu User từ DB vào form theo mô hình service và repository
        $attributes = $this->attributeService->paginate($request, $this->language);//$request để tiến hành chức năng tìm kiếm
        //dd($attributes);

        $dropdown= $this->nestedset->Dropdown();

        //dd($languages);

        $this->authorize('modules', 'attribute.index');//phân quyền

        return view('Backend.dashboard.layout', compact('template','config','attributes','dropdown'));
    }

    //giao diện thêm user
    public function store(){   
        $template='Backend.attribute.attribute.store';

        $config=$this->configCUD();

        $config['seo']=__('messages.attribute.create');

        $config['method']='create';

        // //test nhanh việc lấy được dữ liệu hay không?
        // $location=[
        //     'province'=>$this->provinceRepository->all()
        // ];
        // dd($location);

       
        //dd($provinces);

        $dropdown= $this->nestedset->Dropdown();
        //dd($dropdown);

        $this->authorize('modules', 'attribute.store');//phân quyền

        return view('Backend.dashboard.layout', compact('template','config','dropdown'));
    }

    //xử lý thêm user
    public function create(StoreAttributeRequest $request){
        if($this->attributeService->createAttribute($request, $this->language)){
            return redirect()->route('attribute.index')->with('success','Thêm mới bào viết thành công');
        }
           return redirect()->route('attribute.index')->with('error','Thêm mới bài viết thất bại. Hãy thử lại');
        
    }
    //giao diện sửa user
    public function edit($id){
        //echo $id;
        $template='Backend.attribute.attribute.store';

        $config=$this->configCUD();

        $config['seo']=__('messages.attribute.edit');

        $config['method']='edit';//kiểm tra metho để thay đổi giao diện cho phù hợp

        //truy vấn thông tin
        $attribute=$this->attributeRepository->getAttributeById($id,$this->language);

        if(!$attribute){
            return redirect()->route('attribute.index')->with('error', 'Bài viết này chưa có bản dịch của ngôn ngữ được chọn');
        }
        
        //dd($attribute->attribute_catalogues);

        $dropdown= $this->nestedset->Dropdown();

        $album = json_decode($attribute->album);

        $this->authorize('modules', 'attribute.edit');//phân quyền

        return view('Backend.dashboard.layout', compact('template','config','attribute','dropdown','album'));
    }
    //xử lý sửa user
    public function update($id, UpdateAttributeRequest $request){
        //echo $id; die();
        //dd($request);
        if($this->attributeService->updateAttribute($id, $request, $this->language)){
            return redirect()->route('attribute.index')->with('success','Cập nhật bài viết thành công');
        }
           return redirect()->route('attribute.index')->with('error','Cập nhật bài viết thất bại. Hãy thử lại');
    }
    //giao diện xóa user
    public function destroy($id){
        $template='Backend.attribute.attribute.destroy';

        $config=$this->configCUD();

        $config['seo']=__('messages.attribute.delete');

        //truy vấn thông tin
        $attribute=$this->attributeRepository->getAttributeById($id,$this->language);
        
        //dd($attributeCatalogue);

        $dropdown= $this->nestedset->Dropdown();

        $this->authorize('modules', 'attribute.destroy');//phân quyền

        return view('Backend.dashboard.layout', compact('template','config','attribute'));
    }
    //xử lý xóa user
    public function delete($id){
        //echo $id;
        //echo 123; die();
        if($this->attributeService->deleteAttribute($id, $this->language)){
            return redirect()->route('attribute.index')->with('success','Xóa bài viết thành công');
        }
           return redirect()->route('attribute.index')->with('error','Xóa bài viết thất bại. Hãy thử lại');
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
            'model'=>'Attribute'
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
                'Backend/plugins/nice-select/js/jquery.nice-select.min.js',//mặc dù có model không dùng tới nice-slect nhưng cần thêm vào để tránh lỗi xung đột với sortui
            ],
            'css'=>[
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
            ]
        ];
    }

}
