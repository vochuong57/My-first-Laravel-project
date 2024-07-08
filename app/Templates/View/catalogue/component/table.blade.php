<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th style="width: 50px"> 
                <input type="checkbox" value="" name="" id="checkAll" class="input-checkbox">
            </th>
            <!-- <th style="width: 90px">Ảnh</th> -->
            <th>{{ __('messages.table{ModuleTemplate}_name') }}</th>
            <!-- <th>Số thành viên</th> -->
            <!-- <th>Canonical</th> -->
            <!-- <th>Ghi chú</th> -->
            @include('Backend.dashboard.component.languageTh')
            <th class="text-center" style="width: 100px">{{ __('messages.table{ModuleTemplate}_status') }}</th>
            <th class="text-center" style="width: 100px">{{ __('messages.table{ModuleTemplate}_action') }}</th>
        </tr>
    </thead>
    <tbody>
        <!-- lấy được thông tin từ biến $userCatalogues truyền qua từ UsserController thông qua compact -->
        @if(isset(${moduleTemplate}s) && is_object(${moduleTemplate}s))
        @foreach(${moduleTemplate}s as ${moduleTemplate})
        <tr class="rowdel-{{ ${moduleTemplate}->id }}">
            <td>
                <input type="checkbox" value="{{ ${moduleTemplate}->id }}" name="" class="input-checkbox checkBoxItem">
            </td>
            <!-- <td>
                <span class="image img-cover"><img
                        src=""
                        alt=""></span>
            </td> -->
            <td>
                <div class="info-item name">{{ str_repeat('|----',(${moduleTemplate}->level>0?(${moduleTemplate}->level-1):0)).${moduleTemplate}->name }}</div>
            </td>
            @include('Backend.dashboard.component.languageTd', ['model' => ${moduleTemplate}, 'modeling' => '{ModuleTemplate}'])
            <!-- <td>
                <?php //{{ $userCatalogue->users_count }} ?>
            </td> -->
            <!-- <td>
                <div class="info-item email"><?php //{{ ${moduleTemplate}->canonical  }} ?></div>
            </td> -->
            <!-- <td>
                <div class="info-item email"><?php //{{ ${moduleTemplate}->description }} ?></div>
            </td> -->
            <td class="text-center js-switch-{{ ${moduleTemplate}->id }}">
                <input type="checkbox" class="js-switch status" value="{{ ${moduleTemplate}->publish }}" data-field="publish" data-model="{{ $config['model'] }}" data-modelId="{{ ${moduleTemplate}->id }}" {{ (${moduleTemplate}->publish==2)?'checked':'' }} >
            </td>
            <td class="text-center">
                <a href="{{ route('{view}.edit', ${moduleTemplate}->id) }}" class="btn btn-success"><i class="fa fa-edit"></i></a>
                <a href="{{ route('{view}.destroy', ${moduleTemplate}->id) }}" class="btn btn-danger"><i class="fa fa-trash"></i></a>
            </td>
        </tr>
        @endforeach
        @endif
    </tbody>
</table>
{{ ${moduleTemplate}s->links('pagination::bootstrap-4') }}