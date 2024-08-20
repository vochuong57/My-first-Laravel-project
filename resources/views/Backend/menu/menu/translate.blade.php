@php
    $title = str_replace('{language}', $languageTranslate->name, $config['seo']['title']).' '.$menuCatalogue->name;
@endphp
@include('Backend.dashboard.component.breadcrumb', ['title' => $title])

<form action="{{ route('menu.saveTranslate', ['languageId' => $languageTranslate->id]) }}" method="post">
    @csrf
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-4">
                <div class="panel-title">Thông tin chung</div>
                <div class="panel-description">
                    <p>- Hệ thống tự động lấy ra bản dịch của các Menu đó <span class="success">nếu có</span></p>
                    <p>- Cập nhật các thông tin về bản dịch cho các Menu của bạn phía bên phải
                        <span class="success">đến vị trí mong muốn</span>
                    </p>
                    <p>- Lưu ý cập nhật đầy đủ thông tin 
                        <span class="success">Quản lý menu con</span>
                    </p>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="ibox">
                    <div class="ibox-title">
                        <div class="uk-flex uk-flex-middle uk-flex-space-between">
                            <h5 style="margin: 0">Danh sách bản dịch</h5>
                        </div>
                    </div>
                    <div class="ibox-content">
                        @if(count($menus))
                        @foreach($menus as $menu)
                        <div class="menu-translate-item">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="text-danger text-bold">Menu: {{ $menu->position }}</div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-row">
                                        <div class="uk-flex uk-flex-middle">
                                            <div class="menu-name">Tên menu:</div>
                                            <input type="text" value="{{ $menu->languages->first()->getOriginal('pivot_name') }}" class="form-control" placeholder="" autocomplete="off" disabled>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="uk-flex uk-flex-middle">
                                            <div class="menu-name">Đường dẫn:</div>
                                            <input type="text" value="{{ $menu->languages->first()->getOriginal('pivot_canonical') }}" class="form-control" placeholder="" autocomplete="off" disabled>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-row">
                                        <input type="text" name="translate[name][]" value="{{ ($menu->translate_name) ?? '' }}" class="form-control" placeholder="Nhập vào bản dịch của bạn..." autocomplete="off">
                                    </div>
                                    <div class="form-row">
                                        @php
                                            // V74
                                            $isReadonly = '';

                                            foreach($listCanonicalInRouter as $valRouter){
                                                if($menu->languages->first()->getOriginal('pivot_canonical') == $valRouter){
                                                    $isReadonly = 'readonly';
                                                    break;
                                                }
                                            }
                                        @endphp
                                        <input type="text" name="translate[canonical][]" value="{{ ($menu->translate_canonical) ?? '' }}" class="form-control" placeholder="{{ ($isReadonly) != '' ? 'Đường dẫn ở ngôn ngữ cần dịch này chưa được tạo!' : 'Nhập vào bản dịch của bạn...' }}" autocomplete="off" {{ ($menu->translate_canonical) ? 'readonly' : '' }} {{ $isReadonly }}>
                                        <input type="hidden" name="translate[id][]" value="{{ ($menu->id) ?? '' }}" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr />
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
