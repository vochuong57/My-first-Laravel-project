<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Language;
use App\Models\System;

class FrontendController extends Controller
{
    protected $language;//được lấy từ extends Controller

    public function __construct()
    {
        $this->middleware(function($request, $next) {
            try {
                $locale = app()->getLocale(); // vn cn en
                // dd($locale);
                $language = Language::where('canonical', $locale)->first();

                if (!$language) {
                    throw new \Exception('Vui lòng chọn ngôn ngữ trước khi truy cập.');
                }

                $this->language = $language->id;
            } catch (\Exception $e) {
                return redirect()->route('home.index')->with('error', $e->getMessage());
            }
            return $next($request);
        });

    }

    public function getSystem(){
        $systems = System::where('language_id', $this->language ?? 1)->get();
        $systems = convert_array($systems, 'keyword', 'content');
        // dd($systems);
        return $systems;
    }

    public function getLanguage(){
        $locale = app()->getLocale();
        $language = Language::where('canonical', $locale)->first();
        $languageId = $language->id;
        return $languageId;
    }
}
