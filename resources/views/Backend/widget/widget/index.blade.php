
@include('Backend.dashboard.component.breadcrumb',['title' =>$config['seo']['title']])


<div class="row mt20">

    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>{{ __('messages.tableWidget_brand') }} </h5>
                @include('Backend.dashboard.component.toolbox', ['content'=>(__('messages.toolboxDestroyWidget'))])
            </div>
            <div class="ibox-content">
                <!-- tìm kiếm widget -->
                @include('Backend.widget.widget.component.filter')
                <!-- bảng widget -->
                @include('Backend.widget.widget.component.table')
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