<?php
    // dd($val);
    $category = getCategory($val, 'post_catalogues');

    $product = getProduct($val);

    $image = image($val['image']);
?>
<div class="product-item-2 product">
    <a href="" class="image img-cover"><img src="{{ $image ?? 'Backend/img/not-found.png' }}" alt=""></a>
    <div class="info">
        <div class="info-wrapper">
            <div class="category-title"><a href="{{ $category['canonical'] ?? '' }}" title="{{ $category['name'] ?? '' }}">{{ $category['name'] ?? '' }}</a></div>
            <h3 class="title"><a href="{{ $product['canonical'] }}" title="{{ $product['name'] }}">{{ $product['name'] }}</a></h3>
            <div class="uk-flex uk-flex-middle uk-flex-space-between">
                <div class="price uk-flex uk-flex-bottom">
                    <div class="description">{!! $product['description'] !!}</div>
                </div>
            </div>
        </div>
    </div>
</div>