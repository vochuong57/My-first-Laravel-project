<form action="{{ route('widget.index') }}">
<div class="filter-wrapper">
    <div class="uk-flex uk-flex-middle uk-flex-space-between">
        <div class="perpage">
            <div class="uk-flex uk-flex-middle uk-flex-space-between">
                <select name="perpage" class="form-control input-sm perpage filter mr10" @php $perpage=request('perpage') ?: old('perpage') @endphp>
                    @for($i=20;$i<=200;$i+=20) 
                        <option {{ ($perpage == $i) ? 'selected' : '' }} value="{{ $i }}">{{ $i }} {{ __('messages.perpage') }}</option>
                    @endfor
                </select>

            </div>
        </div>
        <div class="action">
            <div class="uk-flex uk-flex-middle">
                @php
                    $publish = request('publish') ?: old('publish');
                @endphp
                <select name="publish" class="form-control ml10 setupSelect2">
                    @foreach(__('messages.publish') as $key => $val)
                        <option {{ ($publish == $key) ? 'selected' : '' }} value="{{ $key }}">{{ $val }}</option>
                    @endforeach
                </select>
              
                <div class="uk-search uk-flex uk-flex-middle">
                    <div class="input-group">
                        <input type="text" name="keyword" value="{{ request('keyword') ?: old('keyword') }}" placeholder="{{ __('messages.searchInput') }}"
                            class="form-control">
                        <span class="input-group-btn ">
                            <button type="submit" name="search" value="search" class="btn btn-primary mb0 btn-sm">{{ __('messages.search') }}</button>
                        </span>
                    </div>
                </div>
                <a href="{{ route('widget.store') }}" class="btn btn-danger ml10"><i class="fa fa-plus mr5"></i>{{ __('messages.widget.create.title') }}</a>
            </div>
        </div>
    </div>
</div>
</form>