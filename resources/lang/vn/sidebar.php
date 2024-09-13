<?php
    return[
        'module'=>[
            [
                'title'=>'Quản lý thành viên',
                'icon'=>'fa fa-user',
                'name'=>['user', 'permission'],
                'subModule'=>[
                    [
                        'title'=>'QL Nhóm Thành Viên',
                        'route'=>'user/catalogue/index'
                    ],
                    [
                        'title'=>'QL Thành Viên',
                        'route'=>'user/index'
                    ],
                    [
                        'title'=>'QL Quyền',
                        'route'=>'permission/index'
                    ]
                ]
            ],
            [
                'title'=>'Quản lý bài viết',
                'icon'=>'fa fa-file',
                'name'=>['post'],
                'subModule'=>[
                    [
                        'title'=>'QL Nhóm Bài Viết',
                        'route'=>'post/catalogue/index'
                    ],
                    [
                        'title'=>'QL Bài Viết',
                        'route'=>'post/index'
                    ]
                ]
            ],
            [
                'title'=>'Quản lý sản phẩm',
                'icon'=>'fa fa-cube',
                'name'=>['attribute','product'],
                'subModule'=>[
                    [
                        'title'=>'QL Nhóm Thuộc Tính',
                        'route'=>'attribute/catalogue/index'
                    ],
                    [
                        'title'=>'QL Thuộc Tính',
                        'route'=>'attribute/index'
                    ],
                    [
                        'title'=>'QL Nhóm Sản Phẩm',
                        'route'=>'product/catalogue/index'
                    ],
                    [
                        'title'=>'QL Sản Phẩm',
                        'route'=>'product/index'
                    ],
                ]
            ],
            [
                'title'=>'Cấu hình chung',
                'icon'=>'fa fa-sliders',
                'name'=>['language', 'generate', 'system'],
                'subModule'=>[
                    [
                        'title'=>'QL Ngôn Ngữ',
                        'route'=>'language/index'
                    ],
                    [
                        'title'=>'QL Module',
                        'route'=>'generate/index'
                    ],
                    [
                        'title'=>'Cấu hình hệ thống',
                        'route'=>'system/index'
                    ],
                ]
            ],
            [
                'title'=>'QL Menu',
                'icon'=>'fa fa-bars',
                'name'=>['menu'],
                'subModule'=>[
                    [
                        'title'=>'Cài đặt Menu',
                        'route'=>'menu/index'
                    ],
                    
                ]
            ],
            [
                'title'=>'QL Banner & Slide',
                'icon'=>'fa fa-image',
                'name'=>['slide'],
                'subModule'=>[
                    [
                        'title'=>'Cài đặt Slide',
                        'route'=>'slide/index'
                    ],
                    
                ]
            ],
            [
                'title'=>'QL Widget',
                'icon'=>'fa fa-cube',
                'name'=>['widget'],
                'subModule'=>[
                    [
                        'title'=>'Quản lý Widget',
                        'route'=>'widget/index'
                    ]
                ]
            ],
            [
                'title'=>'Quản lý Marketing',
                'icon'=>'fa fa-bullhorn',
                'name'=>['promotion', 'coupon'],
                'subModule'=>[
                    [
                        'title'=>'QL Khuyến mãi',
                        'route'=>'promotion/index'
                    ],
                    // [
                    //     'title'=>'QL mã giảm giá',
                    //     'route'=>'coupon/index'
                    // ],
                ]
            ],
        ]
    ];