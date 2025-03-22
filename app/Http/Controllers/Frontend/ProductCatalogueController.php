<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\FrontendController;
use Illuminate\Http\Request;
use App\Repositories\Interfaces\ProductCatalogueRepositoryInterface as ProductCatalogueRepository;
use App\Services\Interfaces\ProductServiceInterface as ProductService;

class ProductCatalogueController extends FrontendController
{
    protected $productCatalogueRepository;
    protected $productService;
    public function __construct(ProductCatalogueRepository $productCatalogueRepository, ProductService $productService){
        parent::__construct();
        $this->productCatalogueRepository = $productCatalogueRepository;
        $this->productService = $productService;
    }

    public function index($id, $request, $page){
        // dd($id);
        // dd($this->language);
        // dd($this->getLanguage());
        $languageId = $this->getLanguage();

        $relation = [
            'languages' => function($query) use ($languageId){
                $query->where('language_id', $languageId);
            },
        ];
        $productCatalogue = $this->productCatalogueRepository->findById($id,['*'], $relation);
        // dd($productCatalogue);

        $config = $this->config();
        $seo = seo($productCatalogue, $page);
        // dd($seo);
        $breadcrumb = $this->productCatalogueRepository->breadcrumb($productCatalogue, $languageId);
        // dd($breadcrumb);

        $products = $this->productService->paginate($request, $languageId, $productCatalogue, ['path' => $productCatalogue->languages->first()->pivot->canonical], $page);
        // dd($products);
        $productIds = $products->pluck('id')->toArray();
        // dd($productIds);
        if(count($productIds)){
            $products = $this->productService->combineProductWithPromotion($productIds, $languageId, $products);
        }
        // dd($products->items());
        
        return view('frontend.product.catalogue.index', compact('config', 'languageId', 'seo', 'productCatalogue', 'breadcrumb', 'products'));
    }


    private function config(){
        return [
            'css' => [
                // 'https://unpkg.com/swiper/swiper-bundle.min.css',
                'Frontend/resources/library/css/swiper-bundle.min.css',
            ],
            'js' => [
                'Frontend/resources/cart.js',
                 // 'https://unpkg.com/swiper/swiper-bundle.min.js',
                 'Frontend/resources/library/js/swiper-bundle.min.js',
            ],
        ];
    }
}
