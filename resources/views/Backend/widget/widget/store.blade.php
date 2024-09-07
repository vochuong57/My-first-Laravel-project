@include('Backend.dashboard.component.breadcrumb', ['title' =>$config['seo']['title']])
<!-- từ khóa tìm kiếm/validation/Displaying the Validation Errors -->
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
@php
    $url=($config['method']=='create')?route('widget.create'):route('widget.update', $widget->id)
@endphp
<form action="{{ $url }}" method="post" class="box">
    @csrf
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-9">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Thông tin widget</h5>
                    </div>
                    <div class="ibox-content widgetContent">
                        @include('Backend.dashboard.component.content', ['offTitle' => true, 'offContent' => true])
                    </div>
                </div>
                @include('Backend.dashboard.component.album')
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Cấu hình nội dung widget</h5>
                    </div>
                    <div class="ibox-content model-list">
                        <div class="labelText">Chọn Module</div>
                        @foreach(__('module.model') as $key => $val)
                        <div class="model-item uk-flex uk-flex-middle">
                            <input type="radio" name="model" id="{{ $key }}" class="input-radio" value="{{ $key }}" {{ (old('model') == $key) ? 'checked' : '' }}>
                            <label for="{{ $key }}">{{ $val }}</label>
                        </div>
                        @endforeach

                        <div class="search-model-box">
                            <i class="fa fa-search"></i>
                            <input type="text" class="form-control search-model">

                            <div class="ajax-search-result">
                                
                            </div>
                        </div>
                        <div>
                        @php
                            $widgets = old('widget', ($widgetItems) ?? null);

                            //echo '<pre>';
                            //print_r($widgets);
                        @endphp
                        </div>
                        <div class="search-model-result">
                            @if(!empty($widgets))
                            @foreach($widgets['image'] as $key => $val)
                                <div class="search-result-item {{ $widgets['canonical'][$key] }}">
                                    <div class="uk-flex uk-flex-middle uk-flex-space-between">
                                        <div class="uk-flex uk-flex-middle">
                                            <span class="image img-cover">
                                                <img src="{{ $val ?? 'Backend/img/not-found.png' }}" alt="">
                                                <input type="hidden" name="widget[image][]" value="{{ $val ?? '' }}"></input>
                                                <input type="hidden" name="widget[id][]" value="{{ $widgets['id'][$key] }}"></input>
                                                <input type="hidden" name="widget[name][]" value="{{ $widgets['name'][$key] }}"></input>
                                                <input type="hidden" name="widget[canonical][]" value="{{ $widgets['canonical'][$key] }}"></input>
                                            </span>
                                            <span class="name">{{ $widgets['name'][$key] }}</span>
                                        </div>
                                        <div class="delete">
                                            <a class="delete-menu img-scaledown" style="width: 15%; height: 30px; margin-left: 6px"><img src="Backend/img/close.png" alt=""></a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Thông tin chung</h5>
                    </div>
                    <div class="ibox-content">
                        @include('Backend.widget.widget.component.aside')
                    </div>
                </div>
                <div class="ibox short-code">
                    <div class="ibox-title">
                        <h5>Short Code</h5>
                    </div>
                    <div class="ibox-content">
                        <textarea name="short_code" class="textarea form-control">{{ old('short_code', $widget->short_code ?? '') }}</textarea>
                    </div>
                </div>
            </div>
        </div>
       
        <div class="text-right mb15">
            <button class="btn btn-primary" type="submit" name="send" value="send">{{ $config['seo']['btnTitle'] }}</button>
        </div>
    </div>
</form>
