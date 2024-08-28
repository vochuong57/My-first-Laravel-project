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
@if($config['method']=='edit')
<div class="language-container" style="margin: 12">
    @foreach($languages as $language)
        @php
            $url = (session('app_locale') == $language->canonical) ? route('slide.edit', ['id' => $id]) 
            : route('slide.translate', ['languageId' => $language->id, 'id' => $id]);
        @endphp
        <div class="uk-flex uk-flex-middle">
        
            <a  class="image img-cover system-flag"
                href="{{ $url }}">
                <img src="{{ $language->image }}" alt="">
            </a>
        </div>
    @endforeach
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
                        @include('Backend.slide.slide.component.list')
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="ibox slide-setting slide-normal">
                    <div class="ibox-title">
                        <h5>Cài đặt cơ bản</h5>
                    </div>
                    <div class="ibox-content">
                        @include('Backend.slide.slide.component.asideBasic')      
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
                            @include('Backend.slide.slide.component.asideAdvance')
                        </div>
                    </div>
                   
                </div>
                <div class="ibox short-code">
                    <div class="ibox-title">
                        <h5>Short Code</h5>
                    </div>
                    <div class="ibox-content">
                        <textarea name="short_code" class="textarea form-control">{{ old('short_code', $slide->short_code ?? '') }}</textarea>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="text-right mb15">
            <button class="btn btn-primary" type="submit" name="send" value="send">{{ $config['seo']['btnTitle'] }}</button>
        </div>
    </div>
</form>
