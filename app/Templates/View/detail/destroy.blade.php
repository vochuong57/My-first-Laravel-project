@include('Backend.dashboard.component.breadcrumb', ['title' =>$config['seo']['title']])
@include('Backend.dashboard.component.formError')


<form action="{{ route('{moduleTemplate}.delete',${moduleTemplate}->id) }}" method="{moduleTemplate}" class="box">
    @csrf
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-5">
                <div class="panel-head">
                    <div class="panel-title">Thông tin chung</div>
                    <div class="panel-description">
                        <p>- Bạn đang muốn xóa bài viết có tên là: <span style="color: red">{{ ${moduleTemplate}->name }}</span></p>
                        <p>- Lưu ý <span class="text-danger">KHÔNG THỂ</span> khôi phục bài viết sau khi xóa. <br> Hãy chắc chắn bạn muốn thực hiện chức năng này</p>
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
                                    <label for="" class="control-label text-left">Tên bài viết: <span class="text-danger">(*)</span></label>
                                    <input 
                                    type="text"
                                    name="name"
                                    value="{{ old('name', (${moduleTemplate}->name)??'') }}"
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
                                    value="{{ old('canonical', (${moduleTemplate}->canonical)??'') ? config('app.url').old('canonical', (${moduleTemplate}->canonical)??'').config('apps.general.suffix') : 'https://duong-dan-cua-ban.html' }}"
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
