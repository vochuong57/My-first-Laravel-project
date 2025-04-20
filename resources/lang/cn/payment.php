<?php

return [
    'order_info' => '订单信息',
    'have_account' => '您有账号吗?',
    'login_now' => '立即登录',
    'enter_fullname' => '输入姓名',
    'enter_phone' => '输入电话号码',
    'enter_email' => '输入电子邮件',
    'select_province' => '选择省/市',
    'select_district' => '选择区/县',
    'select_ward' => '选择街道/乡镇',
    'enter_address' => '输入地址',
    'additional_note' => '附加说明 (例如: 下午2:00送达)',
    'method_title' => '支付方式',

    'method'=> [
        [
            'name' => 'cod',
            'image' => 'Frontend/resources/img/payment/logo-cod.svg',
            'title' => '货到付款 (COD)',
        ],
        // [
        //     'name' => 'zalo',
        //     'image' => 'Frontend/resources/img/payment/logo-zalopay.svg',
        //     'title' => '通过ZaloPay钱包支付',
        // ],
        [
            'name' => 'momo',
            'image' => 'Frontend/resources/img/payment/logo-momo.webp',
            'title' => '通过Momo钱包支付',
        ],
        // [
        //     'name' => 'shopee',
        //     'image' => 'Frontend/resources/img/payment/logo-shopee.webp',
        //     'title' => '通过ShopeePay支付',
        // ],
        [
            'name' => 'vnpay',
            'image' => 'Frontend/resources/img/payment/logo-vnpay.webp',
            'title' => '通过VNPAY钱包支付',
        ],
        [
            'name' => 'paypal',
            'image' => 'Frontend/resources/img/payment/paypal.ico',
           'title' => '通过PayPal支付',
        ],
    ],

    'return' => '对我们的产品不满意？您可以在 <a href="" title="退货">这里</a> 退货',
    'checkout' => '结账',

    'cart' => '购物车',
    'apply' => '应用',
    'order_total' => '订单总额',
    'discount' => '折扣',
    'shipping_fee' => '运费',
    'total_amount' => '总金额',
];