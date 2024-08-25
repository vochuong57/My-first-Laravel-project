<div class="setting-item">
    <div class="uk-flex uk-flex-middle">
        <span class="setting-text">Tự động chạy</span>
        <div class="setting-value">
            <input type="checkbox" name="setting[autoplay]" value="accept" @if(!old() || old('setting.autoplay') == 'accept') checked="checked" @endif>
        </div>
    </div>
</div>
<div class="setting-item">
    <div class="uk-flex uk-flex-middle">
        <span class="setting-text">Dừng khi di chuột</span>
        <div class="setting-value">
            <input type="checkbox" name="setting[pauseHover]" value="accept" @if(!old() || old('setting.pauseHover') == 'accept') checked="checked" @endif>
        </div>
    </div>
</div>
<div class="setting-item">
    <div class="uk-flex uk-flex-middle">
        <span class="setting-text">Chuyển ảnh</span>
        <div class="setting-value">
            <input type="text" name="setting[animationDelay]" class="form-control int" value="{{ old('setting.animationDelay', ($listSlides->animationDelay) ?? 1000) }}">
            <span class="px">ms</span>
        </div>
    </div>
</div>
<div class="setting-item">
    <div class="uk-flex uk-flex-middle">
        <span class="setting-text">Tốc độ & HIệu ứng</span>
        <div class="setting-value">
            <input type="text" name="setting[animationSpeed]" class="form-control int" value="{{ old('setting.animationSpeed', ($listSlides->animationSpeed) ?? 2000) }}">
            <span class="px">ms</span>
        </div>
    </div>
</div>