<?php

return [
    'order_info' => 'Order Information',
    'have_account' => 'Do you have an account?',
    'login_now' => 'Login now',
    'enter_fullname' => 'Enter Full Name',
    'enter_phone' => 'Enter Phone Number',
    'enter_email' => 'Enter Email',
    'select_province' => 'Select Province/City',
    'select_district' => 'Select District',
    'select_ward' => 'Select Ward',
    'enter_address' => 'Enter Address',
    'additional_note' => 'Additional Notes (e.g., Deliver at 2:00 PM)',
    'method_title' => 'Payment Method',

    'method'=> [
        [
            'name' => 'cod',
            'image' => 'Frontend/resources/img/payment/logo-cod.svg',
            'title' => 'Cash on Delivery (COD)',
        ],
        // [
        //     'name' => 'zalo',
        //     'image' => 'Frontend/resources/img/payment/logo-zalopay.svg',
        //     'title' => 'Pay via ZaloPay Wallet',
        // ],
        [
            'name' => 'momo',
            'image' => 'Frontend/resources/img/payment/logo-momo.webp',
            'title' => 'Pay via Momo Wallet',
        ],
        // [
        //     'name' => 'shopee',
        //     'image' => 'Frontend/resources/img/payment/logo-shopee.webp',
        //     'title' => 'Pay via ShopeePay',
        // ],
        [
            'name' => 'vnpay',
            'image' => 'Frontend/resources/img/payment/logo-vnpay.webp',
            'title' => 'Pay via VNPAY Wallet',
        ],
        [
            'name' => 'paypal',
            'image' => 'Frontend/resources/img/payment/paypal.ico',
            'title' => 'Pay with PayPal',
        ],
    ],

    'return' => 'Not satisfied with our product? You can return it <a href="" title="Return Product">Here</a>',
    'checkout' => 'Checkout',

    'cart' => 'Shopping Cart',
    'apply' => 'Apply',
    'order_total' => 'Order Total',
    'discount' => 'Discount',
    'shipping_fee' => 'Shipping Fee',
    'total_amount' => 'Total Amount',
];