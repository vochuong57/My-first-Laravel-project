<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-8">
        <h2>{{ config('apps.user.title') }}</h2>
        <ol class="breadcrumb" style="margin-bottom:10px;">
            <a href="{{ route('dashboard.index') }}">Dashboard</a>
            <li class="active"><strong>{{ config('apps.user.title') }}</strong></li>
        </ol>
    </div>
</div>


<div class="row mt20">

    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>{{ config('apps.user.tableHeading') }} </h5>
                @include('Backend.user.component.toolbox')
            </div>
            <div class="ibox-content">
                <!-- tìm kiếm user -->
                @include('Backend.user.component.filter')
                <!-- bảng user -->
                @include('Backend.user.component.table')
            </div>
        </div>
    </div>
</div>
<!-- Để chạy <input type="checkbox" class="js-switch" checked /> cần thêm vào file js và css riêng của nó cùng với đoạn mã script này -->

<!-- <script>
    $(document).ready(function () {
        var elem = document.querySelector('.js-switch');
        var switchery = new Switchery(elem, { color: '#1AB394' });
    })
</script> -->