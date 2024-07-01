<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th style="width: 50px;">
                <input type="checkbox" value="" name="" id="checkAll" class="input-checkbox">
            </th>
            <th>{{ __('messages.tableGenerate_name') }}</th>
            <th class="text-center">{{ __('messages.tableGenerate_action') }}</th>
        </tr>
    </thead>
    <tbody>
        <!-- lấy được thông tin từ biến $userCatalogues truyền qua từ UsserController thông qua compact -->
        @if(isset($generates) && is_object($generates))
        @foreach($generates as $generate)
        <tr class="rowdel-{{ $generate->id }}">
            <td>
                <input type="checkbox" value="{{ $generate->id }}" name="" class="input-checkbox checkBoxItem">
            </td>
            <td>
                <div class="info-item name">{{ $generate->name }}</div>
            </td>
            <!-- <td>
                <?php //{{ $userCatalogue->users_count }} ?>
            </td> -->
            <td class="text-center">
                <a href="{{ route('language.edit', $generate->id) }}" class="btn btn-success"><i class="fa fa-edit"></i></a>
                <a href="{{ route('language.destroy', $generate->id) }}" class="btn btn-danger"><i class="fa fa-trash"></i></a>
            </td>
        </tr>
        @endforeach
        @endif
    </tbody>
</table>
{{ $generates->links('pagination::bootstrap-4') }}