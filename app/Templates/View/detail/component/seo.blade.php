<div class="seo-container">
    <div class="meta-title">
        {{ old('meta_title', (${moduleTemplate}->meta_title) ?? 'Bạn chưa có tiêu đề SEO') }}
    </div>
    <div class="canonical">
        {{ old('canonical', (${moduleTemplate}->canonical)??'') ? config('app.url').old('canonical', (${moduleTemplate}->canonical)??'').config('apps.general.suffix') : 'https://duong-dan-cua-ban.html' }}
    </div>
    <div class="meta-description">
        {{ old('meta_description', (${moduleTemplate}->meta_description) ?? 'Bạn chưa có mô tả SEO') }}
    </div>
</div>
<div class="seo-wrapper">
    <div class="row mb15">
        <div class="col-lg-12">
            <div class="form-row">
                <label for="" class="control-label text-left">
                    <div class="uk-flex uk-flex-middle uk-flex-space-between">
                        <span>Tiều đề SEO</span>
                        <span class="count_meta-title">0 ký tự</span>
                    </div>
                </label>
                <input type="text" name="meta_title" 
                    value="{{ old('meta_title', (${moduleTemplate}->meta_title) ?? '') }}" class="form-control" placeholder="" autocomplete="off">
            </div>
        </div>
    </div>
    <div class="row mb15">
        <div class="col-lg-12">
            <div class="form-row">
                <label for="" class="control-label text-left">
                    <span>Từ khóa SEO</span>
                </label>
                <input type="text" name="meta_keyword"
                    value="{{ old('meta_keyword', (${moduleTemplate}->meta_keyword)??'') }}" class="form-control"
                    placeholder="" autocomplete="off">
            </div>
        </div>
    </div>
    <div class="row mb15">
        <div class="col-lg-12">
            <div class="form-row">
                <label for="" class="control-label text-left">
                    <div class="uk-flex uk-flex-middle uk-flex-space-between">
                        <span>Mô tả SEO</span>
                        <span class="count_meta-title">0 ký tự</span>
                    </div>
                </label>
                <textarea 
                    type="text" 
                    name="meta_description"
                    placeholder="" 
                    class="form-control"
                    autocomplete="off">{{ old('meta_description', (${moduleTemplate}->meta_description)??'') }}</textarea>
            </div>
        </div>
    </div>
    <div class="row mb15">
        <div class="col-lg-12">
            <div class="form-row">
                <label for="" class="control-label text-left">
                    <div class="uk-flex uk-flex-middle uk-flex-space-between">
                        <span>Đường dẫn <span class="text-danger">*</span></span>
                        <span class="count_meta-title">0 ký tự</span>
                    </div>
                </label>
                <div class="input-wrapper">
                    <input type="text" name="canonical" value="{{ old('canonical', (${moduleTemplate}->canonical)??'') }}"
                        class="form-control seo-canonical" placeholder="" autocomplete="off">
                        <span class="baseUrl">{{ config('app.url') }}</span>
                </div>
                
            </div>
        </div>
    </div>
</div>