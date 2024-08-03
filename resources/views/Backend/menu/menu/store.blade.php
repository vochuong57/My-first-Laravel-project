@include('Backend.dashboard.component.breadcrumb', ['title' =>$config['seo']['title']])
@include('Backend.dashboard.component.formError')

@php
    $url=($config['method']=='create')?route('menu.create'):route('menu.update', $menu->id)
@endphp
<form action="{{ $url }}" method="post" class="box menuContainer">
    @csrf
    <div class="row">
        <div class="col-lg-5">
            <div class="panel-head">
                <div class="panel-title">Vị trí Menu</div>
                <div class="panel-description">
                    <p>- Website có các vị trí hiển thị cho từng menu</p>
                    <p>- Lựa chọn vị trí mà bạn muốn hiển thị</p>
                </div>
            </div>
        </div>
        <div class="col-lg-7">
            <div class="ibox">
                <div class="ibox-title">
                    <h5>Thông tin chung</h5>
                </div>
                <div class="ibox-content">
                    @include('backend.menu.menu.component.catalogue')
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-5">
                <div class="ibox">
                    <div class="ibox-content">
                        @include('backend.menu.menu.component.list')
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Thông tin chung</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="row">
                            <div class="col-lg-4">
                                <label for="">Tên Menu</label>
                            </div>
                            <div class="col-lg-4">
                                <label for="">Đường dẫn</label>
                            </div>
                            <div class="col-lg-2">
                                <label for="">Vị trí</label>
                            </div>
                            <div class="col-lg-2">
                                <label for="">Xóa</label>
                            </div>
                        </div>
                        <div class="hr-line-dashed" style="margin: 10px 0;"></div>
                        <div class="menu-wrapper">
                            <div class="notification text-center">
                                <h4 style="font-weight: 500; font-size: 16px; color: #000;">
                                    Danh sách liên kết này chưa có bất kì đường dẫn nào.
                                </h4>
                                <p style="color: #555; margin-top: 10px;">
                                    Hãy nhấn vào <span style="color: blue;">"Thêm đường dẫn"</span> để bắt đầu thêm.
                                </p>
                            </div>
                            <div class="row">
                                <div class="col-lg-4">
                                    <input type="text" class="form-control" name="menu[name][]">
                                </div>
                                <div class="col-lg-4">
                                    <input type="text" class="form-control" name="menu[canonical][]">
                                </div>
                                <div class="col-lg-2">
                                    <input type="text" class="form-control" name="menu[order][]">
                                </div>
                                <div class="col-lg-2">
                                    <a href="" class="delete-menu img-scaledown" style="width: 15%; height: 30px; margin-left: 6px"><img src="Backend/img/close.png" alt=""></a>
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

<!-- Modal -->
@include('backend.menu.menu.component.popup')