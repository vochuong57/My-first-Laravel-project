<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>
                <input type="checkbox" value="" name="" id="checkAll" class="input-checkbox">
            </th>
            <!-- <th style="width: 90px">{{ __('messages.tableReview_image') }}</th> -->
            <th>{{ __('messages.tableReview_name') }}</th>
            <th>{{ __('messages.tableReview_phone') }}</th>
            <th>{{ __('messages.tableReview_email') }}</th>
            <th style="width: 450px;">{{ __('messages.tableReview_content') }}</th>
            <th class="text-center">{{ __('messages.tableReview_score') }}</th>
            <th class="text-center">{{ __('messages.tableReview_publish') }}</th>
            <th class="text-center">{{ __('messages.tableReview_action') }}</th>
        </tr>
    </thead>
    <tbody>
        <!-- lấy được thông tin từ biến $reviews truyền qua từ RviewController thông qua compact -->
        @if(isset($reviews) && is_object($reviews))
        @foreach($reviews as $review)
        @php
            $reviewableLink = $review->reviewable->languages->where('id', $language_id)->first()->pivot->canonical ?? null;
            //dd($reviewableLink);
        @endphp
        <tr class="rowdel-{{ $review->id }}">
            <td>
                <input type="checkbox" value="{{ $review->id }}" name="" class="input-checkbox checkBoxItem">
            </td>
            <!-- <td>
                <span class="imagereview img-cover"><img
                        src="{{ old('image', $review->image) ?? 'Backend/img/not-found.png' }}"
                        alt=""></span>
            </td> -->
            <td>
                <div class="info-item fullname">{{ $review->fullname }}</div>
            </td>
            <td>
                <div class="info-item phone">{{ $review->phone }}</div>
            </td>
            <td>
                <div class="info-item email">{{ $review->email }}</div>
            </td>
            <td>
                <div class="info-item description">{{ $review->description }}</div>
            </td>
            <td class="text-center">
                <div class="info-item score text-navy">{{ $review->score }}</div>
            </td>
            <td class="text-center js-switch-{{ $review->id }}">
                <input type="checkbox" class="js-switch status" value="{{ $review->publish }}" data-field="publish" data-model="{{ $config['model'] }}" data-modelId="{{ $review->id }}" {{ ($review->publish==2)?'checked':'' }} >
            </td>
            <td class="text-center">
                {!! !is_null($reviewableLink) ? "<a href='".write_url($reviewableLink)."' class='btn btn-warning'><i class='fa fa-eye'></i></a>" : "" !!}
                <a href="{{ route('review.destroy', $review->id) }}" class="btn btn-danger"><i class="fa fa-trash"></i></a>
            </td>
        </tr>
        @endforeach
        @endif
    </tbody>
</table>
{{ $reviews->links('pagination::bootstrap-4') }}