<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
//chèn thêm thư viện widgetInterface tự tạo để lấy thông tin widget từ DB vào form
use App\Services\Interfaces\WidgetServiceInterface as WidgetService;
//chèn thêm thư viện tự tạo request để kiểm tra dữ liệu đầu vào khi thêm widget
use App\Http\Requests\StoreWidgetRequest;
//chèn thêm viện widgetRepositoryInterface để lấy function findById để truy xuất dữ liệu của id vừa nhập
use App\Repositories\Interfaces\WidgetRepositoryInterface as WidgetRepository;
//chèn thêm thư viện tự tạo request để kiểm tra dữ liệu đầu vào khi edit widget
use App\Http\Requests\UpdateWidgetRequest;
//use App\Models\Widget;



class WidgetController extends Controller
{
    protected $widgetService;
    protected $widgetRepository;

    public function __construct(WidgetService $widgetService, WidgetRepository $widgetRepository){
        $this->widgetService=$widgetService;//định nghĩa  $this->widgetService=$widgetService để biến này nó có thể trỏ tới các phương tức của WidgetService
        $this->widgetRepository=$widgetRepository;
    }
    //giao diện tổng
    public function index(Request $request){//Request $request để tiến hành chức năng tìm kiếm
        //$widgets=Widget::paginate(20);//từ khóa tìm kiếm eloquent

        //Lấy dữ liệu các mảng là các đường dẫn js và css ở function config phía dưới lưu vào biến $config
        $config = $this->configIndex();

        //biến template là nới lưu đường dẫn main của từng dao diện
        $template='Backend.widget.widget.index';

        //chèn thêm mảng 'seo' vào biến config để mảng 'seo' này lấy toàn bộ giá trị của folder config/apps/widget.php
        $config['seo'] = __('messages.widget.index');

        //Đổ dữ liệu Widget từ DB vào form theo mô hình service và repository
        $widgets = $this->widgetService->paginate($request);//$request để tiến hành chức năng tìm kiếm

        $this->authorize('modules', 'widget.index');//phân quyền

        return view('Backend.dashboard.layout', compact('template','config','widgets'));
    }

    //giao diện thêm widget
    public function store(){   
        $template='Backend.widget.widget.store';

        $config=$this->configCUD();

        $config['seo']=__('messages.widget.create');

        $config['method']='create';

        $this->authorize('modules', 'widget.store');//phân quyền

        return view('Backend.dashboard.layout', compact('template','config'));
    }

    //xử lý thêm widget
    public function create(StoreWidgetRequest $request){
        if($this->widgetService->createWidget($request)){
            return redirect()->route('widget.index')->with('success','Thêm mới widget thành công');
        }
           return redirect()->route('widget.index')->with('error','Thêm mới widget thất bại. Hãy thử lại');
        
    }
    //giao diện sửa widget
    public function edit($id){
        //echo $id;
        $template='Backend.widget.widget.store';

        $config=$this->configCUD();

        $config['seo']=__('messages.widget.edit');

        $config['method']='edit';//kiểm tra metho để thay đổi giao diện cho phù hợp

        //truy vấn thông tin
        $widget=$this->widgetRepository->findById($id);
        //dd($widget); die();

        $this->authorize('modules', 'widget.edit');//phân quyền

        return view('Backend.dashboard.layout', compact('template','config','widget'));
    }
    //xử lý sửa widget
    public function update($id, UpdateWidgetRequest $request){
        //echo $id; die();
        //dd($request);
        if($this->widgetService->updateWidget($id, $request)){
            return redirect()->route('widget.index')->with('success','Cập nhật widget thành công');
        }
           return redirect()->route('widget.index')->with('error','Cập nhật widget thất bại. Hãy thử lại');
    }
    //giao diện xóa widget
    public function destroy($id){
        $template='Backend.widget.widget.destroy';

        $config=$this->configCUD();

        $config['seo']=__('messages.widget.delete');

        //truy vấn thông tin
        $widget=$this->widgetRepository->findById($id);
        //dd($widget); die();

        $this->authorize('modules', 'widget.destroy');//phân quyền

        return view('Backend.dashboard.layout', compact('template','config','widget'));
    }
    //xử lý xóa widget
    public function delete($id){
        //echo $id;
        if($this->widgetService->deleteWidget($id)){
            return redirect()->route('widget.index')->with('success','Xóa widget thành công');
        }
           return redirect()->route('widget.index')->with('error','Xóa widget thất bại. Hãy thử lại');
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
            'model'=>'Widget'
        ];
    }

    private function configCUD(){
        return[
            'js'=>[
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
                'Backend/plugins/ckfinder/ckfinder.js',
                'Backend/libary/finder.js',
                'Backend/plugins/ckeditor/ckeditor.js',
            ],
            'css'=>[
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
            ]
        ];
    }

}
