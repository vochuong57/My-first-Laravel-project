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

<form action="{{ route('customer.catalogue.delete',$customerCatalogue->id) }}" method="post" class="box">
    @csrf
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-5">
                <div class="panel-head">
                    <div class="panel-title">{{ __('messages.general') }}</div>
                    <div class="panel-description">
                        <p>{{ __('messages.destroy_panel_description_customerCatalogue_1') }} <span style="color: red">{{ $customerCatalogue->name }}</span></p>
                        <p>{{ __('messages.destroy_panel_description_1') }} <span class="text-danger">{{ __('messages.destroy_panel_description_2') }}</span> {{ __('messages.destroy_panel_description_postCatalogue_2') }} <br> {{ __('messages.destroy_panel_description_3') }}</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>{{ __('messages.general') }}</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="row mb15">
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">{{ __('messages.customerCatalogue_title') }} <span class="text-danger">(*)</span></label>
                                    <input 
                                    type="text"
                                    name="name"
                                    value="{{ old('name', ($customerCatalogue->name)??'') }}"
                                    class="form-control"
                                    placeholder=""
                                    autocomplete="off"
                                    readonly
                                    >
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">{{ __('messages.note') }} <span class="text-danger">(*)</span></label>
                                    <input 
                                    type="text"
                                    name="description"
                                    value="{{ old('description', ($customerCatalogue->description)??'') }}"
                                    class="form-control"
                                    placeholder=""
                                    autocomplete="off"
                                    readonly
                                    >
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
        
        
        <div class="text-right mb15">
            <button class="btn btn-danger" type="submit" name="send" value="send">{{ $config['seo']['btnDelete'] }}</button>
        </div>
    </div>
</form>
<!-- XỬ LÝ HUYỆN XẢ KHI NHẬP SAI BỊ LOAD LẠI TRANG -->
<script>
    var province_id='{{ (isset($customer->province_id)) ? $customer->province_id : old('province_id') }}'
    var district_id='{{ (isset($customer->district_id)) ? $customer->district_id : old('district_id') }}'
    var ward_id='{{ (isset($customer->ward_id)) ? $customer->ward_id : old('ward _id') }}'
</script>