<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>
                <input type="checkbox" value="" name="" id="checkAll" class="input-checkbox">
            </th>
            <th style="width: 90px">{{ __('messages.tableCustomer_image') }}</th>
            <th>{{ __('messages.tableCustomer_name') }}</th>
            <th>Email</th>
            <th>{{ __('messages.tableCustomer_phone') }}</th>
            <th>{{ __('messages.tableCustomer_address') }}</th>
            <th>{{ __('messages.tableCustomer_catalogue') }}</th>
            <th>{{ __('messages.tableCustomer_source') }}</th>
            <th class="text-center">{{ __('messages.tableCustomer_publish') }}</th>
            <th class="text-center">{{ __('messages.tableCustomer_action') }}</th>
        </tr>
    </thead>
    <tbody>
        <!-- lấy được thông tin từ biến $customers truyền qua từ UsserController thông qua compact -->
        @if(isset($customers) && is_object($customers))
        @foreach($customers as $customer)
        <tr class="rowdel-{{ $customer->id }}">
            <td>
                <input type="checkbox" value="{{ $customer->id }}" name="" class="input-checkbox checkBoxItem">
            </td>
            <td>
                <span class="imagecustomer img-cover"><img
                        src="{{ old('image', $customer->image) ?? 'Backend/img/not-found.png' }}"
                        alt=""></span>
            </td>
            <td>
                <div class="info-item name">{{ $customer->name }}</div>
            </td>
            <td>
                <div class="info-item email">{{ $customer->email }}</div>
            </td>
            <td>
                <div class="info-item phone">{{ $customer->phone }}</div>
            </td>
            <td>
                <div class="address-item name">{{ $customer->address }}</div>
            </td>
            <td>
                <div class="address-item name">{{ $customer->customer_catalogues->name }}</div>
            </td>
            <td>
                <div class="address-item name">{{ $customer->sources->name }}</div>
            </td>
            <td class="text-center js-switch-{{ $customer->id }}">
                <input type="checkbox" class="js-switch status" value="{{ $customer->publish }}" data-field="publish" data-model="{{ $config['model'] }}" data-modelId="{{ $customer->id }}" {{ ($customer->publish==2)?'checked':'' }} >
            </td>
            <td class="text-center">
                <a href="{{ route('customer.edit', $customer->id) }}" class="btn btn-success"><i class="fa fa-edit"></i></a>
                <a href="{{ route('customer.destroy', $customer->id) }}" class="btn btn-danger"><i class="fa fa-trash"></i></a>
            </td>
        </tr>
        @endforeach
        @endif
    </tbody>
</table>
{{ $customers->links('pagination::bootstrap-4') }}