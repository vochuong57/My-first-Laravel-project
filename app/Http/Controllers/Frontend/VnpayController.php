<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\FrontendController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VnpayController extends FrontendController
{
  
    public function __construct(){
        parent::__construct();
    }

    public function vnpay_return(){
        echo 123; die();
    }


}
