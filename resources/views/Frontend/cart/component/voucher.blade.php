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