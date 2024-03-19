<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(){
        
    }

    public function index(){
        //echo 123; die();
        $config=$this->config();
        $template='Backend.home.index';
        return view('Backend.dashboard.layout', compact('template','config'));
    }

    private function config(){
        return [
            'js'=>[
                'Backend/js/plugins/flot/jquery.flot.js',
                'Backend/js/plugins/flot/jquery.flot.tooltip.min.js',
                'Backend/js/plugins/flot/jquery.flot.spline.js',
                'Backend/js/plugins/flot/jquery.flot.resize.js',
                'Backend/js/plugins/flot/jquery.flot.pie.js',
                'Backend/js/plugins/flot/jquery.flot.symbol.js',
                'Backend/js/plugins/flot/jquery.flot.time.js',
                'Backend/js/plugins/peity/jquery.peity.min.js',
                'Backend/js/demo/peity-demo.js',
                'Backend/js/inspinia.js',
                'Backend/js/plugins/pace/pace.min.js',
                'Backend/js/plugins/jvectormap/jquery-jvectormap-2.0.2.min.js',
                'Backend/js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js',
                'Backend/js/plugins/easypiechart/jquery.easypiechart.js',
                'Backend/js/plugins/sparkline/jquery.sparkline.min.js',
                'Backend/js/demo/sparkline-demo.js'
            ]
            
        ];
    }
}
