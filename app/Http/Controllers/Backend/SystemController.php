<?php
// V58
namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Classes\System;

class SystemController extends Controller
{
    protected $systemLibrary;

    public function __construct(System $systemLibrary){
        $this->systemLibrary = $systemLibrary;
    }

    public function index(){
        //echo 123; die();
        // dd($this->systemLibrary->config());
        $system = $this->systemLibrary->config();
        $config=$this->config();
        $config['seo']=__('messages.system');
        $template='Backend.system.index';
        return view('Backend.dashboard.layout', compact('template','config', 'system'));
    }

    private function config(){
        return [
            'js'=>[
                'Backend/plugins/ckfinder/ckfinder.js',
                'Backend/libary/finder.js'
            ]
            
        ];
    }
}
