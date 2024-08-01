@include('Backend.dashboard.component.breadcrumb', ['title' =>$config['seo']['index']['title']])
<!-- V58 -->

<form action="" method="post" class="box">
    @csrf
    <div class="wrapper wrapper-content animated fadeInRight">
        @foreach($system as $key => $val)
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
                                            {!! renderSystemInput($name) !!}
                                            @break
                                        @case('images')
                                            {!! renderSystemImages($name) !!}
                                            @break
                                        @case('textarea')
                                            {!! renderSystemTextarea($name) !!}
                                            @break
                                        @case('select')
                                            {!! renderSystemSelect($item, $name) !!}
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
