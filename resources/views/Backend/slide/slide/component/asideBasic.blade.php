<div class="row mb15">
    <div class="col-lg-12 mb10">
        <div class="form-row">
            <label for="" class="control-label text-left">Tên slide: <span class="text-danger">(*)</span></label>
            <input 
            type="text"
            name="name"
            value="{{ old('name', ($slide->name)??'') }}"
            class="form-control"
            placeholder=""
            autocomplete="off"
            >
        </div>
    </div>
    <div class="col-lg-12">
        <div class="form-row">
            <label for="" class="control-label text-left">Từ khóa: <span class="text-danger">(*)</span></label>
            <input 
            type="text"
            name="keyword"
            value="{{ old('keyword', ($slide->keyword)??'') }}"
            class="form-control"
            placeholder=""
            autocomplete="off"
            >
        </div>
    </div>
</div>    
<div class="row">
    <div class="col-lg-12">
        <div class="slide-setting">
            <div class="setting-item">
                <div class="uk-flex uk-flex-middle">
                    <span class="setting-text">Chiều rộng</span>
                    <div class="setting-value">
                        <input type="text" name="setting[width]" class="form-control int" value="{{ old('setting.width', ($slide->width) ?? 0) }}">
                        <span class="px">px</span>
                    </div>
                </div>
            </div>
            <div class="setting-item">
                <div class="uk-flex uk-flex-middle">
                    <span class="setting-text">Chiều cao</span>
                    <div class="setting-value">
                        <input type="text" name="setting[height]" class="form-control int" value="{{ old('setting.height', ($slide->height) ?? 0) }}">
                        <span class="px">px</span>
                    </div>
                </div>
            </div>
            <div class="setting-item">
                <div class="uk-flex uk-flex-middle">
                    <span class="setting-text">Hiệu ứng</span>
                    <div class="setting-value">
                        <select name="setting[animation]" id="" class="form-control setupSelect2">
                            @foreach(__('module.effect') as $key => $val)
                            <option {{ $key == old('setting.animation') ? 'selected' : '' }} value="{{ $key }}">{{ $val }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="setting-item">
                <div class="uk-flex uk-flex-middle">
                    <span class="setting-text">Mũi tên</span>
                    <div class="setting-value">
                        <input type="checkbox" name="setting[arrow]" value="accept" @if(!old() || old('setting.arrow') == 'accept') checked="checked" @endif>
                    </div>
                </div>
            </div>
            <div class="setting-item">
                <div class="uk-flex uk-flex-middle">
                    <span class="setting-text">Thanh điều hướng</span>
                    <div class="setting-value">
                        @foreach(__('module.navigate') as $key => $val)
                        <div class="nav-setting-item uk-flex uk-flex-middle">
                            <input 
                                type="radio" 
                                value="{{ $key }}" 
                                name="setting[navigate]" 
                                id="navigate_{{$key}}" 
                                {{ old('setting.navigate', (!old() ? 'dots' : $key)) === $key ? 'checked' : '' }}
                            >
                            <label for="navigate_{{$key}}">{{ $val }}</label>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>        