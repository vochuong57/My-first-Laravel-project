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
    $url=($config['method']=='create')?route('widget.create'):route('widget.update', $widget->id)
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
                            <select name="" id="" class="setupSelect2">
                                <option value="">Chọn hình thức</option>
                            </select>
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
                                >
                                <span><i class="fa fa-calendar"></i></span>
                            </div>
                        </div>
                        <div class="form-row mt10">
                            <div class="uk-flex uk-flex-middle">
                                <input 
                                    type="checkbox"
                                    name=""
                                    value="accept"
                                    class=""
                                    id="neverDate"
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
                                    value="hide"
                                    name="source"
                                    id="navitate_hide"
                                >
                                <label class="fix-label ml5" for="navitate_hide">Áp dụng cho toàn bộ nguồn khách</label>
                            </div>
                            <div class="nav-setting-item uk-flex uk-flex-middle">
                                <input 
                                    type="radio"
                                    value="dots"
                                    name="source"
                                    id="navitate_dots"
                                    checked
                                >
                                <label class="fix-label ml5" for="navitate_dots">Chọn nguồn khách áp dụng</label>
                            </div>
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
                                    value="hide"
                                    name="source"
                                    id="navitate_hide"
                                >
                                <label class="fix-label ml5" for="navitate_hide">Áp dụng cho toàn bộ nguồn khách</label>
                            </div>
                            <div class="nav-setting-item uk-flex uk-flex-middle">
                                <input 
                                    type="radio"
                                    value="dots"
                                    name="source"
                                    id="navitate_dots"
                                    checked
                                >
                                <label class="fix-label ml5" for="navitate_dots">Chọn đối tượng khách hàng</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
       
        <div class="text-right mb15">
            <button class="btn btn-primary" type="submit" name="send" value="send">{{ $config['seo']['btnTitle'] }}</button>
        </div>
    </div>
</form>
