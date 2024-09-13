<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>
                <input type="checkbox" value="" name="" id="checkAll" class="input-checkbox">
            </th>
            <th>Tên widget</th>
            <th>Từ khóa</th>
            <th>Model</th>
            @include('Backend.dashboard.component.languageTh')
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
            @foreach($languages as $language)
                @if(session('app_locale') == $language->canonical) 
                    @continue 
                @endif
                @php
                    $translated = (isset($widget->description[$language->id])) ? 1 : 0;
                @endphp
                <td style="width: 100px;" class="text-center">
                    <a  class="{{ ($translated == 1) ? '' : 'text-danger' }}"
                        href="{{ route('widget.translate', ['languageId' => $language->id, 'id' => $widget->id]) }}">
                        {{ ($translated == 1) ? 'Đã dịch' : 'Chưa dịch' }}
                    </a>
                </td>
            @endforeach
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