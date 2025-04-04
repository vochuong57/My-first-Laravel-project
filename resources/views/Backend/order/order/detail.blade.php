@include('Backend.dashboard.component.breadcrumb',['title' =>$config['seo']['detail']['title']])

<div class="order-wrapper">
    <div class="row">
        <div class="col-lg-8">
            <div class="ibox">
                <div class="ibox-title">
                    <div class="uk-flex uk-flex-middle uk-flex-space-between">
                        <div class="ibox-title-left">
                            <span>{{ $config['seo']['detail']['invoice_details'] }}</span>
                            <span class="badge">
                                <div class="badge__tip"></div>
                                <div class="badge-text">{{ $config['seo']['detail']['not_delivered'] }}</div>
                            </span>
                        </div>
                        <div class="ibox-title-right">
                            {{ $config['seo']['detail']['source'] }} Website
                        </div>
                    </div>
                </div>
                <div class="ibox-content">
                    <table class="table-order">
                        <tbody>
                            @if($order->cart['detail'])
                            @foreach($order->cart['detail'] as $key => $val)
                            <tr class="order-item">
                                <td>
                                    <div class="image">
                                        <span class="image img-scaledown"><img src="{{ $val['image'] ?? 'Backend/img/not-found.png' }}" alt=""></span>
                                    </div>
                                </td>
                                <td>
                                    <div class="order-item-name">{{ $val['name'] }}</div>
                                    <div class="order-item-voucher text-danger">{{ $config['seo']['detail']['discount_code'] }} - không có</div>
                                </td>
                                <td>
                                    <div class="order-item-price">{{ convert_price($val['price'], true).'đ' }}</div>
                                </td>
                                <td>
                                    <div class="order-item-time">x</div>
                                </td>
                                <td>
                                    <div class="order-item-qty">{{ $val['qty'] }}</div>
                                </td>
                                <td>
                                    <div class="order-item-subtotal">{{ convert_price($val['qty'] * $val['price'], true).'đ' }}</div>
                                </td>
                            </tr>
                            @endforeach
                            <tr>
                                <td colspan="5" class="text-right">{{ $config['seo']['detail']['subtotal'] }}</td>
                                <td class="text-right">{{ convert_price($order->cart['cartTotal'], true) }}đ</td>
                            </tr>
                            <tr>
                                <td colspan="5" class="text-right">{{ $config['seo']['detail']['discount'] }}</td>
                                <td class="text-right">-{{ !is_null($order->promotion) ? convert_price($order->promotion['discount'], true) : 0 }}đ</td>
                            </tr>
                            <tr>
                                <td colspan="5" class="text-right">{{ $config['seo']['detail']['shipping'] }}</td>
                                <td class="text-right">0đ</td>
                            </tr>
                            <tr>
                                <td colspan="5" class="text-right"><strong>{{ $config['seo']['detail']['total_final'] }}</strong></td>
                                <td class="text-right"><strong>{{ !is_null($order->promotion) ? convert_price($order->cart['cartTotal'] - $order->promotion['discount'], true) : convert_price($order->cart['cartTotal'], true) }}đ</strong></td>
                            </tr>
                            @else
                            <tr>
                                <td colspan="6" class="text-center">{{ $config['seo']['detail']['no_product'] }}</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="payment-confirm">
                    <div class="uk-flex uk-flex-middle uk-flex-space-between">
                        <div class="uk-flex uk-flex-middle">
                            <span class="icon"><img src="Backend/img/warning.png" alt=""></span>
                            <div class="payment-title">
                                <div class="text_1">
                                    <span class="isConfirm">{{ $config['seo']['detail']['pending_order_confirmation'] }}</span> {{ !is_null($order->promotion) ? convert_price($order->cart['cartTotal'] - $order->promotion['discount'], true) : convert_price($order->cart['cartTotal'], true) }}đ
                                </div>
                                <div class="text_2">{{ array_column(__('payment.method'), 'title', 'name')[$order->method] ?? $order->method }}</div>
                            </div>
                        </div>
                        <div class="cancle-block">
                            <!-- <button class="button">Hủy đơn</button> -->
                        </div>
                    </div>
                </div>
                <div class="payment-confirm">
                    <div class="uk-flex uk-flex-middle uk-flex-space-between">
                        <div class="uk-flex uk-flex-middle">
                            <span class="icon"><i class="fa fa-truck"></i></span>
                            <div class="payment-title">
                                <div class="text_1">
                                    {{ $config['seo']['detail']['confirm_order'] }}
                                </div>
                            </div>
                        </div>
                        <div class="cancle-block">
                            <button class="button confirm">{{ $config['seo']['detail']['btnConfirm'] }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 order-aside">
            <div class="ibox">
                <div class="ibox-title">
                    <div class="uk-flex uk-flex-middle uk-flex-space-between">
                        <h5>{{ $config['seo']['detail']['note'] }}</h5>
                        <div class="edit span edit-order" data-target="description">{{ $config['seo']['detail']['edit'] }}</div>
                    </div>
                </div>
                <div class="ibox-content">
                    <div class="description">
                        {{ $order->description ?? $config['seo']['detail']['no_description'] }}
                    </div>
                </div>
            </div>
            <div class="ibox">
                <div class="ibox-title">
                    <div class="uk-flex uk-flex-middle uk-flex-space-between">
                        <h5>{{ $config['seo']['detail']['customer_info'] }}</h5>
                        <div class="edit span edit-order" data-target="customerInfo">{{ $config['seo']['detail']['edit'] }}</div>
                    </div>
                </div>
                <div class="ibox-content">
                    <div class="customer-line">
                        <strong>N: </strong>{{ $order->fullname }}
                    </div>
                    <div class="customer-line">
                        <strong>E: </strong>{{ $order->email }}
                    </div>
                    <div class="customer-line">
                        <strong>P: </strong>{{ $order->phone }}
                    </div>
                    <div class="customer-line">
                        <strong>A: </strong>{{ $order->address }}
                    </div>
                    <div class="customer-line">
                        <strong>P: </strong>{{ $order->wards->name }}
                    </div>
                    <div class="customer-line">
                        <strong>Q: </strong>{{ $order->districts->name }}
                    </div>
                    <div class="customer-line">
                        <strong>T: </strong>{{ $order->provinces->name }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<input type="hidden" class="orderId" value="{{ $order->id }}">