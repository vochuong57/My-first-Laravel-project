<div class="filter-content">
    <div class="filter-overlay">
        <div class="filter-close">
            <i class="fi fi-rs-cross"></i>
        </div>
        <div class="filter-content-container">
            @if(!is_null($filters))
            @foreach($filters as $key => $val)
            @php
                $attributeCatalogueName = $val->languages->first()->pivot->name;
            @endphp
            <div class="filter-item">
                <div class="filter-heading">{{ $attributeCatalogueName }}</div>
                @if(count($val->attributes))
                <div class="filter-body">
                    @foreach($val->attributes as $attribute)
                    @php
                        $attributeName = $attribute->languages->first()->pivot->name;
                        $attributeId = $attribute->id;
                    @endphp
                    <div class="filter-choose">
                        <input type="checkbox" id="attribute-{{ $attributeId }}" value="{{ $attributeId }}" class="input-checkbox filtering">
                        <label for="attribute-{{ $attributeId }}">{{ $attributeName }}</label>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
            @endforeach
            @endif
            <div class="filter-item filter-price slider-box">
                <div class="filter-heading" for="priceRange">Lọc Theo Giá:</div>
                <div class="filter-price-content">
                    <input type="text" id="priceRange" readonly class="uk-hidden">
                    <div id="price-range" class="slider ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all">
                        <div class="ui-slider-range ui-widget-header ui-corner-all" style="left: 0%; width: 100%;"></div>
                        <span class="ui-slider-handle" style="left: 0%;"></span>
                        <span class="ui-slider-handle" style="left: 100%;"></span>
                    </div>
                </div>
                <div class="filter-input-value mt5">
                    <div class="uk-flex uk-flex-middle uk-flex-space-between">
                        <input type="text" class="min-value input-value" value="0đ">
                        <input type="text" class="max-value input-value" value="100.000.000đ">
                    </div>
                </div>
            </div>
            <div class="filter-item filter-category">
                <div class="filter-heading">Tình trạng sản phẩm</div>
                <div class="filter-body">
                    <div class="filter-choose">
                        <input type="checkbox" id="input-availble" name="stock[]" value="1" class="input-checkbox filtering">
                        <label for="input-availble">Còn hàng</label>
                    </div>
                    <div class="filter-choose">
                        <input type="checkbox" id="input-outstock" name="stock[]" value="0" class="input-checkbox filtering">
                        <label for="input-outstock">Hết hàng</label>
                    </div>
                </div>
            </div>
            <div class="filter-item filter-review">
                <div class="filter-heading">Lọc theo đánh giá</div>
                <div class="filter-choose uk-flex uk-flex-middle">
                    <input type="checkbox" id="input-rate-5" name="rate[]" value="5" class="input-checkbox filtering">
                    <label for="input-rate-5" class="uk-flex uk-flex-middle">
                        <div class="filter-start">
                            <i class="fi-rs-star"></i>
                            <i class="fi-rs-star"></i>
                            <i class="fi-rs-star"></i>
                            <i class="fi-rs-star"></i>
                            <i class="fi-rs-star"></i>
                        </div>
                    </label>
                    <span class="totalProduct ml5 mb5">(5)</span>
                </div>
                <div class="filter-choose uk-flex uk-flex-middle">
                    <input type="checkbox" id="input-rate-4" name="rate[]" value="4" class="input-checkbox filtering">
                    <label for="input-rate-4" class="uk-flex uk-flex-middle">
                        <div class="filter-start">
                            <i class="fi-rs-star"></i>
                            <i class="fi-rs-star"></i>
                            <i class="fi-rs-star"></i>
                            <i class="fi-rs-star"></i>
                        </div>
                    </label>
                    <span class="totalProduct ml5 mb5">(4)</span>
                </div>
                <div class="filter-choose uk-flex uk-flex-middle">
                    <input type="checkbox" id="input-rate-3" name="rate[]" value="3" class="input-checkbox filtering">
                    <label for="input-rate-3" class="uk-flex uk-flex-middle">
                        <div class="filter-start">
                            <i class="fi-rs-star"></i>
                            <i class="fi-rs-star"></i>
                            <i class="fi-rs-star"></i>
                        </div>
                    </label>
                    <span class="totalProduct ml5 mb5">(3)</span>
                </div>
                <div class="filter-choose uk-flex uk-flex-middle">
                    <input type="checkbox" id="input-rate-2" name="rate[]" value="2" class="input-checkbox filtering">
                    <label for="input-rate-2" class="uk-flex uk-flex-middle">
                        <div class="filter-start">
                            <i class="fi-rs-star"></i>
                            <i class="fi-rs-star"></i>
                        </div>
                    </label>
                    <span class="totalProduct ml5 mb5">(2)</span>
                </div>
                <div class="filter-choose uk-flex uk-flex-middle">
                    <input type="checkbox" id="input-rate-1" name="rate[]" value="1" class="input-checkbox filtering">
                    <label for="input-rate-1" class="uk-flex uk-flex-middle">
                        <div class="filter-start">
                            <i class="fi-rs-star"></i>
                        </div>
                    </label>
                    <span class="totalProduct ml5 mb5">(1)</span>
                </div>
            </div>
        </div>
    </div>
</div>