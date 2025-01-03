<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>
                <input type="checkbox" value="" name="" id="checkAll" class="input-checkbox">
            </th>
            <th>Tên source</th>
            <th>Từ khóa</th>
            <th>Mô tả</th>
            <th class="text-center">Tình trạng</th>
            <th class="text-center">Thao tác</th>
        </tr>
    </thead>
    <tbody>
        <!-- lấy được thông tin từ biến $sources truyền qua từ UsserController thông qua compact -->
        @if(isset($sources) && is_object($sources))
        @foreach($sources as $source)
        <tr class="rowdel-{{ $source->id }}">
            <td>
                <input type="checkbox" value="{{ $source->id }}" name="" class="input-checkbox checkBoxItem">
            </td>
            <td>
                <div class="info-item name">{{ $source->name }}</div>
            </td>
            <td>
                <div class="info-item email">{{ $source->keyword }}</div>
            </td>
            <td>
                <div class="info-item email">{{ strip_tags(html_entity_decode($source->description)) }}</div>
            </td>
            <td class="text-center js-switch-{{ $source->id }}">
                <input type="checkbox" class="js-switch status" value="{{ $source->publish }}" data-field="publish" data-model="{{ $config['model'] }}" data-modelId="{{ $source->id }}" {{ ($source->publish==2)?'checked':'' }} >
            </td>
            <td class="text-center">
                <a href="{{ route('source.edit', $source->id) }}" class="btn btn-success"><i class="fa fa-edit"></i></a>
                <a href="{{ route('source.destroy', $source->id) }}" class="btn btn-danger"><i class="fa fa-trash"></i></a>
            </td>
        </tr>
        @endforeach
        @endif
    </tbody>
</table>
{{ $sources->links('pagination::bootstrap-4') }}