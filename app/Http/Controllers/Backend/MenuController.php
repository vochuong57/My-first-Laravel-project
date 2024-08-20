<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
//chèn thêm thư viện menuInterface tự tạo để lấy thông tin menu từ DB vào form
use App\Services\Interfaces\MenuServiceInterface as MenuService;
//chèn thêm tự viện ProvinceServiceInterface tự tạo để lấy thông tin province từ DB vào form
//chèn thêm thư viện tự tạo request để kiểm tra dữ liệu đầu vào khi thêm menu
use App\Http\Requests\StoreMenuRequest;
use App\Http\Requests\StoreMenuChildrenRequest;
//chèn thêm viện menuRepositoryInterface để lấy function findById để truy xuất dữ liệu của id vừa nhập
use App\Repositories\Interfaces\MenuRepositoryInterface as MenuRepository;
//chèn thêm thư viện tự tạo request để kiểm tra dữ liệu đầu vào khi edit menu
use App\Http\Requests\UpdateMenuRequest;
//use App\Models\Menu;
//chén thêm thư viện của menuCatalogueRepository để lấy thông tin nhóm thành viên cho form thêm
use App\Models\Language;
use App\Repositories\Interfaces\MenuCatalogueRepositoryInterface as MenuCatalogueRepository;
use App\Services\Interfaces\MenuCatalogueServiceInterface as MenuCatalogueService;
use App\Repositories\Interfaces\LanguageRepositoryInterface as LanguageRepository;


class MenuController extends Controller
{
    protected $menuService;
    protected $menuRepository;
    protected $menuCatalogueRepository;
    protected $menuCatalogueService;
    protected $languageRepository;

    public function __construct(MenuService $menuService, MenuRepository $menuRepository, MenuCatalogueRepository $menuCatalogueRepository,MenuCatalogueService $menuCatalogueService, LanguageRepository $languageRepository){
        $this->menuService=$menuService;//định nghĩa  $this->menuService=$menuService để biến này nó có thể trỏ tới các phương tức của MenuService
        $this->menuRepository=$menuRepository;
        $this->menuCatalogueRepository=$menuCatalogueRepository;
        $this->menuCatalogueService=$menuCatalogueService;
        $this->languageRepository=$languageRepository;

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
        $menuCatalogues = $this->menuCatalogueService->paginate($request, $this->language);//$request để tiến hành chức năng tìm kiếm
        // dd($menuCatalogues);

        $this->authorize('modules', 'menu.index');//phân quyền

        return view('Backend.dashboard.layout', compact('template','config','menuCatalogues'));
    }

    // V62 giao diện thêm menu chính
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

    // V66 xử lý thêm menu chính, V71 xử lý save menu cấp 1
    public function create(StoreMenuRequest $request){
        $MenuCatalogueIdPayload = $request->input('menu_catalogue_id');
        // dd($MenuCatalogueIdPayload);
        if($this->menuService->saveMenu($request, $this->language)){
            return redirect()->route('menu.edit', ['id' => $MenuCatalogueIdPayload])->with('success','Save menu chính thành công');
        }
           return redirect()->route('menu.edit', ['id' => $MenuCatalogueIdPayload])->with('error','Save menu chính thất bại. Hãy thử lại');
        
    }
    // V67 giao diện danh sách menu theo id của vị trí menu (menu_catalogue_id)
    public function edit($id){
        // echo $id;
        $template='Backend.menu.menu.show';

        $config=$this->configCUD();

        $config['seo']=__('messages.menu.show');

        $config['method']='edit';//kiểm tra metho để thay đổi giao diện cho phù hợp

        //truy vấn thông tin
        $condition = [
            ['menu_catalogue_id', '=', $id],
        ];
        $languageId = $this->language;
        $relation = [
            'languages' => function($query) use ($languageId){
                $query->where('language_id', $languageId);
            }
        ];
        $order = ['order', 'desc'];
        $menus=$this->menuRepository->findByConditionsWithRelation($condition, $relation, $order);
        // dd($menus); die();

        // V69
        // $a = recursive($menus);
        // dd($a);
        
        // V71
        $menuCatalogueLoaded = $this->menuCatalogueRepository->findById($id);

        $this->authorize('modules', 'menu.edit');//phân quyền

        return view('Backend.dashboard.layout', compact('template','config', 'menus', 'id', 'menuCatalogueLoaded'));
    }
    // //xử lý sửa menu
    // public function update($id, UpdateMenuRequest $request){
    //     //echo $id; die();
    //     //dd($request);
    //     if($this->menuService->updateMenu($id, $request)){
    //         return redirect()->route('menu.index')->with('success','Cập nhật thành viên thành công');
    //     }
    //        return redirect()->route('menu.index')->with('error','Cập nhật thành viên thất bại. Hãy thử lại');
    // }
    // V72 giao diện xóa vị trí menu (menu_catalogue_id)
    public function destroy($id){
        $template='Backend.menu.menu.destroy';

        $config=$this->configCUD();

        $config['seo']=__('messages.menu.delete');

        //truy vấn thông tin
        $menuCatalogue=$this->menuCatalogueRepository->findById($id);
        //dd($menu); die();

        $this->authorize('modules', 'menu.destroy');//phân quyền

        return view('Backend.dashboard.layout', compact('template','config','menuCatalogue'));
    }
    // V72 xử lý xóa vị trí menu (menu_catalogue_id)
    public function delete($id){
        // echo $id; die();
        if($this->menuService->deleteMenu($id, $this->language)){
            return redirect()->route('menu.index')->with('success','Xóa thành viên thành công');
        }
           return redirect()->route('menu.index')->with('error','Xóa thành viên thất bại. Hãy thử lại');
    }

    // V66 Giao diện cập nhật menu con tương ứng với menu chính theo $id của menu cha
    public function children($id){
        $template='Backend.menu.menu.children';

        $config=$this->configCUD();

        $config['seo']=__('messages.menu.create');
        // dd($config['seo']);

        $config['method']='children';

        $languageId = $this->language;

        $menu = $this->menuRepository->findById($id, ['*'], [
            'languages' => function($query) use ($languageId){
                $query->where('language_id', $languageId);
            }
        ]);
        // dd($menu);

        $condition = [
            ['parent_id', '=', $menu->id]
        ];
        $languageId = $this->language;
        $relation = [
            'languages' => function($query) use ($languageId){
                $query->where('language_id', $languageId);
            }
        ];
        $order = ['order', 'desc'];
        $listMenus=$this->menuRepository->findByConditionsWithRelation($condition, $relation, $order);
        // dd($listMenus); die();

        $listMenus = $this->menuService->convertMenu($listMenus);
        // dd($listMenus);
        // dd($listMenus['canonical']);

        // V72
        $listCanonicalInRouter = [];
        if(!empty($listMenus)){
            $listCanonicalInRouter = DB::table('routers')->whereIn('canonical', $listMenus['canonical'])->pluck('canonical')->toArray();
        }
        // dd($listCanonicalInRouter);

        $this->authorize('modules', 'menu.store');//phân quyền

        return view('Backend.dashboard.layout', compact('template','config','menu', 'listMenus', 'listCanonicalInRouter'));
    }

    // V66 save dữ liệu menu con theo menu chính tương ứng
    public function saveChildren(StoreMenuChildrenRequest $request, $id){
        $menu = $this->menuRepository->findById($id);
        // dd($menu);
        if($this->menuService->saveChildren($request, $this->language, $menu)){
            return redirect()->route('menu.edit', ['id' => $menu->menu_catalogue_id])->with('success','Cập nhật menu con thành công');
        }
           return redirect()->route('menu.edit', ['id' => $menu->menu_catalogue_id])->with('error','Cập nhật menu con thất bại. Hãy thử lại');
    }

    // V71 Giao diện cập nhật menu cấp 1 theo parent_id là 0 và menu_catalogue_id là $id
    public function editMenu($id){
        $template='Backend.menu.menu.store';

        $config=$this->configCUD();

        $config['seo']=__('messages.menu.create');
        // dd($config['seo']);

        $menuCatalogues = $this->menuCatalogueRepository->all();

        $menuCatalogueLoaded = $this->menuCatalogueRepository->findById($id);

        $config['method']='save';
        //truy vấn thông tin
        $condition = [
            ['parent_id', '=', 0],
            ['menu_catalogue_id', '=', $id]
        ];
        $languageId = $this->language;
        $relation = [
            'languages' => function($query) use ($languageId){
                $query->where('language_id', $languageId);
            }
        ];
        $order = ['order', 'desc'];
        $listMenus=$this->menuRepository->findByConditionsWithRelation($condition, $relation, $order);
        // dd($listMenus);

        $listMenus = $this->menuService->convertMenu($listMenus);
        // dd($listMenus);

        // V72
        $listCanonicalInRouter = DB::table('routers')->whereIn('canonical', $listMenus['canonical'])->pluck('canonical')->toArray();
        // dd($listCanonicalInRouter);

        $this->authorize('modules', 'menu.store');//phân quyền

        return view('Backend.dashboard.layout', compact('template', 'config', 'menuCatalogues', 'menuCatalogueLoaded', 'listMenus', 'listCanonicalInRouter'));
    }

    // V74
    public function translate($languageTranslateId, $menuCatalogueId){
        // echo 123; die();
        $template='Backend.menu.menu.translate';

        $config=$this->configCUD();

        $config['seo']=__('messages.menu.translate');

        $config['method']='translate';

        $languageTranslate = $this->languageRepository->findById($languageTranslateId);
        // dd($languageTranslate);

        $menuCatalogue=$this->menuCatalogueRepository->findById($menuCatalogueId);

        $condition = [
            ['menu_catalogue_id', '=', $menuCatalogueId]
        ];
        $languageSessionId = $this->language;
        $relation = [
            'languages' => function($query) use ($languageSessionId){
                $query->where('language_id', $languageSessionId);
            }
        ];
        $order = ['order', 'desc'];
        $menus=$this->menuRepository->findByConditionsWithRelation($condition, $relation, $order);
        // dd($menus);

        $listMenus = $this->menuService->convertMenu($menus);
        // dd($listMenus);

        $listCanonicalInRouter = [];
        if(is_array($listMenus) && !empty($listMenus)){
            $listCanonicalInRouter = DB::table('routers')->whereIn('canonical', $listMenus['canonical'])->pluck('canonical')->toArray();
        } 

        $menus = buildMenu($this->menuService->findMenuItemTranslate($menus, $languageSessionId, $languageTranslateId));
        // dd($menus);     

        return view('Backend.dashboard.layout', compact('template', 'config', 'languageTranslate', 'menuCatalogue', 'menus', 'listCanonicalInRouter'));
    }

    // V74
    public function saveTranslate(Request $request, $languageTranslateId){
        if($this->menuService->saveTranslateMenu($request, $languageTranslateId)){
            return redirect()->route('menu.index')->with('success','Cập nhật bản dịch menu thành công');
        }
           return redirect()->route('menu.index')->with('error','Cập nhật bản dịch menu thất bại. Hãy thử lại');
    }

    private function configIndex(){
        return[
            'js'=>[
                'Backend/js/plugins/switchery/switchery.js',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
                
            ],
            'css'=>[
                'Backend/css/plugins/switchery/switchery.css',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
            ],
            'model'=>'MenuCatalogue'
        ];
    }

    private function configCUD(){
        return[
            'js'=>[
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js', 
                'Backend/libary/menu.js',   
                'Backend/js/plugins/nestable/jquery.nestable.js'           
            ],
            'css'=>[
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
            ]
        ];
    }

}
