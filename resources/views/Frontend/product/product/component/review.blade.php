<div class="review-container">
    <div class="panel-head">
        <h2 class="review-heading">Đánh giá sản phẩm</h2>
        <div class="review-statistic">
            <div class="uk-grid uk-grid-medium">
                <div class="uk-width-large-1-3">
                    <div class="review-averate review-item">
                        <div class="title">Đánh giá trung bình</div>
                        <div class="score">5/5</div>
                        <div class="start">
                            @for($i = 0; $i <= 4; $i++)
                                <i class="fa fa-star"></i>
                            @endfor
                        </div>
                        <div class="total-rate">157 đánh giá</div>
                    </div>
                </div>
                <div class="uk-width-large-1-3">
                    <div class="progress-block review-item">
                        @for($i = 5; $i >= 1; $i--)
                        <div class="progress-item">
                            <div class="uk-flex uk-flex-middle">
                                <span class="text">{{ $i }}</span>
                                <i class="fa fa-star"></i>
                                <div class="uk-progress">
                                    <div class="uk-progress-bar" style="width: 40%;"></div>
                                </div>
                                <span class="text">112</span>
                            </div>
                        </div>
                        @endfor
                    </div>
                </div>
                <div class="uk-width-large-1-3">
                    <div class="review-action review-item">
                        <div class="text">Bạn đã mua sản phẩm này chưa?</div>
                        <button class="btn btn-review">Gửi đánh giá</button>
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
            @for($i = 0; $i < 5; $i++)
            <div class="review-block-item">
                <div class="review-general uk-clearfix">
                <div class="review-avatar">
                    <span class="shae">VTC</span>
                </div>
                <div class="review-content-block">
                    <div class="review-content">
                        <div class="name">
                            <span>Võ Tiến Chương</span>
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
                            Chất lượng sản phẩm rất tốt, tôi rất hài lòng với dịch vụ của cửa hàng. Sẽ ủng hộ trong tương lai.
                        </div>
                        <div class="review-toolbox">
                            <div class="created_at">Ngày đăng: 07/06/2025</div>
                            <div class="review-reply">Trả lời</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="review-block-item uk-clearfix reply-block">
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
            </div>
            @endfor
        </div>
    </div>
</div>