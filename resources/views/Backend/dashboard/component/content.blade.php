<div class="ibox">
    <div class="ibox-title">
        <h5>{{ __('messages.general') }}</h5>
    </div>
    <div class="ibox-content">
        <div class="row mb30">
            <div class="col-lg-12">
                <div class="form-row">
                    <label for="" class="control-label text-left">Tiêu đề bài viết: <span
                            class="text-danger">(*)</span></label>
                    <input type="text" name="name" value="{{ old('name', ($object->name)??'') }}" class="form-control"
                        placeholder="" autocomplete="off">
                </div>
            </div>
        </div>
        <div class="row mb30">
            <div class="col-lg-12">
                <div class="form-row">
                    <label for="" class="control-label text-left">Mô tả ngắn: </label>
                    <textarea type="text" placeholder="" autocomplete="off" name="description"
                        class="form-control ck-editor" id="description" data-height="150">
            {{ old('description', ($object->description)??'') }}
            </textarea>
                </div>
            </div>
        </div>
        <div class="row mb-15">
            <div class="col-lg-12">
                <div class="form-row">
                    <div class="uk-flex uk-flex-middle uk-flex-space-between">
                        <label for="" class="control-label text-left">Nội dung: </label>
                        <a href="" class="multipleUploadImageCkeditor" data-target="ckContent">Upload nhiều hình ảnh</a>
                    </div>
                    <textarea type="text" placeholder="" autocomplete="off" name="content"
                        class="form-control ck-editor" id="ckContent" data-height="500">
            {{ old('content', ($object->content)??'') }}
            </textarea>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="ibox">
    <div class="ibox-title">
        <h5>{{ __('messages.seo') }}</h5>
    </div>
    <div class="ibox-content">
        <div class="seo-container">
            <div class="meta-title">
                {{ old('meta_title', ($object->meta_title) ?? __('messages.seo_title')) }}
            </div>
            <div class="canonical">
                {{ old('canonical', ($object->canonical)??'') ? config('app.url').old('canonical', ($object->canonical)??'').config('apps.general.suffix') : __('messages.seo_canonical') }}
            </div>
            <div class="meta-description">
                {{ old('meta_description', ($object->meta_description) ?? __('messages.seo_description')) }}
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
                        <input type="text" name="meta_title"
                            value="{{ old('meta_title', ($object->meta_title) ?? '') }}" class="form-control"
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
                        <input type="text" name="meta_keyword"
                            value="{{ old('meta_keyword', ($object->meta_keyword)??'') }}" class="form-control"
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
                        <textarea type="text" name="meta_description" placeholder="" class="form-control"
                            autocomplete="off">{{ old('meta_description', ($object->meta_description)??'') }}</textarea>
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
                            <input type="text" name="canonical"
                                value="{{ old('canonical', ($object->canonical)??'') }}" class="form-control seo-canonical"
                                placeholder="" autocomplete="off">
                            <span class="baseUrl">{{ config('app.url') }}</span>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>