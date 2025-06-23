<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
//chèn thêm thư viện reviewInterface tự tạo để lấy thông tin review từ DB vào form
use App\Services\Interfaces\ReviewServiceInterface as ReviewService;
//chèn thêm thư viện tự tạo request để kiểm tra dữ liệu đầu vào khi thêm review
use App\Http\Requests\StoreReviewRequest;
//chèn thêm viện reviewRepositoryInterface để lấy function findById để truy xuất dữ liệu của id vừa nhập
use App\Repositories\Interfaces\ReviewRepositoryInterface as ReviewRepository;
//chèn thêm thư viện tự tạo request để kiểm tra dữ liệu đầu vào khi edit review
use App\Http\Requests\UpdateReviewRequest;
//use App\Models\Review;
use App\Models\Language;


class ReviewController extends Controller
{
    protected $reviewService;
    protected $reviewRepository;
    protected $language;

    public function __construct(ReviewService $reviewService, ReviewRepository $reviewRepository){
        $this->reviewService=$reviewService;//định nghĩa  $this->reviewService=$reviewService để biến này nó có thể trỏ tới các phương tức của ReviewService
        $this->reviewRepository=$reviewRepository;

        $this->middleware(function($request, $next) {
            try {
                $locale = app()->getLocale(); // vn cn en
                $language = Language::where('canonical', $locale)->first();

                if (!$language) {
                    throw new \Exception('Vui lòng chọn ngôn ngữ trước khi truy cập review.');
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
        //Lấy dữ liệu các mảng là các đường dẫn js và css ở function config phía dưới lưu vào biến $config
        $config = $this->configIndex();

        //biến template là nới lưu đường dẫn main của từng dao diện
        $template='Backend.review.review.index';

        //chèn thêm mảng 'seo' vào biến config để mảng 'seo' này lấy toàn bộ giá trị của folder config/apps/review.php
        $config['seo'] = __('messages.review.index');

        //Đổ dữ liệu review từ DB vào form theo mô hình service và repository
        $reviews = $this->reviewService->paginate($request);//$request để tiến hành chức năng tìm kiếm
        // dd($reviews);

        $language_id = $this->language;
        // dd($language_id);

        $this->authorize('modules', 'review.index');//phân quyền

        return view('Backend.dashboard.layout', compact('template','config','reviews', 'language_id'));
    }

    public function destroy($id){
        $template='Backend.review.review.destroy';

        $config=$this->configCUD();

        $config['seo']= __('messages.review.delete');

        //truy vấn thông tin
        $review=$this->reviewRepository->findById($id);
        
        // dd($review);

        $this->authorize('modules', 'review.destroy');//phân quyền

        return view('Backend.dashboard.layout', compact('template','config','review'));
    }

    //xử lý xóa review
    public function delete($id){
        //echo $id;
        //echo 123; die();
        if($this->reviewService->deleteReview($id)){
            // echo 123; die();
            return redirect()->route('review.index')->with('success','Xóa review thành công');
        }
           return redirect()->route('review.index')->with('error','Xóa review thất bại. Hãy thử lại');
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
            'model'=>'Review',
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
