@extends('Frontend.homepage.layout')

@section('content')
<div id="homepage" class="homepage">
    @include('Frontend.component.slide')
    <div class="panel-category page-setup">
        <div class="uk-container uk-container-center">
            @if(isset($widgets['category-highlight']) && !is_null($widgets['category-highlight']))
            @php
                $widgetName = $widgets['category-highlight'][0]->widgets['name'];
            @endphp
            <div class="panel-head">
                <div class="uk-flex uk-flex-middle">
                    <h2 class="heading-1"><span>{{ $widgetName }}</span></h2>
                    <div class="category-children">
                        <ul class="uk-list uk-clearfix uk-flex uk-flex-middle">
                            @foreach($widgets['category-highlight'] as $key => $val)
                            @php
                                $name = $val->languages->first()->pivot->name;
                                $canonical = write_url($val->languages->first()->getOriginal('pivot_canonical'));
                            @endphp
                            <li class=""><a href="{{ $canonical }}" title="{{ $name }}">{{ $name }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            @endif

            @if(isset($widgets['category']) && !is_null($widgets['category']))
            <div class="panel-body">
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
                <div class="swiper-container">
                    <div class="swiper-wrapper">
                        @foreach($widgets['category'] as $key => $val)
                        @php
                            $name = $val->languages->first()->pivot->name;
                            $canonical = write_url($val->languages->first()->getOriginal('pivot_canonical'));
                            $image = $val->image;
                            $productCount = $val->products_count;
                        @endphp
                        <div class="swiper-slide">
                            <div class="category-item bg-<?php echo rand(1,7) ?>">
                                <a href="{{ $canonical }}" class="image img-scaledown img-zoomin"><img src="{{ $image }}" alt=""></a>
                                <div class="title"><a href="{{ $canonical }}" title="{{ $name }}">{{ $name }}</a></div>
                                <div class="total-product">{{ $productCount }} sản phẩm</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

        </div>
    </div>
    @php
        $banner = App\Enums\SlideEnum::BANNER;
    @endphp
    @if(isset($slides[$banner]) && !is_null($slides[$banner]))
    <div class="panel-banner">
        <div class="uk-container uk-container-center">
            <div class="panel-body">
                <div class="uk-grid uk-grid-medium">
                    @foreach($slides[$banner]->slideItems as $key => $val)
                    @php
                        $description = $val[0]['description'];
                        $image = $val[0]['image'];
                        $canonical = $val[0]['canonical'] != null ? write_url($val[0]['canonical']) : '#';
                    @endphp
                    <div class="uk-width-large-1-3">
                        <div class="banner-item">
                            <span class="image"><img src="{{ $image }}" alt=""></span>
                            <div class="banner-overlay">
                                <div class="banner-title">{!! $description !!}</div>
                                <a href="{{ $canonical }}" class="btn-shop" title="">Mua ngay</a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="panel-popular">
        <div class="uk-container uk-container-center">
            @if(isset($widgets['category-home']) && !is_null($widgets['category-home']))
            @foreach($widgets['category-home'] as $widget)
            <div class="panel-head">
                <div class="uk-flex uk-flex-middle uk-flex-space-between">
                    @php
                        $mainName = $widget->languages->first()->pivot->name;
                        $mainCanonical = write_url($widget->languages->first()->getOriginal('pivot_canonical'));
                        $childrens = $widget->childrens ?? null;
                    @endphp
                    <h2 class="heading-1"><span>{{ $mainName }}</span></h2>
                    @if(count($childrens[0]))
                    <div class="category-children">
                        <ul class="uk-list uk-clearfix uk-flex uk-flex-middle">
                        <li class=""><a href="{{ $mainCanonical }}" title="">Tất cả</a></li>
                            @foreach($childrens as $key => $val)
                            @php
                                $name = $val[0]->languages->first()->pivot->name;
                                $canonical = write_url($val[0]->languages->first()->getOriginal('pivot_canonical'));
                            @endphp
                            <li class=""><a href="{{ $canonical }}" title="">{{ $name }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                    @else
                    <div class="category-children">
                        <ul class="uk-list uk-clearfix uk-flex uk-flex-middle">
                            <li class=""><a href="{{ $mainCanonical }}" title="">Tất cả</a></li>
                        </ul>
                    </div>
                    @endif
                </div>
            </div>
            @if(count($widget->all_products))
            <div class="panel-body">
                <div class="uk-grid uk-grid-medium">
                    @foreach($widget->all_products as $key => $val)
                    <div class="uk-width-large-1-5 mb20">
                        @include('Frontend/component/product-item', ['val' => $val])
                    </div>
                    @endforeach
                </div>
            </div>
            @else
            <div class="panel-body">
                <div class="uk-grid uk-grid-medium">
                    <div class="uk-width-large-1-5 mb20">
                        <div class="no-data">Không có sản phẩm nào</div>
                    </div>
                </div>
            </div>
            @endif
            @endforeach
            @endif
        </div>
    </div>

    
    <div class="panel-bestseller">
        <div class="uk-container uk-container-center">
            @if(isset($widgets['category-highlight']) && 
                isset($widgets['bestseller']) && 
                !is_null($widgets['category-highlight']) && 
                !is_null($widgets['bestseller'])
            )
            <div class="panel-head">
                <div class="uk-flex uk-flex-middle uk-flex-space-between">
                    @php
                        
                        $widgetName = $widgets['bestseller'][0]->widgets['name'];
                        
                    @endphp
                    <h2 class="heading-1"><span>{{ $widgetName }}</span></h2>
                    <div class="category-children">
                        <ul class="uk-list uk-clearfix uk-flex uk-flex-middle">
                            @foreach($widgets['category-highlight'] as $key => $val)
                            @php
                                $name = $val->languages->first()->pivot->name;
                                $canonical = write_url($val->languages->first()->getOriginal('pivot_canonical'));
                            @endphp
                            <li class=""><a href="{{ $canonical }}" title="{{ $name }}">{{ $name }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            @endif
            <div class="panel-body">
                <div class="uk-grid uk-grid-medium">
                    @if(isset($widgets['bestseller']) && !is_null($widgets['bestseller']))
                   
                    @php
                        $widgetDescription = $widgets['bestseller'][0]->widgets['description'][$languageId];
                        $widgetAlbum = $widgets['bestseller'][0]->widgets['album'][0];
                    @endphp
                    <div class="uk-width-large-1-4">
                        <div class="best-seller-banner">
                            <a href="" class="image img-cover"><img src="{{ $widgetAlbum }}" alt=""></a>
                            <div class="banner-title">{!! $widgetDescription !!}</div>
                        </div>
                    </div>
                    <div class="uk-width-large-3-4">
                        <div class="product-wrapper">
                            <div class="swiper-button-next"></div>
                            <div class="swiper-button-prev"></div>
                            <div class="swiper-container">
                                <div class="swiper-wrapper">
                                    @foreach($widgets['bestseller']->toArray() as $key => $val)
                                    <div class="swiper-slide">
                                        @include('Frontend/component/product-item', ['val' => $val])
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="panel-deal page-setup">
        <div class="uk-container uk-container-center">
            <div class="panel-head">
                <div class="uk-flex uk-flex-middle uk-flex-space-between">
                    <h2 class="heading-1"><span>Giảm giá trong ngày</span></h2>
                </div>
            </div>
            <div class="panel-body">
                <div class="uk-grid uk-grid-medium">
                    <?php for($i = 0; $i<=3; $i++){  ?>
                    <div class="uk-width-large-1-4">
                        @include('Frontend/component/product-item-2')
                    </div>
                    <?php }  ?>
                </div>
            </div>
        </div>
    </div>
    <div class="uk-container uk-container-center">
        <div class="panel-group">
            <div class="panel-body">
                <div class="group-title">Stay home & get your daily <br> needs from our shop</div>
                <div class="group-description">Start Your Daily Shopping with Nest Mart</div>
                <span class="image img-scaledowm"><img src="resources/img/banner-9-min.png" alt=""></span>
            </div>
        </div>
    </div>
    <div class="panel-commit">
        <div class="uk-container uk-container-center">
            <div class="uk-grid uk-grid-medium">
                <div class="uk-width-large-1-5">
                    <div class="commit-item">
                        <div class="uk-flex uk-flex-middle">
                            <span class="image"><img src="resources/img/commit-1.png" alt=""></span>
                            <div class="info">
                                <div class="title">Giá ưu đãi</div>
                                <div class="description">Khi mua từ 500.000đ</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="uk-width-large-1-5">
                    <div class="commit-item">
                        <div class="uk-flex uk-flex-middle">
                            <span class="image"><img src="resources/img/commit-2.png" alt=""></span>
                            <div class="info">
                                <div class="title">Miễn phí vận chuyển</div>
                                <div class="description">Trong bán kính 2km</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="uk-width-large-1-5">
                    <div class="commit-item">
                        <div class="uk-flex uk-flex-middle">
                            <span class="image"><img src="resources/img/commit-3.png" alt=""></span>
                            <div class="info">
                                <div class="title">Ưu đãi</div>
                                <div class="description">Khi đăng ký tài khoản</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="uk-width-large-1-5">
                    <div class="commit-item">
                        <div class="uk-flex uk-flex-middle">
                            <span class="image"><img src="resources/img/commit-4.png" alt=""></span>
                            <div class="info">
                                <div class="title">Đa dạng </div>
                                <div class="description">Sản phẩm đa dạng</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="uk-width-large-1-5">
                    <div class="commit-item">
                        <div class="uk-flex uk-flex-middle">
                            <span class="image"><img src="resources/img/commit-5.png" alt=""></span>
                            <div class="info">
                                <div class="title">Đổi trả </div>
                                <div class="description">Đổi trả trong ngày</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection