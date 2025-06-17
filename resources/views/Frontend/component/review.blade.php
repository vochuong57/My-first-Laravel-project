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