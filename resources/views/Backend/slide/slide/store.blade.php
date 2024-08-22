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
    $url=($config['method']=='create')?route('slide.create'):route('slide.update', $slide->id)
@endphp
<form action="{{ $url }}" method="post" class="box">
    @csrf
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-9">
                <div class="ibox">
                    <div class="ibox-title">
                        <div class="uk-flex uk-flex-middle uk-flex-space-between">
                            <h5>Danh sách Slides</h5>
                            <button type="button" class="addSlide btn">Thêm slide</button>
                        </div>
                    </div>
                    <div class="ibox-content">
                        <div class="row">
                            <div class="col-lg-12">
                                @for($i = 0; $i < 10; $i++)
                                <div class="slide-item">
                                    <div class="row custom-row">
                                        <div class="col-lg-3">
                                            <span class="slide-image img-cover">
                                                <img src="https://static.kinhtedothi.vn/w960/images/upload/2021/12/25/191b0f8b-4161-4f54-b9b9-7671af990ba1.jpg" alt="">
                                            </span>
                                        </div>
                                        <div class="col-lg-9">
                                            <div class="tabs-container">
                                                <ul class="nav nav-tabs">
                                                    <li class="active"><a data-toggle="tab" href="#tab-1"> Thông tin chung</a></li>
                                                    <li class=""><a data-toggle="tab" href="#tab-2">SEO</a></li>
                                                </ul>
                                                <div class="tab-content">
                                                    <div id="tab-1" class="tab-pane active">
                                                        <div class="panel-body">
                                                            <div class="label-text mb5">Mô tả:</div>
                                                            <div class="form-row mb10">
                                                                <textarea name="" class="form-control"></textarea>
                                                            </div>
                                                            <div class="form-row form-row-url">
                                                                <input type="text" name="" class="form-control" placeholder="URL">
                                                                <div class="overlay">
                                                                    <div class="uk-flex uk-flex-middle">
                                                                        <label for="">Mở trong tab mới</label>
                                                                        <input type="checkbox" name="" value="" id="">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div id="tab-2" class="tab-pane">
                                                        <div class="panel-body">
                                                            <div class="label-text mb5">Tiêu đề ảnh:</div>
                                                            <div class="form-row form-row-url slide-seo-tab">
                                                                <input type="text" name="" class="form-control" placeholder="Tiêu đề ảnh">
                                                            </div>
                                                            <div class="label-text mt12">Mô tả ảnh:</div>
                                                            <div class="form-row form-row-url slide-seo-tab">
                                                                <input type="text" name="" class="form-control" placeholder="Mô tả ảnh">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>


                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr />
                                @endfor
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="ibox slide-setting slide-normal">
                    <div class="ibox-title">
                        <h5>Cài đặt cơ bản</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="row mb15">
                            <div class="col-lg-12 mb10">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Tên slide: <span class="text-danger">(*)</span></label>
                                    <input 
                                    type="text"
                                    name="name"
                                    value="{{ old('name', ($slide->name)??'') }}"
                                    class="form-control"
                                    placeholder=""
                                    autocomplete="off"
                                    >
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Từ khóa: <span class="text-danger">(*)</span></label>
                                    <input 
                                    type="text"
                                    name="keyword"
                                    value="{{ old('keyword', ($slide->keyword)??'') }}"
                                    class="form-control"
                                    placeholder=""
                                    autocomplete="off"
                                    >
                                </div>
                            </div>
                        </div>    
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="slide-setting">
                                    <div class="setting-item">
                                        <div class="uk-flex uk-flex-middle">
                                            <span class="setting-text">Chiều rộng</span>
                                            <div class="setting-value">
                                                <input type="text" name="" class="form-control">
                                                <span class="px">px</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="setting-item">
                                        <div class="uk-flex uk-flex-middle">
                                            <span class="setting-text">Chiều cao</span>
                                            <div class="setting-value">
                                                <input type="text" name="" class="form-control">
                                                <span class="px">px</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="setting-item">
                                        <div class="uk-flex uk-flex-middle">
                                            <span class="setting-text">Hiệu ứng</span>
                                            <div class="setting-value">
                                                <select name="" id="" class="form-control">
                                                    <option value="">Fade</option>
                                                    <option value="">...</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="setting-item">
                                        <div class="uk-flex uk-flex-middle">
                                            <span class="setting-text">Mũi tên</span>
                                            <div class="setting-value">
                                                <input type="checkbox" name="" value="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="setting-item">
                                        <div class="uk-flex uk-flex-middle">
                                            <span class="setting-text">Thanh điều hướng</span>
                                            <div class="setting-value">
                                                <div class="nav-setting-item uk-flex uk-flex-middle">
                                                    <input type="radio" value="" name="" id="item_1">
                                                    <label for="item_1">Ẩn thanh điều hướng</label>
                                                </div>
                                                <div class="nav-setting-item uk-flex uk-flex-middle">
                                                    <input type="radio" value="" name="" id="item_2">
                                                    <label for="item_2">Hiển thị dạng dấu chấm</label>
                                                </div>
                                                <div class="nav-setting-item uk-flex uk-flex-middle">
                                                    <input type="radio" value="" name="" id="item_3">
                                                    <label for="item_3">Hiển thị dạng ảnh thumbnails</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>              
                    </div>
                </div>
                   
                <div class="ibox slide-setting slide-advance">
                    <div class="ibox">
                        <div class="ibox-title uk-flex uk-flex-middle uk-flex-space-between">
                            <h5>Cài đặt nâng cao</h5>
                            <div class="ibox-tools">
                                <a class="collapse-link">
                                    <i class="fa fa-chevron-up"></i>
                                </a>
                            </div>
                        </div>
                        <div class="ibox-content">
                            <div class="setting-item">
                                <div class="uk-flex uk-flex-middle">
                                    <span class="setting-text">Tự động chạy</span>
                                    <div class="setting-value">
                                        <input type="checkbox" name="" value="">
                                    </div>
                                </div>
                            </div>
                            <div class="setting-item">
                                <div class="uk-flex uk-flex-middle">
                                    <span class="setting-text">Dừng khi di chuột</span>
                                    <div class="setting-value">
                                        <input type="checkbox" name="" value="">
                                    </div>
                                </div>
                            </div>
                            <div class="setting-item">
                                <div class="uk-flex uk-flex-middle">
                                    <span class="setting-text">Chuyển ảnh</span>
                                    <div class="setting-value">
                                        <input type="text" name="" class="form-control">
                                        <span class="px">ms</span>
                                    </div>
                                </div>
                            </div>
                            <div class="setting-item">
                                <div class="uk-flex uk-flex-middle">
                                    <span class="setting-text">Tốc độ & HIệu ứng</span>
                                    <div class="setting-value">
                                        <input type="text" name="" class="form-control">
                                        <span class="px">ms</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                   
                </div>
                <div class="ibox short-code">
                    <div class="ibox-title">
                        <h5>Short Code</h5>
                    </div>
                    <div class="ibox-content">
                        <textarea name="" class="textarea form-control"></textarea>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="text-right mb15">
            <button class="btn btn-primary" type="submit" name="send" value="send">{{ $config['seo']['btnTitle'] }}</button>
        </div>
    </div>
</form>
