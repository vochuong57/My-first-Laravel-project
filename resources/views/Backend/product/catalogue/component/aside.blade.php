<div class="ibox">
    <div class="ibox-title">
        <h5>{{ __('messages.parent') }}</h5>
    </div>
    <div class="ibox-content">
        <div class="row">
            <div class="col-lg-12">
                <div class="form-row">
                    <span class="text-danger notice">*{{ __('messages.parentNotice') }}</span>
                    <select name="parent_id" class="form-control setupSelect2" id="">
                        @foreach($dropdown as $key => $val)
                        <option
                            {{ $key == old('parent_id', (isset($productCatalogue->parent_id)) ? $productCatalogue->parent_id : '') ? 'selected' : '' }} 
                            value="{{ $key }}"
                        >
                            {{ $val }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

        </div>
    </div>
</div>
<div class="ibox">
    <div class="ibox-title">
        <h5>{{ __('messages.image') }}</h5>
    </div>
    <div class="ibox-content">
        <div class="row">
            <div class="col-lg-12">
                <div class="form-row">
                    <span class="image img-cover image-target">
                        <img src="{{ (old('image', $productCatalogue->image ??'Backend/img/not-found.png')) ?? 'Backend/img/not-found.png' }}" alt="">
                    </span>
                    <input type="hidden" name="image" value="{{ old('image', ($productCatalogue->image)??'') }}">
                </div>
            </div>

        </div>
    </div>
</div>
<div class="ibox">
    <div class="ibox-title">
        <h5>{{ __('messages.advance') }}</h5>
    </div>
    <div class="ibox-content">
        <div class="row">
            <div class="col-lg-12">
                <div class="form-row">
                   
                    <div class="mb15">
                        <select name="publish" class="form-control setupSelect2" id="">
                            @foreach(config('apps.general.publish') as $key => $val)
                                <option 
                                    {{ $key == old('publish', (isset($productCatalogue->publish)) ? $productCatalogue->publish : '') ? 'selected' : '' }} 
                                    value="{{ $key }}"
                                >
                                    {{ $val }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    
                    <div class="mb15">
                        <select name="follow" class="form-control setupSelect2" id="">
                            @foreach(config('apps.general.follow') as $key => $val)
                            <option 
                                {{ $key == old('follow', (isset($productCatalogue->follow)) ? $productCatalogue->follow : '') ? 'selected' : '' }} 
                                value="{{ $key }}"
                            >
                                {{ $val }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>