<base href="{{ config('app.url') }}">
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="robots" content="index,follow">
<meta name="author" content="{{ $systems['homepage_company'] }}">
<meta name="copyright" content="{{ $systems['homepage_company'] }}">
<meta http-equiv="refresh" content="1800">
<link rel="icon" href="{{ $systems['homepage_favicon'] }}" type="image/png" sizes="30x30">

<!-- GOOGLE -->
<title>{{ $seo['meta_title'] }}</title>
<meta name="description" content="{{ $seo['meta_description'] }}">
<meta name="keyword" content="{{ $seo['meta_keyword'] }}">
<link rel="canonical" href="{{ $seo['canonical'] }}">

<!-- for Facebook -->
<meta property="og:locale" content="vi_VN">
<meta property="og:title" content="{{ $seo['meta_title'] }}">
<meta property="og:type" content="website">
<meta property="og:image" content="{{ $seo['meta_images'] }}">
<meta property="og:url" content="{{ $seo['canonical'] }}">
<meta property="og:description" content="{{ $seo['meta_description'] }}">
<meta property="og:site_name" content="">
<meta property="fb:admins" content="">
<meta property="fb:app_id" content="">

<!-- Twitter -->
<meta name="twitter:card" content="summary">
<meta name="twitter:title" content="{{ $seo['meta_title'] }}">
<meta name="twitter:description" content="{{ $seo['meta_description'] }}">
<meta name="twitter:image" content="{{ $seo['meta_images'] }}">

<!-- Debug -->
@php
    $coreCss = [
        'Frontend/resources/fonts/font-awesome-4.7.0/css/font-awesome.min.css',
        'Frontend/resources/uikit/css/uikit.modify.css',
        'Frontend/resources/library/css/library.css',
        'Frontend/resources/plugins/wow/css/libs/animate.css',
        'Frontend/resources/style.css',
        'Frontend/core/plugins/jquery-nice-select-1.1.0/css/nice-select.css',
    ];
@endphp
@foreach($coreCss as $item)
    <link rel="stylesheet" href="{{ asset($item) }}" />
@endforeach
<link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
<script src="{{ asset('Frontend/resources/library/js/jquery.js') }}"></script>