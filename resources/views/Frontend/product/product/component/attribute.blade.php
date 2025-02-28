@if(!empty($product->attributeInfo))
@foreach($product->attributeInfo as $attibuteCatalogue)
@php
    $attributeCatalogueName = $attibuteCatalogue['languages'][0]['pivot']['name'];
    $attributes = $attibuteCatalogue['attributes'];
@endphp
<div class="attribute-item attribute-color">
    <div class="label">{{ $attributeCatalogueName }}: <span></span></div>
    @if(!empty($attributes))
    <div class="attribute-value">
        @foreach($attributes as $attribute)
        @php
            $attributeName = $attribute['languages'][0]['pivot']['name'];
        @endphp
        <a class="choose-attribute" title="{{ $attributeName }}" data-attributeid="{{ $attribute['id'] }}">{{ $attributeName }}</a>
        @endforeach
    </div>
    @endif
</div>
@endforeach
<input type="hidden" name="product_id" value="{{ $product->id }}">
<input type="hidden" name="language_id" value="{{ $languageId }}">
<input type="hidden" class="productCanonical" value="{{ write_url($product->languages->first()->pivot->canonical) }}">
@endif