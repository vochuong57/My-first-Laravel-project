<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th style="width: 50px"> 
                <input type="checkbox" value="" name="" id="checkAll" class="input-checkbox">
            </th>
            <!-- <th style="width: 90px">Ảnh</th> -->
            <th>Tên nhóm</th>
            <!-- <th>Số thành viên</th> -->
            <!-- <th>Canonical</th> -->
            <!-- <th>Ghi chú</th> -->
            <th class="text-center" style="width: 100px">Tình trạng</th>
            <th class="text-center" style="width: 100px">Thao tác</th>
        </tr>
    </thead>
    <tbody>
        <!-- lấy được thông tin từ biến $userCatalogues truyền qua từ UsserController thông qua compact -->
        @if(isset($postCatalogues) && is_object($postCatalogues))
        @foreach($postCatalogues as $postCatalogue)
        <tr class="rowdel-{{ $postCatalogue->id }}">
            <td>
                <input type="checkbox" value="{{ $postCatalogue->id }}" name="" class="input-checkbox checkBoxItem">
            </td>
            <!-- <td>
                <span class="image img-cover"><img
                        src=""
                        alt=""></span>
            </td> -->
            <td>
                <div class="info-item name">{{ str_repeat('|----',($postCatalogue->level>0?($postCatalogue->level-1):0)).$postCatalogue->name }}</div>
            </td>
            <!-- <td>
                <?php //{{ $userCatalogue->users_count }} ?>
            </td> -->
            <!-- <td>
                <div class="info-item email"><?php //{{ $postCatalogue->canonical  }} ?></div>
            </td> -->
            <!-- <td>
                <div class="info-item email"><?php //{{ $postCatalogue->description }} ?></div>
            </td> -->
            <td class="text-center js-switch-{{ $postCatalogue->id }}">
                <input type="checkbox" class="js-switch status" value="{{ $postCatalogue->publish }}" data-field="publish" data-model="{{ $config['model'] }}" data-modelId="{{ $postCatalogue->id }}" {{ ($postCatalogue->publish==2)?'checked':'' }} >
            </td>
            <td class="text-center">
                <a href="{{ route('post.catalogue.edit', $postCatalogue->id) }}" class="btn btn-success"><i class="fa fa-edit"></i></a>
                <a href="{{ route('post.catalogue.destroy', $postCatalogue->id) }}" class="btn btn-danger"><i class="fa fa-trash"></i></a>
            </td>
        </tr>
        @endforeach
        @endif
    </tbody>
</table>
{{ $postCatalogues->links('pagination::bootstrap-4') }}