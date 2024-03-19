<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>
                <input type="checkbox" value="" name="" id="checkAll" class="input-checkbox">
            </th>
            <th style="width: 90px">Ảnh</th>
            <th>Tên ngôn ngữ</th>
            <!-- <th>Số thành viên</th> -->
            <th>Canonical</th>
            <th>Ghi chú</th>
            <th class="text-center">Tình trạng</th>
            <th class="text-center">Thao tác</th>
        </tr>
    </thead>
    <tbody>
        <!-- lấy được thông tin từ biến $userCatalogues truyền qua từ UsserController thông qua compact -->
        @if(isset($languages) && is_object($languages))
        @foreach($languages as $language)
        <tr class="rowdel-{{ $language->id }}">
            <td>
                <input type="checkbox" value="{{ $language->id }}" name="" class="input-checkbox checkBoxItem">
            </td>
            <td>
                <span class="image img-cover"><img
                        src="https://www.tnmt.edu.vn/wp-content/uploads/2023/11/hinh-nen-avatar-ngau-1.jpg"
                        alt=""></span>
            </td>
            <td>
                <div class="info-item name">{{ $language->name }}</div>
            </td>
            <!-- <td>
                <?php //{{ $userCatalogue->users_count }} ?>
            </td> -->
            <td>
                <div class="info-item email">{{ $language->canonical }}</div>
            </td>
            <td>
                <div class="info-item email">{{ $language->description }}</div>
            </td>
            <td class="text-center js-switch-{{ $language->id }}">
                <input type="checkbox" class="js-switch status" value="{{ $language->publish }}" data-field="publish" data-model="Language" data-modelId="{{ $language->id }}" {{ ($language->publish==2)?'checked':'' }} >
            </td>
            <td class="text-center">
                <a href="{{ route('language.edit', $language->id) }}" class="btn btn-success"><i class="fa fa-edit"></i></a>
                <a href="{{ route('language.destroy', $language->id) }}" class="btn btn-danger"><i class="fa fa-trash"></i></a>
            </td>
        </tr>
        @endforeach
        @endif
    </tbody>
</table>
{{ $languages->links('pagination::bootstrap-4') }}