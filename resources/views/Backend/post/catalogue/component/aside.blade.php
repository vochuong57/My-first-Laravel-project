<div class="ibox">
    <div class="ibox-content">
        <div class="row">
            <div class="col-lg-12">
                <div class="form-row">
                    <label for="" class="control-label text-left">Chọn danh mục cha:
                        <span class="text-danger">(*)</span>
                        <span class="text-danger notice">*Chọn root nếu không có danh mục cha</span>
                    </label>
                    <select name="parent_id" class="form-control setupSelect2" id="">
                        @foreach($dropdown as $key => $val)
                        <option
                            {{ $key == old('parent_id', (isset($postCatalogue->parent_id)) ? $postCatalogue->parent_id : '') ? 'selected' : '' }} 
                            value="{{ $key }}"
                        >
                            {{ $val }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

        </div>
    </div>
</div>
<div class="ibox">
    <div class="ibox-title">
        <h5>Chọn ảnh đại diện</h5>
    </div>
    <div class="ibox-content">
        <div class="row">
            <div class="col-lg-12">
                <div class="form-row">
                    <span class="image img-cover image-target">
                        <img src="{{ old('image', ($postCatalogue->image)) ?? 'Backend/img/not-found.png' }}" alt="">
                    </span>
                    <input type="hidden" name="image" value="{{ old('image', ($postCatalogue->image)??'') }}">
                </div>
            </div>

        </div>
    </div>
</div>
<div class="ibox">
    <div class="ibox-title">
        <h5>Cấu hình nâng cao</h5>
    </div>
    <div class="ibox-content">
        <div class="row">
            <div class="col-lg-12">
                <div class="form-row">
                   
                    <div class="mb15">
                        <select name="publish" class="form-control setupSelect2" id="">
                            @foreach(config('apps.general.publish') as $key => $val)
                                <option 
                                    {{ $key == old('publish', (isset($postCatalogue->publish)) ? $postCatalogue->publish : '') ? 'selected' : '' }} 
                                    value="{{ $key }}"
                                >
                                    {{ $val }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    
                    <div class="mb15">
                        <select name="follow" class="form-control setupSelect2" id="">
                            @foreach(config('apps.general.follow') as $key => $val)
                            <option 
                                {{ $key == old('follow', (isset($postCatalogue->follow)) ? $postCatalogue->follow : '') ? 'selected' : '' }} 
                                value="{{ $key }}"
                            >
                                {{ $val }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>