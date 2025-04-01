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
                            @for($i = 0; $i < 3; $i++)
                            <tr class="order-item">
                                <td>
                                    <div class="image">
                                        <span class="image img-scaledown"><img src="https://byvn.net/YhAJ" alt=""></span>
                                    </div>
                                </td>
                                <td>
                                    <div class="order-item-name">Laptop Acer Aspire 7 A715 76G</div>
                                    <div class="order-item-voucher">{{ $config['seo']['detail']['discount_code'] }} -</div>
                                </td>
                                <td>
                                    <div class="order-item-price">17,000,000đ</div>
                                </td>
                                <td>
                                    <div class="order-item-time">x</div>
                                </td>
                                <td>
                                    <div class="order-item-qty">1</div>
                                </td>
                                <td>
                                    <div class="order-item-subtotal">17,000,000đ</div>
                                </td>
                            </tr>
                            @endfor
                            <tr>
                                <td colspan="5" class="text-right">{{ $config['seo']['detail']['subtotal'] }}</td>
                                <td class="text-right">51.000.000đ</td>
                            </tr>
                            <tr>
                                <td colspan="5" class="text-right">{{ $config['seo']['detail']['discount'] }}</td>
                                <td class="text-right">-0đ</td>
                            </tr>
                            <tr>
                                <td colspan="5" class="text-right">{{ $config['seo']['detail']['shipping'] }}</td>
                                <td class="text-right">0đ</td>
                            </tr>
                            <tr>
                                <td colspan="5" class="text-right"><strong>{{ $config['seo']['detail']['total_final'] }}</strong></td>
                                <td class="text-right"><strong>51.000.000đ</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="payment-confirm">
                    <div class="uk-flex uk-flex-middle uk-flex-space-between">
                        <div class="uk-flex uk-flex-middle">
                            <span class="icon"><img src="Backend/img/warning.png" alt=""></span>
                            <div class="payment-title">
                                <div class="text_1">
                                    <span class="isConfirm">{{ $config['seo']['detail']['pending_order_confirmation'] }}</span> 51.000.000đ
                                </div>
                                <div class="text_2">Thanh toán khi nhận hàng (COD)</div>
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
                        <div class="edit span">{{ $config['seo']['detail']['edit'] }}</div>
                    </div>
                </div>
                <div class="ibox-content">
                    <div class="description">
                        Giao hàng lúc 3:00 PM
                    </div>
                </div>
            </div>
            <div class="ibox">
                <div class="ibox-title">
                    <div class="uk-flex uk-flex-middle uk-flex-space-between">
                        <h5>{{ $config['seo']['detail']['customer_info'] }}</h5>
                        <div class="edit span">{{ $config['seo']['detail']['edit'] }}</div>
                    </div>
                </div>
                <div class="ibox-content">
                    <div class="customer-line">
                        <strong>N: </strong>Chương Võ
                    </div>
                    <div class="customer-line">
                        <strong>E: </strong>vochuong57@gmail.com
                    </div>
                    <div class="customer-line">
                        <strong>P: </strong>0346337782
                    </div>
                    <div class="customer-line">
                        <strong>A: </strong>55b đường 19
                    </div>
                    <div class="customer-line">
                        <strong>P: </strong>Linh Chiểu
                    </div>
                    <div class="customer-line">
                        <strong>Q: </strong>Thủ Đức
                    </div>
                    <div class="customer-line">
                        <strong>T: </strong>Hồ Chí Minh
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
