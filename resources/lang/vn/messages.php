<?php
return[
    //POST CATALOGUE
    'postCatalogue' => [
        'index'=>[
            'title'=> 'Quản lý nhóm bài viết',
        ],
        'create'=>[
            'title'=>'Thêm mới nhóm bài viết',
            'btnTitle'=>'Thêm mới'
        ],
        'edit'=>[
            'title'=>'Cập nhật nhóm bài viết',
            'btnTitle'=>'Cập nhật'
        ],
        'delete'=>[
            'title'=>'Xóa nhóm bài viết',
            'btnDelete'=>'Xóa dữ liệu'
        ],
    ],
    
    // aside
    'parent'=>'Chọn danh mục cha',
    'parentNotice'=>'Chọn root nếu không có danh mục cha',
    'image'=>'Chọn ảnh đại diện',
    'advance'=>'Cấu hình nâng cao',
    
    // Dashboard/fillter
    'perpage'=>'bản ghi',
    'search'=>'Tìm kiếm',
    'searchInput'=>'Nhập từ khóa bạn muốn tìm kiếm...',
    
    // general
    'general'=>'Thông tin chung',
    'title_general'=>'Tiêu đề:',
    'description'=>'Mô tả ngắn:',
    'upload'=>'Upload nhiều hình ảnh',
    'content'=>'Nội dung:',
    
    // seo
    'seo'=>'Cấu hình seo',
    'seo_title'=>'Bạn chưa có tiêu đề SEO',
    'seo_canonical'=>'https://duong-dan-cua-ban.html',
    'seo_description'=>'Bạn chưa có mô tả SEO',
    'seo_meta_title'=>'Tiêu đề SEO',
    'character'=>'ký tự',
    'seo_meta_keyword'=>'Từ khóa SEO',
    'seo_meta_description'=>'Mô tả SEO',
    'seo_meta_canonical'=>'Đường dẫn',
    
    // table
    'tablePostCatalogue_brand'=>'Danh sách nhóm bài viết',
    'tablePostCatalogue_name'=>'Tên nhóm',
    'tablePostCatalogue_status'=>'Tình trạng',
    'tablePostCatalogue_action'=>'Thao tác',

    'publish'=>[
        '0'=>'Chọn tình trạng',
        '1'=>'Không xuất bản',
        '2'=>'Xuất bản'
    ],
    'follow'=>[
        '0'=>'Chọn điều hướng',
        '1'=>'nofollow',
        '2'=>'fllow'
    ],
    
    //destroy
    'destroy_panel_description_postCatalogue_1'=>'- Bạn đang muốn xóa nhóm bài viết có tên là:',
    'destroy_panel_description_1'=>'- Lưu ý',
    'destroy_panel_description_2'=>'KHÔNG THỂ',
    'destroy_panel_description_postCatalogue_2'=>'khôi phục nhóm bài viết sau khi xóa.',
    'destroy_panel_description_3'=>'Hãy chắc chắn bạn muốn thực hiện chức năng này',
    'destroyPostCatalogue_name'=>'Tên nhóm bài viết:',
    
    //toolbox
    'toolbox_name'=>'toàn bộ',
    'toolboxDestroyPostCatalogue'=>'Bạn có chắc nhắn muốn xóa những nhóm bài viết này?',

    //PERMISSION
    'permission' => [
        'index'=>[
            'title'=> 'Quản lý quyền',
        ],
        'create'=>[
            'title'=>'Thêm mới quyền',
            'btnTitle'=>'Thêm mới'
        ],
        'edit'=>[
            'title'=>'Cập nhật quyền',
            'btnTitle'=>'Cập nhật'
        ],
        'delete'=>[
            'title'=>'Xóa quyền',
            'btnDelete'=>'Xóa dữ liệu'
        ],
    ],
    // toolbox
    'toolboxDestroyPermission'=>'Bạn có chắc nhắn muốn xóa những quyền này?',

    // table
    'tablePermission_brand'=>'Danh sách quyền',
    'tablePermission_name'=>'Tên quyền',
    'tablePermission_action'=>'Thao tác',

    // destroy
    'destroy_panel_description_permission_1'=>'- Bạn đang muốn xóa quyền có tên là:',
    'destroy_panel_description_permission_2'=>'khôi phục quyền sau khi xóa.',

    // store
    'note_permission' => '- Nhập thông tin chung của quyền',
    'note_1' => '- Lưu ý: những trường đánh dấu',
    'note_2' => 'là bắt buộc',
    'permission_title' => 'Tên quyền:',

    //USER CATALOGUE
    'userCatalogue' => [
        'index'=>[
            'title'=> 'Quản lý nhóm thành viên',
        ],
        'create'=>[
            'title'=>'Thêm mới nhóm thành viên',
            'btnTitle'=>'Thêm mới'
        ],
        'edit'=>[
            'title'=>'Cập nhật nhóm thành viên',
            'btnTitle'=>'Cập nhật'
        ],
        'delete'=>[
            'title'=>'Xóa nhóm thành viên',
            'btnDelete'=>'Xóa dữ liệu'
        ],
        'permission'=>[
            'title'=>'Cập nhật quyền',
            'btnTitle'=>'Cập nhật'
        ]
    ],

    //table
    'tableUserCatalogue_brand'=>'Danh sách nhóm người dùng',
    'tableUserCatalogue_name'=>'Tên nhóm',
    'tableUserCatalogue_count'=>'Số thành viên',
    'tableUserCatalogue_description'=>'Mô tả',
    'tableUserCatalogue_publish'=>'Tình trạng',
    'tableUserCatalogue_action'=>'Thao tác',

    //toolbox
    'toolboxDestroyUserCatalogue'=>'Bạn có chắc nhắn muốn xóa những nhóm thành viên viết này?',

    //filter
    'permission_name' => 'Phân quyền',

    //store
    'note_userCatalogue' => '- Nhập thông tin chung của nhóm thành viên',
    'userCatalogue_title' => 'Tên nhóm:',
    'note' => 'Ghi chú:',

    // destroy
    'destroy_panel_description_userCatalogue_1'=>'- Bạn đang muốn xóa nhóm thành viên có tên là:',
    'destroy_panel_description_userCatalogue_2'=>'khôi phục nhóm thành viên sau khi xóa.',
  
    //LANGUAGE
    'language' => [
        'index'=>[
            'title'=> 'Quản lý ngôn ngữ',
        ],
        'create'=>[
            'title'=>'Thêm mới ngôn ngữ',
            'btnTitle'=>'Thêm mới'
        ],
        'edit'=>[
            'title'=>'Cập nhật ngôn ngữ',
            'btnTitle'=>'Cập nhật'
        ],
        'delete'=>[
            'title'=>'Xóa ngôn ngữ',
            'btnDelete'=>'Xóa dữ liệu'
        ],
    ],

    //table
    'tableLanguage_brand'=>'Danh sách ngôn ngữ',
    'tableLanguage_image'=>'Ảnh',
    'tableLanguage_name'=>'Tên ngôn ngữ',
    'tableLanguage_note'=>'Ghi chú',
    'tableLanguage_publish'=>'Tình trạng',
    'tableLanguage_action'=>'Thao tác',

    //toolbox
    'toolboxDestroyLanguage'=>'Bạn có chắc nhắn muốn xóa những ngôn ngữ viết này?',

    //store
    'note_language' => '- Nhập thông tin chung của ngôn ngữ',
    'language_title' => 'Tên ngôn ngữ:',
    'language_avatar' => 'Ảnh đại diện:',
    'language_note' => 'Ghi chú:',

    // destroy
    'destroy_panel_description_language_1'=>'- Bạn đang muốn xóa ngôn ngữ có tên là:',
    'destroy_panel_description_language_2'=>'khôi phục ngôn ngữ sau khi xóa.',
    
];