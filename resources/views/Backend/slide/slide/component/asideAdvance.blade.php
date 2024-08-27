<div class="setting-item">
    <div class="uk-flex uk-flex-middle">
        <span class="setting-text">Tự động chạy</span>
        <div class="setting-value">
            @php
                // Lấy giá trị 'autoplay' từ old hoặc từ DB
                $autoplay = old('setting.autoplay', $slide->setting['autoplay'] ?? 'accept');
            @endphp
            <input type="checkbox" name="setting[autoplay]" value="accept" {{ ($autoplay == 'accept') ? 'checked' : ''  }}>
        </div>
    </div>
</div>
<div class="setting-item">
    <div class="uk-flex uk-flex-middle">
        <span class="setting-text">Dừng khi di chuột</span>
        <div class="setting-value">
            @php
                // Lấy giá trị 'pauseHover' từ old hoặc từ DB
                $pauseHover = old('setting.pauseHover', $slide->setting['pauseHover'] ?? 'accept');
            @endphp
            <input type="checkbox" name="setting[pauseHover]" value="accept" {{ ($pauseHover == 'accept') ? 'checked' : ''  }}>
        </div>
    </div>
</div>
<div class="setting-item">
    <div class="uk-flex uk-flex-middle">
        <span class="setting-text">Chuyển ảnh</span>
        <div class="setting-value">
            <input type="text" name="setting[animationDelay]" class="form-control int" value="{{ old('setting.animationDelay', ($slide->setting['animationDelay']) ?? 1000) }}">
            <span class="px">ms</span>
        </div>
    </div>
</div>
<div class="setting-item">
    <div class="uk-flex uk-flex-middle">
        <span class="setting-text">Tốc độ & Hiệu ứng</span>
        <div class="setting-value">
            <input type="text" name="setting[animationSpeed]" class="form-control int" value="{{ old('setting.animationSpeed', ($slide->setting['animationSpeed']) ?? 2000) }}">
            <span class="px">ms</span>
        </div>
    </div>
</div>