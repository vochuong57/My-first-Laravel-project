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
                'title'=>'Cấu hình chung',
                'icon'=>'fa fa-file',
                'name'=>['language'],
                'subModule'=>[
                    [
                        'title'=>'QL Ngôn Ngữ',
                        'route'=>'language/index'
                    ],
                ]
            ]
        ]
    ];