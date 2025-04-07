<div class="text-danger mb10"><i>{{ __('messages.total_before_discount') }}</i></div>
<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>
                <input type="checkbox" value="" name="" id="checkAll" class="input-checkbox">
            </th>
            <th>{{ __('messages.code') }}</th>
            <th>{{ __('messages.created_at') }}</th>
            <th>{{ __('messages.customer') }}</th>
            <th>{{ __('messages.method') }}</th>
            <th class="text-right">{{ __('messages.discount') }}</th>
            <th class="text-right">{{ __('messages.shipping_fee') }}</th>
            <th class="text-right">{{ __('messages.total_final') }}</th>
            <th class="text-center">{{ __('messages.status') }}</th>
            <th>{{ __('messages.payment') }}</th>
            <th class="text-center">{{ __('messages.delivery') }}</th>
        </tr>
    </thead>
    <tbody>
        <!-- lấy được thông tin từ biến $orders truyền qua từ UsserController thông qua compact -->
        @if(isset($orders) && is_object($orders))
        @foreach($orders as $order)
        <tr class="rowdel-{{ $order->id }}">
            <td>
                <input type="checkbox" value="{{ $order->id }}" name="" class="input-checkbox checkBoxItem">
            </td>
            <td>
                <a href="{{ route('order.detail', ['id' => $order->id]) }}">{{  $order->code }}</a>
            </td>
            <td>
                {{ convertDateTime($order->created_at, 'H:i d/m/Y ') }}
            </td>
            <td>
                <div><b>N:</b> {{ $order->fullname }}</div>
                <div><b>P:</b> {{ $order->phone }}</div>
                <div><b>A:</b> {{ $order->address.', '.$order->wards->name.', '.$order->districts->name.', '.$order->provinces->name }}</div>
            </td>
            <td>
                {{ array_column(__('payment.method'), 'title', 'name')[$order->method] ?? $order->method }}
            </td>
            <td class="text-right">
                {{ convert_price($order->promotion['discount'], true) }}đ
            </td>
            <td class="text-right">
                {{ convert_price($order->shipping, true) }}đ
            </td>
            <td class="text-right">
                {{ convert_price($order->cart['cartTotal'], true) }}đ
            </td>
            <td class="text-center">
                {{ __('cart.confirm')[$order->confirm] }}
            </td>
            <td>
                {{ __('cart.payment')[$order->payment] }}
            </td>
            <td class="text-center">
                {{ __('cart.delivery')[$order->delivery] }}
            </td>
        </tr>
        @endforeach
        @endif
    </tbody>
</table>
{{ $orders->links('pagination::bootstrap-4') }}