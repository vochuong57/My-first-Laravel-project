<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th style="width: 50px"> 
                <input type="checkbox" value="" name="" id="checkAll" class="input-checkbox">
            </th>
            <!-- <th style="width: 90px">Ảnh</th> -->
            <th>{{ __('messages.tableProductCatalogue_name') }}</th>
            <!-- <th>Số thành viên</th> -->
            <!-- <th>Canonical</th> -->
            <!-- <th>Ghi chú</th> -->
            @include('Backend.dashboard.component.languageTh')
            <th class="text-center" style="width: 100px">{{ __('messages.tableProductCatalogue_status') }}</th>
            <th class="text-center" style="width: 100px">{{ __('messages.tableProductCatalogue_action') }}</th>
        </tr>
    </thead>
    <tbody>
        <!-- lấy được thông tin từ biến $userCatalogues truyền qua từ UsserController thông qua compact -->
        @if(isset($productCatalogues) && is_object($productCatalogues))
        @foreach($productCatalogues as $productCatalogue)
        <tr class="rowdel-{{ $productCatalogue->id }}">
            <td>
                <input type="checkbox" value="{{ $productCatalogue->id }}" name="" class="input-checkbox checkBoxItem">
            </td>
            <!-- <td>
                <span class="image img-cover"><img
                        src=""
                        alt=""></span>
            </td> -->
            <td>
                <div class="info-item name">{{ str_repeat('|----',($productCatalogue->level>0?($productCatalogue->level-1):0)).$productCatalogue->name }}</div>
            </td>
            @include('Backend.dashboard.component.languageTd', ['model' => $productCatalogue, 'modeling' => 'ProductCatalogue'])
            <!-- <td>
                <?php //{{ $userCatalogue->users_count }} ?>
            </td> -->
            <!-- <td>
                <div class="info-item email"><?php //{{ $productCatalogue->canonical  }} ?></div>
            </td> -->
            <!-- <td>
                <div class="info-item email"><?php //{{ $productCatalogue->description }} ?></div>
            </td> -->
            <td class="text-center js-switch-{{ $productCatalogue->id }}">
                <input type="checkbox" class="js-switch status" value="{{ $productCatalogue->publish }}" data-field="publish" data-model="{{ $config['model'] }}" data-modelId="{{ $productCatalogue->id }}" {{ ($productCatalogue->publish==2)?'checked':'' }} >
            </td>
            <td class="text-center">
                <a href="{{ route('product.catalogue.edit', $productCatalogue->id) }}" class="btn btn-success"><i class="fa fa-edit"></i></a>
                <a href="{{ route('product.catalogue.destroy', $productCatalogue->id) }}" class="btn btn-danger"><i class="fa fa-trash"></i></a>
            </td>
        </tr>
        @endforeach
        @endif
    </tbody>
</table>
{{ $productCatalogues->links('pagination::bootstrap-4') }}