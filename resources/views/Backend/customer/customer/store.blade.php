@include('Backend.dashboard.component.breadcrumb', ['title' =>$config['seo']['title']])
@include('Backend.dashboard.component.formError')

@php
    $url=($config['method']=='create')?route('customer.create'):route('customer.update', $customer->id)
@endphp
<form action="{{ $url }}" method="post" class="box">
    @csrf
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-5">
                <div class="panel-head">
                    <div class="panel-title">Thông tin chung</div>
                    <div class="panel-description">
                        <p>- Nhập thông tin chung của người sử dụng</p>
                        <p>- Lưu ý: những trường đánh dấu <span class="text-danger">(*)</span> là bắt buộc</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Thông tin chung</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="row mb15">
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Email: <span class="text-danger">(*)</span></label>
                                    <input 
                                    type="text"
                                    name="email"
                                    value="{{ old('email', ($customer->email)??'') }}"
                                    class="form-control"
                                    placeholder=""
                                    autocomplete="off"
                                    >
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Họ tên: <span class="text-danger">(*)</span></label>
                                    <input 
                                    type="text"
                                    name="name"
                                    value="{{ old('name', ($customer->name)??'') }}"
                                    class="form-control"
                                    placeholder=""
                                    autocomplete="off"
                                    >
                                </div>
                            </div>
                        </div>
                        <div class="row mb15">
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Nhóm khách hàng: <span class="text-danger">(*)</span></label>
                                    <select name="customer_catalogue_id" id="" class="form-control setupSelect2">
                                        <option value="0">[Chọn nhóm thành viên]</option>
                                        @foreach($customerCatalogues as $customerCatalogue)
                                            <option 
                                                {{ $customerCatalogue->id == old('customer_catalogue_id', (isset($customer->customer_catalogue_id)) ? $customer->customer_catalogue_id : '') ? 'selected' : '' }} 
                                                value="{{ $customerCatalogue->id }}"
                                            >
                                                {{ $customerCatalogue->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Nguồn khách: <span class="text-danger">(*)</span></label>
                                    <select name="source_id" id="" class="form-control setupSelect2">
                                        <option value="0">[Chọn nguồn khách]</option>
                                        @foreach($sources as $source)
                                            <option 
                                                {{ $source->id == old('source_id', (isset($customer->source_id)) ? $customer->source_id : '') ? 'selected' : '' }} 
                                                value="{{ $source->id }}"
                                            >
                                                {{ $source->name }}
                                            </option>
                                        @endforeach
                                    </select>   
                                </div>
                            </div>
                            <div class="col-lg-6 mt20">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Ngày sinh: </label>
                                    <input 
                                    type="date"
                                    name="birthday"
                                    value="{{ old('birthday', (isset($customer->birthday))?date('Y-m-d', strtotime($customer->birthday)):'') }}"
                                    class="form-control"
                                    placeholder=""
                                    autocomplete="off"
                                    >
                                </div>
                            </div>
                        </div>
                        @if($config['method']=='create')
                        <div class="row mb15">
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Mật khẩu: <span class="text-danger">(*)</span></label>
                                    <input 
                                    type="password"
                                    name="password"
                                    value=""
                                    class="form-control"
                                    placeholder=""
                                    autocomplete="off"
                                    >
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Nhập lại mật khẩu: <span class="text-danger">(*)</span></label>
                                    <input 
                                    type="password"
                                    name="repassword"
                                    value=""
                                    class="form-control"
                                    placeholder=""
                                    autocomplete="off"
                                    >
                                </div>
                            </div>
                        </div>
                        @endif
                        <div class="row mb15">
                            <div class="col-lg-12">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Ảnh đại diện: </label>
                                    <input 
                                    type="text"
                                    name="image"
                                    value="{{ old('image', ($customer->image)??'') }}"
                                    class="form-control upload-image"
                                    placeholder=""
                                    autocomplete="off"
                                    data-type="Images"
                                    >
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-lg-5">
                <div class="panel-head">
                    <div class="panel-title">Thông tin liên hệ</div>
                    <div class="panel-description">
                        <p>- Nhập thông tin liên hệ của người sử dụng</p>
                        <p>- Lưu ý: những trường đánh dấu <span class="text-danger">(*)</span> là bắt buộc</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Thông tin liên hệ</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="row mb15">
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Thành phố: </label>
                                    <select name="province_id" id="" class="form-control setupSelect2 provinces location" data-target="DTdistricts">
                                        <option value="0">[Chọn thành phố]</option>
                                        @if(isset($provinces))
                                        @foreach($provinces as $province)
                                        <option value="{{ $province->code }}">{{ $province->name }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Quận/Huyện: </label>
                                    <select name="district_id" id="" class="form-control setupSelect2 districts DTdistricts location" data-target="DTwards">
                                        <option value="0">[Chọn Quận/Huyện]</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mb15">
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Phường/Xã: </label>
                                    <select name="ward_id" id="" class="form-control setupSelect2 wards DTwards">
                                        <option value="0">[Chọn Phường/Xã]</option>
                                        
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Địa chỉ: </label>
                                    <input 
                                    type="text"
                                    name="address"
                                    value="{{ old('address', ($customer->address)??'') }}"
                                    class="form-control"
                                    placeholder=""
                                    autocomplete="off"
                                    >
                                </div>
                            </div>
                        </div>
                        <div class="row mb15">
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Số điện thoại: <span class="text-danger">(*)</span></label>
                                    <input 
                                    type="text"
                                    name="phone"
                                    value="{{ old('phone', ($customer->phone)??'') }}"
                                    class="form-control"
                                    placeholder=""
                                    autocomplete="off"
                                    >
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Ghi chú: </label>
                                    <input 
                                    type="text"
                                    name="description"
                                    value="{{ old('description', ($customer->description)??'') }}"
                                    class="form-control"
                                    placeholder=""
                                    autocomplete="off"
                                    >
                                </div>
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
<!-- XỬ LÝ HUYỆN XẢ KHI NHẬP SAI BỊ LOAD LẠI TRANG -->
<script>
    var province_id='{{ (isset($customer->province_id)) ? $customer->province_id : old('province_id') }}'
    var district_id='{{ (isset($customer->district_id)) ? $customer->district_id : old('district_id') }}'
    var ward_id='{{ (isset($customer->ward_id)) ? $customer->ward_id : old('ward_id') }}'
</script>