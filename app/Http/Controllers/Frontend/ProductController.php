<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\FrontendController;
use Illuminate\Http\Request;
use App\Repositories\Interfaces\ProductRepositoryInterface as ProductRepository;
use App\Repositories\Interfaces\ProductCatalogueRepositoryInterface as ProductCatalogueRepository;
use App\Repositories\Interfaces\ReviewRepositoryInterface as ReviewRepository;
use App\Services\Interfaces\ProductServiceInterface as ProductService;
use Illuminate\Support\Facades\DB;

class ProductController extends FrontendController
{
    protected $productRepository;
    protected $productCatalogueRepository;
    protected $productService;
    protected $reviewRepository;
    public function __construct(ProductRepository $productRepository, ProductService $productService, ProductCatalogueRepository $productCatalogueRepository, ReviewRepository $reviewRepository){
         // dd($productRepository);
        parent::__construct();
        $this->productRepository = $productRepository;
        $this->productService = $productService;
        $this->productCatalogueRepository = $productCatalogueRepository;
        $this->reviewRepository = $reviewRepository;
    }

    public function index($id, $request){
        // dd($id);
        // dd($this->language);
        // dd($this->getLanguage());
        $languageId = $this->getLanguage();

        $relation = $this->productService->getProductRelation($languageId);

        $product = $this->productRepository->findById($id, ['*'], $relation);
        // dd($product);
        $config = $this->config();
        $seo = seo($product);
        // dd($seo);

        $relation = [
            'languages' => function($query) use ($languageId){
                $query->where('language_id', $languageId);
            },
            'products' => function($query) use ($languageId){
            },
        ];
        $condition = [
            ['publish', '=', 2],
        ];
        $productCatalogue = $this->productCatalogueRepository->findById($product->product_catalogue_id,['*'], $relation);

        $breadcrumb = $this->productCatalogueRepository->breadcrumb($productCatalogue, $languageId);
        // dd($breadcrumb);
        if($product->attribute != null){
            $product->attributeInfo = $this->productService->getAttributeInfo($product, $languageId);
        }
        // dd($product);
        if($product->publish == 1){
            return redirect()->route('home.index');
        }

        // dd($this->productCatalogueRepository->all($relation));
        $categories = recursive($this->productCatalogueRepository->all($relation, $condition));
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

        $systems = $this->getSystem();

        $reviews = $this->reviewRepository->getReviews($id, 'App\Models\Product', 5, $product->languages->first()->pivot->canonical.config('apps.general.suffix'));
        // dd($reviews);
        // dd($product->reviews->count(), $product->reviews->sum('score'), $product->reviews->avg('score'));
        
        return view('frontend.product.product.index', compact('config', 'languageId', 'seo', 'product', 'breadcrumb', 'productCatalogue', 'categories', 'systems', 'reviews'));
    }

    private function config(){
        return [
            'css' => [
                'Frontend/resources/library/css/swiper-bundle.min.css',
                'Frontend/core/css/product.css',
            ],
            'js' => [
                'Frontend/resources/library/js/swiper-bundle.min.js',
                'Frontend/resources/cart.js',
                'Frontend/core/library/review.js',
            ],
        ];
    }
}
