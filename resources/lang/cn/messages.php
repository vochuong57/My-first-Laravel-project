<?php
return [
    'postCatalogue' => [
        'index'=>[
            'title'=> '管理文章分组',
        ],
        'create'=>[
            'title'=>'新增文章分组',
            'btnTitle'=>'新增'
        ],
        'edit'=>[
            'title'=>'更新文章分组',
            'btnTitle'=>'更新'
        ],
        'delete'=>[
            'title'=>'删除文章分组',
            'btnDelete'=>'删除数据'
        ],
    ],
    // aside
    'parent'=>'选择父级类别',
    'parentNotice'=>'如果没有父级类别，请选择根类别',
    'children' => '选择子类别（如果有）',
    'image'=>'选择图片',
    'advance'=>'高级配置',
    // fillter
    'perpage'=>'每页记录数',
    'search'=>'搜索',
    'searchInput'=>'输入您想搜索的关键字...',
    // general
    'general'=>'通用信息',
    'title_general'=>'标题:',
    'description'=>'简短描述:',
    'upload'=>'上传多张图片',
    'content'=>'内容:',
    // seo
    'seo'=>'SEO配置',
    'seo_title'=>'您还没有SEO标题',
    'seo_canonical'=>'https://您的网址.html',
    'seo_description'=>'您还没有SEO描述',
    'seo_meta_title'=>'SEO标题',
    'character'=>'字符',
    'seo_meta_keyword'=>'SEO关键词',
    'seo_meta_description'=>'SEO描述',
    'seo_meta_canonical'=>'规范URL',
    // album
    'album' => '相册',
    'pickAlbum' => '选择相册',
    'adviseAlbum' => '使用选择按钮或点击此处添加图片',
    // table
    'tablePostCatalogue_brand'=>'文章分组列表',
    'tablePostCatalogue_name'=>'分组名称',
    'tablePostCatalogue_status'=>'状态',
    'tablePostCatalogue_action'=>'操作',

    'publish'=>[
        '0'=>'选择状态',
        '1'=>'未发布',
        '2'=>'已发布'
    ],
    'follow'=>[
        '0'=>'选择跟随',
        '1'=>'nofollow',
        '2'=>'follow'
    ],
    //destroy
    'destroy'=>'通用信息',
    'destroy_panel_description_postCatalogue_1'=>'- 您即将删除一个名为的文章分组:',
    'destroy_panel_description_1'=>'- 注意:',
    'destroy_panel_description_2'=>'无法',
    'destroy_panel_description_postCatalogue_2'=>'在删除后恢复文章分组。',
    'destroy_panel_description_3'=>'确保您要执行此操作',
    'destroyPostCatalogue_name'=>'文章分组名称:',
    //toolbox
    'toolbox_name'=>'全部',
    'toolboxDestroyPostCatalogue'=>'您确定要删除这些文章分组吗？',

    'permission' => [
        'index' => [
            'title' => '管理权限',
        ],
        'create' => [
            'title' => '添加新权限',
            'btnTitle' => '添加新的'
        ],
        'edit' => [
            'title' => '更新权限',
            'btnTitle' => '更新'
        ],
        'delete' => [
            'title' => '删除权限',
            'btnDelete' => '删除数据'
        ],
    ],
    //toolbox
    'toolboxDestroyPermission' =>  '你确定要删除这些权限吗？',
    
    // table
    'tablePermission_brand' => '权限列表',
    'tablePermission_name' => '权限名称',
    'tablePermission_action' => '操作',

    // destroy
    'destroy_panel_description_permission_1'=>'- 你打算删除一个名为的权限:',
    'destroy_panel_description_permission_2'=>'删除后恢复权限。',

    // store
    'note_permission' => '- 输入权限的一般信息',
    'note_1' => '- 注意: 标记的字段',
    'note_2' => '是必需的',
    'permission_title' => '权限名称:',

    //USER CATALOGUE
    'userCatalogue' => [
        'index' => [
            'title' => '管理用户组',
        ],
        'create' => [
            'title' => '添加新用户组',
            'btnTitle' => '添加新',
        ],
        'edit' => [
            'title' => '更新用户组',
            'btnTitle' => '更新',
        ],
        'delete' => [
            'title' => '删除用户组',
            'btnDelete' => '删除数据',
        ],
        'permission' => [
            'title' => '更新权限',
            'btnTitle' => '更新',
        ],
    ],

    //table
    'tableUserCatalogue_brand' => '用户组列表',
    'tableUserCatalogue_name' => '用户组名称',
    'tableUserCatalogue_count' => '成员数量',
    'tableUserCatalogue_description' => '描述',
    'tableUserCatalogue_publish' => '状态',
    'tableUserCatalogue_action' => '操作',

    //toolbox
    'toolboxDestroyUserCatalogue' => '您确定要删除此用户组吗？',

    //filter
    'permission_name' => '权限名称',

    //store
    'note_userCatalogue' => '- 输入用户组的常规信息',
    'userCatalogue_title' => '组名称：',
    'note' => '备注：',

    // destroy
    'destroy_panel_description_userCatalogue_1' => '- 您即将删除名为的用户组:',
    'destroy_panel_description_userCatalogue_2' => '删除后恢复用户组。',

    //LANGUAGE
    'language' => [
        'index' => [
            'title' => '语言管理',
        ],
        'create' => [
            'title' => '添加新语言',
            'btnTitle' => '添加新',
        ],
        'edit' => [
            'title' => '更新语言',
            'btnTitle' => '更新',
        ],
        'delete' => [
            'title' => '删除语言',
            'btnDelete' => '删除数据',
        ],
    ],

    //table
    'tableLanguage_brand' => '语言列表',
    'tableLanguage_image' => '图片',
    'tableLanguage_name' => '语言名称',
    'tableLanguage_note' => '备注',
    'tableLanguage_publish' => '状态',
    'tableLanguage_action' => '操作',

    //toolbox
    'toolboxDestroyLanguage' => '您确定要删除这些语言吗？',

    //store
    //store
    'note_language' => '- 输入语言的常规信息',
    'language_title' => '语言名称：',
    'language_avatar' => '头像：',
    'language_note' => '备注：',

    // destroy
    'destroy_panel_description_language_1' => '- 您即将删除名为的语言:',
    'destroy_panel_description_language_2' => '删除后恢复语言。',

    //translate
    'translate' => [
        'index'=>[
            'title'=> '翻译管理',
            'btnTitle' => '更新'
        ],
    ],

    //generate
    'generate' => [
        'index'=>[
            'title'=> '模块管理',
        ],
        'create'=>[
            'title'=>'新增模块',
            'btnTitle'=>'新增'
        ],
        'edit'=>[
            'title'=>'更新模块',
            'btnTitle'=>'更新'
        ],
        'delete'=>[
            'title'=>'删除模块',
            'btnDelete'=>'删除数据'
        ],
    ],

    //table
    'tableGenerate_brand'=>'模块列表',
    'tableGenerate_image'=>'图片',
    'tableGenerate_name'=>'模块名称',
    'tableGenerate_action'=>'操作',

    //store
    'note_generate' => '- 输入模块的常规信息',
    'generate_title' => '模块名称:',
    'generate_avatar' => '头像:',
    'generate_note' => '备注:',
    'generate_schema1' => '架构:',
    'generate_schema2' => '架构 2:',
    'generate_moduleType' => '模块类型:',
    'generate_schema' => '架构信息',
    'generate_note_schema' => '输入架构信息',
    'generate_sidebar_module' => '功能名称:',
    'generate_path' => '路径:',
];
