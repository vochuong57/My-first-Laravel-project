@include('Backend.dashboard.component.breadcrumb', ['title' =>$config['seo']['title']])
@include('Backend.dashboard.component.formError')

@php
    $url=($config['method']=='create')?route('attribute.catalogue.create'):route('attribute.catalogue.update', $attributeCatalogue->id)
@endphp
<form action="{{ $url }}" method="post" class="box">
    @csrf
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-9">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>{{ __('messages.general') }}</h5>
                    </div>
                    <div class="ibox-content">
                        @include('Backend.attribute.catalogue.component.general')
                    </div>
                </div>
                @include('Backend.dashboard.component.album')
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>{{ __('messages.seo') }}</h5>
                    </div>
                    <div class="ibox-content">
                        @include('Backend.attribute.catalogue.component.seo')
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                @include('Backend.attribute.catalogue.component.aside')
            </div>
        </div>
        
        <div class="text-right mb15 button-fix">
            <button class="btn btn-primary" type="submit" name="send" value="send">{{ $config['seo']['btnTitle'] }}</button>
        </div>
    </div>
</form>