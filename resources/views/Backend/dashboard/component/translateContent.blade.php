@if(!isset($offTitle))
<div class="row mb30">
    <div class="col-lg-12">
        <div class="form-row">
            <label for="" class="control-label text-left">{{ __('messages.title_general') }} <span
                    class="text-danger">(*)</span></label>
            <input type="text" name="translate_name" value="{{ old('translate_name', ($model->name)??'') }}" class="form-control"
                placeholder="" autocomplete="off">
        </div>
    </div>
</div>
@endif
<div class="row mb30">
    <div class="col-lg-12">
        <div class="form-row">
            <label for="" class="control-label text-left">{{ __('messages.description') }} </label>
            <textarea type="text" placeholder="" autocomplete="off" name="translate_description"
                class="form-control ck-editor" id="description_1" data-height="150">
    {{ old('translate_description', ($model->description)??'') }}
    </textarea>
        </div>
    </div>
</div>
@if(!isset($offContent))
<div class="row mb-15">
    <div class="col-lg-12">
        <div class="form-row">
            <div class="uk-flex uk-flex-middle uk-flex-space-between">
                <label for="" class="control-label text-left">{{ __('messages.content') }} </label>
                <a href="" class="multipleUploadImageCkeditor" data-target="ckContent">Upload nhiều hình ảnh</a>
            </div>
            <textarea type="text" placeholder="" autocomplete="off" name="translate_content"
                class="form-control ck-editor" id="ckContent_1" data-height="500">
    {{ old('translate_content', ($model->content)??'') }}
    </textarea>
        </div>
    </div>
</div>
@endif