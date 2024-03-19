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
    $url=($config['method']=='create')?route('post.catalogue.create'):route('post.catalogue.update', $postCatalogue->id)
@endphp
<form action="{{ $url }}" method="post" class="box">
    @csrf
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-9">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Thông tin chung</h5>
                    </div>
                    <div class="ibox-content">
                        @include('Backend.post.catalogue.component.general')
                    </div>
                </div>
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Cấu hình SEO</h5>
                    </div>
                    <div class="ibox-content">
                        @include('Backend.post.catalogue.component.seo')
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                @include('Backend.post.catalogue.component.aside')
            </div>
        </div>
        
        <div class="text-right mb15">
            <button class="btn btn-primary" type="submit" name="send" value="send">{{ $config['seo']['btnTitle'] }}</button>
        </div>
    </div>
</form>
