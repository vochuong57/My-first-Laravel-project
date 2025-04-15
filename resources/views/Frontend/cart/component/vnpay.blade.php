<div class="table-responsive">
    <div class="form-group">
        <label>Mã đơn hàng:</label>

        <label>{{ $_GET['vnp_TxnRef'] }}</label>
    </div>
    <div class="form-group">
        <label>Số tiền:</label>
        <label>{{ convert_price($_GET['vnp_Amount'] / 100, true) }}đ</label>
    </div>
    <div class="form-group">
        <label>Nội dung thanh toán:</label>
        <label>{{ $_GET['vnp_OrderInfo'] }}</label>
    </div>
    <div class="form-group">
        <label>Mã phản hồi (vnp_ResponseCode):</label>
        <label>{{ $_GET['vnp_ResponseCode'] }}</label>
    </div>
    <div class="form-group">
        <label>Mã GD tại VNPAY:</label>
        <label>{{ $_GET['vnp_TransactionNo'] }}</label>
    </div>
    <div class="form-group">
        <label>Mã Ngân hàng:</label>
        <label>{{ $_GET['vnp_BankCode'] }}</label>
    </div>
    <div class="form-group">
        <label>Thời gian thanh toán:</label>
        <label>{{ convertDateTime($_GET['vnp_PayDate'], 'd-m-Y H:i:s', 'YmdHis') }}</label>
    </div>
    <div class="form-group">
        <label>Kết quả:</label>
        <label>
            @if($secureHash == $vnp_SecureHash) 
                @if ($_GET['vnp_ResponseCode'] == '00')
                    <span style='color:blue'>Giao dịch qua cổng VNPAY Thành công</span>
                 
                @else 
                    <span style='color:red'>Giao dịch qua cổng VNPAY Không thành công</span>
                
                @endif
            
            @else 
                <span style='color:red'>Chữ ký không hợp lệ</span>
            
            @endif
        </label>
    </div>
</div>