<?php
// file này tự có khi mới tạo project laravel
namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected $language;

    public function __construct(){
        $this->language = session('app_locale');
    }

    public function currentLanguage(){
        return 1;
    }
}
