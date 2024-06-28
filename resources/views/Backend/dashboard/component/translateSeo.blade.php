<div class="ibox">
    <div class="ibox-title">
        <h5>{{ __('messages.seo') }}</h5>
    </div>
    <div class="ibox-content">
        <div class="seo-container">
            <div class="meta-title">
                {{ old('translate_meta_title', ($model->meta_title) ?? __('messages.seo_title')) }}
            </div>
            <div class="canonical">
                {{ old('translate_canonical', ($model->canonical)??'') ? config('app.url').old('canonical', ($model->canonical)??'').config('apps.general.suffix') : __('messages.seo_canonical') }}
            </div>
            <div class="meta-description">
                {{ old('translate_meta_description', ($model->meta_description) ?? __('messages.seo_description')) }}
            </div>
        </div>
        <div class="seo-wrapper">
            <div class="row mb15">
                <div class="col-lg-12">
                    <div class="form-row">
                        <label for="" class="control-label text-left">
                            <div class="uk-flex uk-flex-middle uk-flex-space-between">
                                <span>{{ __('messages.seo_meta_title') }}</span>
                                <span class="count_meta-title">0 {{__('messages.character') }}</span>
                            </div>
                        </label>
                        <input type="text" name="translate_meta_title"
                            value="{{ old('translate_meta_title', ($model->meta_title) ?? '') }}" class="form-control"
                            placeholder="" autocomplete="off">
                    </div>
                </div>
            </div>
            <div class="row mb15">
                <div class="col-lg-12">
                    <div class="form-row">
                        <label for="" class="control-label text-left">
                            <span>{{ __('messages.seo_meta_keyword') }}</span>
                        </label>
                        <input type="text" name="translate_meta_keyword"
                            value="{{ old('translate_meta_keyword', ($model->meta_keyword)??'') }}" class="form-control"
                            placeholder="" autocomplete="off">
                    </div>
                </div>
            </div>
            <div class="row mb15">
                <div class="col-lg-12">
                    <div class="form-row">
                        <label for="" class="control-label text-left">
                            <div class="uk-flex uk-flex-middle uk-flex-space-between">
                                <span>{{ __('messages.seo_meta_description') }}</span>
                                <span class="count_meta-title">0 {{ __('messages.character') }}</span>
                            </div>
                        </label>
                        <textarea type="text" name="translate_meta_description" placeholder="" class="form-control"
                            autocomplete="off">{{ old('translate_meta_description', ($model->meta_description)??'') }}</textarea>
                    </div>
                </div>
            </div>
            <div class="row mb15">
                <div class="col-lg-12">
                    <div class="form-row">
                        <label for="" class="control-label text-left">
                            <div class="uk-flex uk-flex-middle uk-flex-space-between">
                                <span>{{ __('messages.seo_meta_canonical') }} <span class="text-danger">*</span></span>
                                <span class="count_meta-title">0 {{ __('messages.character') }}</span>
                            </div>
                        </label>
                        <div class="input-wrapper">
                            <input type="text" name="translate_canonical"
                                value="{{ old('translate_canonical', ($model->canonical)??'') }}" class="form-control seo-canonical"
                                placeholder="" autocomplete="off">
                            <span class="baseUrl">{{ config('app.url') }}</span>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>