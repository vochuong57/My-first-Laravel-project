<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
//chèn thêm thư viện userInterface tự tạo để lấy thông tin user từ DB vào form
use App\Services\Interfaces\SlideServiceInterface as SlideService;
//chèn thêm thư viện tự tạo request để kiểm tra dữ liệu đầu vào khi thêm user
use App\Http\Requests\StoreSlideRequest;
//chèn thêm viện userRepositoryInterface để lấy function findById để truy xuất dữ liệu của id vừa nhập
use App\Repositories\Interfaces\SlideRepositoryInterface as SlideRepository;
//chèn thêm thư viện tự tạo request để kiểm tra dữ liệu đầu vào khi edit user
use App\Http\Requests\UpdateSlideRequest;
//use App\Models\User;
use App\Classes\Nestedsetbie;
use App\Models\Language;

class SlideController extends Controller
{
    protected $slideService;
    protected $slideRepository;
    protected $nestedset;
    protected $language;//được lấy từ extends Controller

    public function __construct(SlideService $slideService, SlideRepository $slideRepository)
    {
        $this->slideService = $slideService; // định nghĩa $this->userService=$userCatalogueService để biến này nó có thể trỏ tới các phương tức của UserCatalogueService
        $this->slideRepository = $slideRepository;

        $this->middleware(function($request, $next) {
            try {
                $locale = app()->getLocale(); // vn cn en
                $language = Language::where('canonical', $locale)->first();

                if (!$language) {
                    throw new \Exception('Vui lòng chọn ngôn ngữ trước khi truy cập bài viết.');
                }

                $this->language = $language->id;
                // $this->initialize();
            } catch (\Exception $e) {
                return redirect()->route('dashboard.index')->with('error', $e->getMessage());
            }
            return $next($request);
        });
    }

    // private function initialize(){
    //     $this->nestedset=new Nestedsetbie([
    //         'table'=>'slide_catalogues',
    //         'foreignkey'=>'slide_catalogue_id',
    //         'language_id'=>$this->language,
    //     ]);
    // }

    //giao diện tổng
    public function index(Request $request){//Request $request để tiến hành chức năng tìm kiếm
        //$users=User::paginate(20);//từ khóa tìm kiếm eloquent

        // echo 123; die();

        //Lấy dữ liệu các mảng là các đường dẫn js và css ở function config phía dưới lưu vào biến $config
        $config=$this->configIndex();

        //biến template là nới lưu đường dẫn main của từng dao diện
        $template='Backend.slide.slide.index';

        //chèn thêm mảng 'seo' vào biến config để mảng 'seo' này lấy toàn bộ giá trị của folder config/apps/user.php
        $config['seo']=__('messages.slide.index');

        //Đổ dữ liệu User từ DB vào form theo mô hình service và repository
        $slides = $this->slideService->paginate($request);//$request để tiến hành chức năng tìm kiếm
        // dd($slides);

        // $dropdown= $this->nestedset->Dropdown();

        //dd($languages);

        $this->authorize('modules', 'slide.index');//phân quyền

        return view('Backend.dashboard.layout', compact('template','config','slides'));
    }

    //giao diện thêm slide
    public function store(){   
        $template='Backend.slide.slide.store';

        $config=$this->configCUD();

        $config['seo']=__('messages.slide.create');

        $config['method']='create';

        $this->authorize('modules', 'slide.store');//phân quyền

        return view('Backend.dashboard.layout', compact('template','config'));
    }

    //xử lý thêm slide
    public function create(StoreSlideRequest $request){
        if($this->slideService->createSlide($request, $this->language)){
            return redirect()->route('slide.index')->with('success','Thêm mới slide thành công');
        }
           return redirect()->route('slide.index')->with('error','Thêm mới slide thất bại. Hãy thử lại');
        
    }
    //giao diện sửa user
    public function edit($id){
        //echo $id;
        $template='Backend.slide.slide.store';

        $config=$this->configCUD();

        $config['seo']=config('apps.slide.edit');

        $config['method']='edit';//kiểm tra metho để thay đổi giao diện cho phù hợp

        //truy vấn thông tin
        $slide=$this->slideRepository->getSlideById($id,$this->language);

        if(!$slide){
            return redirect()->route('slide.index')->with('error', 'Bài viết này chưa có bản dịch của ngôn ngữ được chọn');
        }
        
        //dd($slide->slide_catalogues);

        $dropdown= $this->nestedset->Dropdown();

        $album = json_decode($slide->album);

        $this->authorize('modules', 'slide.edit');//phân quyền

        return view('Backend.dashboard.layout', compact('template','config','slide','dropdown','album'));
    }
    //xử lý sửa user
    public function update($id, UpdateSlideRequest $request){
        //echo $id; die();
        //dd($request);
        if($this->slideService->updateSlide($id, $request, $this->language)){
            return redirect()->route('slide.index')->with('success','Cập nhật bài viết thành công');
        }
           return redirect()->route('slide.index')->with('error','Cập nhật bài viết thất bại. Hãy thử lại');
    }
    //giao diện xóa user
    public function destroy($id){
        $template='Backend.slide.slide.destroy';

        $config=$this->configCUD();

        $config['seo']=__('messages.slide.delete');

        //truy vấn thông tin
        $slide=$this->slideRepository->getSlideById($id,$this->language);
        
        //dd($slideCatalogue);

        $dropdown= $this->nestedset->Dropdown();

        $this->authorize('modules', 'slide.destroy');//phân quyền

        return view('Backend.dashboard.layout', compact('template','config','slide'));
    }
    //xử lý xóa user
    public function delete($id){
        //echo $id;
        //echo 123; die();
        if($this->slideService->deleteSlide($id, $this->language)){
            return redirect()->route('slide.index')->with('success','Xóa bài viết thành công');
        }
           return redirect()->route('slide.index')->with('error','Xóa bài viết thất bại. Hãy thử lại');
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
            'model'=>'Slide'
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
                'Backend/libary/slide.js',
            ],
            'css'=>[
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
            ]
        ];
    }

}
