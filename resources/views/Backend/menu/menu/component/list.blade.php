<div class="panel-group" id="accordion">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h5 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">Liên kết tự tạo</a>
            </h5>
        </div>
        <div id="collapseOne" class="panel-collapse collapse in">
            <div class="panel-body">
                <div class="panel-title">Tạo Menu</div>
                <p>- Cài đặt Menu muốn hiển thị</p>
                <p><small class="text-danger">* Khi khởi tạo menu bạn phải chắc chắn rằng đường dẫn của menu có hoạt động.
                    Đường dẫn trên website được khởi tạo tại các module: Bài viết, Sản phẩm, Dự án, ...
                </small></p>
                <p><small class="text-danger">* Tiêu đề và đường dẫn của menu không được bỏ trống.</small></p>
                <p><small class="text-danger">* Hệ thống chỉ hỗ trợ tối đa 5 cấp menu.</small></p>
                <p><small class="text-danger">* Hệ thống sẽ không cho phép bạn chỉnh sửa đường dẫn được tạo ra từ hệ thống.</small></p>
                <p><small class="text-danger">* Trường hợp xóa nhầm menu hãy F5 lại trang trước khi ấn vào nút Lưu lại để khôi phục dữ liệu như ban đầu.</small></p>
                <a style="color:#000; border-color: #c4cdd5; display: inline-block !important;" href="" title="" class="btn btn-default add-menu m-b m-r right">
                    Thêm đường dẫn
                </a>
            </div>
        </div>
    </div>
    @foreach(__('module.model') as $key=> $val)
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" class="collapsed menu-module" data-parent="#accordion" data-model="{{ $key }}" href="#{{ $key }}">{{ $val }}</a>
            </h4>
        </div>
        <div id="{{ $key }}" class="panel-collapse collapse">
            <div class="panel-body">
                <form action="" method="get" data-model="{{ $key }}" class="search-model">
                    <div class="form-row">
                        <input type="text" name="keyword" value="" class="form-control search-menu" placeholder="Nhập 2 ký tự để tìm kiếm..." autocomplete="off">
                    </div>
                </form>
                <div class="menu-list mt20">
                    
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
