<div class="cart-information mb40">
    <div class="uk-grid uk-grid-medium mb20">
        <div class="uk-width-large-1-2">
            <div class="form-row">
                <input type="text" name="fullname" value="{{ old('fullname') }}" placeholder="{{ __('payment.enter_fullname') }}" class="input-text" required>
            </div>
        </div>
        <div class="uk-width-large-1-2">
            <div class="form-row">
                <input type="text" name="phone" value="{{ old('phone') }}" placeholder="{{ __('payment.enter_phone') }}" class="input-text" required>
            </div>
        </div>
    </div>
    <div class="form-row mb20">
        <input type="text" name="email" value="{{ old('email') }}" placeholder="{{ __('payment.enter_email') }}" class="input-text" required>
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
        <input type="text" name="address" value="{{ old('address') }}" placeholder="{{ __('payment.enter_address') }}" class="input-text" required>
    </div>
    <div class="form-row">
        <input type="text" name="description" value="{{ old('description') }}" placeholder="{{ __('payment.additional_note') }}" class="input-text">
    </div>
</div>