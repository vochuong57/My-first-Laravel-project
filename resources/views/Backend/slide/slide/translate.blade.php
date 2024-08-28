@php
    $title = str_replace('{language}', $languageTranslate->name, $config['seo']['title']).' '.$slide->keyword;
@endphp
@include('Backend.dashboard.component.breadcrumb', ['title' => $title])
<div class="language-container" style="margin: 12">
    @foreach($languages as $language)
        @php
            $url = (session('app_locale') == $language->canonical) ? route('slide.edit', ['id' => $slide->id]) 
            : route('slide.translate', ['languageId' => $language->id, 'id' => $slide->id]);
        @endphp
        <div class="uk-flex uk-flex-middle">
        
            <a  class="image img-cover system-flag"
                href="{{ $url }}">
                <img src="{{ $language->image }}" alt="">
            </a>
        </div>
    @endforeach
</div>
@php
    
    // Tính toán giá trị $counter dựa trên số lượng slide hiện có
    $counter = 1;

    if (!empty($listSlides['image'])) {
        $counter += count($listSlides['image']) * 2;
    }

    //echo '<pre>';
    //print_r($listSlides);
@endphp

<form action="{{ route('slide.saveTranslate', ['languageId' => $languageTranslate->id, 'id' => $slide->id]) }}" method="post">
    @csrf
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-4">
                <div class="panel-title">Thông tin chung</div>
                <div class="panel-description">
                    <p>- Hệ thống tự động tạo ra đúng số lượng bản dịch dần dịch đã chọn</p>
                    <p>- Cập nhật các thông tin về bản dịch cho các slide của bạn phía bên phải</p>
                    <p>- Lưu ý cập nhật đầy đủ thông tin và ấn vào nút
                        <span class="success">Lưu lại</span>
                    </p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox">
                    <div class="ibox-title">
                        <div class="uk-flex uk-flex-middle uk-flex-space-between">
                            <h5 style="margin: 0">Danh sách bản dịch</h5>
                        </div>
                    </div>
                    <div class="ibox-content">
                        @if(!is_null($listSlides))
                        @foreach($listSlides['image'] as $key => $val)
                            @php
                                $tab_1 = "tab_" . $counter;
                                $tab_2 = "tab_" . ($counter + 1);
                            @endphp
                        <div class="menu-translate-item">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="slide-item">
                                        <div class="row custom-row">
                                            <div class="col-lg-3">
                                                <span class="slide-image img-cover">
                                                    <img src="{{ $val }}" alt="" disabled>
                                                </span>
                                            </div>
                                            <div class="col-lg-9">
                                                <div class="tabs-container">
                                                    <ul class="nav nav-tabs">
                                                        <li class="active"><a data-toggle="tab" href="#{{ $tab_1 }}"> Thông tin chung</a></li>
                                                        <li><a data-toggle="tab" href="#{{ $tab_2 }}">SEO</a></li>
                                                    </ul>
                                                    <div class="tab-content">
                                                        <div id="{{ $tab_1 }}" class="tab-pane active">
                                                            <div class="panel-body">
                                                                <div class="label-text mb5">Mô tả:</div>
                                                                <div class="form-row mb10">
                                                                    <textarea class="form-control" disabled>{{ $listSlides['description'][$key] }}</textarea>
                                                                </div>
                                                                <div class="form-row form-row-canonical">
                                                                    <input type="text" class="form-control" placeholder="URL" value="{{ $listSlides['canonical'][$key] }}" disabled>
                                                                    <div class="overlay">
                                                                        <div class="uk-flex uk-flex-middle">
                                                                            <label for="input_{{ $tab_1 }}">Mở trong tab mới</label>
                                                                            <input type="checkbox" value="_blank" id="input_{{ $tab_1 }}" {{ (isset($listSlides['window'][$key]) && $listSlides['window'][$key] == '_blank') ? 'checked' : '' }} disabled>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div id="{{ $tab_2 }}" class="tab-pane">
                                                            <div class="panel-body">
                                                                <div class="label-text mb5">Tiêu đề ảnh:</div>
                                                                <div class="form-row form-row-canonical slide-seo-tab">
                                                                    <input type="text" class="form-control" placeholder="Tiêu đề ảnh" value="{{ $listSlides['name'][$key] }}" disabled>
                                                                </div>
                                                                <div class="label-text mt12">Mô tả ảnh:</div>
                                                                <div class="form-row form-row-canonical slide-seo-tab">
                                                                    <input type="text" class="form-control" placeholder="Mô tả ảnh" value="{{ $listSlides['alt'][$key] }}" disabled>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr />
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="slide-item">
                                        <div class="row custom-row">
                                            <div class="col-lg-3">
                                                <span class="slide-image img-cover">
                                                    <img src="{{ $val }}" alt="" disabled>
                                                    <input type="hidden" name="translate[image][]" value="{{ $val }}">
                                                </span>
                                            </div>
                                            <div class="col-lg-9">
                                                <div class="tabs-container">
                                                    <ul class="nav nav-tabs">
                                                        <li class="active"><a data-toggle="tab" href="#{{ $tab_1 }}-"> Thông tin chung</a></li>
                                                        <li><a data-toggle="tab" href="#{{ $tab_2 }}-">SEO</a></li>
                                                    </ul>
                                                    <div class="tab-content">
                                                        <div id="{{ $tab_1 }}-" class="tab-pane active">
                                                            <div class="panel-body">
                                                                <div class="label-text mb5">Mô tả:</div>
                                                                <div class="form-row mb10">
                                                                    <textarea name="translate[description][]" class="form-control">{{ $listSlidesTranslate['description'][$key] ?? '' }}</textarea>
                                                                </div>
                                                                <div class="form-row form-row-canonical">
                                                                    <input type="text" name="translate[canonical][]" class="form-control" placeholder="URL" value="{{ $listSlidesTranslate['canonical'][$key] ?? '' }}">
                                                                    <div class="overlay">
                                                                        <div class="uk-flex uk-flex-middle">
                                                                            <label for="input_{{ $tab_1 }}-">Mở trong tab mới</label>
                                                                            <input type="checkbox" name="translate[window][]" value="_blank" id="input_{{ $tab_1 }}-" {{ (isset($listSlidesTranslate['window'][$key]) && $listSlidesTranslate['window'][$key] == '_blank') ? 'checked' : '' }}>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div id="{{ $tab_2 }}-" class="tab-pane">
                                                            <div class="panel-body">
                                                                <div class="label-text mb5">Tiêu đề ảnh:</div>
                                                                <div class="form-row form-row-canonical slide-seo-tab">
                                                                    <input type="text" name="translate[name][]" class="form-control" placeholder="Tiêu đề ảnh" value="{{ $listSlidesTranslate['name'][$key] ?? '' }}">
                                                                </div>
                                                                <div class="label-text mt12">Mô tả ảnh:</div>
                                                                <div class="form-row form-row-canonical slide-seo-tab">
                                                                    <input type="text" name="translate[alt][]" class="form-control" placeholder="Mô tả ảnh" value="{{ $listSlidesTranslate['alt'][$key] ?? '' }}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr />
                                    </div>  
                                </div>
                            </div>
                        </div>
                            @php
                                $counter += 2
                            @endphp
                        @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="text-right mb15">
            <button class="btn btn-primary" type="submit" name="send" value="send">{{ $config['seo']['btnTitleSave'] }}</button>
        </div>
    </div>
</form>
