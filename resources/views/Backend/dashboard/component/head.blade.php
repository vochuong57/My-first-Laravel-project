<base href="{{ env('APP_URL') }}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- đối với là phương thức gửi là POST ở AJAX thì ta thêm  -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>INSPINIA | Dashboard v.2</title>

    <link href="Backend/css/bootstrap.min.css" rel="stylesheet">
    <link href="Backend/font-awesome/css/font-awesome.css" rel="stylesheet">

    <link href="Backend/css/animate.css" rel="stylesheet">
    @if(isset($config['css'])&& is_array($config['css']))
        @foreach($config['css'] as $key => $val)
        {!! '<link href="'.$val.'" rel="stylesheet">' !!}
        @endforeach
    @endif
    <link href="Backend/css/style.css" rel="stylesheet">
    <link href="Backend/css/customize.css" rel="stylesheet">
    <script src="Backend/js/jquery-3.1.1.min.js"></script>



