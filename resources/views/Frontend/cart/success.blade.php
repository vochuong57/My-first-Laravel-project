@extends('Frontend.homepage.layout', ['popup' => false])

@section('content')
    <div class="cart-success">
        <div class="panel-head">
            <h2 class="cart-heading">{{ __('success.order_success') }}</h2>
            <div class="discover-text"><a href="#">{{ __('success.explore_more_products') }}</a></div>
        </div>
        <div class="panel-body">
            <h2 class="cart-heading"><span>{{ __('success.order_details') }}</span></h2>
            <div class="checkout-box">
                <div class="checkout-box-head">
                    <div class="uk-grid uk-grid-medium uk-flex uk-flex-middle">
                        <div class="uk-width-large-1-3"></div>
                        <div class="uk-width-large-1-3">
                            <div class="order-title uk-text-center">{{ __('success.order') }} #{{ $order->code }}</div>
                        </div>
                        <div class="uk-width-large-1-3">
                            <div class="order-date">{{ convertDateTime($order->created_at) }}</div>
                        </div>
                    </div>
                </div>
                <div class="checkout-box-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>{{ __('success.product_name') }}</th>
                                <th>{{ __('success.quantity') }}</th>
                                <th>{{ __('success.list_price') }}</th>
                                <th>{{ __('success.sale_price') }}</th>
                                <th>{{ __('success.total_amount') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $carts = $order->cart['detail'];
                                //dd($carts);
                            @endphp
                            @if(count($carts))
                            @foreach($carts as $key => $val)
                            <tr>
                                <td>{{ $val['name'] }}</td>
                                <td>{{ $val['qty'] }}</td>
                                <td style="text-decoration: line-through">{{ !is_null($val['priceOriginal']) ? convert_price($val['priceOriginal'], true).'đ' : '' }}</td>
                                <td>{{ convert_price($val['price'], true).'đ' }}</td>
                                <td>{{ convert_price($val['qty'] * $val['price'], true).'đ' }}</td>
                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4">{{ __('success.discount_code') }}</td>
                                <td>{{ !is_null($order->promotion) ? $order->promotion['code'] : '' }}</td>
                            </tr>
                            <tr>
                                <td colspan="4">{{ __('success.total_product_value') }}</td>
                                <td>{{ convert_price($order->cart['cartTotal'], true) }}đ</td>
                            </tr>
                            <tr>
                                <td colspan="4">{{ __('success.total_discount_value') }}</td>
                                <td>-{{ !is_null($order->promotion) ? convert_price($order->promotion['discount'], true) : 0 }}đ</td>
                            </tr>
                            <tr>
                                <td colspan="4">{{ __('success.shipping_fee') }}</td>
                                <td>0đ</td>
                            </tr>
                            <tr class="total_payment">
                                <td colspan="4"><span>{{ __('success.total_payment') }}</span></td>
                                <td>{{ !is_null($order->promotion) ? convert_price($order->cart['cartTotal'] - $order->promotion['discount'], true) : convert_price($order->cart['cartTotal'], true) }}đ</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        <div class="panel-foot">
            <h2 class="cart-heading"><span>{{ __('success.shipping_info') }}</span></h2>
            <div class="checkout-box">
                <div>{{ __('success.recipient_name') }} <span>{{ $order->fullname }}</span></div>
                <div>{{ __('success.recipient_email') }} <span>{{ $order->email }}</span></div>
                <div>{{ __('success.recipient_address') }} <span>{{ $order->fullAddress }}</span></div>
                <div>{{ __('success.recipient_phone') }} <span>{{ $order->phone }}</span></div>
                <div>{{ __('success.payment_method') }} <span>{{ array_column(__('payment.method'), 'title', 'name')[$order->method] ?? $order->method }}</span></div>
            </div>
        </div>
    </div>
@endsection