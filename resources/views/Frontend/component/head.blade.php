<base href="{{ config('app.url') }}">
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="robots" content="index,follow">
<meta name="author" content="">
<meta name="copyright" content="">
<meta http-equiv="refresh" content="1800">
<link rel="icon" href="" type="image/png" sizes="30x30">

<!-- GOOGLE -->
<title>Home 2 | Economic Marketplace</title>
<meta name="description" content="">
<link rel="canonical" href="">

<!-- for Facebook -->
<meta property="og:locale" content="vi_VN">
<meta property="og:title" content="">
<meta property="og:type" content="article">
<meta property="og:image" content="/upload/image/logo.png">
<meta property="og:url" content="http://linote.codechuanseo.com.vn/">
<meta property="og:description" content="">
<meta property="og:site_name" content="">
<meta property="fb:admins" content="">
<meta property="fb:app_id" content="">

<!-- Twitter -->
<meta name="twitter:card" content="summary">
<meta name="twitter:title" content="">
<meta name="twitter:description" content="">
<meta name="twitter:image" content="">

<!-- Debug -->
@php
    $coreCss = [
        'Frontend/resources/fonts/font-awesome-4.7.0/css/font-awesome.min.css',
        'Frontend/resources/uikit/css/uikit.modify.css',
        'Frontend/resources/library/css/library.css',
        'Frontend/resources/plugins/wow/css/libs/animate.css',
        'Frontend/resources/style.css',
    ];
@endphp
@foreach($coreCss as $item)
    <link rel="stylesheet" href="{{ asset($item) }}" />
@endforeach
<link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
<script src="{{ asset('Frontend/resources/library/js/jquery.js') }}"></script>