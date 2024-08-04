<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
//chèn thêm thư viện menuInterface tự tạo để lấy thông tin menu từ DB vào form
use App\Services\Interfaces\MenuServiceInterface as MenuService;
//chèn thêm tự viện ProvinceServiceInterface tự tạo để lấy thông tin province từ DB vào form
//chèn thêm thư viện tự tạo request để kiểm tra dữ liệu đầu vào khi thêm menu
use App\Http\Requests\StoreMenuRequest;
//chèn thêm viện menuRepositoryInterface để lấy function findById để truy xuất dữ liệu của id vừa nhập
use App\Repositories\Interfaces\MenuRepositoryInterface as MenuRepository;
//chèn thêm thư viện tự tạo request để kiểm tra dữ liệu đầu vào khi edit menu
use App\Http\Requests\UpdateMenuRequest;
//use App\Models\Menu;
//chén thêm thư viện của menuCatalogueRepository để lấy thông tin nhóm thành viên cho form thêm
use App\Models\Language;
use App\Repositories\Interfaces\MenuCatalogueRepositoryInterface as MenuCatalogueRepository;



class MenuController extends Controller
{
    protected $menuService;
    protected $menuRepository;
    protected $menuCatalogueRepository;

    public function __construct(MenuService $menuService, MenuRepository $menuRepository, MenuCatalogueRepository $menuCatalogueRepository){
        $this->menuService=$menuService;//định nghĩa  $this->menuService=$menuService để biến này nó có thể trỏ tới các phương tức của MenuService
        $this->menuRepository=$menuRepository;
        $this->menuCatalogueRepository=$menuCatalogueRepository;

        $this->middleware(function($request, $next) {
            try {
                $locale = app()->getLocale(); // vn cn en
                $language = Language::where('canonical', $locale)->first();

                if (!$language) {
                    throw new \Exception('Vui lòng chọn ngôn ngữ trước khi truy cập bài viết.');
                }

                $this->language = $language->id;
            } catch (\Exception $e) {
                return redirect()->route('dashboard.index')->with('error', $e->getMessage());
            }
            return $next($request);
        });
    }
    //giao diện tổng
    public function index(Request $request){//Request $request để tiến hành chức năng tìm kiếm
        //$menus=Menu::paginate(20);//từ khóa tìm kiếm eloquent

        //Lấy dữ liệu các mảng là các đường dẫn js và css ở function config phía dưới lưu vào biến $config
        $config = $this->configIndex();

        //biến template là nới lưu đường dẫn main của từng dao diện
        $template='Backend.menu.menu.index';

        //chèn thêm mảng 'seo' vào biến config để mảng 'seo' này lấy toàn bộ giá trị của folder config/apps/menu.php
        $config['seo'] = __('messages.menu');

        //Đổ dữ liệu Menu từ DB vào form theo mô hình service và repository
        $menus = $this->menuService->paginate($request, $this->language);//$request để tiến hành chức năng tìm kiếm

        $this->authorize('modules', 'menu.index');//phân quyền

        return view('Backend.dashboard.layout', compact('template','config','menus'));
    }

    //giao diện thêm menu
    public function store(){   
        $template='Backend.menu.menu.store';

        $config=$this->configCUD();

        $config['seo']=__('messages.menu.create');

        $config['method']='create';

        $menuCatalogues = $this->menuCatalogueRepository->all();
        // dd($menuCatalogues);

        $this->authorize('modules', 'menu.store');//phân quyền

        return view('Backend.dashboard.layout', compact('template','config','menuCatalogues'));
    }

    //xử lý thêm menu
    public function create(StoreMenuRequest $request){
        if($this->menuService->createMenu($request)){
            return redirect()->route('menu.index')->with('success','Thêm mới thành viên thành công');
        }
           return redirect()->route('menu.index')->with('error','Thêm mới thành viên thất bại. Hãy thử lại');
        
    }
    //giao diện sửa user
    public function edit($id){
        //echo $id;
        $template='Backend.menu.menu.store';

        $config=$this->configCUD();

        $config['seo']=config('apps.menu.edit');

        $config['method']='edit';//kiểm tra metho để thay đổi giao diện cho phù hợp

        $provinces=$this->provinceRepository->all();
        //dd($provinces);

        //truy vấn thông tin
        $menu=$this->menuRepository->findById($id);
        //dd($menu); die();

        $this->authorize('modules', 'menu.edit');//phân quyền

        return view('Backend.dashboard.layout', compact('template','config','provinces','menu'));
    }
    //xử lý sửa menu
    public function update($id, UpdateMenuRequest $request){
        //echo $id; die();
        //dd($request);
        if($this->menuService->updateMenu($id, $request)){
            return redirect()->route('menu.index')->with('success','Cập nhật thành viên thành công');
        }
           return redirect()->route('menu.index')->with('error','Cập nhật thành viên thất bại. Hãy thử lại');
    }
    //giao diện xóa menu
    public function destroy($id){
        $template='Backend.menu.menu.destroy';

        $config=$this->configCUD();

        $config['seo']=config('apps.menu.delete');

        //truy vấn thông tin
        $menu=$this->menuRepository->findById($id);
        //dd($menu); die();

        $this->authorize('modules', 'menu.destroy');//phân quyền

        return view('Backend.dashboard.layout', compact('template','config','menu'));
    }
    //xử lý xóa menu
    public function delete($id){
        //echo $id;
        if($this->menuService->deleteMenu($id)){
            return redirect()->route('menu.index')->with('success','Xóa thành viên thành công');
        }
           return redirect()->route('menu.index')->with('error','Xóa thành viên thất bại. Hãy thử lại');
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
            'model'=>'Menu'
        ];
    }

    private function configCUD(){
        return[
            'js'=>[
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js', 
                'Backend/libary/menu.js',              
            ],
            'css'=>[
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
            ]
        ];
    }

}
