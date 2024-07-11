<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th style="width: 50px"> 
                <input type="checkbox" value="" name="" id="checkAll" class="input-checkbox">
            </th>
            <!-- <th style="width: 90px">Ảnh</th> -->
            <th>{{ __('messages.table{ModuleTemplate}_name') }}</th>
            @include('Backend.dashboard.component.languageTh')
            <th style="width: 80px" class="text-center">{{ __('messages.table{ModuleTemplate}_pos') }}</th>
            <!-- <th>Canonical</th> -->
            <!-- <th>Ghi chú</th> -->
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
                <input type="checkbox" value="{{ ${moduleTemplate}->id }}" data-languageId = "{{ ${moduleTemplate}->language_id }}" name="" class="input-checkbox checkBoxItem">
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
                            <img src="{{ ${moduleTemplate}->image ?? 'Backend/img/not-found.png' }}" alt="">
                        </div>
                    </div>
                    <div class="main-info">
                        <div class="name">
                            <span class="maintitle">{{ ${moduleTemplate}->name }}</span>
                        </div>

                        <div class="catalogue">
                            <span class="text-danger">{{ __('messages.table{ModuleTemplate}_displayCatalogue') }}</span>
                            @foreach(${moduleTemplate}->{moduleTemplate}_catalogues as $val) <?php // '->{moduleTemplate}_catalogues' là function {moduleTemplate}_catalogues() của Model/{ModuleTemplate} có LQ theo đường {ModuleTemplate}Repository ?>
                            @foreach($val->{moduleTemplate}_catalogue_language as $cat)
                            <a href="{{ route('{moduleTemplate}.index', ['{moduleTemplate}_catalogue_id'=>$val->id]) }}">{{ $cat->name }}</a>
                            @endforeach
                            @endforeach
                        </div>
                    </div>
                </div>
            </td>
            @include('Backend.dashboard.component.languageTd', ['model' => ${moduleTemplate}, 'modeling' => '{ModuleTemplate}'])
            <td>
                <input type="text" name="order" value="{{ ${moduleTemplate}->order }}" class="form-control sort-order text-right" data-id="{{ ${moduleTemplate}->id }}" data-model="{{ $config['model'] }}">
            </td>
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
                <a href="{{ route('{moduleTemplate}.edit', ${moduleTemplate}->id) }}" class="btn btn-success"><i class="fa fa-edit"></i></a>
                <a href="{{ route('{moduleTemplate}.destroy', ${moduleTemplate}->id) }}" class="btn btn-danger"><i class="fa fa-trash"></i></a>
            </td>
        </tr>
        @endforeach
        @endif
    </tbody>
</table>
{{ ${moduleTemplate}s->links('pagination::bootstrap-4') }}