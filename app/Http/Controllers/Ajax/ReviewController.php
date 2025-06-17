<?php

namespace App\Http\Controllers\Ajax;
use App\Http\Requests\StoreReviewRequest;

//chèn thêm thư viện có sẵn Request để lấy thông tin value của input mà ajax đã thiết lập
use Illuminate\Http\Request;
//thêm thư viện tự tạo
use App\Http\Controllers\FrontendController;
use App\Services\Interfaces\ReviewServiceInterface as ReviewService;
use App\Repositories\Interfaces\ReviewRepositoryInterface as ReviewRepository;

class ReviewController extends FrontendController
{
    protected $reviewService;
    protected $reviewRepository;

    public function __construct(ReviewService $reviewService, ReviewRepository $reviewRepository){
        parent::__construct();
        $this->reviewService=$reviewService;
        $this->reviewRepository=$reviewRepository;
    }

    public function create(StoreReviewRequest $request){
        $post = $request->except('_token');
        // dd($post);
        $response = $this->reviewService->create($post);

        return response()->json($response);
    }
    
}
