<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\FrontendController;
use Illuminate\Http\Request;
use App\Repositories\Interfaces\SlideRepositoryInterface as SlideRepository;
use App\Services\Interfaces\WidgetServiceInterface as WidgetService;

class HomeController extends FrontendController
{
    protected $slideRepository;
    protected $widgetService;

    public function __construct(SlideRepository $slideRepository, WidgetService $widgetService){
        parent::__construct();
        $this->slideRepository = $slideRepository;
        $this->widgetService = $widgetService;
    }

    public function index(){
        // dd($this->language);
        $config = $this->config();

        $slides = $this->slideRepository->findByCondition(...$this->slideArgument());
        // dd($slides);
        if($slides != null){
            $slideItems = $slides->album[$this->language];
        }else{
            $slideItems = [];
        }
        // dd($slideItems);

        $widgets = [
            'category' => $this->widgetService->findWidgetByKeyword('category', $this->language, $children = true),
            'news' => $this->widgetService->findWidgetByKeyword('news', $this->language),
            'category-highlight' => $this->widgetService->findWidgetByKeyword('category-highlight', $this->language),
        ];
        // dd($widgets['category']);
        
        return view('frontend.homepage.home.index', compact('config', 'slides', 'slideItems', 'widgets'));
    }

    private function slideArgument(){
        return [
            $condition = [
                ['publish', '=', 2],
                ['keyword', '=', 'main-slide'],
            ],
        ];
    }

    private function config(){
        return [];
    }
}
