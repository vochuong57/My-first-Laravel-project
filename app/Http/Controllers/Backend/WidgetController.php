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
use App\Models\Language;
use App\Repositories\Interfaces\LanguageRepositoryInterface as LanguageRepository;


class WidgetController extends Controller
{
    protected $widgetService;
    protected $widgetRepository;
    protected $language;
    protected $languageRepository;

    public function __construct(WidgetService $widgetService, WidgetRepository $widgetRepository, LanguageRepository $languageRepository){
        $this->widgetService=$widgetService;//định nghĩa  $this->widgetService=$widgetService để biến này nó có thể trỏ tới các phương tức của WidgetService
        $this->widgetRepository=$widgetRepository;
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
        if($this->widgetService->createWidget($request, $this->language)){
            return redirect()->route('widget.index')->with('success','Thêm mới widget thành công');
        }
           return redirect()->route('widget.index')->with('error','Thêm mới widget thất bại. Hãy thử lại');
        
    }
    //giao diện sửa widget
    public function edit($id){
        // echo $id;
        $template='Backend.widget.widget.store';

        $config=$this->configCUD();

        $config['seo']=__('messages.widget.edit');

        $config['method']='edit';//kiểm tra metho để thay đổi giao diện cho phù hợp

        //truy vấn thông tin
        $widget=$this->widgetRepository->findById($id);
        // dd($widget); die();

        // dd($widget->description);
        if (isset($widget->description[$this->language])) {
            $widget->description = $widget->description[$this->language]; // Truy cập vào key 1 trong mảng description
        }else{
            $widget->description = null;
        }
        
        // dd($widget);
        // dd($widget->description);

        $repositoryInstance = loadClassInterface($widget->model);
        // dd($repositoryInstance); 
        
        $listModelId = $widget->model_id;
        // dd($listModelId);

        $widgetItemsCollection = [];
        foreach($listModelId as $modelId){
            $condition = [
                ['id', '=', $modelId]
            ];
            $languageId = $this->language;
            // dd($languageId);
            $relation = [
                'languages' => function($query) use ($languageId){
                    $query->where('language_id', $languageId);
                }
            ];
            $result = $repositoryInstance->findByConditionsWithRelation($condition, $relation);
            // dd($result);
            $widgetItemsCollection[] = $result;
        }
        // dd($widgetItemsCollection);

        $widgetItems = [
            'name' => [],
            'canonical' => [],
            'image' => [],
            'id' => []
        ];
        foreach($widgetItemsCollection as $widgetItemCollection){
            $result = $this->widgetService->convertWidget($widgetItemCollection);
            // dd($result);
            // Gộp kết quả vào mảng $widgetItems

            foreach ($result as $key => $values) {
                $widgetItems[$key] = array_merge($widgetItems[$key], $values);
            }
        }
        
        // dd($widgetItems);

        $this->authorize('modules', 'widget.edit');//phân quyền

        return view('Backend.dashboard.layout', compact('template','config', 'widget', 'widgetItems'));
    }
    //xử lý sửa widget
    public function update($id, UpdateWidgetRequest $request){
        //echo $id; die();
        //dd($request);
        if($this->widgetService->updateWidget($id, $request, $this->language)){
            return redirect()->route('widget.index')->with('success','Cập nhật widget thành công');
        }
           return redirect()->route('widget.index')->with('error','Cập nhật widget thất bại. Hãy thử lại');
    }

    //giao diện Dịch widget
    public function translate($languageTranslateId, $id){   
        $template='Backend.widget.widget.translate';

        $config=$this->configCUD();

        $config['seo']=__('messages.widget.translate');

        $config['method']='create';

        $languageTranslate = $this->languageRepository->findById($languageTranslateId);

        $widgetSession = $this->widgetRepository->findById($id);

        if (isset($widgetSession->description[$this->language])) {
            $widgetSession->description = $widgetSession->description[$this->language];
        } else {
            $widgetSession->description = null;
        }    

        // dd($widgetSession);

        $widgetTranslate = $this->widgetRepository->findById($id);

        if (isset($widgetTranslate->description[$languageTranslateId])) {
            $widgetTranslate->description = $widgetTranslate->description[$languageTranslateId];
        } else {
            $widgetTranslate->description = null;
        }        

        // dd($widgetTranslate);

        $this->authorize('modules', 'widget.translate');//phân quyền

        return view('Backend.dashboard.layout', compact('template','config', 'languageTranslate', 'widgetSession', 'widgetTranslate'));
    }

    public function saveTranslate(Request $request, $languageTranslateId, $id){
        if($this->widgetService->saveTranslateWidget($request, $languageTranslateId, $id)){
            return redirect()->route('widget.index')->with('success','Cập nhật bản dịch widget thành công');
        }
           return redirect()->route('widget.index')->with('error','Cập nhật bản dịch widget thất bại. Hãy thử lại');
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
        if($this->widgetService->deleteWidget($id, $this->language)){
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
                'Backend/libary/widget.js',
            ],
            'css'=>[
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
            ]
        ];
    }

}
