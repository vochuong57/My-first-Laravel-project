<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th style="width: 50px"> 
                <input type="checkbox" value="" name="" id="checkAll" class="input-checkbox">
            </th>
            <!-- <th style="width: 90px">Ảnh</th> -->
            <th>{{ __('messages.tableProduct_name') }}</th>
            @include('Backend.dashboard.component.languageTh')
            <th style="width: 80px" class="text-center">{{ __('messages.tableProduct_pos') }}</th>
            <!-- <th>Canonical</th> -->
            <!-- <th>Ghi chú</th> -->
            <th class="text-center" style="width: 100px">{{ __('messages.tableProduct_status') }}</th>
            <th class="text-center" style="width: 100px">{{ __('messages.tableProduct_action') }}</th>
        </tr>
    </thead>
    <tbody>
        <!-- lấy được thông tin từ biến $userCatalogues truyền qua từ UsserController thông qua compact -->
        @if(isset($products) && is_object($products))
        @foreach($products as $product)
        <tr class="rowdel-{{ $product->id }}">
            <td>
                <input type="checkbox" value="{{ $product->id }}" data-languageId = "{{ $product->language_id }}" name="" class="input-checkbox checkBoxItem">
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
                            <img src="{{ $product->image ?? 'Backend/img/not-found.png' }}" alt="">
                        </div>
                    </div>
                    <div class="main-info">
                        <div class="name">
                            <span class="maintitle">{{ $product->name }}</span>
                        </div>

                        <div class="catalogue">
                            <span class="text-danger">{{ __('messages.tableProduct_displayCatalogue') }}</span>
                            @foreach($product->product_catalogues as $val) <?php // '->product_catalogues' là function product_catalogues() của Model/Product có LQ theo đường ProductRepository ?>
                            @foreach($val->product_catalogue_language as $cat)
                            <a href="{{ route('product.index', ['product_catalogue_id'=>$val->id]) }}">{{ $cat->name }}</a>
                            @endforeach
                            @endforeach
                        </div>
                    </div>
                </div>
            </td>
            @include('Backend.dashboard.component.languageTd', ['model' => $product, 'modeling' => 'Product'])
            <td>
                <input type="text" name="order" value="{{ $product->order }}" class="form-control sort-order text-right" data-id="{{ $product->id }}" data-model="{{ $config['model'] }}">
            </td>
            <!-- <td>
                <?php //{{ $userCatalogue->users_count }} ?>
            </td> -->
            <!-- <td>
                <div class="info-item email"><?php //{{ $product->canonical  }} ?></div>
            </td> -->
            <!-- <td>
                <div class="info-item email"><?php //{{ $product->description }} ?></div>
            </td> -->
            <td class="text-center js-switch-{{ $product->id }}">
                <input type="checkbox" class="js-switch status" value="{{ $product->publish }}" data-field="publish" data-model="{{ $config['model'] }}" data-modelId="{{ $product->id }}" {{ ($product->publish==2)?'checked':'' }} >
            </td>
            <td class="text-center">
                <a href="{{ route('product.edit', $product->id) }}" class="btn btn-success"><i class="fa fa-edit"></i></a>
                <a href="{{ route('product.destroy', $product->id) }}" class="btn btn-danger"><i class="fa fa-trash"></i></a>
            </td>
        </tr>
        @endforeach
        @endif
    </tbody>
</table>
{{ $products->links('pagination::bootstrap-4') }}