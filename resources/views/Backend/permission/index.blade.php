
@include('Backend.dashboard.component.breadcrumb',['title' =>$config['seo']['index']['title']])


<div class="row mt20">

    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>{{ __('messages.tablePermission_brand') }} </h5>
                @include('Backend.dashboard.component.toolbox', ['content'=>(__('messages.toolboxDestroyPermission'))])
            </div>
            <div class="ibox-content">
                <!-- tìm kiếm user -->
                @include('Backend.permission.component.filter')
                <!-- bảng user -->
                @include('Backend.permission.component.table')
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