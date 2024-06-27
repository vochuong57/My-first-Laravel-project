<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>
                <input type="checkbox" value="" name="" id="checkAll" class="input-checkbox">
            </th>
            <th style="width: 90px">{{ __('messages.tableLanguage_image') }}</th>
            <th>{{ __('messages.tableLanguage_name') }}</th>
            <!-- <th>Số thành viên</th> -->
            <th>Canonical</th>
            <th>{{ __('messages.tableLanguage_note') }}</th>
            <th class="text-center">{{ __('messages.tableLanguage_publish') }}</th>
            <th class="text-center">{{ __('messages.tableLanguage_action') }}</th>
        </tr>
    </thead>
    <tbody>
        <!-- lấy được thông tin từ biến $userCatalogues truyền qua từ UsserController thông qua compact -->
        @if(isset($languagesIndex) && is_object($languagesIndex))
        @foreach($languagesIndex as $languageIndex)
        <tr class="rowdel-{{ $languageIndex->id }}">
            <td>
                <input type="checkbox" value="{{ $languageIndex->id }}" name="" class="input-checkbox checkBoxItem">
            </td>
            <td>
                <span class="image img-cover"><img
                        src="{{ old('image', $languageIndex->image) ?? 'Backend/img/not-found.png' }}"
                        alt=""></span>
            </td>
            <td>
                <div class="info-item name">{{ $languageIndex->name }}</div>
            </td>
            <!-- <td>
                <?php //{{ $userCatalogue->users_count }} ?>
            </td> -->
            <td>
                <div class="info-item email">{{ $languageIndex->canonical }}</div>
            </td>
            <td>
                <div class="info-item email">{{ $languageIndex->description }}</div>
            </td>
            <td class="text-center js-switch-{{ $languageIndex->id }}">
                <input type="checkbox" class="js-switch status" value="{{ $languageIndex->publish }}" data-field="publish" data-model="{{ $config['model'] }}" data-modelId="{{ $languageIndex->id }}" {{ ($languageIndex->publish==2)?'checked':'' }} >
            </td>
            <td class="text-center">
                <a href="{{ route('language.edit', $languageIndex->id) }}" class="btn btn-success"><i class="fa fa-edit"></i></a>
                <a href="{{ route('language.destroy', $languageIndex->id) }}" class="btn btn-danger"><i class="fa fa-trash"></i></a>
            </td>
        </tr>
        @endforeach
        @endif
    </tbody>
</table>
{{ $languagesIndex->links('pagination::bootstrap-4') }}