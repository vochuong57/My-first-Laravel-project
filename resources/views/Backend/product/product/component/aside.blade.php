<div class="ibox">
    <div class="ibox-title">
        <h5>{{ __('messages.parent') }}</h5>
    </div>
    <div class="ibox-content">
        <div class="row mb15">
            <div class="col-lg-12">
                <div class="form-row">
                    <span class="text-danger notice">*{{ __('messages.parent') }}</span>
                    <select name="product_catalogue_id" class="form-control setupSelect2" id="">
                        @foreach($dropdown as $key => $val)
                        <option
                            {{ $key == old('product_catalogue_id', (isset($product->product_catalogue_id)) ? $product->product_catalogue_id : '') ? 'selected' : '' }} 
                            value="{{ $key }}"
                        >
                            {{ $val }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        @php
            //Xử lý đổ dữ liệu danh sách nhóm bài viết vào danh mục phụ
            $catalogue = [];
            if(isset($product)){
                foreach($product->product_catalogues as $key => $val){
                    $catalogue[]=$val->id;
                }
            }
        @endphp
        <div class="row">
            <div class="col-lg-12">
                <div class="form-row">
                    <label class="control-label">{{ __('messages.children') }}</label>
                    <select multiple name="catalogue[]" class="form-control setupSelect2" id="">
                        @foreach($dropdown as $key => $val)
                        <option
                            @if(is_array(old('catalogue', (isset($catalogue)  && count($catalogue)) ? $catalogue : [])) && in_array($key, old('catalogue', (isset($catalogue)) ? $catalogue : []))) selected @endif
                            value="{{ $key }}"
                        >
                            {{ $val }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="ibox">
    <div class="ibox-title">
        <h5>{{ __('messages.image') }}</h5>
    </div>
    <div class="ibox-content">
        <div class="row">
            <div class="col-lg-12">
                <div class="form-row">
                    <span class="image img-cover image-target">
                        <img src="{{ (old('image', $product->image ??'Backend/img/not-found.png')) ?? 'Backend/img/not-found.png' }}" alt="">
                    </span>
                    <input type="hidden" name="image" value="{{ old('image', ($product->image)??'') }}">
                </div>
            </div>

        </div>
    </div>
</div>
<div class="ibox">
    <div class="ibox-title">
        <h5>{{ __('messages.advance') }}</h5>
    </div>
    <div class="ibox-content">
        <div class="row">
            <div class="col-lg-12">
                <div class="form-row">
                   
                <div class="mb15">
                        <select name="publish" class="form-control setupSelect2" id="">
                            @foreach(__('messages.publish') as $key => $val)
                                <option 
                                    {{ $key == old('publish', (isset($product->publish)) ? $product->publish : '') ? 'selected' : '' }} 
                                    value="{{ $key }}"
                                >
                                    {{ $val }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    
                    <div class="mb15">
                        <select name="follow" class="form-control setupSelect2" id="">
                            @foreach(__('messages.follow') as $key => $val)
                            <option 
                                {{ $key == old('follow', (isset($product->follow)) ? $product->follow : '') ? 'selected' : '' }} 
                                value="{{ $key }}"
                            >
                                {{ $val }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<div class="ibox">
    <div class="ibox-title">
        <h5>{{ __('messages.General_product_information') }}</h5>
    </div>
    <div class="ibox-content">
        <div class="row">
            <div class="col-lg-12">
                <div class="form-row">

                @php
                    $timestamp = time(); // Lấy thời gian hiện tại dưới dạng UNIX timestamp
                @endphp
                   
                    <div class="mb15">
                        <label for="">{{ __('messages.Product_code') }}</label>
                        <input 
                            type="text"
                            name="code"
                            value="{{ old('code', ($product->code) ?? $timestamp) }}"
                            class="form-control"
                        >
                    </div>

                    
                    <div class="mb15">
                        <label for="">{{ __('messages.Product_made_in') }}</label>
                        <input 
                            type="text"
                            name="made_id"
                            value="{{ old('made_id', ($product->made_id) ?? null) }}"
                            class="form-control"
                        >
                    </div>

                    <div class="mb15">
                        <label for="">{{ __('messages.Product_price') }}</label>
                        <input 
                            type="text"
                            name="price"
                            value="{{ old('price', (isset($product->price)) ? number_format($product->price, 0 , ',', '.') : '') }}"
                            class="form-control int"
                        >
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>