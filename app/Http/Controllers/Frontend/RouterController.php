<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\FrontendController;
use Illuminate\Http\Request;
use App\Repositories\Interfaces\RouterRepositoryInterface as RouterRepository;

class RouterController extends FrontendController
{
    protected $routerRepository;
    public function __construct(RouterRepository $routerRepository){
        parent::__construct();
        $this->routerRepository = $routerRepository;
    }

    public function index($canonical, Request $request){
        // dd($canonical);
        // dd($this->language);
        $condition = [
            ['canonical', '=', $canonical],
            ['language_id', '=', $this->language],
        ];
        $router = $this->routerRepository->findByCondition($condition);
        // dd($router);
        if(!is_null($router) && !empty($router)){
            $method = 'index';
            echo app($router->controller)->{$method}($router->module_id, $request, $page = 1);
        }
    }

    public function page($canonical, Request $request, $page){
        // dd($canonical);
        // dd($this->language);
        $router = $this->getRouter($canonical, $this->language);
        if(!is_null($router) && !empty($router)){
            $method = 'index';
            echo app($router->controller)->{$method}($router->module_id, $request, $page);
        }
    }

    private function getRouter($canonical, $languageId){
        $condition = [
            ['canonical', '=', $canonical],
            ['language_id', '=', $languageId],
        ];
        $router = $this->routerRepository->findByCondition($condition);
        return $router;
    }


    private function config(){
        return [];
    }
}
