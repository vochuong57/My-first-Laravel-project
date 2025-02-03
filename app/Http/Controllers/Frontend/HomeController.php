<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\FrontendController;
use Illuminate\Http\Request;
use App\Repositories\Interfaces\SlideRepositoryInterface as SlideRepository;

class HomeController extends FrontendController
{
    protected $slideRepository;

    public function __construct(SlideRepository $slideRepository){
        parent::__construct();
        $this->slideRepository = $slideRepository;
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
        return view('frontend.homepage.home.index', compact('config', 'slides', 'slideItems'));
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
