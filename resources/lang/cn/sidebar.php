<?php
    return[
        'module'=>[
            [
                'title'=>'文章',
                'icon'=>'fa fa-user',
                'name'=>['user', 'permission'],
                'subModule'=>[
                    [
                        'title'=>'文章组',
                        'route'=>'user/catalogue/index'
                    ],
                    [
                        'title'=>'文章',
                        'route'=>'user/index'
                    ],
                    [
                        'title'=>'权限',
                        'route'=>'permission/index'
                    ]
                ]
            ],
            [
                'title'=>'用户组',
                'icon'=>'fa fa-file',
                'name'=>['post'],
                'subModule'=>[
                    [
                        'title'=>'用户组',
                        'route'=>'post/catalogue/index'
                    ],
                    [
                        'title'=>'用户',
                        'route'=>'post/index'
                    ]
                ]
            ],
            [
                'title' => '产品管理',
                'icon' => 'fa fa-cube',
                'name' => ['attribute', 'product'],
                'subModule' => [
                    [
                        'title' => '属性组管理',
                        'route' => 'attribute/catalogue/index'
                    ],
                    [
                        'title' => '属性管理',
                        'route' => 'attribute/index'
                    ],
                    [
                        'title' => '产品组管理',
                        'route' => 'product/catalogue/index'
                    ],
                    [
                        'title' => '产品管理',
                        'route' => 'product/index'
                    ],
                ]
            ],
            [
                'title'=>'通用配置',
                'icon'=>'fa fa-sliders',
                'name'=>['language','generate','system'],
                'subModule'=>[
                    [
                        'title'=>'语言',
                        'route'=>'language/index'
                    ],
                    [
                        'title' => '模块管理',
                        'route' => 'generate/index'
                    ],
                    [
                        'title' => '系统配置',
                        'route' => 'system/index'
                    ]                                        
                ]
            ],
            [
                'title' => '菜单管理',
                'icon' => 'fa fa-bars',
                'name' => ['menu'],
                'subModule' => [
                    [
                        'title' => '菜单设置',
                        'route' => 'menu/index'
                    ],
                ]
            ],
            [
                'title' => '管理横幅和幻灯片',
                'icon' => 'fa fa-image',
                'name' => ['slide'],
                'subModule' => [
                    [
                        'title' => '幻灯片设置',
                        'route' => 'slide/index'
                    ],
                ]
            ],   
            [
                'title' => '管理小部件',
                'icon' => 'fa fa-cube',
                'name' => ['widget'],
                'subModule' => [
                    [
                        'title' => '小部件管理',
                        'route' => 'widget/index'
                    ]
                ]
            ],                     
        ]
    ];

