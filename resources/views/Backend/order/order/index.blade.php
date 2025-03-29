
@include('Backend.dashboard.component.breadcrumb',['title' =>$config['seo']['index']['title']])


<div class="row mt20">

    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>{{ __('messages.tableOrder_brand') }} </h5>
            </div>
            <div class="ibox-content">
                @include('Backend.order.order.component.filter')
                @include('Backend.order.order.component.table')
            </div>
        </div>
    </div>

    
</div>
