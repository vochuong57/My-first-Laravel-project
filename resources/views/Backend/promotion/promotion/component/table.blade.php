<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>
                <input type="checkbox" value="" name="" id="checkAll" class="input-checkbox">
            </th>
            <th>Tên chương trình</th>
            <th class="text-center">Chiết khấu</th>
            <th>Thông tin</th>
            <th>Ngày bắt đầu</th>
            <th>Ngày kết thúc</th>
            <?php //include('Backend.dashboard.component.languageTh') ?>
            <th class="text-center">Tình trạng</th>
            <th class="text-center">Thao tác</th>
        </tr>
    </thead>
    <tbody>
        <!-- lấy được thông tin từ biến $promotions truyền qua từ UsserController thông qua compact -->
        @if(isset($promotions) && is_object($promotions))
        @foreach($promotions as $promotion)
        <tr class="rowdel-{{ $promotion->id }}">
            <td>
                <input type="checkbox" value="{{ $promotion->id }}" name="" class="input-checkbox checkBoxItem">
            </td>
            @php
                $status = '';
                if($promotion->endDate != null && strtotime($promotion->endDate) - strtotime(now()) <= 0){
                    $status = '<span class="text-danger text-small">- Hết hạn</span>';
                }
            @endphp
            <td>
                <div class="info-item email"><span>{{ $promotion->name }} {!! $status !!}</span> <div class="label"></div> </div>
                <div class="text-small text-success">Mã KM: {{ $promotion->code }}</div>
            </td>
            <td>
                <div class="discount-information text-center">
                    {!! renderDiscountInformation($promotion); !!}
                </div>
            </td>
            <td>
                <div>{{ __('module.promotion')[$promotion->method] }}</div>
            </td>
            @php
                $startDate = convertDateTime($promotion->startDate);
                $endDate = convertDateTime($promotion->endDate);
            @endphp
            <td>
                <div class="info-item email">{{ $startDate }}</div>
            </td>
            <td>
                <div class="info-item email">{{ ($promotion->neverEndDate == null) ? $endDate : 'Không có ngày kết thúc' }}</div>
            </td>
            <?php
            // foreach($languages as $language)
            //     if(session('app_locale') == $language->canonical) 
            //         @continue 
            //     endif
            //     php
            //         $translated = (isset($promotion->description[$language->id])) ? 1 : 0;
            //     endphp
            //     <td style="width: 100px;" class="text-center">
            //         <a  class="{{ ($translated == 1) ? '' : 'text-danger' }}"
            //             href="{{ route('promotion.translate', ['languageId' => $language->id, 'id' => $promotion->id]) }}">
            //             {{ ($translated == 1) ? 'Đã dịch' : 'Chưa dịch' }}
            //         </a>
            //     </td>
            // endforeach
            ?>
            <td class="text-center js-switch-{{ $promotion->id }}">
                <input type="checkbox" class="js-switch status" value="{{ $promotion->publish }}" data-field="publish" data-model="{{ $config['model'] }}" data-modelId="{{ $promotion->id }}" {{ ($promotion->publish==2)?'checked':'' }} >
            </td>
            <td class="text-center">
                <a href="{{ route('promotion.edit', $promotion->id) }}" class="btn btn-success"><i class="fa fa-edit"></i></a>
                <a href="{{ route('promotion.destroy', $promotion->id) }}" class="btn btn-danger"><i class="fa fa-trash"></i></a>
            </td>
        </tr>
        @endforeach
        @endif
    </tbody>
</table>
{{ $promotions->links('pagination::bootstrap-4') }}