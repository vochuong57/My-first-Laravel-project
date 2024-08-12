<?php
if(!function_exists('convert_price')){
    function convert_price(string $price = ''){
        return str_replace('.','',$price);
    }
}

//V59
if(!function_exists('convert_array')){
    function convert_array($system = null, $keyword = '', $value = ''){
        $temp = [];
        if(is_array($system)){
            foreach($system as $key => $val){
                $temp[$val[$keyword]] = $val[$value];
            }
        }
        if(is_object($system)){
            foreach($system as $key => $val){
                $temp[$val->{$keyword}] = $val->{$value};
            }
        }

        return $temp;
    }
}

// V58
if(!function_exists('renderSystemInput')){
    function renderSystemInput(string $name = '', $systems = null){
        return '<input 
                type="text"
                name="config['.$name.']"
                value="'.old($name, ($systems[$name]) ?? '').'"
                class="form-control"
                placeholder=""
                autocomplete="off"
                >';
    }
}

if(!function_exists('renderSystemImages')){
    function renderSystemImages(string $name = '', $systems = null){
        return '<input 
                type="text"
                name="config['.$name.']"
                value="'.old($name, ($systems[$name]) ?? '').'"
                class="form-control upload-image"
                placeholder=""
                autocomplete="off"
                >';
    }
}

if(!function_exists('renderSystemTextarea')){
    function renderSystemTextarea(string $name = '', $systems = null){
        return '<textarea name="config['.$name.']"  class="form-control system-textarea">'.old($name, ($systems[$name]) ?? '').'</textarea>';
    }
}

if(!function_exists('renderSystemLink')){
    function renderSystemLink(array $item = []){
        return (isset($item['link'])) ? '<a class="system-link" target="'.$item['link']['target'].'" href="'.$item['link']['href'].'">'.$item['link']['text'].'</a>' : '';
    }
}

if(!function_exists('renderSystemTitle')){
    function renderSystemTitle(array $item = []){
        return (isset($item['title'])) ? '<span class="system-link text-danger">'.$item['title'].'</span>' : '';
    }
}

if(!function_exists('renderSystemSelect')){
    function renderSystemSelect(array $item = [], string $name = '', $systems = null){
        $html = '';
        $html = '<select name="config['.$name.']" class="form-control">';
                foreach($item['option'] as $key => $val){
                    $html .= '<option '.((isset($systems[$name]) && $key == $systems[$name]) ? 'selected' : '' ).' value="'.$key.'">'.$val.'</option>';
                }
        $html .= '</select>';
        return $html;
    }
}

if(!function_exists('renderSystemEditor')){
    function renderSystemEditor(string $name = '', $systems = null){
        return '<textarea name="config['.$name.']" id="'.$name.'" class="form-control system-textarea ck-editor">'.old($name, ($systems[$name]) ?? '').'</textarea>';
    }
}

// V69
if(!function_exists('recursive')){ 
    function recursive($data, $parentId = 0){ // data chạy từ 28, 29 ,49, 50, 51
        $temp=[];
        if(!is_null($data) && count($data)){
            foreach($data as $key => $val){
                if($val->parent_id == $parentId){ // lần đầu chạy ở parentId 0 ra được 2 mảng là của 28, 29. Lần chạy thứ hai parentId 28 ra được 3 mảng là 49, 50, 51 và pareetId 29 thì không mảng nào được tạo ra
                    $temp[]=[
                        'item' => $val,  
                        'children' => recursive($data, $val->id) // lúc này lần chạy thứ 2 parentId là 28, 29, lần chạy thứ 3 thì parent id là 49, 50, 51
                    ];
                }
            }
        }
        return $temp;
    }
}

// <ol class="dd-list">
//     @foreach($menus as $key => $val)
//     <li class="dd-item" data-id="{{ $val->id }}">
//         <div class="dd-handle">
//             <span class="label label-info"><i class="fa fa-arrows"></i></span> {{ $val->languages->first()->getOriginal('pivot_name') }}
//         </div>
//         <a class="create-children-menu" href="{{ route('menu.children', $val->id) }}"> Quản lý menu con </a>
//         <ol class="dd-list">
//             <li class="dd-item" data-id="2">
//                 <div class="dd-handle">
//                     <span class="pull-right"> 12:00 pm </span>
//                     <span class="label label-info"><i class="fa fa-arrows"></i></span> Vivamus vestibulum nulla nec ante.
//                 </div>
//             </li>
//             <li class="dd-item" data-id="3">
//                 <div class="dd-handle">
//                     <span class="pull-right"> 11:00 pm </span>
//                     <span class="label label-info"><i class="fa fa-arrows"></i></span> Nunc dignissim risus id metus.
//                 </div>
//             </li>
//             <li class="dd-item" data-id="4">
//                 <div class="dd-handle">
//                     <span class="pull-right"> 11:00 pm </span>
//                     <span class="label label-info"><i class="fa fa-arrows"></i></span> Vestibulum commodo
//                 </div>
//             </li>
//         </ol>
//     </li>
//     @endforeach
// </ol>

// V69
if(!function_exists('recursive_menu')){
    function recursive_menu($data){
        $html = '';
        if(count($data)){
            foreach($data as $key => $val){
                $itemId = $val['item']->id;
                $itemName = $val['item']->languages->first()->getOriginal('pivot_name');
                $itemUrl = route('menu.children', ['id' => $itemId]);

                $html .= "<li class='dd-item' data-id='$itemId'>";
                $html .=    "<div class='dd-handle'>";
                $html .=        "<span class='label label-info'><i class='fa fa-arrows'></i></span> $itemName";
                $html .=    "</div>";
                $html .=    "<a class='create-children-menu' href='$itemUrl'> Quản lý menu con </a>";
                if(count($val['children'])){
                    $html .= "<ol class='dd-list'>";
                    $html .=    recursive_menu($val['children']);
                    $html .= "</ol>";
                }
                $html .= "</li>";

            }
        }
        return $html;
    }
}