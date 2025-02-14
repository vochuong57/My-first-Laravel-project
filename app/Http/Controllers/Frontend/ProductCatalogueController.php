<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\FrontendController;
use Illuminate\Http\Request;
use App\Repositories\Interfaces\ProductCatalogueRepositoryInterface as ProductCatalogueRepository;

class ProductCatalogueController extends FrontendController
{
    protected $productCatalogueRepository;
    public function __construct(ProductCatalogueRepository $productCatalogueRepository){
        parent::__construct();
        $this->productCatalogueRepository = $productCatalogueRepository;
    }

    public function index($id, $languageId){
        // dd($id);
        // dd($languageId);
        $productCatalogue = $this->productCatalogueRepository->getProductCatalogueById($id, $languageId);
        // dd($productCatalogue);
        $config = $this->config();
        $seo = [
            'meta_title' => (!is_null($productCatalogue->meta_title)) ? $productCatalogue->meta_title : $productCatalogue->name,
            'meta_keyword' => $productCatalogue->meta_keyword ?? '',
            'meta_description' => (!is_null($productCatalogue->meta_description)) ? $productCatalogue->meta_description : cut_string_and_decode($productCatalogue->description, 168),
            'canonical' => write_url($productCatalogue->canonical),
            'meta_images' => (!is_null($productCatalogue->image)) ? $productCatalogue->image : 'Backend/img/not-found.png',
        ];
        // dd($seo);
        
        return view('frontend.product.catalogue.index', compact('config', 'languageId', 'seo', 'productCatalogue'));
    }


    private function config(){
        return [];
    }
}
