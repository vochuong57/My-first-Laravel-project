<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>
                <input type="checkbox" value="" name="" id="checkAll" class="input-checkbox">
            </th>
            <th>{{ __('messages.tablePermission_name') }}</th>
            <!-- <th>Số thành viên</th> -->
            <th>Canonical</th>
            <th class="text-center">{{ __('messages.tablePermission_action') }}</th>
        </tr>
    </thead>
    <tbody>
        <!-- lấy được thông tin từ biến $userCatalogues truyền qua từ UsserController thông qua compact -->
        @if(isset($permissions) && is_object($permissions))
        @foreach($permissions as $permission)
        <tr class="rowdel-{{ $permission->id }}">
            <td>
                <input type="checkbox" value="{{ $permission->id }}" name="" class="input-checkbox checkBoxItem">
            </td>
            
            <td>
                <div class="info-item name">{{ $permission->name }}</div>
            </td>
            <!-- <td>
                <?php //{{ $userCatalogue->users_count }} ?>
            </td> -->
            <td>
                <div class="info-item email">{{ $permission->canonical }}</div>
            </td>
           
            <td class="text-center">
                <a href="{{ route('permission.edit', $permission->id) }}" class="btn btn-success"><i class="fa fa-edit"></i></a>
                <a href="{{ route('permission.destroy', $permission->id) }}" class="btn btn-danger"><i class="fa fa-trash"></i></a>
            </td>
        </tr>
        @endforeach
        @endif
    </tbody>
</table>
{{ $permissions->links('pagination::bootstrap-4') }}