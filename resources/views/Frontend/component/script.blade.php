@php
    $coreScript = [
        'Frontend/resources/plugins/wow/dist/wow.min.js',
        'Frontend/resources/uikit/js/uikit.min.js',
        'Frontend/resources/uikit/js/components/sticky.min.js',
        'Frontend/resources/function.js',
        'Frontend/core/plugins/jquery-nice-select-1.1.0/js/jquery.nice-select.min.js',
        'Backend/js/plugins/toastr/toastr.min.js',
    ];
    if(isset($config['js'])){
        $coreScript = array_merge($coreScript, $config['js']);
    }
@endphp
@foreach($coreScript as $item)
    <script src="{{ $item }}"></script>
@endforeach
<div id="fb-root"></div>
<script async defer crossorigin="anonymous" src="https://connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v17.0&appId=103609027035330&autoLogAppEvents=1" nonce="E1aWx0Pa"></script>
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
