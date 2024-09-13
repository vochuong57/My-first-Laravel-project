@php
    $title = str_replace('{language}', $languageTranslate->name, $config['seo']['title']).' '.$widgetSession->keyword;
@endphp
@include('Backend.dashboard.component.breadcrumb', ['title' => $title])
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
    $url=route('widget.saveTranslate', [$languageTranslate->id, $widgetSession->id])
@endphp
<form action="{{ $url }}" method="post" class="box">
    @csrf
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-6">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Thông tin widget Dữ liệu đối chiếu</h5>
                    </div>
                    <div class="ibox-content widgetContent">
                        @include('Backend.dashboard.component.content', ['offTitle' => true, 'offContent' => true, 'disabled' => true, 'model' => $widgetSession ?? null])
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Thông tin widget Dịch</h5>
                    </div>
                    <div class="ibox-content widgetContent">
                        @include('Backend.dashboard.component.TranslateContent', ['offTitle' => true, 'offContent' => true, 'model' => $widgetTranslate ?? null])
                    </div>
                </div>
            </div>
        </div>
       
        <div class="text-right mb15">
            <button class="btn btn-primary" type="submit" name="send" value="send">{{ $config['seo']['btnTitleSave'] }}</button>
        </div>
    </div>
</form>
