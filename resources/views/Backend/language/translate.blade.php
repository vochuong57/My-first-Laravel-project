@include('Backend.dashboard.component.breadcrumb', ['title' =>$config['seo']['title']])
@include('Backend.dashboard.component.formError')

<form action="{{route('language.storeTranslate')}}" method="post" class="box">
    @csrf
    <input type="hidden" name="option[id]" value="{{ $option['id'] }}">
    <input type="hidden" name="option[languageId]" value="{{ $option['languageId'] }}">
    <input type="hidden" name="option[model]" value="{{ $option['model'] }}">
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-6">
                @include('Backend.dashboard.component.content',['model' => ($object) ?? null, 'disabled' => 1])
                @include('Backend.dashboard.component.seo',['model' => ($object) ?? null, 'disabled' => 1])
            </div>
            <div class="col-lg-6">
                @include('Backend.dashboard.component.translateContent',['model' => ($objectTranslate) ?? null])
                @include('Backend.dashboard.component.translateSeo',['model' => ($objectTranslate) ?? null])
            </div>
        </div>
        
        <div class="text-right mb15 button-fix">
            <button class="btn btn-primary" type="submit" name="send" value="send">{{ $config['seo']['btnTitle'] }}</button>
        </div>
    </div>
</form>

