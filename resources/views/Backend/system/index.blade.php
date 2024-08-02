@include('Backend.dashboard.component.breadcrumb', ['title' =>$config['seo']['index']['title']])
<!-- V58 -->

<form action="{{ route('system.create') }}" method="post" class="box">
    @csrf
    <div class="wrapper wrapper-content animated fadeInRight">
        @foreach($systemConfig as $key => $val)
        <div class="row">
            <div class="col-lg-5">
                <div class="panel-head">
                    <div class="panel-title">{{ $val['label'] }}</div>
                    <div class="panel-description">
                        {{ $val['description'] }}
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Thông tin chung</h5>
                    </div>
                    @if(count($val['value']))
                    <div class="ibox-content">
                        @foreach($val['value'] as $keyVal => $item)
                        @php
                            $name = $key.'_'.$keyVal;
                        @endphp
                        <div class="row mb15">
                            <div class="col-lg-12">
                                <div class="form-row">
                                    <label for="" class="uk-flex uk-flex-space-between">
                                        <span>{{ $item['label'] }}</span>
                                        <span>{!! renderSystemLink($item) !!}</span>
                                         
                                    </label>
                                    @switch($item['type'])
                                        @case('text')
                                            {!! renderSystemInput($name, $systems) !!}
                                            @break
                                        @case('images')
                                            {!! renderSystemImages($name, $systems) !!}
                                            @break
                                        @case('textarea')
                                            {!! renderSystemTextarea($name, $systems) !!}
                                            @break
                                        @case('select')
                                            {!! renderSystemSelect($item, $name, $systems) !!}
                                            @break
                                        @case('editor')
                                            {!! renderSystemEditor($name, $systems) !!}
                                            @break
                                    @endswitch
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>
        <hr>
        @endforeach
        <div class="text-right mb15">
            <button class="btn btn-primary" type="submit" name="send" value="send">{{ $config['seo']['create']['btnTitle'] }}</button>
        </div>
    </div>
</form>
