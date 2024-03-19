<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th style="width: 50px"> 
                <input type="checkbox" value="" name="" id="checkAll" class="input-checkbox">
            </th>
            <!-- <th style="width: 90px">Ảnh</th> -->
            <th>Tiêu đề</th>
            <th style="width: 80px" class="text-center">vị trí</th>
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
                <div class="uk-flex uk-flex-middle">
                    <div class="image mr5">
                        <div class="img-cover image-post">
                            <img src="{{ $post->image }}" alt="">
                        </div>
                    </div>
                    <div class="main-info">
                        <div class="name">
                            <span class="maintitle">{{ $post->name }}</span>
                        </div>

                        <div class="catalogue">
                            <span class="text-danger">Nhóm hiển thị</span>
                            @foreach($post->post_catalogues as $val)
                            @foreach($val->post_catalogue_language as $cat)
                            <a href="{{ route('post.index', ['post_catalogue_id'=>$val->id]) }}">{{ $cat->name }}</a>
                            @endforeach
                            @endforeach
                        </div>
                    </div>
                </div>
            </td>
            <td>
                <input type="text" name="order" value="{{ $post->order }}" class="form-control sort-order text-right" data-id="{{ $post->id }}" data-model="{{ $config['model'] }}">
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
                <input type="checkbox" class="js-switch status" value="{{ $post->publish }}" data-field="publish" data-model="{{ $config['model'] }}" data-modelId="{{ $post->id }}" {{ ($post->publish==2)?'checked':'' }} >
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