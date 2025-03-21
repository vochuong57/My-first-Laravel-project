<div class="cart-summary">
    <div class="cart-summary-item">
        <div class="uk-flex uk-flex-middle uk-flex-space-between">
            <div class="summary-title">{{ __('payment.order_total') }}</div>
            <div class="summary-value show-pre-total-price">{{ (isset($cartCaculate['cartTotal'])) ? convert_price($cartCaculate['cartTotal'], true) : 0 }}đ</div>
        </div>
    </div>
    <div class="cart-summary-item">
        <div class="uk-flex uk-flex-middle uk-flex-space-between">
            <div class="summary-title">{{ __('payment.discount') }}</div>
            <div class="summary-value discount-value">-{{ convert_price($cartPromotion['discount'], true) }}đ</div>
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
            <div class="summary-value cart-total show-total-price">{{ (isset($cartCaculate['cartTotal'])) ? convert_price($cartCaculate['cartTotal'] - $cartPromotion['discount'], true) : 0 }}đ</div>
        </div>
    </div>
</div>