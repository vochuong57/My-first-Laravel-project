<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
//chèn thêm thư viện userInterface tự tạo để lấy thông tin user từ DB vào form
use App\Services\Interfaces\PostServiceInterface as PostService;
//chèn thêm thư viện tự tạo request để kiểm tra dữ liệu đầu vào khi thêm user
use App\Http\Requests\StorePostRequest;
//chèn thêm viện userRepositoryInterface để lấy function findById để truy xuất dữ liệu của id vừa nhập
use App\Repositories\Interfaces\PostRepositoryInterface as PostRepository;
//chèn thêm thư viện tự tạo request để kiểm tra dữ liệu đầu vào khi edit user
use App\Http\Requests\UpdatePostRequest;
//use App\Models\User;
use App\Classes\Nestedsetbie;
use App\Models\Language;

class PostController extends Controller
{
    protected $postService;
    protected $postRepository;
    protected $nestedset;
    protected $language;//được lấy từ extends Controller

    public function __construct(PostService $postService, PostRepository $postRepository)
    {
        $this->postService = $postService; // định nghĩa $this->userService=$userCatalogueService để biến này nó có thể trỏ tới các phương tức của UserCatalogueService
        $this->postRepository = $postRepository;

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
            'table'=>'post_catalogues',
            'foreignkey'=>'post_catalogue_id',
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
        $template='Backend.post.post.index';

        //chèn thêm mảng 'seo' vào biến config để mảng 'seo' này lấy toàn bộ giá trị của folder config/apps/user.php
        $config['seo']=config('apps.post.index');

        //Đổ dữ liệu User từ DB vào form theo mô hình service và repository
        $posts = $this->postService->paginate($request, $this->language);//$request để tiến hành chức năng tìm kiếm
        //dd($posts);

        $dropdown= $this->nestedset->Dropdown();

        //dd($languages);

        $this->authorize('modules', 'post.index');//phân quyền

        return view('Backend.dashboard.layout', compact('template','config','posts','dropdown'));
    }

    //giao diện thêm user
    public function store(){   
        $template='Backend.post.post.store';

        $config=$this->configCUD();

        $config['seo']=config('apps.post.create');

        $config['method']='create';

        // //test nhanh việc lấy được dữ liệu hay không?
        // $location=[
        //     'province'=>$this->provinceRepository->all()
        // ];
        // dd($location);

       
        //dd($provinces);

        $dropdown= $this->nestedset->Dropdown();
        //dd($dropdown);

        $this->authorize('modules', 'post.store');//phân quyền

        return view('Backend.dashboard.layout', compact('template','config','dropdown'));
    }

    //xử lý thêm user
    public function create(StorePostRequest $request){
        if($this->postService->createPost($request, $this->language)){
            return redirect()->route('post.index')->with('success','Thêm mới bào viết thành công');
        }
           return redirect()->route('post.index')->with('error','Thêm mới bài viết thất bại. Hãy thử lại');
        
    }
    //giao diện sửa user
    public function edit($id){
        //echo $id;
        $template='Backend.post.post.store';

        $config=$this->configCUD();

        $config['seo']=config('apps.post.edit');

        $config['method']='edit';//kiểm tra metho để thay đổi giao diện cho phù hợp

        //truy vấn thông tin
        $post=$this->postRepository->getPostById($id,$this->language);

        if(!$post){
            return redirect()->route('post.index')->with('error', 'Bài viết này chưa có bản dịch của ngôn ngữ được chọn');
        }
        
        //dd($post->post_catalogues);

        $dropdown= $this->nestedset->Dropdown();

        $album = json_decode($post->album);

        $this->authorize('modules', 'post.edit');//phân quyền

        return view('Backend.dashboard.layout', compact('template','config','post','dropdown','album'));
    }
    //xử lý sửa user
    public function update($id, UpdatePostRequest $request){
        //echo $id; die();
        //dd($request);
        if($this->postService->updatePost($id, $request, $this->language)){
            return redirect()->route('post.index')->with('success','Cập nhật bài viết thành công');
        }
           return redirect()->route('post.index')->with('error','Cập nhật bài viết thất bại. Hãy thử lại');
    }
    //giao diện xóa user
    public function destroy($id){
        $template='Backend.post.post.destroy';

        $config=$this->configCUD();

        $config['seo']=config('apps.post.delete');

        //truy vấn thông tin
        $post=$this->postRepository->getPostById($id,$this->language);
        
        //dd($postCatalogue);

        $dropdown= $this->nestedset->Dropdown();

        $this->authorize('modules', 'post.destroy');//phân quyền

        return view('Backend.dashboard.layout', compact('template','config','post'));
    }
    //xử lý xóa user
    public function delete($id){
        //echo $id;
        //echo 123; die();
        if($this->postService->deletePost($id, $this->language)){
            return redirect()->route('post.index')->with('success','Xóa bài viết thành công');
        }
           return redirect()->route('post.index')->with('error','Xóa bài viết thất bại. Hãy thử lại');
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
            'model'=>'Post'
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
