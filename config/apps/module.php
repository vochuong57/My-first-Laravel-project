<?php
    return[
        'module'=>[
            [
                'title'=>'Quản lý thành viên',
                'icon'=>'fa fa-user',
                'subModule'=>[
                    [
                        'title'=>'QL Nhóm Thành Viên',
                        'route'=>'user/catalogue/index'
                    ],
                    [
                        'title'=>'QL Thành Viên',
                        'route'=>'user/index'
                    ]
                ]
            ],
            [
                'title'=>'Quản lý bài viết',
                'icon'=>'fa fa-file',
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
            ]
        ]
    ];