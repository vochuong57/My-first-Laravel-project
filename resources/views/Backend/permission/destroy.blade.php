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

<form action="{{ route('permission.delete',$permission->id) }}" method="post" class="box">
    @csrf
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-5">
                <div class="panel-head">
                    <div class="panel-title">{{ __('messages.destroy') }}</div>
                    <div class="panel-description">
                        <p>{{ __('messages.destroy_panel_description_permission_1') }} <span style="color: red">{{ $permission->name }}</span></p>
                        <p>{{ __('messages.destroy_panel_description_1') }} <span class="text-danger">{{ __('messages.destroy_panel_description_2') }}</span> {{ __('messages.destroy_panel_description_permission_2') }} <br> {{ __('messages.destroy_panel_description_3') }}</p>
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
                                    <label for="" class="control-label text-left">Tên ngôn ngữ: <span class="text-danger">(*)</span></label>
                                    <input 
                                    type="text"
                                    name="name"
                                    value="{{ old('name', ($permission->name)??'') }}"
                                    class="form-control"
                                    placeholder=""
                                    autocomplete="off"
                                    >
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Canonical: <span class="text-danger">(*)</span></label>
                                    <input 
                                    type="text"
                                    name="canonical"
                                    value="{{ old('canonical', ($permission->canonical)??'') }}"
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
            <button class="btn btn-danger" type="submit" name="send" value="send">{{ $config['seo']['btnDelete'] }}</button>
        </div>
    </div>
</form>
<!-- XỬ LÝ HUYỆN XẢ KHI NHẬP SAI BỊ LOAD LẠI TRANG -->
<script>
    var province_id='{{ (isset($user->province_id)) ? $user->province_id : old('province_id') }}'
    var district_id='{{ (isset($user->district_id)) ? $user->district_id : old('district_id') }}'
    var ward_id='{{ (isset($user->ward_id)) ? $user->ward_id : old('ward _id') }}'
</script>