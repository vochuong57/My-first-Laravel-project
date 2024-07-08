<div class="row mb30">
    <div class="col-lg-12">
        <div class="form-row">
            <label for="" class="control-label text-left">Tiêu đề bài viết: <span
                    class="text-danger">(*)</span></label>
            <input type="text" name="name" value="{{ old('name', (${moduleTemplate}->name)??'') }}" class="form-control"
                placeholder="" autocomplete="off">
        </div>
    </div>
</div>
<div class="row mb30">
    <div class="col-lg-12">
        <div class="form-row">
            <label for="" class="control-label text-left">Mô tả ngắn: </label>
            <textarea 
                type="text" 
                placeholder="" 
                autocomplete="off"
                name="description" 
                class="form-control ck-editor" 
                id="description" 
                data-height="150"
            >
            {{ old('description', (${moduleTemplate}->description)??'') }}
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
            <textarea 
                type="text" 
                placeholder="" 
                autocomplete="off"
                name="content" 
                class="form-control ck-editor" 
                id="ckContent" 
                data-height="500"
            >
            {{ old('content', (${moduleTemplate}->content)??'') }}
            </textarea>
        </div>
    </div>
</div>