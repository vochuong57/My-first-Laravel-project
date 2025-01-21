<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
//chèn thêm thư viện promotionInterface tự tạo để lấy thông tin promotion từ DB vào form
use App\Services\Interfaces\PromotionServiceInterface as PromotionService;
//chèn thêm thư viện tự tạo request để kiểm tra dữ liệu đầu vào khi thêm promotion
use App\Http\Requests\StorePromotionRequest;
//chèn thêm viện promotionRepositoryInterface để lấy function findById để truy xuất dữ liệu của id vừa nhập
use App\Repositories\Interfaces\PromotionRepositoryInterface as PromotionRepository;
//chèn thêm thư viện tự tạo request để kiểm tra dữ liệu đầu vào khi edit promotion
use App\Http\Requests\UpdatePromotionRequest;
//use App\Models\Promotion;
use App\Models\Language;
use App\Repositories\Interfaces\LanguageRepositoryInterface as LanguageRepository;
use App\Repositories\Interfaces\SourceRepositoryInterface as SourceRepository;


class PromotionController extends Controller
{
    protected $promotionService;
    protected $promotionRepository;
    protected $language;
    protected $languageRepository;
    protected $sourceRepository;

    public function __construct(PromotionService $promotionService, PromotionRepository $promotionRepository, LanguageRepository $languageRepository, SourceRepository $sourceRepository){
        $this->promotionService=$promotionService;//định nghĩa  $this->promotionService=$promotionService để biến này nó có thể trỏ tới các phương tức của PromotionService
        $this->promotionRepository=$promotionRepository;
        $this->languageRepository=$languageRepository;
        $this->sourceRepository=$sourceRepository;

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
        //$promotions=Promotion::paginate(20);//từ khóa tìm kiếm eloquent

        //Lấy dữ liệu các mảng là các đường dẫn js và css ở function config phía dưới lưu vào biến $config
        $config = $this->configIndex();

        //biến template là nới lưu đường dẫn main của từng dao diện
        $template='Backend.promotion.promotion.index';

        //chèn thêm mảng 'seo' vào biến config để mảng 'seo' này lấy toàn bộ giá trị của folder config/apps/promotion.php
        $config['seo'] = __('messages.promotion.index');

        //Đổ dữ liệu promotion từ DB vào form theo mô hình service và repository
        $promotions = $this->promotionService->paginate($request);//$request để tiến hành chức năng tìm kiếm

        $this->authorize('modules', 'promotion.index');//phân quyền

        return view('Backend.dashboard.layout', compact('template','config','promotions'));
    }

    //giao diện thêm promotion
    public function store(){   
        $template='Backend.promotion.promotion.store';

        $config=$this->configCUD();

        $config['seo']=__('messages.promotion.create');

        $config['method']='create';

        $sources =  $this->sourceRepository->all();

        $this->authorize('modules', 'promotion.store');//phân quyền

        return view('Backend.dashboard.layout', compact('template','config', 'sources'));
    }

    //xử lý thêm promotion
    public function create(StorePromotionRequest $request){
        if($this->promotionService->createPromotion($request, $this->language)){
            return redirect()->route('promotion.index')->with('success','Thêm mới promotion thành công');
        }
           return redirect()->route('promotion.index')->with('error','Thêm mới promotion thất bại. Hãy thử lại');
        
    }
    //giao diện sửa promotion
    public function edit($id){
        // echo $id;
        $template='Backend.promotion.promotion.store';

        $config=$this->configCUD();

        $config['seo']=__('messages.promotion.edit');

        $config['method']='edit';//kiểm tra metho để thay đổi giao diện cho phù hợp

        //truy vấn thông tin
        $promotion=$this->promotionRepository->findById($id);
        // dd($promotion); die();
        // dd($promotion->discountInformation);
        
        // dd($promotionItems);

        $sources =  $this->sourceRepository->all();

        $this->authorize('modules', 'promotion.edit');//phân quyền

        return view('Backend.dashboard.layout', compact('template','config', 'promotion', 'sources'));
    }
    //xử lý sửa promotion
    public function update($id, UpdatePromotionRequest $request){
        //echo $id; die();
        //dd($request);
        if($this->promotionService->updatePromotion($id, $request, $this->language)){
            return redirect()->route('promotion.index')->with('success','Cập nhật promotion thành công');
        }
           return redirect()->route('promotion.index')->with('error','Cập nhật promotion thất bại. Hãy thử lại');
    }

    //giao diện Dịch promotion
    public function translate($languageTranslateId, $id){   
        $template='Backend.promotion.promotion.translate';

        $config=$this->configCUD();

        $config['seo']=__('messages.promotion.translate');

        $config['method']='create';

        $languageTranslate = $this->languageRepository->findById($languageTranslateId);

        $promotionSession = $this->promotionRepository->findById($id);

        if (isset($promotionSession->description[$this->language])) {
            $promotionSession->description = $promotionSession->description[$this->language];
        } else {
            $promotionSession->description = null;
        }    

        // dd($promotionSession);

        $promotionTranslate = $this->promotionRepository->findById($id);

        if (isset($promotionTranslate->description[$languageTranslateId])) {
            $promotionTranslate->description = $promotionTranslate->description[$languageTranslateId];
        } else {
            $promotionTranslate->description = null;
        }        

        // dd($promotionTranslate);

        $this->authorize('modules', 'promotion.translate');//phân quyền

        return view('Backend.dashboard.layout', compact('template','config', 'languageTranslate', 'promotionSession', 'promotionTranslate'));
    }

    public function saveTranslate(Request $request, $languageTranslateId, $id){
        if($this->promotionService->saveTranslatePromotion($request, $languageTranslateId, $id)){
            return redirect()->route('promotion.index')->with('success','Cập nhật bản dịch promotion thành công');
        }
           return redirect()->route('promotion.index')->with('error','Cập nhật bản dịch promotion thất bại. Hãy thử lại');
    }

    //giao diện xóa promotion
    public function destroy($id){
        $template='Backend.promotion.promotion.destroy';

        $config=$this->configCUD();

        $config['seo']=__('messages.promotion.delete');

        //truy vấn thông tin
        $promotion=$this->promotionRepository->findById($id);
        //dd($promotion); die();

        $this->authorize('modules', 'promotion.destroy');//phân quyền

        return view('Backend.dashboard.layout', compact('template','config','promotion'));
    }
    //xử lý xóa promotion
    public function delete($id){
        //echo $id;
        if($this->promotionService->deletePromotion($id, $this->language)){
            return redirect()->route('promotion.index')->with('success','Xóa promotion thành công');
        }
           return redirect()->route('promotion.index')->with('error','Xóa promotion thất bại. Hãy thử lại');
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
            'model'=>'Promotion'
        ];
    }

    private function configCUD(){
        return[
            'js'=>[
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
                'Backend/plugins/ckfinder/ckfinder.js',
                'Backend/libary/finder.js',
                'Backend/plugins/ckeditor/ckeditor.js',
                'Backend/libary/promotion.js',
                'Backend/plugins/datetimepicker-master/build/jquery.datetimepicker.full.js'
            ],
            'css'=>[
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
                'Backend/plugins/datetimepicker-master/build/jquery.datetimepicker.min.css'
            ]
        ];
    }

}
