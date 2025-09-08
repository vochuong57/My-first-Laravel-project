<?php
namespace App\Http\ViewComposers;

use Illuminate\View\View;
use App\Repositories\Interfaces\ProductCatalogueRepositoryInterface as ProductCatalogueRepository;
use App\Models\Language;

class ProductCatalogueComposer
{
    protected $language;
    protected $productCatalogue;

    public function __construct(ProductCatalogueRepository $productCatalogueRepository)
    {
        $this->language = app()->getLocale();
        $this->productCatalogueRepository = $productCatalogueRepository;
    }

    public function compose(View $view)
    {
        $languageDB = Language::where('canonical', $this->language)->first();
        $languageId = $languageDB->id;
        // dd($languageId);
        // xem hướng dẫn logic ở v145

        $relation = [
            'languages' => [
                'whereHas' => true, // chỉ lấy danh mục có bản dịch đúng ngôn ngữ
                'closure' => function($query) use ($languageId) {
                    $query->where('language_id', $languageId);
                }
            ],
            'products' => [
                'whereHas' => false, // không loại bỏ danh mục không có sản phẩm nào
                'closure' => function($query) use ($languageId) {
                    // Sai không dùng with vì sẽ lấy cả sản phẩm không có bản dịch đúng ngôn ngữ
                    // $query->with(['languages' => function($q) use ($languageId) {
                    //     $q->where('language_id', $languageId);
                    // }]);
                    // chỉ lấy sản phẩm có bản dịch đúng ngôn ngữ
                    $query->whereHas('languages', function($q) use ($languageId) {
                        $q->where('language_id', $languageId);
                    })->with(['languages' => function($q) use ($languageId) {
                        $q->where('language_id', $languageId);
                    }]);
                },
            ]
        ];

        $condition = [
            ['publish', '=', 2],
        ];
        // dd($this->productCatalogueRepository->allRelationWhereHas($relation, $condition)->toArray());
        $categories = recursive($this->productCatalogueRepository->allRelationWhereHas($relation, $condition));
        // dd($categories);

        if (!is_null($categories)) {
            foreach ($categories as $key => $val) {
                // dd($val);
                // Đếm số lượng sản phẩm cho danh mục cha
                $categories[$key]['products_count'] = modelCount($val, 'products');
                
                // Đếm số lượng sản phẩm cho các danh mục con
                foreach ($val['children'] as $childKey => $childVal) {
                    $categories[$key]['children'][$childKey]['products_count'] = modelCount($childVal, 'products');
                }
            }
        }
        
        // dd($categories);

        $view->with('categories', $categories);
    }
}