<div class="cart-method">
    <h2 class="cart-heading mb20"><span>{{ __('payment.method_title') }}</span></h2>
    <div class="cart-method mb30">
        @foreach(__('payment.method') as $key => $val)
        <label for="{{ $val['name'] }}" class="uk-flex uk-flex-middle method-item">
            <input 
                type="radio" 
                name="method" 
                value="{{ $val['name'] }}" 
                id="{{ $val['name'] }}"
                @php
                    // Lấy giá trị 'method' từ old hoặc từ DB
                    $method = old('method', $order->method ?? 'cod');
                @endphp
                {{ ($method == $val['name']) ? 'checked' : '' }}
            >
            <span class="image"><img src="{{ $val['image'] }}" alt=""></span>
            <span class="title">{{ $val['title'] }}</span>
        </label>
        @endforeach
    </div>
    <div class="cart-return mb10">
        <span>{!! __('payment.return') !!}</span>
    </div>
    <button type="submit" class="cart-checkout" value="create" name="create">{{ __('payment.checkout') }}</button>
</div>