<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>
                <input type="checkbox" value="" name="" id="checkAll" class="input-checkbox">
            </th>
            <th>Tên Menu</th>
            <th>Từ khóa</th>
            <!-- <th>Ngày tạo</th>
            <th>Người tạo</th> -->
            <th class="text-center">Tình trạng</th>
            <th class="text-center">Thao tác</th>
        </tr>
    </thead>
    <tbody>
        <!-- lấy được thông tin từ biến $menuCatalogues truyền qua từ UsserController thông qua compact -->
        @if(isset($menuCatalogues) && is_object($menuCatalogues))
        @foreach($menuCatalogues as $menuCatalogue)
        <tr class="rowdel-{{ $menuCatalogue->id }}">
            <td>
                <input type="checkbox" value="{{ $menuCatalogue->id }}" name="" class="input-checkbox checkBoxItem">
            </td>
            <td>
                <div class="info-item name">{{ $menuCatalogue->name }}</div>
            </td>
            <td>
                <div class="info-item email">{{ $menuCatalogue->keyword }}</div>
            </td>
            <!-- <td>
                <div class="info-item phone">-</div>
            </td>
            <td>
                <div class="address-item name">-</div>
            </td> -->
            <td class="text-center js-switch-{{ $menuCatalogue->id }}">
                <input type="checkbox" class="js-switch status" value="{{ $menuCatalogue->publish }}" data-field="publish" data-model="{{ $config['model'] }}" data-modelId="{{ $menuCatalogue->id }}" {{ ($menuCatalogue->publish==2)?'checked':'' }} >
            </td>
            <td class="text-center">
                <a href="{{ route('menu.edit', $menuCatalogue->id) }}" class="btn btn-success"><i class="fa fa-edit"></i></a>
                <a href="{{ route('menu.destroy', $menuCatalogue->id) }}" class="btn btn-danger"><i class="fa fa-trash"></i></a>
            </td>
        </tr>
        @endforeach
        @endif
    </tbody>
</table>
{{ $menuCatalogues->links('pagination::bootstrap-4') }}