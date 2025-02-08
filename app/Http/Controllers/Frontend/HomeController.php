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

        $slides = $this->slideService->findSlideByKeyword([SlideEnum::MAIN, SlideEnum::BANNER], $this->language);

        $widgets = $this->widgetService->getWidgetForFrontend([
            ['category', $this->language, $children = true],
            ['news', $this->language],
            ['category-highlight', $this->language],
            ['category-home', $this->language, $children = true],
        ]);
        // dd($widgets['category-home']->toArray());
        
        return view('frontend.homepage.home.index', compact('config', 'slides', 'widgets'));
    }


    private function config(){
        return [];
    }
}
