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
    
    //V192 V193
    public function filter(Request $request){
        $option = $request->all();
        // dd($option);
        // dd($option['reviewable_link']);
        if(isset($option['score'])){
            if($option['score'] === 'null'){
                $option['score'] = null;
            }
        }else{
            $option['score'] = null;
        }
        $response = $this->reviewRepository->fillterReviews($option['reviewable_id'], $option['reviewable_type'], $option['perpage'], $option['reviewable_link'], $option['score'], $option['sort']);
        // dd($response);
        return response()->json(
            [
                'code' => 200,
                'html' => $this->htmlReviews($response),
                'current_page' => $response->currentPage(),
            ]
        );
    }

    private function htmlReviews($reviews){
    $systems = $this->getSystem();
    $html = '';

    if($reviews->isNotEmpty()){
        foreach($reviews as $review){
            $html .= '
                <div class="review-block-item">
                    <div class="review-general uk-clearfix">
                        <div class="review-avatar">
                            <span class="shae">' . getReviewName($review->fullname) . '</span>
                        </div>
                        <div class="review-content-block">
                            <div class="review-content">
                                <div class="name">
                                    <span>' . $review->fullname . '</span>
                                    <span class="review-buy">
                                        <i class="fa fa-check"></i>
                                        Đã mua hàng tại ' . $systems['homepage_brand'] . '
                                    </span>
                                </div>
                                ' . generateStarRating($review->score) . '
                                <div class="description">
                                    ' . $review->description . '
                                </div>
                                <div class="review-toolbox">
                                    <div class="created_at">Ngày đăng: ' . convertDateTime($review->created_at, 'd/m/Y H:i:s') . '</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            ';
        }
        $html .= '
                <div class="uk-flex uk-flex-center">
                    '.$reviews->links('pagination::bootstrap-4').'
                </div>';
    } else {
        $html = '<div class="no-review"><span class="text">Chưa có đánh giá nào. Hãy là người đánh giá đầu tiên!</span></div>';
    }

    return $html;
}

}
