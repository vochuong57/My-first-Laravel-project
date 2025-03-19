@extends('Frontend.homepage.layout', ['popup' => false])

@section('content')
<div class="cart-container">
    <div class="uk-container uk-container-center">
        <form action="uk-form form" method="post">
            @csrf
            <div class="cart-wrapper">
                <div class="uk-grid uk-grid-medium">
                    <div class="uk-width-large-3-5">
                        <div class="panel-cart cart-left">
                            <div class="panel-head">
                                <div class="uk-flex uk-flex-middle uk-flex-space-between">
                                    <h2 class="cart-heading">
                                        <span>{{ __('payment.order_info') }}</span>
                                    </h2>
                                    <span class="has-account">{{ __('payment.have_account') }} <a href="" title="{{ __('payment.login_now') }}">{{ __('payment.login_now') }}</a></span>
                                </div>
                            </div>
                            <div class="panel-body">
                                <div class="cart-information mb40">
                                    <div class="uk-grid uk-grid-medium mb20">
                                        <div class="uk-width-large-1-2">
                                            <div class="form-row">
                                                <input type="text" name="fullname" value="" placeholder="{{ __('payment.enter_fullname') }}" class="input-text" required>
                                            </div>
                                        </div>
                                        <div class="uk-width-large-1-2">
                                            <div class="form-row">
                                                <input type="text" name="phone" value="" placeholder="{{ __('payment.enter_phone') }}" class="input-text" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row mb20">
                                        <input type="text" name="email" value="" placeholder="{{ __('payment.enter_email') }}" class="input-text" required>
                                    </div>
                                    <div class="uk-grid uk-grid-medium mb20">
                                        <div class="uk-width-large-1-3">
                                            <select name="province_id" id="" class="form-control setupSelect2 provinces location" data-target="DTdistricts">
                                                <option value="0">{{ __('payment.select_province') }}</option>
                                                @if(isset($provinces))
                                                @foreach($provinces as $province)
                                                <option value="{{ $province->code }}">{{ $province->name }}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        <div class="uk-width-large-1-3">
                                            <select name="district_id" id="" class="form-control setupSelect2 districts DTdistricts location" data-target="DTwards">
                                                <option value="0">{{ __('payment.select_district') }}</option>
                                            </select>
                                        </div>
                                        <div class="uk-width-large-1-3">
                                            <select name="ward_id" id="" class="form-control setupSelect2 wards DTwards">
                                                <option value="0">{{ __('payment.select_ward') }}</option>
                                            </select>
                                        </div>
                                        <!-- XỬ LÝ HUYỆN XẢ KHI NHẬP SAI BỊ LOAD LẠI TRANG -->
                                        <script>
                                            var province_id='{{ (isset($order->province_id)) ? $order->province_id : old('province_id') }}'
                                            var district_id='{{ (isset($order->district_id)) ? $order->district_id : old('district_id') }}'
                                            var ward_id='{{ (isset($order->ward_id)) ? $order->ward_id : old('ward_id') }}'
                                        </script>
                                    </div>
                                    <div class="form-row mb20">
                                        <input type="text" name="address" value="" placeholder="{{ __('payment.enter_address') }}" class="input-text" required>
                                    </div>
                                    <div class="form-row">
                                        <input type="text" name="description" value="" placeholder="{{ __('payment.additional_note') }}" class="input-text">
                                    </div>
                                </div>
                                <div class="cart-method">
                                    <h2 class="cart-heading mb20"><span>{{ __('payment.method_title') }}</span></h2>
                                    <div class="cart-method mb30">
                                        @foreach(__('payment.method') as $key => $val)
                                        <label for="{{ $val['name'] }}" class="uk-flex uk-flex-middle method-item">
                                            <input type="radio" name="method" value="{{ $val['name'] }}" {{ $key == 0 ? 'checked' : '' }} id="{{ $val['name'] }}">
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
                            </div>
                        </div>
                    </div>
                    <div class="uk-width-large-2-5">
                        <div class="panel-cart">
                            <div class="panel-head">
                                <h2 class="cart-heading">{{ __('payment.cart') }}</h2>
                            </div>
                            <div class="panel-body">
                                @if(count($carts) && !is_null($carts))
                                <div class="cart-list">
                                    @php
                                        $total = 0;
                                    @endphp
                                    @foreach($carts as $keyCart => $cart)
                                    @php
                                        $total += $cart->price * $cart->qty;
                                    @endphp
                                    <div class="cart-item">
                                        <div class="uk-grid uk-grid-medium">
                                            <div class="uk-width-small-1-1 uk-width-medium-1-5">
                                                <div class="cart-item-image">
                                                    <span class="image img-scaledown"><img src="{{ $cart->image }}" alt=""></span>
                                                    <span class="cart-item-number">{{ $cart->qty }}</span>
                                                </div>
                                            </div>
                                            <div class="uk-width-small-1-1 uk-width-medium-4-5">
                                                <div class="cart-item-info">
                                                    <h3 class="title"><span title="{{ $cart->name }}">{{ $cart->name }}</span></h3>
                                                    <div class="cart-item-action uk-flex uk-flex-middle uk-flex-space-between">
                                                        <div class="cart-item-qty">
                                                            <button type="button" class="btn-qty minus">-</button>
                                                            <input type="text" class="input-qty" value="{{ $cart->qty }}">
                                                            <input type="hidden" class="rowId" value="{{ $cart->rowId }}">
                                                            <button type="button" class="btn-qty plus">+</button>
                                                        </div>
                                                        <div class="cart-item-price">
                                                            <div class="uk-flex uk-flex-middle">
                                                                @if(!is_null($cart->priceOriginal))
                                                                <div class="cart-price-old mr10">{{ convert_price($cart->priceOriginal, true) }}đ</div>
                                                                @endif
                                                                <div class="cart-price-sale mr10">{{ convert_price($cart->price, true) }}đ</div>
                                                                <div class="cart-price-total">{{ convert_price($cart->price * $cart->qty, true) }}đ</div>
                                                            </div>
                                                        </div>
                                                        <div class="cart-item-remove"><span>x</span></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                            <div class="panel-voucher uk-hidden">
                                <div class="voucher-list">
                                    @for($i = 0; $i <= 10; $i++)
                                    <div class="voucher-item {{ $i == 0 ? 'active' : '' }}">
                                        <div class="voucher-left"></div>
                                        <div class="voucher-right">
                                            <div class="voucher-title">SALE103 <span>(Còn 20)</span></div>
                                            <div class="voucher-description">
                                                <p>Khuyến mãi nhân dịp 10/3, giảm giá 50% sản phẩm</p>
                                            </div>
                                        </div>
                                    </div>
                                    @endfor
                                </div>
                                <div class="voucher-form">
                                    <input type="text" name="voucher" placeholder="Chọn mã giảm giá" value="SALE103" readonly>
                                    <a href="" class="apply-voucher">{{ __('payment.apply') }}</a>
                                </div>
                            </div>
                            <div class="panel-foot mt30">
                                <div class="cart-summary">
                                    <div class="cart-summary-item">
                                        <div class="uk-flex uk-flex-middle uk-flex-space-between">
                                            <div class="summary-title">{{ __('payment.order_total') }}</div>
                                            <div class="summary-value show-total-price">{{ (isset($total)) ? convert_price($total, true) : 0 }}đ</div>
                                        </div>
                                    </div>
                                    <div class="cart-summary-item">
                                        <div class="uk-flex uk-flex-middle uk-flex-space-between">
                                            <div class="summary-title">{{ __('payment.discount') }}</div>
                                            <div class="summary-value">-0đ</div>
                                        </div>
                                    </div>
                                    <div class="cart-summary-item">
                                        <div class="uk-flex uk-flex-middle uk-flex-space-between">
                                            <div class="summary-title">{{ __('payment.shipping_fee') }}</div>
                                            <div class="summary-value">Miễn phí</div>
                                        </div>
                                    </div>
                                    <div class="cart-summary-item">
                                        <div class="uk-flex uk-flex-middle uk-flex-space-between">
                                            <div class="summary-title bold">{{ __('payment.total_amount') }}</div>
                                            <div class="summary-value cart-total show-total-price">{{ (isset($total)) ? convert_price($total, true) : 0 }}đ</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
