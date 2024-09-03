@include('Backend.dashboard.component.breadcrumb', ['title' =>$config['seo']['title']])
<!-- từ khóa tìm kiếm/validation/Displaying the Validation Errors -->
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
@php
    $url=($config['method']=='create')?route('widget.create'):route('widget.update', $widget->id)
@endphp
<form action="{{ $url }}" method="post" class="box">
    @csrf
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-9">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Thông tin widget</h5>
                    </div>
                    <div class="ibox-content widgetContent">
                        @include('Backend.dashboard.component.content', ['offTitle' => true, 'offContent' => true])
                    </div>
                </div>
                @include('Backend.dashboard.component.album')
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Cấu hình nội dung widget</h5>
                    </div>
                    <div class="ibox-content model-list">
                        <div class="labelText">Chọn Module</div>
                        @foreach(__('module.model') as $key => $val)
                        <div class="model-item uk-flex uk-flex-middle">
                            <input type="radio" name="model" id="{{ $key }}" class="input-radio" value="{{ $key }}">
                            <label for="{{ $key }}">{{ $val }}</label>
                        </div>
                        @endforeach

                        <div class="search-model-box">
                            <i class="fa fa-search"></i>
                            <input type="text" class="form-control search-model">

                            <div class="ajax-search-result hidden">
                                @for($i = 0; $i < 4; $i++)
                                <button class="ajax-search-item">
                                    <div class="uk-flex uk-flex-middle uk-flex-space-between">
                                        <span>HÌNH THỨC THANH TOÁN KHI ĐẶT HÀNG ONLINE</span>
                                        <div class="auto-icon">
                                            <i class="fa fa-check"></i>
                                        </div>
                                    </div>
                                </button>
                                @endfor
                            </div>
                        </div>

                        <div class="search-model-result">
                            @for($i = 0; $i < 10; $i++)
                            <div class="search-result-item">
                                <div class="uk-flex uk-flex-middle uk-flex-space-between">
                                    <div class="uk-flex uk-flex-middle">
                                        <span class="image img-cover">
                                            <img src="https://www.vpbank.com.vn/-/media/vpbank-latest/1retail/picture/tap-and-pay/800x507-vpp.jpg" alt="">
                                        </span>
                                        <span class="name">HÌNH THỨC THANH TOÁN KHI ĐẶT HÀNG ONLINE</span>
                                    </div>
                                    <div class="delete">
                                        <a class="delete-menu img-scaledown" style="width: 15%; height: 30px; margin-left: 6px"><img src="Backend/img/close.png" alt=""></a>
                                    </div>
                                </div>
                            </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Thông tin chung</h5>
                    </div>
                    <div class="ibox-content">
                        @include('Backend.widget.widget.component.aside')
                    </div>
                </div>
                <div class="ibox short-code">
                    <div class="ibox-title">
                        <h5>Short Code</h5>
                    </div>
                    <div class="ibox-content">
                        <textarea name="short_code" class="textarea form-control">{{ old('short_code', $widget->short_code ?? '') }}</textarea>
                    </div>
                </div>
            </div>
        </div>
       
        <div class="text-right mb15">
            <button class="btn btn-primary" type="submit" name="send" value="send">{{ $config['seo']['btnTitle'] }}</button>
        </div>
    </div>
</form>
