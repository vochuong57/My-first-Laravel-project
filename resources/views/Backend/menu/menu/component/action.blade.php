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
    <div class="notification text-center {{ (!empty($listMenus)) ? 'none' : '' }}">
        <h4 style="font-weight: 500; font-size: 16px; color: #000;">
            Danh sách liên kết này chưa có bất kì đường dẫn nào.
        </h4>
        <p style="color: #555; margin-top: 10px;">
            Hãy nhấn vào <span style="color: blue;">"Thêm đường dẫn"</span> để bắt đầu thêm.
        </p>
    </div>
    @php
        $menu = old('menu', ($listMenus) ?? null);
    @endphp
    @if(!empty($menu))
        @foreach($menu['name'] as $key => $val)
        <div class="row mb10 menu-item {{ $menu['canonical'][$key] }}">
            <div class="col-lg-4">
                <input type="text" class="form-control" name="menu[name][]" value="{{ $val }}">
            </div>
            <div class="col-lg-4">
                <input type="text" class="form-control" name="menu[canonical][]" value="{{ $menu['canonical'][$key] }}">
            </div>
            <div class="col-lg-2">
                <input type="text" class="form-control" name="menu[order][]" value="{{ $menu['order'][$key] }}">
            </div>
            <div class="col-lg-2">
                <a class="delete-menu img-scaledown" style="width: 15%; height: 30px; margin-left: 6px"><img src="Backend/img/close.png" alt=""></a>
                <input type="text" name="menu[id][]" class="" value="{{ $menu['id'][$key] }}">
            </div>
        </div>
        @endforeach
    @else
    @endif
</div>
@php
    //echo '<pre>';
    //print_r($menu);
@endphp