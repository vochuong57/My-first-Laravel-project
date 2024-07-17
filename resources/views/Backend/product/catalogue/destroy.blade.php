@include('Backend.dashboard.component.breadcrumb', ['title' =>$config['seo']['title']])
@include('Backend.dashboard.component.formError')

<form action="{{ route('product.catalogue.delete',$productCatalogue->id) }}" method="post" class="box">
    @csrf
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-5">
                <div class="panel-head">
                    <div class="panel-title">{{ __('messages.general') }}</div>
                    <div class="panel-description">
                        <p>{{ __('messages.destroy_panel_description_productCatalogue_1') }} <span style="color: red">{{ $productCatalogue->name }}</span></p>
                        <p>{{ __('messages.destroy_panel_description_1') }} <span class="text-danger">{{ __('messages.destroy_panel_description_2') }}</span> {{ __('messages.destroy_panel_description_productCatalogue_2') }} <br> {{ __('messages.destroy_panel_description_3') }}</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>{{ __('messages.destroy') }}</h5>
                    </div>
                    <div class="ibox-content">
                    <div class="row mb15">
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">{{ __('messages.destroyProductCatalogue_name') }} <span class="text-danger">(*)</span></label>
                                    <input 
                                    type="text"
                                    name="name"
                                    value="{{ old('name', ($productCatalogue->name)??'') }}"
                                    class="form-control"
                                    placeholder=""
                                    autocomplete="off"
                                    >
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Canonical: <span class="text-danger">(*)</span></label>
                                    <input 
                                    type="text"
                                    name="canonical"
                                    value="{{ old('canonical', ($productCatalogue->canonical)??'') ? config('app.url').old('canonical', ($productCatalogue->canonical)??'').config('apps.general.suffix') :  __('messages.seo_canonical') }}"
                                    class="form-control"
                                    placeholder=""
                                    autocomplete="off"
                                    >
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        
        <div class="text-right mb15">
            <button class="btn btn-danger" type="submit" name="send" value="send">{{ $config['seo']['btnDelete'] }}</button>
        </div>
    </div>
</form>
