
@include('Backend.dashboard.component.breadcrumb',['title' =>$config['seo']['title']])


<div class="row mt20">

    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>{{ $config['seo']['table'] }} </h5>
                @include('Backend.language.component.toolbox')
            </div>
            <div class="ibox-content">
                <!-- tìm kiếm user -->
                @include('Backend.language.component.filter')
                <!-- bảng user -->
                @include('Backend.language.component.table')
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