<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\FrontendController;
use Illuminate\Http\Request;
use App\Repositories\Interfaces\SlideRepositoryInterface as SlideRepository;
use App\Services\Interfaces\WidgetServiceInterface as WidgetService;
use App\Services\Interfaces\SlideServiceInterface as SlideService;
use App\Enums\SlideEnum;

class HomeController extends FrontendController
{
    protected $slideRepository;
    protected $widgetService;
    protected $slideService;

    public function __construct(SlideRepository $slideRepository, WidgetService $widgetService, SlideService $slideService){
        parent::__construct();
        $this->slideRepository = $slideRepository;
        $this->widgetService = $widgetService;
        $this->slideService = $slideService;
    }

    public function index(){
        // dd($this->language);
        $config = $this->config();

        $slides = $this->slideService->findSlideByKeyword([SlideEnum::MAIN, SlideEnum::BANNER, SlideEnum::BANNER_2, SlideEnum::BANNER_3], $this->language);

        $widgets = $this->widgetService->getWidgetForFrontend([
            ['category', $this->language, $children = true],
            ['news', $this->language],
            ['category-highlight', $this->language],
            ['category-home', $this->language, $children = true],
            ['bestseller', $this->language, $children = true],
        ]);
        // dd($widgets['news']->toArray());

        $languageId = $this->language;

        $systems = $this->getSystem();
        // dd($systems);
        $seo = [
            'meta_title' => $systems['seo_meta_title'],
            'meta_keyword' => $systems['seo_meta_keyword'],
            'meta_description' => $systems['seo_meta_description'],
            'canonical' => config('app.url'),
            'meta_images' => $systems['seo_meta_images'],
        ];
        // dd($seo);
        
        return view('frontend.homepage.home.index', compact('config', 'slides', 'widgets', 'languageId', 'seo'));
    }


    private function config(){
        return [
            'js' => [
                'Frontend/resources/cart.js',
            ],
        ];
    }
}
