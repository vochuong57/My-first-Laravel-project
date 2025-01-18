<div class="ibox">
    <div class="ibox-title">
        <h5>Cài đặt thông tin chi tiết khuyến mãi</h5>
    </div>
    <div class="ibox-content">
        <div class="form-row">
            <div class="fix-label ml5" for="">Chọn hình thức khuyến mãi</div>
            <select name="method" id="" class="setupSelect2 promotionMethod">
                <option value="default">Chọn hình thức</option>
                @foreach(__('module.promotion') as $key => $val)
                    <option value="{{ $key }}">{{ $val }}</option>
                @endforeach
            </select>
        </div>
        <div class="promotion-container">
            
        </div>
    </div>
</div>