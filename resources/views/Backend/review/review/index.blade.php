
@include('Backend.dashboard.component.breadcrumb',['title' =>$config['seo']['title']])


<div class="row mt20">

    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>{{ __('messages.tableReview_brand') }} </h5>
                @include('Backend.dashboard.component.toolbox', ['content'=>'đánh giá'])
            </div>
            <div class="ibox-content">
                @include('Backend.review.review.component.filter')
                @include('Backend.review.review.component.table')
            </div>
        </div>
    </div>

    
</div>
