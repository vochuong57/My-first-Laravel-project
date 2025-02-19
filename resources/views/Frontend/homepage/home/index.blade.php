@extends('Frontend.homepage.layout', ['popup' => true])

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
                <div class="swiper-container category-swiper-container">
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
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
                            <div class="swiper-container bestseller-swipper-container">
                                <div class="swiper-button-next"></div>
                                <div class="swiper-button-prev"></div>
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

    @if(isset($widgets['news']) && !is_null($widgets['news']))
    @php
        $widgetDescription = $widgets['news'][0]->widgets['description'][$languageId];
    @endphp
    <div class="panel-deal page-setup">
        <div class="uk-container uk-container-center">
            <div class="panel-head">
                <div class="uk-flex uk-flex-middle uk-flex-space-between">
                    <h2 class="heading-1"><span>{!! $widgetDescription !!}</span></h2>
                </div>
            </div>
            <div class="panel-body">
                <div class="uk-grid uk-grid-medium">
                    @foreach($widgets['news']->toArray() as $key => $val)
                    <div class="uk-width-large-1-4">
                        @include('Frontend/component/product-item-2', ['val'=>$val])
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif

    @php
        $banner2 = App\Enums\SlideEnum::BANNER_2;
    @endphp
    @if(isset($slides[$banner2]) && !is_null($slides[$banner2]))
    <div class="uk-container uk-container-center">
        <div class="panel-group">
            <div class="panel-body">
                @foreach($slides[$banner2]->slideItems as $key => $val)
                @php
                    $name = $val[0]['name'] != null ? $val[0]['name'] : '';
                    $image = $val[0]['image'] ?? 'Backend/img/not-found.png';
                    $alt = $val[0]['alt'] != null ? $val[0]['alt'] : '';
                @endphp
                <div class="group-title">{!! $name !!}</div>
                <div class="group-description">{!! $alt !!}</div>
                <span class="image img-scaledowm"><img src="{{ $image }}" alt=""></span>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    @php
        $banner3 = App\Enums\SlideEnum::BANNER_3;
    @endphp
    @if(isset($slides[$banner3]) && !is_null($slides[$banner3]))
    <div class="panel-commit">
        <div class="uk-container uk-container-center">
            <div class="uk-grid uk-grid-medium">
                @foreach($slides[$banner3]->slideItems as $key => $val)
                @php
                    $name = $val[0]['name'] != null ? $val[0]['name'] : '';
                    $image = $val[0]['image'] ?? 'Backend/img/not-found.png';
                    $alt = $val[0]['alt'] != null ? $val[0]['alt'] : '';
                @endphp
                <div class="uk-width-large-1-5">
                    <div class="commit-item">
                        <div class="uk-flex uk-flex-middle">
                            <span class="image"><img src="{{ $image }}" alt=""></span>
                            <div class="info">
                                <div class="title">{!! $name !!}</div>
                                <div class="description">{!! $alt !!}</div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>
@endsection