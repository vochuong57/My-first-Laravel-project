<!DOCTYPE html>
<html>
    <head>
        <style>
            .cart-success{
                padding: 30px 10px;
            }
            @media (min-width: 1220px){
                .cart-success{
                    width:800px;
                    margin:0 auto;
                }
            }
            .cart-success .cart-heading{
                text-align: center;
                margin-bottom:30px;
            }
            .cart-success .cart-heading > span{
                text-transform: uppercase;
                font-weight: 700;
            }
            .discover-text > *{
                display: inline-block;
                padding:10px 25px;
                background: #2f5acf;
                border-radius: 16px;
                cursor:pointer;
                color:#fff;
            }
            .discover-text{
                text-align: center;
            }
            .checkout-box{
                border:1px solid #000;
                padding:15px 20px;
                border-radius: 16px;
            }
            .cart-success .panel-body{
                margin-bottom:40px;
            }
            .checkout-box-head{
                margin-bottom:30px;
            }

            .checkout-box-head .order-title{
                border:1px solid #000;
                border-radius: 16px;
                padding:10px 15px;
                font-weight: 700;
                font-size:16px;
            }
            .checkout-box-head .order-date{
                display: flex;
                align-items: center;
                font-size:16px;
                font-weight: bold;
                text-align: center;
            }
            .cart-success .table{
                width:100%;
                border-spacing: 0;
                background: #d9d9d9;
                border-collapse: inherit;
            }
            .cart-success .table thead>tr th{
                color:#fff;
                background: #2f5acf;
                font-weight: 500;
                font-size:14px;
                vertical-align: middle;
                border-bottom: 2px solid #dee2e6;
                text-align: center;
                border:none;
                padding:12px 15px;
            }
            .cart-success tbody tr td{
                padding:12px 15px;
                vertical-align: middle;
                font-size: 14px;
                color:#000;
                border-bottom:1px solid #ccc;
            }
            .cart-success tfoot{
                background: #fff;
            }
            .cart-success tfoot tr td{
                padding:8px;
            }

            .cart-success .table td:last-child{
                text-align: right;
            }
            .cart-success .table tbody tr:nth-child(2n) td {
                background-color: #d9d9d9;
            }
            .total_payment{
                font-weight: bold;
                font-size:24px;
            }
            .panel-foot .checkout-box div{
                margin-bottom:15px;
                font-weight: 500;
            }
        </style>
    </head>
    <body>
        <div class="cart-success">
            <div class="panel-head">
                <h2 class="cart-heading">{{ __('success.order_success') }}</h2>
                <div class="discover-text"><a href="{{ $data->link }}" style="color: #fff">{{ __('success.explore_more_products') }}</a></div>
            </div>
            <div class="panel-body">
                <h2 class="cart-heading"><span>{{ __('success.order_details') }}</span></h2>
                <div class="checkout-box">
                    <div class="checkout-box-head">
                        <div class="uk-grid uk-grid-medium uk-flex uk-flex-middle">
                            <div class="uk-width-large-1-3"></div>
                            <div class="uk-width-large-1-3">
                                <div class="order-title uk-text-center">{{ __('success.order') }} #{{ $data->code }}</div>
                            </div>
                            <div class="uk-width-large-1-3">
                                <div class="order-date">{{ convertDateTime($data->created_at) }}</div>
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
                                    $carts = $data->cart['detail'];
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
                                    <td>{{ !is_null($data->promotion) ? $data->promotion['code'] : '' }}</td>
                                </tr>
                                <tr>
                                    <td colspan="4">{{ __('success.total_product_value') }}</td>
                                    <td>{{ convert_price($data->cart['cartTotal'], true) }}đ</td>
                                </tr>
                                <tr>
                                    <td colspan="4">{{ __('success.total_discount_value') }}</td>
                                    <td>-{{ !is_null($data->promotion) ? convert_price($data->promotion['discount'], true) : 0 }}đ</td>
                                </tr>
                                <tr>
                                    <td colspan="4">{{ __('success.shipping_fee') }}</td>
                                    <td>0đ</td>
                                </tr>
                                <tr class="total_payment">
                                    <td colspan="4"><span>{{ __('success.total_payment') }}</span></td>
                                    <td>{{ !is_null($data->promotion) ? convert_price($data->cart['cartTotal'] - $data->promotion['discount'], true) : convert_price($data->cart['cartTotal'], true) }}đ</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <div class="panel-foot">
                <h2 class="cart-heading"><span>{{ __('success.shipping_info') }}</span></h2>
                <div class="checkout-box">
                    <div>{{ __('success.recipient_name') }} <span>{{ $data->fullname }}</span></div>
                    <div>{{ __('success.recipient_email') }} <span>{{ $data->email }}</span></div>
                    <div>{{ __('success.recipient_address') }} <span>{{ $data->fullAddress }}</span></div>
                    <div>{{ __('success.recipient_phone') }} <span>{{ $data->phone }}</span></div>
                    <div>{{ __('success.payment_method') }} <span>{{ array_column(__('payment.method'), 'title', 'name')[$data->method] ?? $data->method }}</span></div>
                </div>
            </div>
        </div>
    </body>
</html>