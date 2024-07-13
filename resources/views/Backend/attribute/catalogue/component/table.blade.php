<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th style="width: 50px"> 
                <input type="checkbox" value="" name="" id="checkAll" class="input-checkbox">
            </th>
            <!-- <th style="width: 90px">Ảnh</th> -->
            <th>{{ __('messages.tableAttributeCatalogue_name') }}</th>
            <!-- <th>Số thành viên</th> -->
            <!-- <th>Canonical</th> -->
            <!-- <th>Ghi chú</th> -->
            @include('Backend.dashboard.component.languageTh')
            <th class="text-center" style="width: 100px">{{ __('messages.tableAttributeCatalogue_status') }}</th>
            <th class="text-center" style="width: 100px">{{ __('messages.tableAttributeCatalogue_action') }}</th>
        </tr>
    </thead>
    <tbody>
        <!-- lấy được thông tin từ biến $userCatalogues truyền qua từ UsserController thông qua compact -->
        @if(isset($attributeCatalogues) && is_object($attributeCatalogues))
        @foreach($attributeCatalogues as $attributeCatalogue)
        <tr class="rowdel-{{ $attributeCatalogue->id }}">
            <td>
                <input type="checkbox" value="{{ $attributeCatalogue->id }}" name="" class="input-checkbox checkBoxItem">
            </td>
            <!-- <td>
                <span class="image img-cover"><img
                        src=""
                        alt=""></span>
            </td> -->
            <td>
                <div class="info-item name">{{ str_repeat('|----',($attributeCatalogue->level>0?($attributeCatalogue->level-1):0)).$attributeCatalogue->name }}</div>
            </td>
            @include('Backend.dashboard.component.languageTd', ['model' => $attributeCatalogue, 'modeling' => 'AttributeCatalogue'])
            <!-- <td>
                <?php //{{ $userCatalogue->users_count }} ?>
            </td> -->
            <!-- <td>
                <div class="info-item email"><?php //{{ $attributeCatalogue->canonical  }} ?></div>
            </td> -->
            <!-- <td>
                <div class="info-item email"><?php //{{ $attributeCatalogue->description }} ?></div>
            </td> -->
            <td class="text-center js-switch-{{ $attributeCatalogue->id }}">
                <input type="checkbox" class="js-switch status" value="{{ $attributeCatalogue->publish }}" data-field="publish" data-model="{{ $config['model'] }}" data-modelId="{{ $attributeCatalogue->id }}" {{ ($attributeCatalogue->publish==2)?'checked':'' }} >
            </td>
            <td class="text-center">
                <a href="{{ route('attribute.catalogue.edit', $attributeCatalogue->id) }}" class="btn btn-success"><i class="fa fa-edit"></i></a>
                <a href="{{ route('attribute.catalogue.destroy', $attributeCatalogue->id) }}" class="btn btn-danger"><i class="fa fa-trash"></i></a>
            </td>
        </tr>
        @endforeach
        @endif
    </tbody>
</table>
{{ $attributeCatalogues->links('pagination::bootstrap-4') }}