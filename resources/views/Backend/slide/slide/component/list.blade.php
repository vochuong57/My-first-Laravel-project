<div>
@php
    $slides = old('slide', ($slideItems) ?? null);


    // Tính toán giá trị $counter dựa trên số lượng slide hiện có
    $counter = 1;
    if (!empty($slides['image'])) {
        $counter += count($slides['image']) * 2;
    }

    //echo '<pre>';
    //print_r($slides);
@endphp
</div>
<div id="sortable" class="row slide-list sortui ui-sortable">
    <div class="text-danger slide-notification {{ (!empty($slides)) ? 'none' : '' }}">Chưa có hình ảnh nào được chọn....</div>
   
    @if(!empty($slides))
    @foreach($slides['image'] as $key => $val)
        @php
            $tab_1 = "tab_" . $counter;
            $tab_2 = "tab_" . ($counter + 1);
        @endphp
    <div class="col-lg-12 ui-state-default">
        <div class="slide-item">
            <div class="row custom-row">
                <div class="col-lg-3">
                    <span class="slide-image img-cover">
                        <img src="{{ $val }}" alt="">
                        <input type="hidden" name="slide[image][]" value="{{ $val }}">
                        <span class="delete-slide"><i class="fa fa-trash btn btn-danger"></i></span>
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
                                        <textarea name="slide[description][]" class="form-control">{{ $slides['description'][$key] }}</textarea>
                                    </div>
                                    <div class="form-row form-row-canonical">
                                        <input type="text" name="slide[canonical][]" class="form-control" placeholder="URL" value="{{ $slides['canonical'][$key] }}">
                                        <div class="overlay">
                                            <div class="uk-flex uk-flex-middle">
                                                <label for="input_{{ $tab_1 }}">Mở trong tab mới</label>
                                                <input type="checkbox" name="slide[window][]" value="_blank" id="input_{{ $tab_1 }}" {{ (isset($slides['window'][$key]) && $slides['window'][$key] == '_blank') ? 'checked' : '' }}>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="{{ $tab_2 }}" class="tab-pane">
                                <div class="panel-body">
                                    <div class="label-text mb5">Tiêu đề ảnh:</div>
                                    <div class="form-row form-row-canonical slide-seo-tab">
                                        <input type="text" name="slide[name][]" class="form-control" placeholder="Tiêu đề ảnh" value="{{ $slides['name'][$key] }}">
                                    </div>
                                    <div class="label-text mt12">Mô tả ảnh:</div>
                                    <div class="form-row form-row-canonical slide-seo-tab">
                                        <input type="text" name="slide[alt][]" class="form-control" placeholder="Mô tả ảnh" value="{{ $slides['alt'][$key] }}">
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
        @php
            $counter += 2
        @endphp
    @endforeach
    @else
    @endif
</div>
<script>
    // Khởi tạo giá trị counter từ PHP
    var counter = @json($counter);
</script>