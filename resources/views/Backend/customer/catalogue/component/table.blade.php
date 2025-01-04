<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>
                <input type="checkbox" value="" name="" id="checkAll" class="input-checkbox">
            </th>
            <th>{{ __('messages.tableCustomerCatalogue_name') }}</th>
            <th>{{ __('messages.tableCustomerCatalogue_count') }}</th>
            <th>{{ __('messages.tableCustomerCatalogue_description') }}</th>
            <th class="text-center">{{ __('messages.tableCustomerCatalogue_publish') }}</th>
            <th class="text-center">{{ __('messages.tableCustomerCatalogue_action') }}</th>
        </tr>
    </thead>
    <tbody>
        <!-- lấy được thông tin từ biến $customerCatalogues truyền qua từ UsserController thông qua compact -->
        @if(isset($customerCatalogues) && is_object($customerCatalogues))
        @foreach($customerCatalogues as $customerCatalogue)
        <tr class="rowdel-{{ $customerCatalogue->id }}">
            <td>
                <input type="checkbox" value="{{ $customerCatalogue->id }}" name="" class="input-checkbox checkBoxItem">
            </td>
            
            <td>
                <div class="info-item name">{{ $customerCatalogue->name }}</div>
            </td>
            <td>
                {{ $customerCatalogue->customers_count }}
            </td>
            <td>
                <div class="info-item email">{{ $customerCatalogue->description }}</div>
            </td>
           
            <td class="text-center js-switch-{{ $customerCatalogue->id }}">
                <input type="checkbox" class="js-switch status" value="{{ $customerCatalogue->publish }}" data-field="publish" data-model="{{ $config['model'] }}" data-modelId="{{ $customerCatalogue->id }}" {{ ($customerCatalogue->publish==2)?'checked':'' }} >
            </td>
            <td class="text-center">
                <a href="{{ route('customer.catalogue.edit', $customerCatalogue->id) }}" class="btn btn-success"><i class="fa fa-edit"></i></a>
                <a href="{{ route('customer.catalogue.destroy', $customerCatalogue->id) }}" class="btn btn-danger"><i class="fa fa-trash"></i></a>
            </td>
        </tr>
        @endforeach
        @endif
    </tbody>
</table>
{{ $customerCatalogues->links('pagination::bootstrap-4') }}