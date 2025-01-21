<div class="ibox">
    <div class="ibox-title">
        <h5>Thời gian áp dụng chương trình</h5>
    </div>
    <div class="ibox-content">
        <div class="form-row mb15">
            <label for="" class="control-label text-left">Ngày bắt đầu: <span class="text-danger">(*)</span></label>
            <div class="form-date">
                <input 
                    type="text"
                    name="startDate"
                    value="{{ old('startDate', isset($model->startDate) ? convertDateTime($model->startDate) : '') }}"
                    class="form-control datepicker"
                    placeholder="dd/mm/yyyy H:i"
                    autocomplete="off"
                >
                <span><i class="fa fa-calendar"></i></span>
            </div>
        </div>
        <div class="form-row mb15">
            <label for="" class="control-label text-left">Ngày kết thúc: <span class="text-danger">(*)</span></label>
            <div class="form-date">
                <input 
                    type="text"
                    name="endDate"
                    value="{{ old('endDate', isset($model->endDate) ? convertDateTime($model->endDate) : '') }}"
                    class="form-control datepicker"
                    placeholder="dd/mm/yyyy H:i"
                    autocomplete="off"
                    @php
                        // Lấy giá trị 'neverDate' từ DB hoặc từ old
                        $neverDate = old('neverDate', $model->neverDate ?? 'noData');
                    @endphp
                    {{ ($neverDate == 'noData') ? '' : 'disabled' }}
                >
                <span><i class="fa fa-calendar"></i></span>
            </div>
        </div>
        <div class="form-row mt10">
            <div class="uk-flex uk-flex-middle">
                <input 
                    type="checkbox"
                    name="neverDate"
                    value="accept"
                    class=""
                    id="neverDate"
                    @php
                        // Lấy giá trị 'neverDate' từ DB hoặc từ old
                        $neverDate = old('neverDate', $model->neverDate ?? 'noData');
                    @endphp
                    {{ ($neverDate == 'noData') ? '' : 'checked'  }}
                >
                <label class="fix-label ml5" for="neverDate">Không có ngày kết thúc</label>
            </div>
        </div>
    </div>
</div>
<div class="ibox">
    <div class="ibox-title">
        <h5>Nguồn khách áp dụng</h5>
    </div>
    <div class="ibox-content">
        <div class="setting-value">
            <div class="nav-setting-item uk-flex uk-flex-middle">
                <input 
                    type="radio"
                    value="all"
                    name="source"
                    id="allSource"
                    class="chooseSource"
                    {{ (old('source', $model->discountInformation['source']['status'] ?? '') || !old('source') ) == 'all' ? 'checked' : '' }}
                >
                <label class="fix-label ml5" for="allSource">Áp dụng cho toàn bộ nguồn khách</label>
            </div>
            <div class="nav-setting-item uk-flex uk-flex-middle">
                <input 
                    type="radio"
                    value="choose"
                    name="source"
                    id="chooseSource"
                    class="chooseSource"
                    {{ (old('source', $model->discountInformation['source']['status'] ?? '')) == 'choose' ? 'checked' : '' }}
                >
                <label class="fix-label ml5" for="chooseSource">Chọn nguồn khách áp dụng</label>
            </div>
            @php
                // V112 save form Source
                $sourceStatus = old('source', $model->discountInformation['source']['status'] ?? null)
            @endphp
            @if($sourceStatus == 'choose')
                @php
                    $sourceValue = old('sourceValue', $model->discountInformation['source']['data'] ?? []); 
                @endphp
                <div class="source-wrapper">
                    <select name="sourceValue[]" id="" class="multipleSelect2" multiple>
                        @foreach($sources as $key => $val)
                        <option 
                            value="{{ $val->id }}"
                            {{ (in_array($val->id, $sourceValue)) ? 'selected' : '' }}
                        >
                        {{ $val->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
            @endif
        </div>
    </div>
</div>
<div class="ibox">
    <div class="ibox-title">
        <h5>Đối tượng áp dụng</h5>
    </div>
    <div class="ibox-content">
        <div class="setting-value">
            <div class="nav-setting-item uk-flex uk-flex-middle">
                <input 
                    type="radio"
                    value="all"
                    name="apply"
                    id="allApply"
                    class="chooseApply"
                    {{ (old('apply', $model->discountInformation['apply']['status'] ?? '') || !old('apply') ) == 'all' ? 'checked' : '' }}
                >
                <label class="fix-label ml5" for="allApply">Áp dụng cho toàn bộ đối tượng</label>
            </div>
            <div class="nav-setting-item uk-flex uk-flex-middle">
                <input 
                    type="radio"
                    value="choose"
                    name="apply"
                    id="chooseApply"
                    class="chooseApply"
                    {{ (old('apply', $model->discountInformation['apply']['status'] ?? '')) == 'choose' ? 'checked' : '' }}
                >
                <label class="fix-label ml5" for="chooseApply">Chọn đối tượng áp dụng</label>
            </div>
        </div>
        @php
            $applyStatus = old('apply', $model->discountInformation['apply']['status'] ?? null);
        @endphp
        @if($applyStatus == 'choose')
            <div class="apply-wrapper">
                <select class="multipleSelect2 conditionItem" name="applyValue[]" id="" multiple>
                    @foreach(__('module.applyValue') as $key => $val)
                    <option value="{{ $val['id'] }}">{{ $val['name'] }}</option>
                    @endforeach
                </select>

                <div class="wrapper-condition"></div>
            </div>
        @endif
    </div>
</div>