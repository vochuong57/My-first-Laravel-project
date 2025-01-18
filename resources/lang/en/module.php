<?php
use App\Enums\PromotionEnum;

return [
    'model' => [
        'PostCatalogue' => 'Post Category',
        'Post' => 'Post',
        'ProductCatalogue' => 'Product Category',
        'Product' => 'Product'
    ],
    'type'=>[
        'dropdown-menu' => 'Dropdown Menu',
        'mega-menu' => 'Mega Menu'
    ],
    // V74
    'effect' => [
        'fade' => 'Fade',
        'cube' => 'Cube',
        'coverflow' => 'Coverflow',
        'flip' => 'Flip',
        'cards' => 'Cards',
        'creative' => 'Creative'
    ],
    'navigate' => [
        'hide' => 'Hide navigation bar',
        'dots' => 'Show as dots',
        'thumbnails' => 'Show as Thumbnails'
    ],

    // V97
    'promotion' => [
        PromotionEnum::ORDER_AMOUNT_RANGE => 'Discount by total order value',
        PromotionEnum::PRODUCT_AND_QUANTITY => 'Discount by individual product',
        'product_quantity_range' => 'Discount by product quantity',
        'goods_discount_by_quantity' => 'Buy product - discount product'
    ],
    // V100
    'item' => [
    'Product' => 'Product Version',
    'ProductCatalogue' => 'Product Category',
    ],
    // V110
   'gender' => [
        [
            'id' => 1,
            'name' => 'Male'
        ],
        [
            'id' => 2,
            'name' => 'Female'
        ],
    ],
    'day' => array_map(function($value){
        return ['id' => $value - 1, 'name' => $value];
    }, range(1, 31)),
    // V113
    'applyValue' => [
        [
            'id' => 'staff_take_care_customer',
            'name' => 'Responsible Staff',
        ],
        [
            'id' => 'customer_group',
            'name' => 'Customer Group',
        ],
        [
            'id' => 'customer_gender',
            'name' => 'Gender',
        ],
        [
            'id' => 'customer_birthday',
            'name' => 'Date of Birth',
        ],
    ],

];
