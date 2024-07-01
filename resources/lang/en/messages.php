<?php
return[
    'postCatalogue' => [
        'index'=>[
            'title'=> 'Manage post groups',
        ],
        'create'=>[
            'title'=>'Add new post group',
            'btnTitle'=>'Add new'
        ],
        'edit'=>[
            'title'=>'Update post group',
            'btnTitle'=>'Update'
        ],
        'delete'=>[
            'title'=>'Delete post group',
            'btnDelete'=>'Delete data'
        ],
    ],
    // aside
    'parent'=>'Select parent category',
    'parentNotice'=>'Select root if there is no parent category',
    'image'=>'Choose image',
    'advance'=>'Advanced configuration',
    // fillter
    'perpage'=>'records per page',
    'search'=>'Search',
    'searchInput'=>'Enter keywords you want to search...',
    // general
    'general'=>'General information',
    'title_general'=>'Title:',
    'description'=>'Short description:',
    'upload'=>'Upload multiple images',
    'content'=>'Content:',
    // seo
    'seo'=>'SEO configuration',
    'seo_title'=>'You have no SEO title',
    'seo_canonical'=>'https://your-url.html',
    'seo_description'=>'You have no SEO description',
    'seo_meta_title'=>'SEO title',
    'character'=>'characters',
    'seo_meta_keyword'=>'SEO keywords',
    'seo_meta_description'=>'SEO description',
    'seo_meta_canonical'=>'Canonical URL',
    // table
    'tablePostCatalogue_brand'=>'List of post groups',
    'tablePostCatalogue_name'=>'Group name',
    'tablePostCatalogue_status'=>'Status',
    'tablePostCatalogue_action'=>'Action',

    'publish'=>[
        '0'=>'Select status',
        '1'=>'Unpublished',
        '2'=>'Published'
    ],
    'follow'=>[
        '0'=>'Select follow',
        '1'=>'nofollow',
        '2'=>'follow'
    ],
    //destroy
    'destroy'=>'General information',
    'destroy_panel_description_postCatalogue_1'=>'- You are about to delete a post group named:',
    'destroy_panel_description_1'=>'- Note:',
    'destroy_panel_description_2'=>'UNABLE',
    'destroy_panel_description_postCatalogue_2'=>'to recover the post group after deletion.',
    'destroy_panel_description_3'=>'Make sure you want to perform this action',
    'destroyPostCatalogue_name'=>'Post group name:',
    //toolbox
    'toolbox_name'=>'all',
    'toolboxDestroyPostCatalogue'=>'Are you sure you want to delete these post groups?',

    'permission' => [
        'index' => [
            'title' => 'Manage Permissions',
        ],
        'create' => [
            'title' => 'Add New Permission',
            'btnTitle' => 'Add New'
        ],
        'edit' => [
            'title' => 'Update Permission',
            'btnTitle' => 'Update'
        ],
        'delete' => [
            'title' => 'Delete Permission',
            'btnDelete' => 'Delete Data'
        ],
    ],
    //toolbox
    'toolboxDestroyPermission' => 'Are you sure you want to delete these permissions?',
    
    // table
    'tablePermission_brand' => 'List of Permissions',
    'tablePermission_name' => 'Permission Name',
    'tablePermission_action' => 'Action',

    // destroy
    'destroy_panel_description_permission_1'=>'- You are intending to delete a permission named:',
    'destroy_panel_description_permission_2'=>'restore permissions after deletion.',

    // store
    'note_permission' => '- Enter general information of the permission',
    'note_1' => '- Note: fields marked',
    'note_2' => 'are required',
    'permission_title' => 'Permission Name:',

    //USER CATALOGUE
    'userCatalogue' => [
        'index' => [
            'title' => 'Manage User Groups',
        ],
        'create' => [
            'title' => 'Add New User Group',
            'btnTitle' => 'Add New',
        ],
        'edit' => [
            'title' => 'Update User Group',
            'btnTitle' => 'Update',
        ],
        'delete' => [
            'title' => 'Delete User Group',
            'btnDelete' => 'Delete Data',
        ],
        'permission' => [
            'title' => 'Update Permission',
            'btnTitle' => 'Update',
        ],
    ],

    //table
    'tableUserCatalogue_brand' => 'User Group List',
    'tableUserCatalogue_name' => 'Group Name',
    'tableUserCatalogue_count' => 'Number of Members',
    'tableUserCatalogue_description' => 'Description',
    'tableUserCatalogue_publish' => 'Status',
    'tableUserCatalogue_action' => 'Actions',

    //toolbox
    'toolboxDestroyUserCatalogue' => 'Are you sure you want to delete this user group?',

    //filter
    'permission_name' => 'Permission',

    //store
    'note_userCatalogue' => '- Enter general information of the user group',
    'userCatalogue_title' => 'Group Name:',
    'note' => 'Note:',

    // destroy
    'destroy_panel_description_userCatalogue_1' => '- You are about to delete the user group named:',
    'destroy_panel_description_userCatalogue_2' => 'restore the user group after deletion.',

    //LANGUAGE
    'language' => [
        'index' => [
            'title' => 'Language Management',
        ],
        'create' => [
            'title' => 'Add New Language',
            'btnTitle' => 'Add New',
        ],
        'edit' => [
            'title' => 'Update Language',
            'btnTitle' => 'Update',
        ],
        'delete' => [
            'title' => 'Delete Language',
            'btnDelete' => 'Delete Data',
        ],
    ],

    //table
    'tableLanguage_brand' => 'Language List',
    'tableLanguage_image' => 'Image',
    'tableLanguage_name' => 'Language Name',
    'tableLanguage_note' => 'Note',
    'tableLanguage_publish' => 'Status',
    'tableLanguage_action' => 'Actions',

    //toolbox
    'toolboxDestroyLanguage' => 'Are you sure you want to delete these languages?',

    //store
    'note_language' => '- Enter general information of the language',
    'language_title' => 'Language Name:',
    'language_avatar' => 'Avatar:',
    'language_note' => 'Note:',

    // destroy
    'destroy_panel_description_language_1' => '- You are about to delete the language named:',
    'destroy_panel_description_language_2' => 'restore the language after deletion.',

    //translate
    'translate' => [
        'index'=>[
            'title'=> 'Translation Management',
            'btnTitle' => 'Update'
        ],
    ],

    //generate
    'generate' => [
        'index'=>[
            'title'=> 'Module Management',
        ],
        'create'=>[
            'title'=>'Add New Module',
            'btnTitle'=>'Add New'
        ],
        'edit'=>[
            'title'=>'Update Module',
            'btnTitle'=>'Update'
        ],
        'delete'=>[
            'title'=>'Delete Module',
            'btnDelete'=>'Delete Data'
        ],
    ],

    //table
    'tableGenerate_brand'=>'Module List',
    'tableGenerate_image'=>'Image',
    'tableGenerate_name'=>'Module Name',
    'tableGenerate_action'=>'Action',

    //store
    'note_generate' => '- Enter the general information of the language',
    'generate_title' => 'Language Name:',
    'generate_avatar' => 'Avatar:',
    'generate_note' => 'Note:',

];
