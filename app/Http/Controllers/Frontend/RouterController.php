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
            echo app($router->controller)->{$method}($router->module_id, $request);
        }
    }


    private function config(){
        return [];
    }
}
