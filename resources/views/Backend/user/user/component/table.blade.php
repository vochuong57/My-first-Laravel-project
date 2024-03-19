<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>
                <input type="checkbox" value="" name="" id="checkAll" class="input-checkbox">
            </th>
            <th style="width: 90px">Ảnh</th>
            <th>Họ tên</th>
            <th>Email</th>
            <th>Số điện thoại</th>
            <th>Địa chỉ</th>
            <th>Nhóm thành viên</th>
            <th class="text-center">Tình trạng</th>
            <th class="text-center">Thao tác</th>
        </tr>
    </thead>
    <tbody>
        <!-- lấy được thông tin từ biến $users truyền qua từ UsserController thông qua compact -->
        @if(isset($users) && is_object($users))
        @foreach($users as $user)
        <tr class="rowdel-{{ $user->id }}">
            <td>
                <input type="checkbox" value="{{ $user->id }}" name="" class="input-checkbox checkBoxItem">
            </td>
            <td>
                <span class="imageUser img-cover"><img
                        src="{{ old('image', $user->image) ?? 'Backend/img/not-found.png' }}"
                        alt=""></span>
            </td>
            <td>
                <div class="info-item name">{{ $user->name }}</div>
            </td>
            <td>
                <div class="info-item email">{{ $user->email }}</div>
            </td>
            <td>
                <div class="info-item phone">{{ $user->phone }}</div>
            </td>
            <td>
                <div class="address-item name">{{ $user->address }}</div>
            </td>
            <td>
                <div class="address-item name">{{ $user->user_catalogues->name }}</div>
            </td>
            <td class="text-center js-switch-{{ $user->id }}">
                <input type="checkbox" class="js-switch status" value="{{ $user->publish }}" data-field="publish" data-model="{{ $config['model'] }}" data-modelId="{{ $user->id }}" {{ ($user->publish==2)?'checked':'' }} >
            </td>
            <td class="text-center">
                <a href="{{ route('user.edit', $user->id) }}" class="btn btn-success"><i class="fa fa-edit"></i></a>
                <a href="{{ route('user.destroy', $user->id) }}" class="btn btn-danger"><i class="fa fa-trash"></i></a>
            </td>
        </tr>
        @endforeach
        @endif
    </tbody>
</table>
{{ $users->links('pagination::bootstrap-4') }}