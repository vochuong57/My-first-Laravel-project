<?php
namespace App\Http\ViewComposers;

use Illuminate\View\View;
use App\Repositories\Interfaces\MenuCatalogueRepositoryInterface as MenuCatalogueRepository;
use App\Repositories\Interfaces\MenuRepositoryInterface as MenuRepository;
use App\Models\Language;

class MenuComposer
{
    protected $language;
    protected $menuCatalogueRepository;
    protected $menuRepository;

    public function __construct(MenuCatalogueRepository $menuCatalogueRepository, MenuRepository $menuRepository)
    {
        $this->language = app()->getLocale();
        $this->menuCatalogueRepository = $menuCatalogueRepository;
        $this->menuRepository = $menuRepository;
    }

    public function compose(View $view)
    {
        // dd($this->language);
        $language = Language::where('canonical', $this->language)->first();

        $condition = [
            ['publish', '=', 2],
        ];
        $menuCatalogues=$this->menuCatalogueRepository->findByConditions($condition);
        // dd($menuCatalogues);
        //truy vấn thông tin
        $temp = [];
        foreach($menuCatalogues as $menuCatalogue){
            $condition = [
                ['menu_catalogue_id', '=', $menuCatalogue->id],
            ];
            $languageId = $language->id;
            $relation = [
                'languages' => function($query) use ($languageId){
                    $query->where('language_id', $languageId);
                }
            ];
            $order = ['order', 'desc'];
            $menus=$this->menuRepository->findByConditionsWithRelation($condition, $relation, $order);
            // dd($menus);
            $menus = recursive($menus);
            // dd($menus);
            if($menuCatalogue->keyword == 'main-menu'){
                $menus = frontend_recursive_menu($menus);
            }
            // dd($menus);
            $temp[$menuCatalogue->keyword] = $menus;
        }
        // dd($temp['menu-footer']);

        $view->with('menus', $temp);
    }
}