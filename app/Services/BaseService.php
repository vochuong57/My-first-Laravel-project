<?php

namespace App\Services;

use App\Services\Interfaces\BaseServiceInterface;
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
use App\Classes\Nestedsetbie;
use App\Repositories\Interfaces\RouterRepositoryInterface as RouterRepository;
use Illuminate\Support\Str;
use App\Models\Language;


/**
 * Class UserService
 * @package App\Services
 */
class BaseService implements BaseServiceInterface
{
    protected $nestedset;
    protected $routerRepository;
    public function __construct(RouterRepository $routerRepository){
        $this->routerRepository=$routerRepository;
    }

    public function currentLanguage(){
        $locale = app()->getLocale(); // vn cn en
        $language = Language::where('canonical', $locale)->first();

        return $language->id;
    }

    public function formatAlbum($request){
        return ($request->input('album') && !empty($request->input('album'))) ? json_encode($request->input('album')) : null;
    }
    
    public function nestedset(){
        $this->nestedset->Get();//gọi Get để lấy dữ liệu
        $this->nestedset->Recursive(0, $this->nestedset->Set());//gọi Recursive để tính toán lại các giá trị của từng node
        $this->nestedset->Action();//gọi đến Action để cập nhật lại các giá trị lft rgt
    }
    
    public function formatRouterPayload($request, $model, $controllerName, $languageId){
        $payloadRouter=[
            'canonical' => Str::slug($request->input('canonical')),
            'module_id' => $model->id,
            'language_id' => $languageId,
            'controller' => 'App\Http\Controllers\Frontend\\'.$controllerName.''
        ];
        return $payloadRouter;
    }
    public function createRouter($request, $model, $controllerName, $languageId){
        $payloadRouter = $this->formatRouterPayload($request, $model, $controllerName, $languageId);
        $this->routerRepository->create($payloadRouter);
    }
    public function updateRouter($request, $model, $controllerName, $languageId){
        $payloadRouter = $this->formatRouterPayload($request, $model, $controllerName, $languageId);
        $condition=[
            ['module_id', '=', $model->id],
            ['language_id', '=', $languageId],
            ['controller', '=', 'App\Http\Controllers\Frontend\\'.$controllerName.'']
        ];
        $router = $this->routerRepository->findByCondition($condition);
        $this->routerRepository->update($router->id, $payloadRouter);
    }
}
