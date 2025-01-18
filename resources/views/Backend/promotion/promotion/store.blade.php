@include('Backend.dashboard.component.breadcrumb', ['title' =>$config['seo']['title']])
<!-- từ khóa tìm kiếm/validation/Displaying the Validation Errors -->
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
@php
    $url=($config['method']=='create')?route('promotion.create'):route('promotion.update', $promotion->id)
@endphp
<form action="{{ $url }}" method="post" class="box">
    @csrf
    <div class="wrapper wrapper-content animated fadeInRight promotion-wrapper">
        <div class="row">
            <div class="col-lg-8">
                @include('Backend.promotion.component.general', ['model' => $promotion ?? null])
                @include('Backend.promotion.promotion.component.detail')
            </div>
            <div class="col-lg-4">
                @include('Backend.promotion.component.aside', ['model' => $promotion ?? null])
            </div>
        </div>
       
        <div class="text-right mb15">
            <button class="btn btn-primary btn-submit-promotion" type="submit" name="send" value="send">{{ $config['seo']['btnTitle'] }}</button>
        </div>
    </div>
</form>
@include('Backend.promotion.promotion.component.popup')

<!-- V113 -->
<!-- Đổ ra dữ liệu sản phẩm áp dụng select cho hình thức khuyến mãi product_and_quantity -->
<input type="hidden" class="input-product-and-quantity" value="{{ json_encode(__('module.item')) }}">

<!-- Xử lý save form cho đối tượng áp dụng -->
<input type="hidden" class="applyValueList" value="{{ json_encode(__('module.applyValue')) }}">
@php
    $applyValue = old('applyValue', $promotion->applyValue ?? []);
@endphp
@if(count($applyValue))
    <input type="hidden" class="conditionItemSelected" value="{{ json_encode($applyValue) }}">
    @foreach($applyValue as $key => $val)
        <input type="hidden" class="condition_input_{{ $val }}" value="{{ json_encode(old($val)) }}">
    @endforeach
@endif

<!-- V114 -->
<!-- xử lý save form select hình thức khuyến mại -->
<input type="hidden" class="preload_promotionMethod" value="{{ json_encode(old('method', $promotion->method ?? null)) }}">
<!-- xử lý save form cho sản phẩm áp dụng loại khuyến mại product_and_quantity (select lv2 của product_and_quantity) -->
<input type="hidden" class="preload_select-product-and-quantity" value="{{ json_encode(old('chooseModule', $promotion->chooseModule ?? null)) }}">
<!-- xử lý save form cho hình thức khuyến mại order_amount_range -->
<input type="hidden" class="input_order_amount_range" value="{{ json_encode(old('promotion_order_amount_range', $promotion->promotion_order_amount_range ?? null)) }}">

<!-- V115 -->
<!-- xử lý save form 4 columns end product_and_quantity method  -->
<input type="hidden" class="input_product_and_quantity" value="{{ json_encode(old('product_and_quantity', $promotion->product_and_quantity ?? null)) }}">
<!-- xử lý save form column first nơi chưa các sản phẩm (.objectItem) -->
<input type="hidden" class="input_object" value="{{ json_encode(old('object', $promotion->object ?? null)) }}">