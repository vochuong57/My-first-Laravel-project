<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>
                <input type="checkbox" value="" name="" id="checkAll" class="input-checkbox">
            </th>
            <th>Tên Menu</th>
            <th>Từ khóa</th>
            <th>Ngày tạo</th>
            <th>Người tạo</th>
            <th class="text-center">Tình trạng</th>
            <th class="text-center">Thao tác</th>
        </tr>
    </thead>
    <tbody>
        <!-- lấy được thông tin từ biến $menus truyền qua từ UsserController thông qua compact -->
        @if(isset($menus) && is_object($menus))
        @foreach($menus as $menu)
        <tr class="rowdel-{{ $menu->id }}">
            <td>
                <input type="checkbox" value="{{ $menu->id }}" name="" class="input-checkbox checkBoxItem">
            </td>
            <td>
                <div class="info-item name">-</div>
            </td>
            <td>
                <div class="info-item email">-</div>
            </td>
            <td>
                <div class="info-item phone">-</div>
            </td>
            <td>
                <div class="address-item name">-</div>
            </td>
            <td>
                <div class="address-item name">-</div>
            </td>
            <td class="text-center js-switch-{{ $menu->id }}">
                <input type="checkbox" class="js-switch status" value="{{ $menu->publish }}" data-field="publish" data-model="{{ $config['model'] }}" data-modelId="{{ $menu->id }}" {{ ($menu->publish==2)?'checked':'' }} >
            </td>
            <td class="text-center">
                <a href="{{ route('menu.edit', $menu->id) }}" class="btn btn-success"><i class="fa fa-edit"></i></a>
                <a href="{{ route('menu.destroy', $menu->id) }}" class="btn btn-danger"><i class="fa fa-trash"></i></a>
            </td>
        </tr>
        @endforeach
        @endif
    </tbody>
</table>
