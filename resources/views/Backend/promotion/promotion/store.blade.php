@include('Backend.dashboard.component.breadcrumb', ['title' =>$config['seo']['title']])
<!-- từ khóa tìm kiếm/validation/Displaying the Validation Errors -->
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
@php
    $url=($config['method']=='create')?route('promotion.create'):route('promotion.update', $promotion->id)
@endphp
<form action="{{ $url }}" method="post" class="box">
    @csrf
    <div class="wrapper wrapper-content animated fadeInRight promotion-wrapper">
        <div class="row">
            <div class="col-lg-8">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Thông tin chung</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="row mb15">
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Tên chương trình: <span class="text-danger">(*)</span></label>
                                    <input 
                                        type="text"
                                        name="name"
                                        value="{{ old('name', ($promotion->name)??'') }}"
                                        class="form-control"
                                        placeholder=""
                                        autocomplete="off"
                                    >
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Mã khuyến mãi: <span class="text-danger">(*)</span></label>
                                    <input 
                                        type="text"
                                        name="code"
                                        value="{{ old('code', ($promotion->code)??'') }}"
                                        class="form-control"
                                        placeholder=""
                                        autocomplete="off"
                                    >
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Mô tả khuyến mãi: </label>
                                    <textarea name="description" class="form-control form-textare" style="height: 100px;">{{ old('description', $promotion->description ?? null) }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Cài đặt thông tin chi tiết khuyến mãi</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="form-row">
                            <div class="fix-label ml5" for="">Chọn hình thức khuyến mãi</div>
                            <select name="" id="" class="setupSelect2 promotionMethod">
                                <option value="0">Chọn hình thức</option>
                                @foreach(__('module.promotion') as $key => $val)
                                    <option value="{{ $key }}">{{ $val }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="promotion-container">
                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
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
                                    value="{{ old('startDate', ($promotion->startDate)??'') }}"
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
                                    value="{{ old('endDate', ($promotion->endDate)??'') }}"
                                    class="form-control datepicker"
                                    placeholder="dd/mm/yyyy H:i"
                                    autocomplete="off"
                                    @php
                                        // Lấy giá trị 'neverDate' từ DB hoặc từ old
                                        $neverDate = old('neverDate', $promotion->neverDate ?? 'noData');
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
                                        $neverDate = old('neverDate', $promotion->neverDate ?? 'noData');
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
                                    {{ (old('source', $promotion->sourceStatus ?? '') || !old('source') ) == 'all' ? 'checked' : '' }}
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
                                    {{ (old('source', $promotion->sourceStatus ?? '')) == 'choose' ? 'checked' : '' }}
                                >
                                <label class="fix-label ml5" for="chooseSource">Chọn nguồn khách áp dụng</label>
                            </div>
                            @php
                                // V112 save form Source
                                $sourceStatus = old('source', $promotion->sourceStatus ?? null)
                            @endphp
                            @if($sourceStatus == 'choose')
                                @php
                                    $sourceValue = old('sourceValue', $promotion->sourceValue ?? []); 
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
                                    {{ (old('apply', $promotion->applyStatus ?? '') || !old('apply') ) == 'all' ? 'checked' : '' }}
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
                                    {{ (old('apply', $promotion->applyStatus ?? '')) == 'choose' ? 'checked' : '' }}
                                >
                                <label class="fix-label ml5" for="chooseApply">Chọn đối tượng áp dụng</label>
                            </div>
                        </div>
                        @php
                            $applyStatus = old('apply', $promotion->applyStatus ?? null);
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
            </div>
        </div>
       
        <div class="text-right mb15">
            <button class="btn btn-primary" type="submit" name="send" value="send">{{ $config['seo']['btnTitle'] }}</button>
        </div>
    </div>
</form>
@include('Backend.promotion.promotion.component.popup')
<!-- Đổ ra dữ liệu sản phẩm áp dụng select cho hình thức khuyến mãi product_and_quantity -->
<input type="hidden" class="input-product-and-quantity" value="{{ json_encode(__('module.item')) }}">

<!-- Xử lý save form cho đối tượng áp dụng -->
<input type="hidden" class="applyValueList" value="{{ json_encode(__('module.applyValue')) }}">
@php
    $applyValue = old('applyValue', $promotion->applyValue ?? []);
@endphp
@if(count($applyValue))
    <input type="hidden" class="conditionItemSelected" value="{{ json_encode($applyValue) }}">
    @foreach($applyValue as $key => $val)
        <input type="hidden" class="condition_input_{{ $val }}" value="{{ json_encode(old($val)) }}">
    @endforeach
@endif