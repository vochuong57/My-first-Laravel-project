<?php

namespace App\Services;

use App\Services\Interfaces\MenuCatalogueServiceInterface;
use App\Repositories\Interfaces\MenuCatalogueRepositoryInterface as MenuCatalogueRepository;
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

/**
 * Class UserService
 * @package App\Services
 */
class MenuCatalogueService extends BaseService implements MenuCatalogueServiceInterface
{
    protected $menuCatalogueRepository;
    protected $controllerName = 'MenuCatalogueController';

    public function __construct(MenuCatalogueRepository $menuCatalogueRepository){
        $this->menuCatalogueRepository=$menuCatalogueRepository;
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
        // $menuCatalogues=$this->menuCatalogueRepository->pagination(
        //     $this->paginateSelect(),
        //     $condition,
        //     $perpage,
        //     ['path'=> 'menuCatalogue/index', 'groupBy' => $this->paginateSelect()],
        //     ['menuCatalogues.id', 'DESC'],
        //     [
        //         ['menuCatalogue_language as tb2','tb2.menuCatalogue_id','=','menuCatalogues.id'],//dùng cho hiển thị nội dung table
        //         ['menuCatalogue_catalogue_menuCatalogue as tb3','menuCatalogues.id', '=', 'tb3.menuCatalogue_id']//dùng cho whereRaw lọc tìm kiếm bài viết theo nhóm bài viêt
        //     ],
        //     ['menuCatalogue_catalogues'],//là function menuCatalogue_catalogues của Model/menuCatalogue
        //     $this->whereRaw($request),
        // );
        // //dd($menuCatalogues);
        
        return [];
    }
    public function createMenuCatalogue($request){
        DB::beginTransaction();
        try{
            // dd($request->only('name','keyword'));
            $payload = $request->only('name','keyword');
            $payload['user_id'] = Auth::id();
            $payload['keyword'] = Str::slug( $payload['keyword']);
            // dd($payload);

            $menuCatalogue = $this->menuCatalogueRepository->create($payload);
            // dd($menuCatalogue->id);
            DB::commit();
            return [
                'name' => $menuCatalogue->name,
                'id' => $menuCatalogue->id
            ];
        }catch(\Exception $ex){
            DB::rollBack();
            echo $ex->getMessage();//die();
            return false;
        }
    }

    public function updateMenuCatalogue($id, $request){
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
   
    public function deleteMenuCatalogue($id){
        DB::beginTransaction();
        try{
            // //echo '123'; die();
            // //Đầu tiền xóa đi bản dịch đó khỏi menuCatalogue_language
            // $where=[
            //     ['menuCatalogue_id', '=', $id],
            //     ['language_id', '=', $languageId]
            // ];
            // $this->menuCatalogueLanguageRepository->deleteByWhere($where);

            // //Tiếp theo xóa đi canonical của bản dịch đó khỏi routers
            // $findRouter=[
            //     ['module_id', '=', $id],
            //     ['language_id', '=', $languageId],
            //     ['controller', '=', 'App\Http\Controllers\Frontend\MenuCatalogueController'],
            // ];
            // $this->routerRepository->deleteByWhere($findRouter);

            // //Sau khi xóa xong thì nó tiếp tục kiểm tra xem thử là còn cái menuCatalogue_id đó trong menuCatalogue_language không
            // $condition=[
            //     ['menuCatalogue_id', '=', $id]
            // ];
            // $flag = $this->menuCatalogueLanguageRepository->findByCondition($condition);

            // //Nếu không tìm thấy nữa thì ta mới tiến hành xóa đi MenuCatalogue
            // if(!$flag){
            //     $menuCatalogue=$this->menuCatalogueRepository->forceDelete($id);
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


