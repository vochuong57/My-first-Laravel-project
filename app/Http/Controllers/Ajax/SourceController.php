<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
//thêm thư viện tự tạo
use App\Repositories\Interfaces\SourceRepositoryInterface as SourceRepository;
//chèn thêm thư viện có sẵn Request để lấy thông tin value id từ option mà ajax đã thiết lập
use Illuminate\Http\Request;

class SourceController extends Controller
{
    protected $sourceRepository;
    
    public function __construct(SourceRepository $sourceRepository){
        $this->sourceRepository=$sourceRepository;
    }

    // V110
    public function getAllSource(){
        try {
            $sources = $this->sourceRepository->all();
    
            return response()->json([
                'error' => false,
                'data' => $sources,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'messages' => $e->getMessage(),
            ]);
        }
    }
}
