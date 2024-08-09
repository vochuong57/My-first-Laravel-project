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
    'children'=>'Chọn danh mục con nếu có',
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

    // album
    'album'=>'Album Ảnh',
    'pickAlbum'=>'Chọn Album',
    'adviseAlbum'=>'Sử dụng nút chọn hình hoặc click vào đây để thêm hình ảnh',
    
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
    
    //translate
    'translate' => [
        'index'=>[
            'title'=> 'Quản lý dịch thuật',
            'btnTitle' => 'Cập nhật'
        ]
    ],

    //generate
    'generate' => [
        'index'=>[
            'title'=> 'Quản lý module',
        ],
        'create'=>[
            'title'=>'Thêm mới module',
            'btnTitle'=>'Thêm mới'
        ],
        'edit'=>[
            'title'=>'Cập nhật module',
            'btnTitle'=>'Cập nhật'
        ],
        'delete'=>[
            'title'=>'Xóa module',
            'btnDelete'=>'Xóa dữ liệu'
        ],
    ],

    //table
    'tableGenerate_brand'=>'Danh sách Module',
    'tableGenerate_image'=>'Ảnh',
    'tableGenerate_name'=>'Tên Module',
    'tableGenerate_action'=>'Thao tác',

    //store
    'note_generate' => '- Nhập thông tin chung của Module',
    'generate_title' => 'Tên Module:',
    'generate_avatar' => 'Ảnh đại diện:',
    'generate_note' => 'Ghi chú:',
    'generate_schema1' => 'Schema:',
    'generate_schema2' => 'Schema 2:',
    'generate_moduleType' => 'Loại Module:',
    'generate_schema' => 'Thông tin Schema',
    'generate_note_schema' => 'Nhập thông tin Schema',
    'generate_sidebar_module' => 'Tên chức năng:',
    'generate_path' => 'Đường dẫn:',

    //ATTRIBUTE CATALOGUE
    'attributeCatalogue' => [
        'index'=>[
            'title'=> 'Quản lý nhóm thuộc tính',
        ],
        'create'=>[
            'title'=>'Thêm mới nhóm thuộc tính',
            'btnTitle'=>'Thêm mới'
        ],
        'edit'=>[
            'title'=>'Cập nhật nhóm thuộc tính',
            'btnTitle'=>'Cập nhật'
        ],
        'delete'=>[
            'title'=>'Xóa nhóm thuộc tính',
            'btnDelete'=>'Xóa dữ liệu'
        ],
    ],

    //table
    'tableAttributeCatalogue_brand'=>'Danh sách nhóm thuộc tính',
    'tableAttributeCatalogue_name'=>'Tiêu đề',
    'tableAttributeCatalogue_status'=>'Tình trạng',
    'tableAttributeCatalogue_action'=>'Thao tác',

    //ATTRIBUTE
    'attribute' => [
        'index'=>[
            'title'=> 'Quản lý thuộc tính',
        ],
        'create'=>[
            'title'=>'Thêm mới thuộc tính',
            'btnTitle'=>'Thêm mới'
        ],
        'edit'=>[
            'title'=>'Cập nhật thuộc tính',
            'btnTitle'=>'Cập nhật'
        ],
        'delete'=>[
            'title'=>'Xóa thuộc tính',
            'btnDelete'=>'Xóa dữ liệu'
        ],
    ],

    //table
    'tableAttribute_brand'=>'Danh sách thuộc tính',
    'tableAttribute_name'=>'Tiêu đề',
    'tableAttribute_pos'=>'Vị trí',
    'tableAttribute_status'=>'Tình trạng',
    'tableAttribute_action'=>'Thao tác',
    'tableAttribute_displayCatalogue'=>'Nhóm thuộc tính',

    //PRODUCT CATALOGUE
    'productCatalogue' => [
        'index'=>[
            'title'=> 'Quản lý nhóm sản phẩm',
        ],
        'create'=>[
            'title'=>'Thêm mới nhóm sản phẩm',
            'btnTitle'=>'Thêm mới'
        ],
        'edit'=>[
            'title'=>'Cập nhật nhóm sản phẩm',
            'btnTitle'=>'Cập nhật'
        ],
        'delete'=>[
            'title'=>'Xóa nhóm sản phẩm',
            'btnDelete'=>'Xóa dữ liệu'
        ],
    ],

    //table
    'tableProductCatalogue_brand'=>'Danh sách nhóm sản phẩm',
    'tableProductCatalogue_name'=>'Tiêu đề',
    'tableProductCatalogue_status'=>'Tình trạng',
    'tableProductCatalogue_action'=>'Thao tác',

    //PRODUCT
    'product' => [
        'index'=>[
            'title'=> 'Quản lý sản phẩm',
        ],
        'create'=>[
            'title'=>'Thêm mới sản phẩm',
            'btnTitle'=>'Thêm mới'
        ],
        'edit'=>[
            'title'=>'Cập nhật sản phẩm',
            'btnTitle'=>'Cập nhật'
        ],
        'delete'=>[
            'title'=>'Xóa sản phẩm',
            'btnDelete'=>'Xóa dữ liệu'
        ],
    ],

    //table
    'tableProduct_brand'=>'Danh sách sản phẩm',
    'tableProduct_name'=>'Tiêu đề',
    'tableProduct_pos'=>'Vị trí',
    'tableProduct_status'=>'Tình trạng',
    'tableProduct_action'=>'Thao tác',
    'tableProduct_displayCatalogue'=>'Nhóm sản phẩm',

    //aside
    'General_product_information'=>'Thông tin chung',
    'Product_code'=>'Mã sản phẩm',
    'Product_made_in'=>'Xuất xứ',
    'Product_price'=>'Giá bán sản phẩm',

    //store
    'The_product_has_many_versions'=>'Sản phẩm có nhiều phiên bản',
    'tphmv_content1'=>'Cho phép bạn bán các phiên bản khác nhau của sản phẩm, ví dụ: quần, áo thì có các',
    'tphmv_content2'=>'màu sắc',
    'tphmv_content3'=>'và',
    'tphmv_content4'=>'khác nhau.',
    'tphmv_content5'=>'Mỗi phiên bản sẽ là 1 dòng trong mục danh sách phiên bản phía dưới',

    //variant
    'Product_variantCheckbox'=>'Sản phẩm này có nhiều biến thể. Ví dụ như khác nhau về màu sắc, kích thước',
    'Product_attribute-title-1'=>'Chọn thuộc tính',
    'Product_attribute-title-2'=>'Chọn giá trị của thuộc tính (nhập 2 từ để tìm kiếm)',
    'Product_add-variant'=>'Thêm phiên bản mới',
    'Product_select-attribute-group'=>'Chọn nhóm thuộc tính',
    'Product_placeholder-select2'=>'Nhập tối thiểu 2 ký tự để tìm kiếm',
    
    //productVariant
    'List_product_versions'=>'Danh sách phiên bản',
    'Product_image-product-variant'=>'Hình ảnh',
    'Product_storage-product-variant'=>'Số lượng',
    'Product_price-product-variant'=>'Giá tiền',
    'Product_update_version_information'=>'Cập nhật thông tin phiên bản',
    'Product_inventory'=>'Tồn kho',
    'Product_cancel'=>'Hủy bỏ',
    'Product_save'=>'Lưu lại',
    'Product_manage_file'=>'Quản lý file',
    'Product_file_name'=>'Tên file',
    'Product_file_path'=>'Đường dẫn',
    'Product_publish'=>'Tình trạng',

    //SYSTEM
    'system' => [
        'index'=>[
            'title'=> 'Cấu hình hệ thống',
        ],
        'create'=>[
            'title'=>'Cài đặt cấu hình hệ thống',
            'btnTitle'=>'Cập nhật'
        ],
        
    ],

    //MENU
    'menu' => [
        'index'=>[
            'title'=> 'Quản lý Menu',
        ],
        'create'=>[
            'title'=>'Thêm mới Menu',
            'btnTitle'=>'Thêm mới'
        ],
        'show'=>[
            'title'=>'Danh sách menu',
            'btnTitle'=>'Cập nhật'
        ],
    ],
];