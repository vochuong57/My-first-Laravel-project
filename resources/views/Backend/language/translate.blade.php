@include('Backend.dashboard.component.breadcrumb', ['title' =>$config['seo']['title']])
<form action="" method="post" class="box">
    @csrf
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-6">
                @include('Backend.dashboard.component.content')
            </div>
            <div class="col-lg-6">
                @include('Backend.dashboard.component.translate',['model' => ($objectTranslate) ?? null])
            </div>
        </div>
        
        <div class="text-right mb15 button-fix">
            <button class="btn btn-primary" type="submit" name="send" value="send">{{ $config['seo']['btnDelete'] }}</button>
        </div>
    </div>
</form>

