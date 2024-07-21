<div class="row">
    <div class="col-lg-12">
        <div class="variant-checkbox uk-flex uk-flex-middle">
            <input 
                type="checkbox"
                value=""
                name="accept"
                id="variantCheckbox"
            >
            <label for="variantCheckbox" class="">{{ __('messages.Product_variantCheckbox') }}</label>
        </div>
    </div>
</div>
<div class="variant-wrapper hidden">
    <div class="row variant-container">
        <div class="col-lg-3">
            <div class="attribute-title">{{ __('messages.Product_attribute-title-1') }}</div>
        </div>
        <div class="col-lg-9">
        <div class="attribute-title">{{ __('messages.Product_attribute-title-2') }}</div>
        </div>
    </div>
    <div class="variant-body">
        
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

    //variant
    let selectAttributeGroup = "{{ __('messages.Product_select-attribute-group') }}";
    let addVariant = "{{ __('messages.Product_add-variant') }}";
    let placeholderSelect2 = "{{ __('messages.Product_placeholder-select2') }}";

    //productVariant
    let imageProductVariant = "{{ __('messages.Product_image-product-variant') }}";
    let storageProductVariant = "{{ __('messages.Product_storage-product-variant') }}";
    let priceProductVariant = "{{ __('messages.Product_price-product-variant') }}";
</script>