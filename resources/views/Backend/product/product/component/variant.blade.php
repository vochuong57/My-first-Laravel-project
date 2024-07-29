<div class="row">
    <div class="col-lg-12">
        <div class="variant-checkbox uk-flex uk-flex-middle">
            <input 
                type="checkbox"
                value="1"
                name="accept"
                id="variantCheckbox"
                {{ old('accept') == 1 ? 'checked' : '' }}
            >
            <label for="variantCheckbox" class="">{{ __('messages.Product_variantCheckbox') }}</label>
        </div>
    </div>
</div>

<div class="variant-wrapper {{ old('accept') == 1 ? '' : 'hidden' }}">
    <div class="row variant-container">
        <div class="col-lg-3">
            <div class="attribute-title">{{ __('messages.Product_attribute-title-1') }}</div>
        </div>
        <div class="col-lg-9">
            <div class="attribute-title">{{ __('messages.Product_attribute-title-2') }}</div>
        </div>
    </div>
    <div class="variant-body">
        <!-- v54 -->
        @if(old('attributeCatalogue'))
        @foreach(old('attributeCatalogue') as $keyAttr => $valAttr)
        <div class="row mb20 variant-item">
            <div class="col-lg-3">
                <div class="attribute-catalogue">
                    <select name="attributeCatalogue[]" id="" class="choose-attribute setupNiceSelect">
                        <option value="0">{{ __('messages.Product_select-attribute-group') }}</option>
                        @foreach($attributeCatalogues as $key => $val)
                        <option {{ ($valAttr == $val->id) ? 'selected' : '' }} value="{{ $val->id }}">{{ $val->attribute_catalogue_language->first()->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-lg-8">
                @if($valAttr == 0)
                <input type="text" name="" class="fake-variant form-control" disabled>
                @else
                <select name="attribute[{{ $valAttr }}][]" class="selectVariant form-control variant-{{ $valAttr }}" multiple data-catid="{{ $valAttr }}" id=""></select>
                @endif
            </div>
            <div class="col-lg-1">
                <button type="button" class="remove-attribute btn btn-danger"><i class="fa fa-trash"></i></button>
            </div>
        </div>
        @endforeach
        @endif
    </div>
    <div class="variant-foot mt10">
        <button type="button" class="add-variant">{{ __('messages.Product_add-variant') }}</button>
    </div>
</div>
<script>
    var attributeCatalogues = @json($attributeCatalogues->map(function($item){
        $name = $item->attribute_catalogue_language->first()->name;
        return[
            'id' => $item->id,
            'name' => $name
        ];
    })->values());

    // V54
    var attribute='{{ base64_encode(json_encode(old('attribute'))) }}'

    // V55
    var variant = '{{ base64_encode(json_encode(old('variant'))) }}'

    //variant
    let selectAttributeGroup = "{{ __('messages.Product_select-attribute-group') }}";
    let addVariant = "{{ __('messages.Product_add-variant') }}";
    let placeholderSelect2 = "{{ __('messages.Product_placeholder-select2') }}";

    //productVariant
    let imageProductVariant = "{{ __('messages.Product_image-product-variant') }}";
    let storageProductVariant = "{{ __('messages.Product_storage-product-variant') }}";
    let priceProductVariant = "{{ __('messages.Product_price-product-variant') }}";

    let updateVersionInformation = "{{ __('messages.Product_update_version_information') }}";
    let cancel = "{{ __('messages.Product_cancel') }}";
    let save = "{{ __('messages.Product_save') }}";
    let adviseAlbum = "{{ __('messages.adviseAlbum') }}";
    let inventory = "{{ __('messages.Product_inventory') }}";
    let manageFile = "{{ __('messages.Product_manage_file') }}";
    let fileName = "{{ __('messages.Product_file_name') }}";
    let filePath = "{{ __('messages.Product_file_path') }}";
    let publish = "{{ __('messages.Product_publish') }}";
</script>