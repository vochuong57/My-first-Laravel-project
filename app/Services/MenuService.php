<?php

namespace App\Services;

use App\Services\Interfaces\MenuServiceInterface;
use App\Repositories\Interfaces\MenuRepositoryInterface as MenuRepository;
use App\Repositories\Interfaces\MenuLanguageRepositoryInterface as MenuLanguageRepository;
use App\Repositories\Interfaces\MenuCatalogueRepositoryInterface as MenuCatalogueRepository;
use App\Repositories\Interfaces\RouterRepositoryInterface as RouterRepository;
//thêm thư viện cho việc xử lý INSERT
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
//thêm thư viện xử lý xử lý DATE
use Illuminate\Support\Carbon;
//thêm thư viện xử lý password
use Illuminate\Support\Facades\Hash;
//gọi thư viện userRepository để cập nhật trạng thái khi đã chọn thay đổi trạng thái của userCatalogue
//use App\Repositories\Interfaces\UserRepositoryInterface as UserRepository;
use Illuminate\Support\Facades\Auth;
use App\Services\BaseService;//tiến hành chèn dữ liệu vào bảng ngoài cụ thể là menu_catalogue_language
use Request;
use Spatie\LaravelIgnition\Exceptions\CannotExecuteSolutionForNonLocalIp;
use Illuminate\Support\Str;
use App\Classes\Nestedsetbie;

/**
 * Class UserService
 * @package App\Services
 */
class MenuService extends BaseService implements MenuServiceInterface
{
    protected $menuRepository;
    protected $menuLanguageRepository;
    protected $menuCatalogueRepository;
    protected $routerRepository;
    protected $controllerName = 'MenuController';
    protected $language;    

    public function __construct(MenuRepository $menuRepository, MenuLanguageRepository $menuLanguageRepository, MenuCatalogueRepository $menuCatalogueRepository, RouterRepository $routerRepository){
        $this->menuRepository=$menuRepository;
        $this->menuLanguageRepository=$menuLanguageRepository;
        $this->menuCatalogueRepository=$menuCatalogueRepository;
        $this->routerRepository=$routerRepository;
        $this->language=$this->currentLanguage();
        $this->nestedset=new Nestedsetbie([
            'table'=>'menus',
            'foreignkey'=>'menu_id',
            'language_id'=>$this->currentLanguage(),
        ]);
    }

    public function paginate($request, $languageId){//$request để tiến hành chức năng tìm kiếm
        // //dd($request);
        // //echo 123; die();
        // $condition['keyword']=addslashes($request->input('keyword'));
        // $condition['publish']=$request->input('publish');
        // // Kiểm tra nếu giá trị publish là 0, thì gán lại thành null
        // if ($condition['publish'] == '0') {
        //     $condition['publish'] = null;
        // }
        // $condition['where']=[
        //     ['tb2.language_id', '=', $languageId],
        // ];
        // //dd($condition);
        // $perpage=$request->integer('perpage', 20);
        // $menus=$this->menuRepository->pagination(
        //     $this->paginateSelect(),
        //     $condition,
        //     $perpage,
        //     ['path'=> 'menu/index', 'groupBy' => $this->paginateSelect()],
        //     ['menus.id', 'DESC'],
        //     [
        //         ['menu_language as tb2','tb2.menu_id','=','menus.id'],//dùng cho hiển thị nội dung table
        //         ['menu_catalogue_menu as tb3','menus.id', '=', 'tb3.menu_id']//dùng cho whereRaw lọc tìm kiếm bài viết theo nhóm bài viêt
        //     ],
        //     ['menu_catalogues'],//là function menu_catalogues của Model/Menu
        //     $this->whereRaw($request),
        // );
        // //dd($menus);
        
        return [];
    }
    // V66, V71
    public function saveMenu($request, $languageId){
        DB::beginTransaction();
        try{
            
            $payload = $request->only('menu', 'menu_catalogue_id');
            // dd($payload['menu_catalogue_id']);

            // v71 ---------------------------------Đối với việc bỏ bớt mainMenu--------------------------------
            
            $arrayMainMenuIdsDB = DB::table('menus')->where('parent_id', 0)->where('menu_catalogue_id', $payload['menu_catalogue_id'])->pluck('id')->toArray();
            // dd($arrayMainMenuIdsDB); // 28, 29, 57

            $arrayMainMenuIdsPayload = $payload['menu']['id'];
            // dd($arrayMainMenuIdsPayload); // 28, 29

            $differentIds = array_diff($arrayMainMenuIdsDB, $arrayMainMenuIdsPayload); 
            // dd($differentIds);

            if(count($differentIds) > 0){
                foreach($differentIds as $differentid){
                    //Tìm và xóa menu con của menu cấp 1 trước
                    $hasChildrenIds = $this->getAllChildMenuIds($differentid);
                    foreach($hasChildrenIds as $hasChildrenId){
                        $this->menuLanguageRepository->deleteByWhere([
                            ['menu_id', '=', $hasChildrenId],
                            ['language_id', '=', $languageId]
                        ]);

                        $hasMenuIdInMenuLanguage = $this->menuLanguageRepository->findByCondition([
                            ['menu_id', '=', $hasChildrenId]
                        ]);

                        if($hasMenuIdInMenuLanguage == null){
                            $this->menuRepository->deleteByWhere([
                                ['id', '=', $hasChildrenId],
                            ]);
                        }
                    }
                    
                    // Sau đó mới xóa đi menu 1 cấp 1 đang truy cập
                    $this->menuLanguageRepository->deleteByWhere([
                        ['menu_id', '=', $differentid],
                        ['language_id', '=', $languageId]
                    ]);

                    $hasMenuIdInMenuLanguage = $this->menuLanguageRepository->findByCondition([
                        ['menu_id', '=', $differentid]
                    ]);

                    if($hasMenuIdInMenuLanguage == null){
                        $this->menuRepository->deleteByWhere([
                            ['id', '=', $differentid],
                        ]);
                    }
                }
            }

            // V66, V71 ---------------------------------Đối với việc thêm mới và cập nhật mainMenu--------------------------------

            if(count($payload['menu']['name'])){
                foreach($payload['menu']['name'] as $key => $val){
                    $menuId = $payload['menu']['id'][$key];
                    // 1. menus
                    $menuArray = [
                        'menu_catalogue_id' => $payload['menu_catalogue_id'],
                        // 'type' => $payload['type'],
                        'order' => $payload['menu']['order'][$key],
                        'user_id' => Auth::id()
                    ];
                    // dd($menuArray);

                    // V71
                    if($menuId == 0){
                        $menuSave = $this->menuRepository->create($menuArray);
                    }else{
                        $menuSave = $this->menuRepository->updateReturn($menuId, $menuArray);
                        $hasChildrenIds = $this->getAllChildMenuIds($menuId);
                        // dd($hasChildrenIds);
                        if(count($hasChildrenIds)){
                            $this->menuRepository->updateByWhereIn('id', $hasChildrenIds, ['menu_catalogue_id' => $payload['menu_catalogue_id']]);  
                        }
                    }
                    // dd($menuSave);

                    // 2. menu_language
                    if($menuSave->id > 0){
                        $menuSave->languages()->detach($languageId, $menuSave->id);
                        $payloadLanguage = [
                            'language_id' => $languageId,
                            'name' => $val,
                            'canonical' => $payload['menu']['canonical'][$key],
                        ];
                        $language = $this->menuRepository->createPivot($menuSave,$payloadLanguage,'languages');
                        // dd($language);
                    }
                }
                // die();
                $this->nestedset();
            }
            // die();
            // echo 1; die();
            DB::commit();
            return true;
        }catch(\Exception $ex){
            DB::rollBack();
            echo $ex->getMessage();die();
            return false;
        }
    }

    // V71
    public function getAllChildMenuIds($menuId)
    {
        // Lấy danh sách các id của các menu con
        $childrenIds = DB::table('menus')->where('parent_id', $menuId)->pluck('id')->toArray();
        // dd($childrenIds);

        // Tạo mảng chứa tất cả các id
        $allIds = $childrenIds;

        // Đệ quy để tìm các menu con của các menu con
        foreach ($childrenIds as $childId) {
            $allIds = array_merge($allIds, $this->getAllChildMenuIds($childId));
        }

        return $allIds;
    }

    // V68
    public function saveChildren($request, $languageId, $menu){
        DB::beginTransaction();
        try{
            
            $payload = $request->only('menu');
            // dd($payload);

            // V68 ---------------------------------Đối với việc bỏ bớt childrenMenu--------------------------------
            
            $parentId = $menu->id;
            $arrayChildrenMenuIdsDB = DB::table('menus')->where('parent_id', $parentId)->pluck('id')->toArray();
            // dd($arrayChildrenMenuIdsDB); // 49, 50, 51, 52

            $arrayChildrenMenuIdsPayload = $payload['menu']['id'];
            // dd($arrayChildrenMenuIdsPayload); // 49, 50, 51

            $differentIds = array_diff($arrayChildrenMenuIdsDB, $arrayChildrenMenuIdsPayload); 
            // dd($differentIds);

            if(count($differentIds) > 0){
                foreach($differentIds as $differentid){
                    //Tìm và xóa menu con của menu con truy cập trước
                    $hasChildrenIds = $this->getAllChildMenuIds($differentid);
                    foreach($hasChildrenIds as $hasChildrenId){
                        $this->menuLanguageRepository->deleteByWhere([
                            ['menu_id', '=', $hasChildrenId],
                            ['language_id', '=', $languageId]
                        ]);

                        $hasMenuIdInMenuLanguage = $this->menuLanguageRepository->findByCondition([
                            ['menu_id', '=', $hasChildrenId]
                        ]);

                        if($hasMenuIdInMenuLanguage == null){
                            $this->menuRepository->deleteByWhere([
                                ['id', '=', $hasChildrenId],
                            ]);
                        }
                    }
                    
                    // Sau đó mới xóa đi menu con đang truy cập
                    $this->menuLanguageRepository->deleteByWhere([
                        ['menu_id', '=', $differentid],
                        ['language_id', '=', $languageId]
                    ]);

                    $hasMenuIdInMenuLanguage = $this->menuLanguageRepository->findByCondition([
                        ['menu_id', '=', $differentid]
                    ]);

                    if($hasMenuIdInMenuLanguage == null){
                        $this->menuRepository->deleteByWhere([
                            ['id', '=', $differentid],
                        ]);
                    }
                }
            }

            // V68 ---------------------------------Đối với việc thêm mới và cập nhật childrenMenu--------------------------------

            if(count($payload['menu']['name'])){
                foreach($payload['menu']['name'] as $key => $val){
                    $menuId = $payload['menu']['id'][$key];
                    // 1. menus
                    $menuArray = [
                        'menu_catalogue_id' => $menu->menu_catalogue_id,
                        'parent_id' => $menu->id,
                        'order' => $payload['menu']['order'][$key],
                        'user_id' => Auth::id()
                    ];
                    // dd($menuArray);
                    
                    if($menuId == 0){
                        $menuSave = $this->menuRepository->create($menuArray);
                    }else{
                        $menuSave = $this->menuRepository->updateReturn($menuId, $menuArray);
                    }
                    // dd($menuSave);

                    // 2. menu_language
                    if($menuSave->id > 0){
                        $menuSave->languages()->detach($languageId, $menuSave->id);
                        $payloadLanguage = [
                            'language_id' => $languageId,
                            'name' => $val,
                            'canonical' => $payload['menu']['canonical'][$key],
                        ];
                        // dd($payloadLanguage);
                        $language = $this->menuRepository->createPivot($menuSave,$payloadLanguage,'languages');
                        // dd($language);
                    }
                }
                // die();
                $this->nestedset();
            }
            // die();
            // echo 1; die();
            DB::commit();
            return true;
        }catch(\Exception $ex){
            DB::rollBack();
            echo $ex->getMessage();die();
            return false;
        }
    }

    // public function updateMenu($id, $request, $languageId){
    //     DB::beginTransaction();
    //     try{
            
            
    //         DB::commit();
    //         return true;
    //     }catch(\Exception $ex){
    //         DB::rollBack();
    //         echo $ex->getMessage();//die();
    //         return false;
    //     }
    // }
   
    // V72
    public function deleteMenu($id, $languageId){
        DB::beginTransaction();
        try{
            $menuIds = DB::table('menus')->where('menu_catalogue_id', $id)->pluck('id')->toArray();
            // dd($menuIds);
            // dd(count($menuIds));
            $countDeletedMenu = 0;
            foreach($menuIds as $menuId){
                $this->menuLanguageRepository->deleteByWhere([
                    ['menu_id', '=', $menuId],
                    ['language_id', '=', $languageId]
                ]);
                // echo 1; die();

                $hasMenuIdInMenuLanguage = $this->menuLanguageRepository->findByCondition([
                    ['menu_id', '=', $menuId]
                ]);
                // echo 2; die();
                // dd($hasMenuIdInMenuLanguage);

                if($hasMenuIdInMenuLanguage == null){
                    $this->menuRepository->deleteByWhere([
                        ['id', '=', $menuId],
                    ]);
                    $countDeletedMenu++;
                    // echo 3; die();
                }
            }
            // dd($countDeletedMenu);
            if(count($menuIds) == $countDeletedMenu){
                $this->menuCatalogueRepository->deleteByWhere([
                    ['id', '=', $id],
                ]);
                // echo 4; die();
            }
            // echo 1; die();
            DB::commit();
            return true;
        }catch(\Exception $ex){
            DB::rollBack();
            echo $ex->getMessage();die();
            return false;
        }
    }

    // V68
    public function convertMenu($menuArray = null):array{
        $temp = [];
        $fields = ['name', 'canonical', 'order', 'id'];
        if(count($menuArray)){
            foreach($menuArray as $key => $val){
                foreach($fields as $field){
                    if($field == 'name' || $field == 'canonical'){
                        $temp[$field][] = $val->languages->first()->pivot->{$field};
                        // $temp[$field][] = $val->languages->first()->getOriginal('pivot_'.$field);
                    }else{
                        $temp[$field][] = $val->{$field};
                    }
                }
            }
        }
        return $temp;
    }

    // V70
    public function updateDrag($json, $menuCatalogueId, $parentId = 0){
        // dd($json);
        // dd(count($json)); // 3, 2
        if(count($json)){
            foreach($json as $key => $val){
                // dd($key);
                $update = [
                    'order' => count($json) - $key, // của 49 là 3, của 28 là 2, của 50 là 2, của 51 là 1, của 29 là 1
                    'parent_id'=> $parentId, // của 49 là 0, của 28 là 0, của 50 là 28, của 51 là 28, của 29 là 0
                ];

                $menu = $this->menuRepository->update($val['id'], $update);
                if(isset($val['children']) && count($val['children'])){
                    $this->updateDrag($val['children'], $menuCatalogueId, $val['id']);
                }
            }
            $this->nestedset();
        }
    }

    //V74 
    public function findMenuItemTranslate($menus, $languageSessionId, $languageTranslateId){
        $output = [];
        if(count($menus)){
            foreach($menus as $menu){

                // $detailMenu = $this->menuLanguageRepository->findByCondition([
                //     ['menu_id', '=', $menu->id],
                //     ['language_id', '=', $languageTranslateId]
                // ]);
                $condition=[
                    ['id', '=', $menu->id]
                ];
                $relation=[
                    'languages'=> function($query) use ($languageTranslateId){
                        $query->where('language_id', $languageTranslateId);
                    }
                ];
                $detailMenu = $this->menuRepository->findByConditionsWithRelation($condition, $relation);
                // dd($detailMenu);
                if(count($detailMenu) > 0){
                    foreach($detailMenu as $model){
                        $translateLanguage = $model->languages->first(); 

                        if(!is_null($translateLanguage)){
                            $menu->translate_name = $translateLanguage->getOriginal('pivot_name');
                            $menu->translate_canonical = $translateLanguage->pivot->canonical;
                            break;
                        }
                    }
                }
                $canonical = $menu->languages->first()->getOriginal('pivot_canonical');
                $router = $this->routerRepository->findByCondition([
                    ['canonical', '=', $canonical]
                ]);

                if (is_null($menu->translate_canonical)) {
                    if($router){
                        // dd($router);
                        $controller = explode('\\', $router->controller);
                        // dd($controller[4]);
                        $model = str_replace('Controller', '', $controller[4]);
                        // dd($model);
    
                        $repositoryInterfaceNamespace='\App\Repositories\\'.$model.'Repository';
                        if(class_exists($repositoryInterfaceNamespace)){
                            $repositoryInstance=app($repositoryInterfaceNamespace);
                        }
    
                        $condition = [
                            ['id', '=', $router->module_id],
                        ];
                        $relation = [
                            'languages' => function($query) use ($languageTranslateId){
                                $query->where('language_id', $languageTranslateId);
                            }
                        ];
                        // $order = ['order', 'desc'];
                        $translateObject = $repositoryInstance->findByConditionsWithRelation($condition, $relation);
                        // dd($translateObject);
    
                        if(!is_null($translateObject)){
                            foreach ($translateObject as $model) {
                                $translateLanguage = $model->languages->first();
                    
                                if (!is_null($translateLanguage)) {
                                    $menu->translate_name = $translateLanguage->getOriginal('pivot_name');
                                    $menu->translate_canonical = $translateLanguage->getOriginal('pivot_canonical');
                                    break;
                                }
                            }
                        }
                    }
                }
                $output[] = $menu;
            }
        }
        return $output;
    }

    // V74
    public function saveTranslateMenu($request, $languageTranslateId){
        DB::beginTransaction();
        try{
            $payload = $request->only('translate');
            // dd($payload);
            foreach($payload['translate']['name'] as $key => $val){
                if($val == null) continue;
                $temp=[
                    'language_id' => $languageTranslateId,
                    'name' => $val,
                    'canonical' => $payload['translate']['canonical'][$key]
                ];
                // dd($temp);
                $menu = $this->menuRepository->findById($payload['translate']['id'][$key]);
                $menu->languages()->detach($languageTranslateId, $menu->id);
                $this->menuRepository->createPivot($menu, $temp, 'languages');
            }
            // echo 1; die();
            DB::commit();
            return true;
        }catch(\Exception $ex){
            DB::rollBack();
            echo $ex->getMessage();die();
            return false;
        }
    }
}



