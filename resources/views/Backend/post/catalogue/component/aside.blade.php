<div class="ibox">
    <div class="ibox-content">
        <div class="row">
            <div class="col-lg-12">
                <div class="form-row">
                    <label for="" class="control-label text-left">Chọn danh mục cha:
                        <span class="text-danger">(*)</span>
                        <span class="text-danger notice">*Chọn root nếu không có danh mục cha</span>
                    </label>
                    <select name="" class="form-control setupSelect2" id="">
                        <option value="0">Chọn danh mục cha</option>
                        <option value="1">Root</option>
                        <option value="2">...</option>
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
                    <span class="image img-cover">
                        <img src="Backend/img/not-found.png" alt="">
                        <input type="hidden" name="image" value="">
                    </span>
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
                    @php
                    $publish = request('publish') ?: old('publish');
                    @endphp
                    <div class="mb15">
                        <select name="publish" class="form-control setupSelect2" id="">
                            @foreach(config('apps.general.publish') as $key => $val)
                            <option value="{{ $key }}">{{ $val }}</option>
                            @endforeach
                        </select>
                    </div>
                    @php
                    $follow = request('follow') ?: old('follow');
                    @endphp
                    <div class="mb15">
                        <select name="follow" class="form-control setupSelect2" id="">
                            @foreach(config('apps.general.follow') as $key => $val)
                            <option value="{{ $key }}">{{ $val }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>