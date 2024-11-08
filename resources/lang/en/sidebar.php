<?php
    return[
        'module'=>[
            [
                'title'=>'User Group',
                'icon'=>'fa fa-user',
                'name'=>['user', 'permission'],
                'subModule'=>[
                    [
                        'title'=>'User Group',
                        'route'=>'user/catalogue/index'
                    ],
                    [
                        'title'=>'User',
                        'route'=>'user/index'
                    ],
                    [
                        'title'=>'Permission',
                        'route'=>'permission/index'
                    ]
                ]
            ],
            [
                'title'=>'Article',
                'icon'=>'fa fa-file',
                'name'=>['post'],
                'subModule'=>[
                    [
                        'title'=>'Article Group',
                        'route'=>'post/catalogue/index'
                    ],
                    [
                        'title'=>'Article',
                        'route'=>'post/index'
                    ]
                ]
            ],
            [
                'title' => 'Product Management',
                'icon' => 'fa fa-cube',
                'name' => ['attribute', 'product'],
                'subModule' => [
                    [
                        'title' => 'Attribute Group Management',
                        'route' => 'attribute/catalogue/index'
                    ],
                    [
                        'title' => 'Attribute Management',
                        'route' => 'attribute/index'
                    ],
                    [
                        'title' => 'Product Group Management',
                        'route' => 'product/catalogue/index'
                    ],
                    [
                        'title' => 'Product Management',
                        'route' => 'product/index'
                    ],
                ]
            ],
            [
                'title'=>'General Configuration',
                'icon'=>'fa fa-sliders',
                'name'=>['language','generate','system'],
                'subModule'=>[
                    [
                        'title'=>'Language',
                        'route'=>'language/index'
                    ],
                    [
                        'title' => 'Module Management',
                        'route' => 'generate/index'
                    ],    
                    [
                        'title' => 'System Configuration',
                        'route' => 'system/index'
                    ]                                    
                ]
            ],
            [
                'title' => 'Menu Management',
                'icon' => 'fa fa-bars',
                'name' => ['menu'],
                'subModule' => [
                    [
                        'title' => 'Menu Settings',
                        'route' => 'menu/index'
                    ],
                ]
            ],
            [
                'title' => 'Manage Banner & Slide',
                'icon' => 'fa fa-image',
                'name' => ['slide'],
                'subModule' => [
                    [
                        'title' => 'Slide Settings',
                        'route' => 'slide/index'
                    ],
                ]
            ],  
            [
                'title' => 'Manage Widget',
                'icon' => 'fa fa-cube',
                'name' => ['widget'],
                'subModule' => [
                    [
                        'title' => 'Widget Management',
                        'route' => 'widget/index'
                    ]
                ]
            ],                      
        ]
    ];