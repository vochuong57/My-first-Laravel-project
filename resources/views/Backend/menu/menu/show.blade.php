@include('Backend.dashboard.component.breadcrumb', ['title' =>$config['seo']['title']])

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-4">
            <div class="panel-title">Danh sách menu</div>
            <div class="panel-description">
                <p>- Danh sách Menu giúp bạn dễ dàng kiểm soát bố cục menu. Bạn có thể thêm mới hoặc cập nhập menu bằng
                    nút <span class="success">Cập nhật menu</span>
                </p>
                <p>- Bạn có thể thay đổi vị trí hiển thị menu bằng cách <span class="text-bold uppercase">kéo thả menu</span> 
                    <span class="success">đến vị trí mong muốn</span>
                </p>
                <p>- Dễ dàng khởi tạo menu con bằng cách ấn vào nút 
                    <span class="success">Quản lý menu con</span>
                </p>
                <p><span class="text-danger">- Hỗ trợ tới danh mục con cấp 5</span></p>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="ibox">
                <div class="ibox-title">
                    <div class="uk-flex uk-flex-middle uk-flex-space-between">
                        <h5 style="margin: 0">Menu Chính</h5>
                        <a href="" class="custom-button">Cập nhật menu</a>
                    </div>
                </div>
                <div class="ibox-content">
                    @if(count($menus))
                    <div class="dd" id="nestable2">
                        <ol class="dd-list">
                            @foreach($menus as $key => $val)
                            <li class="dd-item" data-id="{{ $val->id }}">
                                <div class="dd-handle">
                                    <span class="label label-info"><i class="fa fa-arrows"></i></span> {{ $val->languages->first()->getOriginal('pivot_name') }}
                                </div>
                                <ol class="dd-list">
                                    <li class="dd-item" data-id="2">
                                        <div class="dd-handle">
                                            <span class="pull-right"> 12:00 pm </span>
                                            <span class="label label-info"><i class="fa fa-arrows"></i></span> Vivamus vestibulum nulla nec ante.
                                        </div>
                                    </li>
                                    <li class="dd-item" data-id="3">
                                        <div class="dd-handle">
                                            <span class="pull-right"> 11:00 pm </span>
                                            <span class="label label-info"><i class="fa fa-arrows"></i></span> Nunc dignissim risus id metus.
                                        </div>
                                    </li>
                                    <li class="dd-item" data-id="4">
                                        <div class="dd-handle">
                                            <span class="pull-right"> 11:00 pm </span>
                                            <span class="label label-info"><i class="fa fa-arrows"></i></span> Vestibulum commodo
                                        </div>
                                    </li>
                                </ol>
                            </li>
                            @endforeach
                        </ol>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
