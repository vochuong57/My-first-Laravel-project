<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>
                <input type="checkbox" value="" name="" id="checkAll" class="input-checkbox">
            </th>
            <th>Tên nhóm</th>
            <th>Từ khóa</th>
            <th>Danh sách hình ảnh</th>
            <th class="text-center">Tình trạng</th>
            <th class="text-center">Thao tác</th>
        </tr>
    </thead>
    <tbody>
        <!-- lấy được thông tin từ biến $slides truyền qua từ UsserController thông qua compact -->
        @if(isset($slides) && is_object($slides))
        @foreach($slides as $slide)
        <tr class="rowdel-{{ $slide->id }}">
            <td>
                <input type="checkbox" value="{{ $slide->id }}" name="" class="input-checkbox checkBoxItem">
            </td>
            <td>
                <div class="info-item name">{{ $slide->name }}</div>
            </td>
            <td>
                <div class="info-item email">{{ $slide->keyword }}</div>
            </td>
            <td>
            @php
                // V79
                $album = $slide->album;
            @endphp
            @if(isset($album[$languageSessionId]))
                <div class="list-image-table sortable-list sortui ui-sortable" data-slideId="{{ $slide->id }}" data-languageSessionId="{{ $languageSessionId }}">
                @foreach($album[$languageSessionId] as $parentId => $items)
                    @foreach($items as $item)
                        @if(isset($item['image']))
                        <span class="img-list ui-state-default-list">
                            <img src="{{ $item['image'] }}" alt="">
                            <input type="hidden" name=""
                                data-id="{{ $parentId }}"
                                data-image="{{ $item['image'] }}"
                                data-description="{{ $item['description'] }}"
                                data-window="{{ $item['window'] }}"
                                data-canonical="{{ $item['canonical'] }}"
                                data-name="{{ $item['name'] }}"
                                data-alt="{{ $item['alt'] }}"
                            >
                        </span>
                        @endif
                    @endforeach
                @endforeach
                </div>
            @else
                <p>Không có hình ảnh nào để hiển thị.</p>
            @endif
            </td>
            <td class="text-center js-switch-{{ $slide->id }}">
                <input type="checkbox" class="js-switch status" value="{{ $slide->publish }}" data-field="publish" data-model="{{ $config['model'] }}" data-modelId="{{ $slide->id }}" {{ ($slide->publish==2)?'checked':'' }} >
            </td>
            <td class="text-center">
                <a href="{{ route('slide.edit', $slide->id) }}" class="btn btn-success"><i class="fa fa-edit"></i></a>
                <a href="{{ route('slide.destroy', $slide->id) }}" class="btn btn-danger"><i class="fa fa-trash"></i></a>
            </td>
        </tr>
        @endforeach
        @endif
    </tbody>
</table>
{{ $slides->links('pagination::bootstrap-4') }}