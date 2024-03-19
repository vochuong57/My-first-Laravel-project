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
        @if(isset($posts) && is_object($posts))
        @foreach($posts as $post)
        <tr class="rowdel-{{ $post->id }}">
            <td>
                <input type="checkbox" value="{{ $post->id }}" name="" class="input-checkbox checkBoxItem">
            </td>
            <!-- <td>
                <span class="image img-cover"><img
                        src=""
                        alt=""></span>
            </td> -->
            <td>
                <div class="info-item name">{{ $post->name }}</div>
            </td>
            <!-- <td>
                <?php //{{ $userCatalogue->users_count }} ?>
            </td> -->
            <!-- <td>
                <div class="info-item email"><?php //{{ $post->canonical  }} ?></div>
            </td> -->
            <!-- <td>
                <div class="info-item email"><?php //{{ $post->description }} ?></div>
            </td> -->
            <td class="text-center js-switch-{{ $post->id }}">
                <input type="checkbox" class="js-switch status" value="{{ $post->publish }}" data-field="publish" data-model="Post" data-modelId="{{ $post->id }}" {{ ($post->publish==2)?'checked':'' }} >
            </td>
            <td class="text-center">
                <a href="{{ route('post.edit', $post->id) }}" class="btn btn-success"><i class="fa fa-edit"></i></a>
                <a href="{{ route('post.destroy', $post->id) }}" class="btn btn-danger"><i class="fa fa-trash"></i></a>
            </td>
        </tr>
        @endforeach
        @endif
    </tbody>
</table>
{{ $posts->links('pagination::bootstrap-4') }}