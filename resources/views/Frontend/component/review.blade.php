@php
    $totalReviews = $model->reviews->count() ?? 0;
    $totalRate = round($model->reviews->avg('score') ?? 0, 1);
    //dd($totalRate, $totalReviews);
    $starPercent = $totalRate ? ($totalRate / 5) * 100 : 0 ?? 0;
@endphp
<div class="review-container">
    <div class="panel-head">
        <h2 class="review-heading">Đánh giá sản phẩm</h2>
        <div class="review-statistic">
            <div class="uk-grid uk-grid-medium">
                <div class="uk-width-large-1-3">
                    <div class="review-averate review-item">
                        <div class="title">Đánh giá trung bình</div>
                        <div class="score">{{ $totalRate }}/5</div>
                        <div class="star-rating" style="--star-width: {{ $starPercent }}%;">
                            <div class="stars"></div>
                        </div>
                        <div class="total-rate">{{ $totalReviews }} đánh giá</div>
                    </div>
                </div>
                <div class="uk-width-large-1-3">
                    <div class="progress-block review-item">
                        @for($i = 5; $i >= 1; $i--)
                        @php
                            $starPercent = ($totalReviews != 0) ? $model->reviews->where('score', $i)->count() / $totalReviews * 100 : 0;
                            $countStar = $model->reviews->where('score', $i)->count() ?? 0;
                        @endphp
                        <div class="progress-item">
                            <div class="uk-flex uk-flex-middle">
                                <span class="text">{{ $i }}</span>
                                <i class="fa fa-star"></i>
                                <div class="uk-progress">
                                    <div class="uk-progress-bar" style="width: {{ $starPercent }}%;"></div>
                                </div>
                                <span class="text">{{ $countStar }}</span>
                            </div>
                        </div>
                        @endfor
                    </div>
                </div>
                <div class="uk-width-large-1-3">
                    <div class="review-action review-item">
                        <div class="text">Bạn đã mua sản phẩm này chưa?</div>
                        <button class="btn btn-review" data-uk-modal="{target:'#review'}">Gửi đánh giá</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="panel-body">
        <div class="review-filter">
            <div class="uk-flex uk-flex-middle">
                <span class="filter-text">Lọc xem theo: </span>
                <div class="filter-item">
                    <span>Đã mua hàng</span>
                    <span>5 sao</span>
                    <span>4 sao</span>
                    <span>3 sao</span>
                    <span>2 sao</span>
                    <span>1 sao</span>
                </div>
            </div>
        </div>
        <div class="review-wrapper">
            @if($reviews->isNotEmpty())
            @foreach($reviews as $review)
            <div class="review-block-item">
                <div class="review-general uk-clearfix">
                <div class="review-avatar">
                    <span class="shae">{{ getReviewName($review->fullname) }}</span>
                </div>
                <div class="review-content-block">
                    <div class="review-content">
                        <div class="name">
                            <span>{{ $review->fullname }}</span>
                            <span class="review-buy">
                                <i class="fa fa-check"></i>
                                Đã mua hàng tại {{ $systems['homepage_brand']}}
                            </span>
                        </div>
                        <!-- <div class="review-star">
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                        </div> -->
                        {!! generateStarRating($review->score) !!}
                        <div class="description">
                            {{ $review->description }}
                        </div>
                        <div class="review-toolbox">
                            <div class="created_at">Ngày đăng: {{ convertDateTime($review->created_at, 'd/m/Y H:i:s') }}</div>
                            <!-- <div class="review-reply">Trả lời</div> -->
                        </div>
                    </div>
                </div>
            </div>
            <!-- <div class="review-block-item uk-clearfix reply-block">
                <div class="review-avatar">
                    <span class="shae">NA</span>
                </div>
                <div class="review-content-block">
                    <div class="review-content">
                        <div class="name">
                            <span>Nguyệt Anh</span>
                            <span class="review-buy">
                                <i class="fa fa-check"></i>
                                Đã mua hàng tại {{ $systems['homepage_brand']}}
                            </span>
                        </div>
                        <div class="review-star">
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                        </div>
                        <div class="description">
                            Cảm ơn VTC!
                        </div>
                        <div class="review-toolbox">
                            <div class="created_at">Ngày đăng: 07/06/2025</div>
                            <div class="review-reply">Trả lời</div>
                        </div>
                    </div>
                </div>
            </div> -->
            @endforeach
            <div class="uk-flex uk-flex-center">
                {{ $reviews->links('pagination::bootstrap-4') }}
            </div>
            @else
            <div class="no-review">
                <span class="text">Chưa có đánh giá nào. Hãy là người đánh giá đầu tiên!</span>
            </div>
            @endif
            </div>
            </div>
            </div>
        </div>
    </div>
</div>

<div id="review" class="uk-modal">
    <div class="uk-modal-dialog">
        <a href="" class="uk-modal-close uk-close"></a>
        <div class="review-popup-wrapper">
            <div class="panel-head">Đánh giá sản phẩm</div>
            <div class="panel-body">
                <div class="product-review">
                    <span class="image img-scaledown">
                        <img src="{{ $model->image ?? 'Backend/img/not-found.png' }}" alt="{{ $model->name }}">
                    </span>
                    <div class="product-title">{{ $model->languages->first()->pivot->name }}</div>
                    <div class="popup-rating">
                        <div class="rate">
                            <input type="radio" id="star5" name="rate" value="5" />
                            <label for="star5" title="text">5 stars</label>
                            <input type="radio" id="star4" name="rate" value="4" />
                            <label for="star4" title="text">4 stars</label>
                            <input type="radio" id="star3" name="rate" value="3" />
                            <label for="star3" title="text">3 stars</label>
                            <input type="radio" id="star2" name="rate" value="2" />
                            <label for="star2" title="text">2 stars</label>
                            <input type="radio" id="star1" name="rate" value="1" />
                            <label for="star1" title="text">1 star</label>
                        </div>
                        <!-- <div class="rate-text">Không thích</div> -->
                    </div>
                    <div class="score-error"></div>
                    <div class="review-form">
                        <form action="" class="uk-form form">
                            <div class="form-row">
                                <textarea name="" id="" class="review-textarea"></textarea>
                                <div class="error description-error"></div>
                            </div>
                            <div class="form-row">
                                <div class="uk-flex uk-flex-middle">
                                    <div class="gender-item uk-flex uk-flex-middle">
                                        <input type="radio" class="gender" value="male" id="male">
                                        <label for="">Nam</label>
                                    </div>
                                    <div class="gender-item uk-flex uk-flex-middle">
                                        <input type="radio" class="gender" value="female" id="female">
                                        <label for="">Nữ</label>
                                    </div>
                                </div>
                                <div class="error gender-error"></div>
                            </div>
                            <div class="uk-grid uk-grid-medium">
                                <div class="uk-width-large-1-2">
                                    <div class="form-row">
                                        <input type="text" name="fullname" value="" class="review-text" placeholder="Nhập họ và tên">
                                        <div class="error fullname-error"></div>
                                    </div>
                                </div>
                                <div class="uk-width-large-1-2">
                                    <div class="form-row">
                                        <input type="text" name="phone" value="" class="review-text" placeholder="Nhập số điện thoại">
                                        <div class="error phone-error"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <input type="text" name="email" value="" class="review-text" placeholder="Nhập vào email">
                                <div class="error email-error"></div>
                            </div>
                            <button type="button" class="btn-send-review">Hoàn tất</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<input type="hidden" class="reviewable_type" value="{{ $reviewable }}">
<input type="hidden" class="reviewable_id" value="{{ $model->id }}">
<input type="hidden" class="review_parent_id" value="0">
<input type="hidden" class="reviewable_link" value="{{ $model->languages->first()->pivot->canonical.config('apps.general.suffix') }}">