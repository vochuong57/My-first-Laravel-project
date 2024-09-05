<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
//chèn thêm thư viện có sẵn Request để lấy thông tin value của input mà ajax đã thiết lập
use Illuminate\Http\Request;
//thêm thư viện tự tạo
use App\Services\Interfaces\UserServiceInterface as UserService;
use App\Models\Language;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    protected $userService;
    protected $language;

    public function __construct(UserService $userService){
        $this->userService=$userService;

        $this->middleware(function($request, $next) {
            $locale = app()->getLocale(); // vn cn en
            $language = Language::where('canonical', $locale)->first();
            $this->language = $language->id;
            return $next($request);
        });
    }

    public function changeStatus(Request $request){
        //echo 123; die();
        $post=$request->input();
        //dd($post);
        $serviceInterfaceNamespace='\App\Services\\'.ucfirst($post['model']).'Service';
        if(class_exists($serviceInterfaceNamespace)){
            $serviceInstance=app($serviceInterfaceNamespace);
        }
        $flag=$serviceInstance->updateStatus($post);

        //$flag=$this->userService->updateStatus($post);

        return response()->json(['flag'=>$flag]);
    }
    public function changeStatusAll(Request $request){
        $post=$request->input();
        $serviceInterfaceNamespace='\App\Services\\'.ucfirst($post['model']).'Service';
        if(class_exists($serviceInterfaceNamespace)){
            $serviceInstance=app($serviceInterfaceNamespace);
        }
        $flag=$serviceInstance->updateStatusAll($post);
        return response()->json(['flag'=>$flag]);
    }
    public function deleteAll(Request $request){
        $post=$request->input();
        //dd($post);
        $serviceInterfaceNamespace='\App\Services\\'.ucfirst($post['model']).'Service';
        if(class_exists($serviceInterfaceNamespace)){
            $serviceInstance=app($serviceInterfaceNamespace);
        }
        $flag=$serviceInstance->deleteAll($post);
        return response()->json(['flag'=>$flag]);
    }

    // V63
    public function getMenu(Request $request){
        $model = $request->input('model');
        // dd($model);
        $repositoryInterfaceNamespace='\App\Repositories\\'.$model.'Repository';
        if(class_exists($repositoryInterfaceNamespace)){
            $repositoryInstance=app($repositoryInterfaceNamespace);
        }
        // dd($repositoryInstance);
        $page = ($request->input('page')) ?? 1; //V64
        // dd($page);
        $keyword = addslashes($request->input('keyword'));//V65
        // dd($keyword);
        $arguments = $this->paginationAgrument($model, $keyword);
        // dd($arguments);
        $object = $repositoryInstance->pagination(...array_values($arguments));
        return response()->json($object);
    }

    private function paginationAgrument(string $model = '', string $keyword): array{
        $model = Str::snake($model);
        $join = [
            [$model.'_language as tb2', 'tb2.'.$model.'_id', '=', $model.'s.id'],
        ];
        if(strpos($model,'_catalogue') == false){
            $join[] = [''.$model.'_catalogue_'.$model.' as tb3', ''.$model.'s.id', '=', 'tb3.'.$model.'_id'];
        }
        return [
            'select' => ['id', 'name', 'canonical'],
            'condition' => [
                'where' => [
                    ['tb2.language_id', '=', $this->language]
                ],
                'keyword' => $keyword
            ],
            'perpage' => 10,
            'paginationConfig' => [
                'path' => $model.'.index',
                'groupBy' => ['id', 'name', 'canonical']
            ],
            'orderBy' => [$model.'s.id', 'DESC'],
            'join' => $join,
            'relations' => [],
        ];
    }

    // V87
    private function loadClassInterface(string $model = '', $interface = 'Repository'){
        $repositoryInterfaceNamespace='\App\Repositories\\'.ucfirst($model).$interface;
        if(class_exists($repositoryInterfaceNamespace)){
            $repositoryInstance=app($repositoryInterfaceNamespace);
        }
        return $repositoryInstance;
    }
    // V87
    public function getModelObject(Request $request){
        $get = $request->input();
        // dd($get);
        $repositoryInstance = $this->loadClassInterface($get['model'], 'Repository');
        // dd($repositoryInstance);

        $condition = [];
        $languageId = $this->language;
        $relation = [
            'languages' => function($query) use ($languageId, $get){
                $query->where('language_id', $languageId);
                $query->where(Str::snake($get['model']).'_language.name', 'LIKE', '%'.$get['keyword'].'%');
            }
        ];
        // $order = ['order', 'desc'];
        $object = $repositoryInstance->findByConditionsWithRelation($condition, $relation);
        // dd($object);
        return response()->json($object);
    }
}
