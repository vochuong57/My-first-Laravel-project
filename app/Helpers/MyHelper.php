<?php
// V58
if(!function_exists('convert_price')){
    function convert_price(string $price = ''){
        return str_replace('.','',$price);
    }
}

if(!function_exists('renderSystemInput')){
    function renderSystemInput(string $name = ''){
        return '<input 
                type="text"
                name="config['.$name.']"
                value="'.old($name).'"
                class="form-control"
                placeholder=""
                autocomplete="off"
                >';
    }
}

if(!function_exists('renderSystemImages')){
    function renderSystemImages(string $name = ''){
        return '<input 
                type="text"
                name="config['.$name.']"
                value="'.old($name).'"
                class="form-control upload-image"
                placeholder=""
                autocomplete="off"
                >';
    }
}

if(!function_exists('renderSystemTextarea')){
    function renderSystemTextarea(string $name = ''){
        return '<textarea name="config['.$name.']" value="'.old($name).'" class="form-control system-textarea"></textarea>';
    }
}

if(!function_exists('renderSystemLink')){
    function renderSystemLink(array $item = []){
        return (isset($item['link'])) ? '<a class="system-link" target="'.$item['link']['target'].'" href="'.$item['link']['href'].'">'.$item['link']['text'].'</a>' : '';
    }
}

if(!function_exists('renderSystemSelect')){
    function renderSystemSelect(array $item = [], string $name = ''){
        $html = '';
        $html = '<select name="config['.$name.']" class="form-control">';
                foreach($item['option'] as $key => $val){
                    $html .= '<option value="'.$key.'">'.$val.'</option>';
                }
        $html .= '</select>';
        return $html;
    }
}