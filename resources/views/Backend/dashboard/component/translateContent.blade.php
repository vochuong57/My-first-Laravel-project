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
                    <input type="text" name="translate_name" value="{{ old('translate_name', ($model->name)??'') }}" class="form-control"
                        placeholder="" autocomplete="off">
                </div>
            </div>
        </div>
        <div class="row mb30">
            <div class="col-lg-12">
                <div class="form-row">
                    <label for="" class="control-label text-left">Mô tả ngắn: </label>
                    <textarea type="text" placeholder="" autocomplete="off" name="translate_description"
                        class="form-control ck-editor" id="description_1" data-height="150">
            {{ old('translate_description', ($model->description)??'') }}
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
                    <textarea type="text" placeholder="" autocomplete="off" name="translate_content"
                        class="form-control ck-editor" id="ckContent_1" data-height="500">
            {{ old('translate_content', ($model->content)??'') }}
            </textarea>
                </div>
            </div>
        </div>
    </div>
</div>