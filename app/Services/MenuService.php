<?php

namespace App\Services;

use App\Services\Interfaces\MenuServiceInterface;
use App\Repositories\Interfaces\MenuRepositoryInterface as MenuRepository;
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
    protected $controllerName = 'MenuController';
    protected $language;    

    public function __construct(MenuRepository $menuRepository){
        $this->menuRepository=$menuRepository;
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
    public function createMenu($request, $languageId){
        DB::beginTransaction();
        try{
            
            $payload = $request->only('menu', 'menu_catalogue_id', 'type');
            // dd($payload);

            if(count($payload['menu']['name'])){
                foreach($payload['menu']['name'] as $key => $val){
                    $menuArray = [
                        'menu_catalogue_id' => $payload['menu_catalogue_id'],
                        'type' => $payload['type'],
                        'order' => $payload['menu']['order'][$key],
                        'user_id' => Auth::id()
                    ];
                    // dd($menuArray);
                    $menu = $this->menuRepository->create($menuArray);
                    // dd($menu);
                    if($menu->id > 0){
                        $menu->languages()->detach($languageId, $menu->id);
                        $payloadLanguage = [
                            'language_id' => $languageId,
                            'name' => $val,
                            'canonical' => $payload['menu']['canonical'][$key],
                        ];
                        $language1 = $this->menuRepository->createPivot($menu,$payloadLanguage,'languages');
                        // dd($language1);
                    }
                }
                // die();
                $this->nestedset();
            }
            // die();

            DB::commit();
            return true;
        }catch(\Exception $ex){
            DB::rollBack();
            echo $ex->getMessage();die();
            return false;
        }
    }

    public function updateMenu($id, $request, $languageId){
        DB::beginTransaction();
        try{
            
            
            DB::commit();
            return true;
        }catch(\Exception $ex){
            DB::rollBack();
            echo $ex->getMessage();//die();
            return false;
        }
    }
   
    public function deleteMenu($id, $languageId){
        DB::beginTransaction();
        try{
            // //echo '123'; die();
            // //Đầu tiền xóa đi bản dịch đó khỏi menu_language
            // $where=[
            //     ['menu_id', '=', $id],
            //     ['language_id', '=', $languageId]
            // ];
            // $this->menuLanguageRepository->deleteByWhere($where);

            // //Tiếp theo xóa đi canonical của bản dịch đó khỏi routers
            // $findRouter=[
            //     ['module_id', '=', $id],
            //     ['language_id', '=', $languageId],
            //     ['controller', '=', 'App\Http\Controllers\Frontend\MenuController'],
            // ];
            // $this->routerRepository->deleteByWhere($findRouter);

            // //Sau khi xóa xong thì nó tiếp tục kiểm tra xem thử là còn cái menu_id đó trong menu_language không
            // $condition=[
            //     ['menu_id', '=', $id]
            // ];
            // $flag = $this->menuLanguageRepository->findByCondition($condition);

            // //Nếu không tìm thấy nữa thì ta mới tiến hành xóa đi Menu
            // if(!$flag){
            //     $menu=$this->menuRepository->forceDelete($id);
            // }
            DB::commit();
            return true;
        }catch(\Exception $ex){
            DB::rollBack();
            echo $ex->getMessage();//die();
            return false;
        }
    }
}



