<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
//chèn thêm thư viện có sẵn Request để lấy thông tin value của input mà ajax đã thiết lập
use Illuminate\Http\Request;
//thêm thư viện tự tạo
use App\Repositories\Interfaces\MenuRepositoryInterface as MenuRepository;
use App\Models\Language;
use App\Http\Requests\StoreMenuCatalogueRequest;
use App\Services\Interfaces\MenuCatalogueServiceInterface as MenuCatalogueService;

class MenuController extends Controller
{
    protected $menuRepository;
    protected $language;
    protected $menuCatalogueService;

    public function __construct(MenuRepository $menuRepository, MenuCatalogueService $menuCatalogueService){
       $this->menuRepository=$menuRepository;
       $this->menuCatalogueService=$menuCatalogueService;

        $this->middleware(function($request, $next) {
            $locale = app()->getLocale(); // vn cn en
            $language = Language::where('canonical', $locale)->first();
            $this->language = $language->id;
            return $next($request);
        });
    }

    public function createCatalogue(StoreMenuCatalogueRequest $request){
        // echo 1; die();
        $menuCatalogue = $this->menuCatalogueService->createMenuCatalogue($request);
        if($menuCatalogue !== false){
            return response()->json([
                'code' => 0,
                'message' => 'Tạo nhóm menu thành công',
                'data' => $menuCatalogue
            ]);
        }
        return response()->json([
            'code' => '1',
            'message' => 'Có vấn đề xảy ra, hãy thử lại',
        ]);
    }
}
