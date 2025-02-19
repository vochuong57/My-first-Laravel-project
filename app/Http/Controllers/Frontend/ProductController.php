<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\FrontendController;
use Illuminate\Http\Request;
use App\Repositories\Interfaces\ProductRepositoryInterface as ProductRepository;
use App\Repositories\Interfaces\ProductCatalogueRepositoryInterface as ProductCatalogueRepository;
use App\Services\Interfaces\ProductServiceInterface as ProductService;
use Illuminate\Support\Facades\DB;

class ProductController extends FrontendController
{
    protected $productRepository;
    protected $productCatalogueRepository;
    protected $productService;
    public function __construct(ProductRepository $productRepository, ProductService $productService, ProductCatalogueRepository $productCatalogueRepository){
        parent::__construct();
        $this->productRepository = $productRepository;
        $this->productService = $productService;
        $this->productCatalogueRepository = $productCatalogueRepository;
    }

    public function index($id, $request){
        // dd($id);
        // dd($this->language);
        // dd($this->getLanguage());
        $languageId = $this->getLanguage();

        $relation = $this->productService->getProductRelation($languageId);

        $product = $this->productRepository->findById($id, ['*'], $relation);
        // dd($product->toArray());
        $config = $this->config();
        $seo = seo($product);
        // dd($seo);

        $relation = [
            'languages' => function($query) use ($languageId){
                $query->where('language_id', $languageId);
            },
        ];
        $productCatalogue = $this->productCatalogueRepository->findById($product->product_catalogue_id,['*'], $relation);

        $breadcrumb = $this->productCatalogueRepository->breadcrumb($productCatalogue, $languageId);
        // dd($breadcrumb);
        
        return view('frontend.product.product.index', compact('config', 'languageId', 'seo', 'product', 'breadcrumb', 'productCatalogue'));
    }

    private function config(){
        return [];
    }
}
