<div class="ibox">
    <div class="ibox-title">
        <h5>{{ __('messages.general') }}</h5>
    </div>
    <div class="ibox-content">
        <div class="row mb30">
            <div class="col-lg-12">
                <div class="form-row">
                    <label for="" class="control-label text-left">{{ __('messages.title_general') }} <span
                            class="text-danger">(*)</span></label>
                    <input type="text" name="name" value="{{ old('name', ($model->name)??'') }}" class="form-control"
                        placeholder="" autocomplete="off" {{(isset($disabled)) ? 'disabled' : ''}}>
                </div>
            </div>
        </div>
        <div class="row mb30">
            <div class="col-lg-12">
                <div class="form-row">
                    <label for="" class="control-label text-left">{{ __('messages.description') }} </label>
                    <textarea type="text" placeholder="" autocomplete="off" name="description"
                        class="form-control ck-editor" id="description" data-height="150" {{(isset($disabled)) ? 'disabled' : ''}}>
            {{ old('description', ($model->description)??'') }}
            </textarea>
                </div>
            </div>
        </div>
        <div class="row mb-15">
            <div class="col-lg-12">
                <div class="form-row">
                    <div class="uk-flex uk-flex-middle uk-flex-space-between">
                        <label for="" class="control-label text-left">{{ __('messages.content') }} </label>
                        <a href="" class="multipleUploadImageCkeditor" data-target="ckContent">Upload nhiều hình ảnh</a>
                    </div>
                    <textarea type="text" placeholder="" autocomplete="off" name="content"
                        class="form-control ck-editor" id="ckContent" data-height="500" {{(isset($disabled)) ? 'disabled' : ''}}>
            {{ old('content', ($model->content)??'') }}
            </textarea>
                </div>
            </div>
        </div>
    </div>
</div>
