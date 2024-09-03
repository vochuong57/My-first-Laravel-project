<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>
                <input type="checkbox" value="" name="" id="checkAll" class="input-checkbox">
            </th>
            <th>Tên widget</th>
            <th>Từ khóa</th>
            <th>Model</th>
            <th class="text-center">Tình trạng</th>
            <th class="text-center">Thao tác</th>
        </tr>
    </thead>
    <tbody>
        <!-- lấy được thông tin từ biến $widgets truyền qua từ UsserController thông qua compact -->
        @if(isset($widgets) && is_object($widgets))
        @foreach($widgets as $widget)
        <tr class="rowdel-{{ $widget->id }}">
            <td>
                <input type="checkbox" value="{{ $widget->id }}" name="" class="input-checkbox checkBoxItem">
            </td>
            <td>
                <div class="info-item name">{{ $widget->name }}</div>
            </td>
            <td>
                <div class="info-item email">{{ $widget->keyword }}</div>
            </td>
            <td>
                <div class="info-item email">{{ $widget->model }}</div>
            </td>
            <td class="text-center js-switch-{{ $widget->id }}">
                <input type="checkbox" class="js-switch status" value="{{ $widget->publish }}" data-field="publish" data-model="{{ $config['model'] }}" data-modelId="{{ $widget->id }}" {{ ($widget->publish==2)?'checked':'' }} >
            </td>
            <td class="text-center">
                <a href="{{ route('widget.edit', $widget->id) }}" class="btn btn-success"><i class="fa fa-edit"></i></a>
                <a href="{{ route('widget.destroy', $widget->id) }}" class="btn btn-danger"><i class="fa fa-trash"></i></a>
            </td>
        </tr>
        @endforeach
        @endif
    </tbody>
</table>
{{ $widgets->links('pagination::bootstrap-4') }}