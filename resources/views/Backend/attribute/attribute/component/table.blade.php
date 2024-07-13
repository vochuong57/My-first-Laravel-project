<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th style="width: 50px"> 
                <input type="checkbox" value="" name="" id="checkAll" class="input-checkbox">
            </th>
            <!-- <th style="width: 90px">Ảnh</th> -->
            <th>{{ __('messages.tableAttribute_name') }}</th>
            @include('Backend.dashboard.component.languageTh')
            <th style="width: 80px" class="text-center">{{ __('messages.tableAttribute_pos') }}</th>
            <!-- <th>Canonical</th> -->
            <!-- <th>Ghi chú</th> -->
            <th class="text-center" style="width: 100px">{{ __('messages.tableAttribute_status') }}</th>
            <th class="text-center" style="width: 100px">{{ __('messages.tableAttribute_action') }}</th>
        </tr>
    </thead>
    <tbody>
        <!-- lấy được thông tin từ biến $userCatalogues truyền qua từ UsserController thông qua compact -->
        @if(isset($attributes) && is_object($attributes))
        @foreach($attributes as $attribute)
        <tr class="rowdel-{{ $attribute->id }}">
            <td>
                <input type="checkbox" value="{{ $attribute->id }}" data-languageId = "{{ $attribute->language_id }}" name="" class="input-checkbox checkBoxItem">
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
                            <img src="{{ $attribute->image ?? 'Backend/img/not-found.png' }}" alt="">
                        </div>
                    </div>
                    <div class="main-info">
                        <div class="name">
                            <span class="maintitle">{{ $attribute->name }}</span>
                        </div>

                        <div class="catalogue">
                            <span class="text-danger">{{ __('messages.tableAttribute_displayCatalogue') }}</span>
                            @foreach($attribute->attribute_catalogues as $val) <?php // '->attribute_catalogues' là function attribute_catalogues() của Model/Attribute có LQ theo đường AttributeRepository ?>
                            @foreach($val->attribute_catalogue_language as $cat)
                            <a href="{{ route('attribute.index', ['attribute_catalogue_id'=>$val->id]) }}">{{ $cat->name }}</a>
                            @endforeach
                            @endforeach
                        </div>
                    </div>
                </div>
            </td>
            @include('Backend.dashboard.component.languageTd', ['model' => $attribute, 'modeling' => 'Attribute'])
            <td>
                <input type="text" name="order" value="{{ $attribute->order }}" class="form-control sort-order text-right" data-id="{{ $attribute->id }}" data-model="{{ $config['model'] }}">
            </td>
            <!-- <td>
                <?php //{{ $userCatalogue->users_count }} ?>
            </td> -->
            <!-- <td>
                <div class="info-item email"><?php //{{ $attribute->canonical  }} ?></div>
            </td> -->
            <!-- <td>
                <div class="info-item email"><?php //{{ $attribute->description }} ?></div>
            </td> -->
            <td class="text-center js-switch-{{ $attribute->id }}">
                <input type="checkbox" class="js-switch status" value="{{ $attribute->publish }}" data-field="publish" data-model="{{ $config['model'] }}" data-modelId="{{ $attribute->id }}" {{ ($attribute->publish==2)?'checked':'' }} >
            </td>
            <td class="text-center">
                <a href="{{ route('attribute.edit', $attribute->id) }}" class="btn btn-success"><i class="fa fa-edit"></i></a>
                <a href="{{ route('attribute.destroy', $attribute->id) }}" class="btn btn-danger"><i class="fa fa-trash"></i></a>
            </td>
        </tr>
        @endforeach
        @endif
    </tbody>
</table>
{{ $attributes->links('pagination::bootstrap-4') }}