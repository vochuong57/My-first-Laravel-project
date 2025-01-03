<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
//chèn thêm thư viện sourceInterface tự tạo để lấy thông tin source từ DB vào form
use App\Services\Interfaces\SourceServiceInterface as SourceService;
//chèn thêm thư viện tự tạo request để kiểm tra dữ liệu đầu vào khi thêm source
use App\Http\Requests\StoreSourceRequest;
//chèn thêm viện sourceRepositoryInterface để lấy function findById để truy xuất dữ liệu của id vừa nhập
use App\Repositories\Interfaces\SourceRepositoryInterface as SourceRepository;
//chèn thêm thư viện tự tạo request để kiểm tra dữ liệu đầu vào khi edit source
use App\Http\Requests\UpdateSourceRequest;
//use App\Models\Source;
use App\Models\Language;
use App\Repositories\Interfaces\LanguageRepositoryInterface as LanguageRepository;


class SourceController extends Controller
{
    protected $sourceService;
    protected $sourceRepository;
    protected $language;
    protected $languageRepository;

    public function __construct(SourceService $sourceService, SourceRepository $sourceRepository, LanguageRepository $languageRepository){
        $this->sourceService=$sourceService;//định nghĩa  $this->sourceService=$sourceService để biến này nó có thể trỏ tới các phương tức của sourceService
        $this->sourceRepository=$sourceRepository;
        $this->languageRepository=$languageRepository;

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
    //giao diện tổng
    public function index(Request $request){//Request $request để tiến hành chức năng tìm kiếm
        //$sources=Source::paginate(20);//từ khóa tìm kiếm eloquent

        //Lấy dữ liệu các mảng là các đường dẫn js và css ở function config phía dưới lưu vào biến $config
        $config = $this->configIndex();

        //biến template là nới lưu đường dẫn main của từng dao diện
        $template='Backend.source.source.index';

        //chèn thêm mảng 'seo' vào biến config để mảng 'seo' này lấy toàn bộ giá trị của folder config/apps/source.php
        $config['seo'] = __('messages.source.index');

        //Đổ dữ liệu Source từ DB vào form theo mô hình service và repository
        $sources = $this->sourceService->paginate($request);//$request để tiến hành chức năng tìm kiếm

        $this->authorize('modules', 'source.index');//phân quyền

        return view('Backend.dashboard.layout', compact('template','config','sources'));
    }

    //giao diện thêm source
    public function store(){   
        $template='Backend.source.source.store';

        $config=$this->configCUD();

        $config['seo']=__('messages.source.create');

        $config['method']='create';

        $this->authorize('modules', 'source.store');//phân quyền

        return view('Backend.dashboard.layout', compact('template','config'));
    }

    //xử lý thêm source
    public function create(StoreSourceRequest $request){
        if($this->sourceService->createSource($request)){
            return redirect()->route('source.index')->with('success','Thêm mới source thành công');
        }
           return redirect()->route('source.index')->with('error','Thêm mới source thất bại. Hãy thử lại');
        
    }
    //giao diện sửa source
    public function edit($id){
        // echo $id;
        $template='Backend.source.source.store';

        $config=$this->configCUD();

        $config['seo']=__('messages.source.edit');

        $config['method']='edit';//kiểm tra metho để thay đổi giao diện cho phù hợp

        //truy vấn thông tin
        $source=$this->sourceRepository->findById($id);
        // dd($source);
        
        $this->authorize('modules', 'source.edit');//phân quyền

        return view('Backend.dashboard.layout', compact('template','config', 'source'));
    }
    //xử lý sửa source
    public function update($id, UpdateSourceRequest $request){
        //echo $id; die();
        //dd($request);
        if($this->sourceService->updateSource($id, $request, $this->language)){
            return redirect()->route('source.index')->with('success','Cập nhật source thành công');
        }
           return redirect()->route('source.index')->with('error','Cập nhật source thất bại. Hãy thử lại');
    }

    //giao diện xóa source
    public function destroy($id){
        $template='Backend.source.source.destroy';

        $config=$this->configCUD();

        $config['seo']=__('messages.source.delete');

        //truy vấn thông tin
        $source=$this->sourceRepository->findById($id);
        //dd($source); die();

        $this->authorize('modules', 'source.destroy');//phân quyền

        return view('Backend.dashboard.layout', compact('template','config','source'));
    }
    //xử lý xóa source
    public function delete($id){
        //echo $id;
        if($this->sourceService->deleteSource($id, $this->language)){
            return redirect()->route('source.index')->with('success','Xóa source thành công');
        }
           return redirect()->route('source.index')->with('error','Xóa source thất bại. Hãy thử lại');
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
            'model'=>'Source'
        ];
    }

    private function configCUD(){
        return[
            'js'=>[
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
                'Backend/plugins/ckfinder/ckfinder.js',
                'Backend/libary/finder.js',
                'Backend/plugins/ckeditor/ckeditor.js',
                'Backend/libary/source.js',
            ],
            'css'=>[
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
            ]
        ];
    }

}
