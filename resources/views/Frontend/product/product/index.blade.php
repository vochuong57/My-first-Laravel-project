@extends('Frontend.homepage.layout', ['popup' => false])

@section('content')
<div class="product-container">
    <div class="uk-container uk-container-center">
        @include('Frontend.component.breadcrumb', ['model' => $productCatalogue, 'breadcrumb' => $breadcrumb])
        <div class="panel-body">
            @php
                $album = json_decode($product->album) ?? [];
            @endphp
            <div class="uk-grid uk-grid-medium">
                <div class="uk-width-large-1-2">
                    <div class="popup-gallery">
                        @if(!empty($album))
                        <div class="swiper-container popup-swiper-container">
                            <div class="swiper-button-next"></div>
                            <div class="swiper-button-prev"></div>
                            <div class="swiper-wrapper big-pic">
                                <?php foreach($album as $key => $val){  ?>
                                <div class="swiper-slide">
                                    <a class="image img-cover"><img src="<?php echo $val?>" alt="<?php echo $val ?>"></a>
                                </div>
                                <?php }  ?>
                            </div>
                            <div class="swiper-pagination"></div>
                        </div>
                        <div class="swiper-container-thumbs popup-swiper-container-thumbs">
                            <div class="swiper-wrapper pic-list">
                                <?php foreach($album as $key => $val){  ?>
                                <div class="swiper-slide">
                                    <span  class="image img-cover"><img src="<?php echo $val?>" alt="<?php echo $val ?>"></span>
                                </div>
                                <?php }  ?>
                            </div>
                        </div>
                        @else
                        @php
                            $image = $product->image ?? 'Backend/img/not-found.png';
                        @endphp
                        <div class="swiper-container popup-swiper-container">
                            <div class="swiper-button-next"></div>
                            <div class="swiper-button-prev"></div>
                            <div class="swiper-wrapper big-pic">
                                <div class="swiper-slide">
                                    <a class="image img-cover"><img src="{{ $image }}"></a>
                                </div>
                            </div>
                            <div class="swiper-pagination"></div>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="uk-width-large-1-2">
                    <div class="popup-product">
                        @php
                            $title = $product->languages->first()->pivot->name;
                            $author = $product->users->name;
                            $price = getPrice($product->toArray());
                            $description = $product->languages->first()->pivot->description;
                        @endphp
                        <h2 class="title"><span>{{ $title }}</span>
                        </h2>
                        <div class="rating">
                            <div class="uk-flex uk-flex-middle">
                                <div class="author">{{ $author }}</div>
                                <div class="star">
                                    <?php for($i = 0; $i<=4; $i++){ ?>
                                    <i class="fa fa-star"></i>
                                    <?php }  ?>
                                </div>
                                <div class="rate-number">(65 reviews)</div>
                            </div>
                        </div>
                        <div class="price">
                            <div class="uk-flex uk-flex-bottom">
                                <div class="price-sale">{{ $price['price_sale'] }}đ</div>
                                <div class="price-old">{{ ($price['price_old'] == $price['price_sale']) ? '' : $price['price_old'].'đ' }}</div>
                            </div>
                        </div>
                        <div class="description">
                            {!! $description !!}
                        </div>
                        <div class="attribute">
                            <!-- <div class="attribute-item attribute-color">
                                <div class="label">Color: <span>Pink Gold</span></div>
                                <div class="uk-grid uk-grid-small">
                                    <?php //foreach($album as $key => $val){  ?>
                                    <div class="uk-width-large-1-10">
                                        <div class="color-item <?php //if($key == 1){
                                                //echo 'outstock';
                                            //}else if($key == 4){
                                                    //echo 'active';
                                            //}  ?>">
                                            <span class="image"><img src="<?php //echo $val; ?>" alt=""></span>
                                        </div>
                                    </div>
                                    <?php //}  ?>
                                </div>
                            </div> -->
                            <!-- attribute -->
                            @if(!empty($product->attributeInfo))
                            @foreach($product->attributeInfo as $attibuteCatalogue)
                            @php
                                $attributeCatalogueName = $attibuteCatalogue['languages'][0]['pivot']['name'];
                                $attributes = $attibuteCatalogue['attributes'];
                            @endphp
                            <div class="attribute-item attribute-color">
                                <div class="label">{{ $attributeCatalogueName }}: <span>S22</span></div>
                                @if(!empty($attributes))
                                <div class="attribute-value">
                                    @foreach($attributes as $attribute)
                                    @php
                                        $attributeName = $attribute['languages'][0]['pivot']['name'];
                                    @endphp
                                    <a href="" title="">{{ $attributeName }}</a>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                            @endforeach
                            @endif
                        </div>
                        <div class="quantity">
                            <div class="text">Quantity</div>
                            <div class="uk-flex uk-flex-middle">
                                <div class="quantitybox uk-flex uk-flex-middle">
                                    <div class="minus quantity-button"><img src="resources/img/minus.svg" alt=""></div>
                                    <input type="text" name="" value="1" class="quantity-text">
                                    <div class="plus quantity-button"><img src="resources/img/plus.svg" alt=""></div>
                                </div>
                                <div class="btn-group uk-flex uk-flex-middle">
                                    <div class="btn-item btn-1"><a href="" title="">Add To Cart</a></div>
                                    <div class="btn-item btn-2"><a href="" title="">Buy Now</a></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="uk-width-large-1-4">
        <div class="aside">
            @if(!is_null($categories))
            @foreach($categories as $key => $val)
            @php
                $name = $val['item']->languages->first()->pivot->name;
                $productCount = $val['products_count'];
            @endphp
            <div class="aside-panel aside-category">
                <div class="aside-heading">{{ $name }} - Tổng: {{ $productCount }}</div>
                @if(!is_null($val['children']) && !empty($val['children']))
                @foreach($val['children'] as $key => $val)
                @php
                    $childrenName = $val['item']->languages->first()->pivot->name;
                    $childrenCanonical = write_url($val['item']->languages->first()->pivot->canonical);
                    $childrenImage = $val['item']->image;
                    $productChildrenCount = $val['products_count'];
                @endphp
                <div class="aside-body">
                    <ul class="uk-list uk-clearfix">
                        <li class="mb20">
                            <div class="categories-item-1">
                                <a href="{{ $childrenCanonical }}" title="{{ $childrenName }}">
                                    <div class="uk-flex uk-flex-middle">
                                        <img src="{{ $childrenImage }}" alt="{{ $childrenName }}">
                                        <span class="title">{{ $childrenName }}</span>
                                    </div>
                                    <span class="total">{{ $productChildrenCount }}</span>
                                </a>
                            </div>
                        </li>
                    </ul>
                </div>
                @endforeach
                @endif
            </div>
            @endforeach
            @endif
        </div>
    </div>
</div>
@endsection