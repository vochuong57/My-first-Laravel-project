<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
//chèn thêm thư viện userInterface tự tạo để lấy thông tin user từ DB vào form
use App\Services\Interfaces\PostCatalogueServiceInterface as PostCatalogueService;
//chèn thêm thư viện tự tạo request để kiểm tra dữ liệu đầu vào khi thêm user
use App\Http\Requests\StorePostCatalogueRequest;
//chèn thêm viện userRepositoryInterface để lấy function findById để truy xuất dữ liệu của id vừa nhập
use App\Repositories\Interfaces\PostCatalogueRepositoryInterface as PostCatalogueRepository;
//chèn thêm thư viện tự tạo request để kiểm tra dữ liệu đầu vào khi edit user
use App\Http\Requests\UpdatePostCatalogueRequest;
//use App\Models\User;
use App\Classes\Nestedsetbie;
use App\Http\Requests\DeletePostCatalogueRequest;
use App\Models\Language;


class PostCatalogueController extends Controller
{
    protected $postCatalogueService;
    protected $postCatalogueRepository;
    protected $nestedset;
    protected $language;//được lấy từ extends Controller

    public function __construct(PostCatalogueService $postCatalogueService, PostCatalogueRepository $postCatalogueRepository){
        $this->postCatalogueService=$postCatalogueService;//định nghĩa  $this->userService=$userCatalogueService để biến này nó có thể trỏ tới các phương tức của UserCatalogueService
        $this->postCatalogueRepository=$postCatalogueRepository;
        
        $this->middleware(function($request, $next) {
            try {
                $locale = app()->getLocale(); // vn cn en
                $language = Language::where('canonical', $locale)->first();

                if (!$language) {
                    throw new \Exception('Vui lòng chọn ngôn ngữ trước khi truy cập nhóm bài viết.');
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
            'table'=>'post_catalogues',
            'foreignkey'=>'post_catalogue_id',
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
        $template='Backend.post.catalogue.index';

        //chèn thêm mảng 'seo' vào biến config để mảng 'seo' này lấy toàn bộ giá trị của folder config/apps/user.php
        $config['seo']=__('messages.postCatalogue');
        //dd($config['seo']);
        //Đổ dữ liệu User từ DB vào form theo mô hình service và repository
        $postCatalogues = $this->postCatalogueService->paginate($request, $this->language);//$request để tiến hành chức năng tìm kiếm
        //dd($userCatalogues);

        $this->authorize('modules', 'post.catalogue.index');//phân quyền

        return view('Backend.dashboard.layout', compact('template','config','postCatalogues'));
    }

    //giao diện thêm user
    public function store(){   
        $template='Backend.post.catalogue.store';

        $config=$this->configCUD();

        $config['seo']=__('messages.postCatalogue.create');

        $config['method']='create';

        // //test nhanh việc lấy được dữ liệu hay không?
        // $location=[
        //     'province'=>$this->provinceRepository->all()
        // ];
        // dd($location);

       
        //dd($provinces);

        $dropdown= $this->nestedset->Dropdown();
        //dd($dropdown);

        $this->authorize('modules', 'post.catalogue.store');//phân quyền

        return view('Backend.dashboard.layout', compact('template','config','dropdown'));
    }

    //xử lý thêm user
    public function create(StorePostCatalogueRequest $request){
        if($this->postCatalogueService->createPostCatalogue($request, $this->language)){
            return redirect()->route('post.catalogue.index')->with('success','Thêm mới nhóm bào viết thành công');
        }
           return redirect()->route('post.catalogue.index')->with('error','Thêm mới nhóm bài viết thất bại. Hãy thử lại');
        
    }
    //giao diện sửa user
    public function edit($id){
        //echo $id;
        $template='Backend.post.catalogue.store';

        $config=$this->configCUD();

        $config['seo']=__('messages.postCatalogue.edit');//config('apps.postCatalogue.edit');

        $config['method']='edit';//kiểm tra metho để thay đổi giao diện cho phù hợp

        //truy vấn thông tin
        $postCatalogue=$this->postCatalogueRepository->getPostCatalogueById($id,$this->language);

        if(!$postCatalogue){
            return redirect()->route('post.catalogue.index')->with('error', 'Nhóm bài viết này chưa có bản dịch của ngôn ngữ được chọn');
        }
        
        //dd($postCatalogue);

        $dropdown= $this->nestedset->Dropdown();

        $album = json_decode($postCatalogue->album);

        $this->authorize('modules', 'post.catalogue.edit');//phân quyền

        return view('Backend.dashboard.layout', compact('template','config','postCatalogue','dropdown','album'));
    }
    //xử lý sửa user
    public function update($id, UpdatePostCatalogueRequest $request){
        //echo $id; die();
        //dd($request);
        if($this->postCatalogueService->updatePostCatalogue($id, $request, $this->language)){
            return redirect()->route('post.catalogue.index')->with('success','Cập nhật nhóm bài viết thành công');
        }
           return redirect()->route('post.catalogue.index')->with('error','Cập nhật nhóm bài viết thất bại. Hãy thử lại');
    }
    //giao diện xóa user
    public function destroy($id){
        $template='Backend.post.catalogue.destroy';

        $config=$this->configCUD();

        $config['seo']=__('messages.postCatalogue.delete');

        //truy vấn thông tin
        $postCatalogue=$this->postCatalogueRepository->getPostCatalogueById($id,$this->language);
        
        //dd($postCatalogue);

        $dropdown= $this->nestedset->Dropdown();

        $this->authorize('modules', 'post.catalogue.destroy');//phân quyền

        return view('Backend.dashboard.layout', compact('template','config','postCatalogue'));
    }
    //xử lý xóa user
    public function delete($id, DeletePostCatalogueRequest $request){
        //echo $id;
        //echo 123; die();
        if($this->postCatalogueService->deletePostCatalogue($id, $this->language)){
            return redirect()->route('post.catalogue.index')->with('success','Xóa nhóm bài viết thành công');
        }
           return redirect()->route('post.catalogue.index')->with('error','Xóa nhóm bài viết thất bại. Hãy thử lại');
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
            'model'=>'PostCatalogue'
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
