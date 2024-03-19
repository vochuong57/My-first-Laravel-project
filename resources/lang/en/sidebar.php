<?php
    return[
        'module'=>[
            [
                'title'=>'User Group',
                'icon'=>'fa fa-file',
                'name'=>['post'],
                'subModule'=>[
                    [
                        'title'=>'User Group',
                        'route'=>'post/catalogue/index'
                    ],
                    [
                        'title'=>'User',
                        'route'=>'post/index'
                    ]
                ]
            ],
            [
                'title'=>'Article',
                'icon'=>'fa fa-user',
                'name'=>['user'],
                'subModule'=>[
                    [
                        'title'=>'Article Group',
                        'route'=>'user/catalogue/index'
                    ],
                    [
                        'title'=>'Article',
                        'route'=>'user/index'
                    ]
                ]
            ],
            [
                'title'=>'General Configuration',
                'icon'=>'fa fa-file',
                'name'=>['language'],
                'subModule'=>[
                    [
                        'title'=>'Language',
                        'route'=>'language/index'
                    ],
                ]
            ]
        ]
    ];