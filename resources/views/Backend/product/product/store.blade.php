@include('Backend.dashboard.component.breadcrumb', ['title' =>$config['seo']['title']])
@include('Backend.dashboard.component.formError')

@php
    $url=($config['method']=='create')?route('product.create'):route('product.update', $product->id)
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
                        @include('Backend.product.product.component.general')
                    </div>
                </div>

                @include('Backend.dashboard.component.album')

                <div class="ibox variant-box">
                    <div class="ibox-title">
                        <div>
                            <h5>{{ __('messages.The_product_has_many_versions') }}</h5>
                        </div>
                        <div class="description">{{ __('messages.tphmv_content1') }} <strong class="text-danger">{{ __('messages.tphmv_content2') }}</strong> 
                            {{ __('messages.tphmv_content3') }} <strong class="text-danger">size</strong> {{ __('messages.tphmv_content4') }} 
                            {{ __('messages.tphmv_content5') }}
                        </div>
                    </div>
                    <div class="ibox-content">
                        @include('Backend.product.product.component.variant')
                    </div>
                </div>

                <div class="ibox product-variant">
                    <div class="ibox-title">
                        <div>
                            <h5>{{ __('messages.List_product_versions') }}</h5>
                        </div>
                    </div>
                    <div class="ibox-content">
                        @include('Backend.product.product.component.productVariant')
                    </div>
                </div>

                <div class="ibox">
                    <div class="ibox-title">
                        <h5>{{ __('messages.seo') }}</h5>
                    </div>
                    <div class="ibox-content">
                        @include('Backend.product.product.component.seo')
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                @include('Backend.product.product.component.aside')
            </div>
        </div>
        
        <div class="text-right mb15 button-fix">
            <button class="btn btn-primary" type="submit" name="send" value="send">{{ $config['seo']['btnTitle'] }}</button>
        </div>
    </div>
</form>
