<?php
// V58
namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Classes\System;
use App\Services\Interfaces\SystemServiceInterface as SystemService;
use App\Repositories\Interfaces\SystemRepositoryInterface as SystemRepository;
use App\Models\Language;


class SystemController extends Controller
{
    protected $systemLibrary;
    protected $systemService;
    protected $systemRepository;
    protected $language;

    public function __construct(System $systemLibrary, SystemService $systemService, SystemRepository $systemRepository){
        $this->systemLibrary = $systemLibrary;
        $this->systemService=$systemService;
        $this->systemRepository=$systemRepository;

        $this->middleware(function($request, $next) {
            try {
                $locale = app()->getLocale(); // vn cn en
                $language = Language::where('canonical', $locale)->first();

                if (!$language) {
                    throw new \Exception('Vui lòng chọn ngôn ngữ trước khi truy cập bài viết.');
                }

                $this->language = $language->id;
               
            } catch (\Exception $e) {
                return redirect()->route('dashboard.index')->with('error', $e->getMessage());
            }
            return $next($request);
        });
    }

    public function index(){
        //echo 123; die();
        // dd($this->systemLibrary->config());
        $systemConfig = $this->systemLibrary->config();
        $condition=[
            ['language_id', '=', $this->language]
        ];
        $systems = convert_array($this->systemRepository->findByConditions($condition), 'keyword', 'content');
        // dd($systems);
        $config=$this->config();
        $config['seo']=__('messages.system');
        $template='Backend.system.index';
        return view('Backend.dashboard.layout', compact('template','config', 'systemConfig', 'systems'));
    }

    // V59
    public function create(Request $request){
        if($this->systemService->saveSystem($request, $this->language)){
            return redirect()->route('system.index')->with('success','Cập nhật bản ghi thành công');
        }
           return redirect()->route('system.index')->with('error','Cập nhật bản ghi thất bại. Hãy thử lại');
        
    }

    private function config(){
        return [
            'js'=>[
                'Backend/plugins/ckfinder/ckfinder.js',
                'Backend/libary/finder.js',
                'Backend/plugins/ckeditor/ckeditor.js',
            ]
            
        ];
    }
}
