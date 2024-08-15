@include('Backend.dashboard.component.breadcrumb', ['title' =>$config['seo']['title']. $menuCatalogueLoaded->name])

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
                        <a href="{{ route('menu.editMenu', ['id' => $id]) }}" class="custom-button">Cập nhật menu cấp 1</a>
                    </div>
                </div>
                <div class="ibox-content" id="dataCatalogue" data-catalogueId="{{ $id }}">
                    @php
                        // V69
                        $menus = recursive($menus);
                        $menuString = recursive_menu($menus);
                    @endphp
                    @if(count($menus))
                    <div class="dd" id="nestable2">
                        <ol class="dd-list">
                            {!! $menuString !!}
                        </ol>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
