<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
//chèn thêm thư viện có sẵn Request để lấy thông tin value của input mà ajax đã thiết lập
use Illuminate\Http\Request;
//thêm thư viện tự tạo
use App\Repositories\Interfaces\SlideRepositoryInterface as SlideRepository;
use App\Services\Interfaces\SlideServiceInterface as SlideService;

class SlideController extends Controller
{
    protected $slideRepository;
    protected $slideService;

    public function __construct(SlideRepository $slideRepository, SlideService $slideService){
        $this->slideRepository=$slideRepository;
        $this->slideService=$slideService;
    }

    // V79
    public function drag(Request $request){
        $post = $request->only('slideId', 'languageSessionId', 'items');
        // dd($post);

        $slideId = $post['slideId'];

        $languageSessionId = $post['languageSessionId'];

        $items = $post['items'];
        // dd($items);

        $flag = $this->slideService->updateDrag($slideId, $items, $languageSessionId);
    }
}
