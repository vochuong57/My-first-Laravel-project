<div class="row">
    <div class="col-lg-12 mb10">
        <div class="uk-flex uk-flex-middle uk-flex-space-between">
            <div for="" class="text-bold">Chọn vị trí hiển thị <span class="text-danger">(*)</span></div>
            <button type="button" class="createMenuCatalogue btn btn-danger" data-toggle="modal" data-target="#createMenuCatalogue">Tạo vị trí hiển thị</button>
        </div>
    </div>
    <div class="col-lg-6">
        <select class="setupSelect2" name="menu_catalogue_id" id="">
            <option value="0">[Chọn vị trí hiển thị]</option>
            @if(isset($menuCatalogues))
            @foreach($menuCatalogues as $menuCatalogue)
            <option value="{{ $menuCatalogue->id }}">{{ $menuCatalogue->name }}</option>
            @endforeach
            @endif
        </select>
    </div>
    <div class="col-lg-6">
        <select class="setupSelect2" name="menu_type" id="">
            <option value="0">[Chọn kiểu menu]</option>
            @foreach(__('module.type') as $key => $val)
            <option value="{{ $key }}">{{ $val }}</option>
            @endforeach
        </select>
    </div>
</div>