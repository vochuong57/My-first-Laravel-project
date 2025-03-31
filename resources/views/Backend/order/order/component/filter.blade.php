<form action="{{ route('order.index') }}">
<div class="filter-wrapper">
    <div class="uk-flex uk-flex-middle uk-flex-space-between">
        <div class="uk-flex uk-flex-middle">
            <div class="perpage">
                <div class="uk-flex uk-flex-middle uk-flex-space-between">
                    <select name="perpage" class="form-control input-sm perpage filter mr10" @php $perpage=request('perpage') ?: old('perpage') @endphp>
                        @for($i=20;$i<=200;$i+=20) 
                            <option {{ ($perpage == $i) ? 'selected' : '' }} value="{{ $i }}">{{ $i }} {{ __('messages.perpage') }}</option>
                        @endfor
                    </select>
    
                </div>
            </div>
            <div class="date-item-box">
                @php
                    $created_at = request('created_at') ?: old('created_at');
                @endphp
                <input type="text" name="created_at" value="{{ $created_at }}" class="daterange" placeholder="{{ __('messages.dateRange') }}" readonly>
            </div>
            <script>
                let trans_daterange = {
                    apply: @json(__('messages.trans_daterange_apply')),
                    delete: @json(__('messages.trans_daterange_delete')),
                    month: @json(__('messages.trans_daterange_month')),
                }
            </script>
        </div>
        <div class="action">
            <div class="uk-flex uk-flex-middle">
                <div class="mr10">
                    @foreach(__('cart') as $key => $val)
                    @php
                        ${$key} = request($key) ?: old($key);
                    @endphp
                    <select name="{{ $key }}" class="form-control ml10 setupSelect2">
                        @foreach($val as $index => $item)
                            <option {{ (${$key} == $index) ? 'selected' : '' }} value="{{ $index }}">{{ $item }}</option>
                        @endforeach
                    </select>
                    @endforeach
                </div>
                <div class="uk-search uk-flex uk-flex-middle">
                    <div class="input-group">
                        <input type="text" name="keyword" value="{{ request('keyword') ?: old('keyword') }}" placeholder="{{ __('messages.searchInput') }}"
                            class="form-control">
                        <span class="input-group-btn ">
                            <button type="submit" name="search" value="search" class="btn btn-primary mb0 btn-sm">{{ __('messages.search') }}</button>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</form>