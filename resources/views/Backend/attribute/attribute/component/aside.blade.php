<div class="ibox">
    <div class="ibox-title">
        <h5>{{ __('messages.parent') }}</h5>
    </div>
    <div class="ibox-content">
        <div class="row mb15">
            <div class="col-lg-12">
                <div class="form-row">
                    <span class="text-danger notice">*{{ __('messages.parent') }}</span>
                    <select name="attribute_catalogue_id" class="form-control setupSelect2" id="">
                        @foreach($dropdown as $key => $val)
                        <option
                            {{ $key == old('attribute_catalogue_id', (isset($attribute->attribute_catalogue_id)) ? $attribute->attribute_catalogue_id : '') ? 'selected' : '' }} 
                            value="{{ $key }}"
                        >
                            {{ $val }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        @php
            //Xử lý đổ dữ liệu danh sách nhóm bài viết vào danh mục phụ
            $catalogue = [];
            if(isset($attribute)){
                foreach($attribute->attribute_catalogues as $key => $val){
                    $catalogue[]=$val->id;
                }
            }
        @endphp
        <div class="row">
            <div class="col-lg-12">
                <div class="form-row">
                    <label class="control-label">{{ __('messages.children') }}</label>
                    <select multiple name="catalogue[]" class="form-control setupSelect2" id="">
                        @foreach($dropdown as $key => $val)
                        <option
                            @if(is_array(old('catalogue', (isset($catalogue)  && count($catalogue)) ? $catalogue : [])) && in_array($key, old('catalogue', (isset($catalogue)) ? $catalogue : []))) selected @endif
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
        <h5>{{ __('messages.image') }}</h5>
    </div>
    <div class="ibox-content">
        <div class="row">
            <div class="col-lg-12">
                <div class="form-row">
                    <span class="image img-cover image-target">
                        <img src="{{ (old('image', $attribute->image ??'Backend/img/not-found.png')) ?? 'Backend/img/not-found.png' }}" alt="">
                    </span>
                    <input type="hidden" name="image" value="{{ old('image', ($attribute->image)??'') }}">
                </div>
            </div>

        </div>
    </div>
</div>
<div class="ibox">
    <div class="ibox-title">
        <h5>{{ __('messages.advance') }}</h5>
    </div>
    <div class="ibox-content">
        <div class="row">
            <div class="col-lg-12">
                <div class="form-row">
                   
                    <div class="mb15">
                        <select name="publish" class="form-control setupSelect2" id="">
                            @foreach(config('apps.general.publish') as $key => $val)
                                <option 
                                    {{ $key == old('publish', (isset($attribute->publish)) ? $attribute->publish : '') ? 'selected' : '' }} 
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
                                {{ $key == old('follow', (isset($attribute->follow)) ? $attribute->follow : '') ? 'selected' : '' }} 
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