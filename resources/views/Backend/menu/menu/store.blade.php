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
                <!-- <div class="ibox-title">
                    <h5>Thông tin chung</h5>
                </div> -->
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
                    <!-- <div class="ibox-title">
                        <h5>Thông tin chung</h5>
                    </div> -->
                    <div class="ibox-content">
                        @include('backend.menu.menu.component.action')
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