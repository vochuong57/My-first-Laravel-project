@include('Backend.dashboard.component.breadcrumb', ['title' =>$config['seo']['children']. $menu->languages->first()->getOriginal('pivot_name')])
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @php
                // Gộp các thông báo lỗi trùng lặp và đếm số lần xảy ra
                $errorMessages = [];
                foreach ($errors->all() as $error) {
                    $errorMessages[$error] = isset($errorMessages[$error]) ? $errorMessages[$error] + 1 : 1;
                }
            @endphp

            @foreach ($errorMessages as $message => $count)
                <li>{{ str_replace('{number}', $count, $message) }}</li>
            @endforeach
        </ul>
    </div>
@endif


@php
    $url=($config['method']=='create')?route('menu.create'):(($config['method'] == 'children') ? route('menu.saveChildren', [$menu->id]) : route('menu.update', $menu->id))
@endphp
<form action="{{ $url }}" method="post" class="box menuContainer">
    @csrf
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-5">
                <div class="ibox">
                    <div class="ibox-content">
                        @include('backend.menu.menu.component.list')
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="ibox">
                    <!-- <div class="ibox-title">
                        <h5>Thông tin chung</h5>
                    </div> -->
                    <div class="ibox-content">
                        @include('backend.menu.menu.component.action')
                    </div>
                </div>
            </div>
        </div>
  
        <div class="text-right mb15">
            <button class="btn btn-primary" type="submit" name="send" value="send">{{ $config['seo']['btnTitle'] }}</button>
        </div>
    </div>
</form>

<!-- Modal -->
@include('backend.menu.menu.component.popup')