<div class="fomr-group">
    <label>Trạng thái: </label>
    <label>Thanh toán qua PAYPAL Thành công!</label>
</div>
<div class="fomr-group">
    <label>Tài khoản thanh toán: </label>
    <label>{{ $response['payment_source']['paypal']['email_address'] }}</label>
</div>
<div class="fomr-group">
    <label>Số tiền đã thanh toán: </label>
    <label>{{ $response['purchase_units'][0]['payments']['captures'][0]['amount']['value'] }} {{ $response['purchase_units'][0]['payments']['captures'][0]['amount']['currency_code'] }}</label>
</div>