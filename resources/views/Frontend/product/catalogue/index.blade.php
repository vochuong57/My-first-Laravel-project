@extends('Frontend.homepage.layout', ['popup' => true])

@section('content')
    <div class="product-catalogue page-wrapper">
        <div class="uk-container uk-container-center">
           @include('Frontend.component.breadcrumb', ['model' => $productCatalogue, 'breadcrumb' => $breadcrumb])

            <div class="pannel-body" style="margin-top: 10px">
                <div class="filter">
                    @include('frontend.product.catalogue.component.filter')
                    @include('frontend.product.catalogue.component.filterContent')
                </div>
                <div class="product-list">
                    @if(count($products->items()))
                    <div class="uk-grid uk-grid-medium">
                        @foreach($products->items() as $key => $val)
                        <div class="uk-width-1-2 uk-width-small-1-2 uk-width-medium-1-3 uk-width-large-1-5 mb20">
                            @include('Frontend/component/product-item', ['val' => $val])
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="no-data" style="margin-bottom: 20px">Không có sản phẩm nào</div>
                    @endif
                </div>
                <div class="uk-flex uk-flex-center pagination-laravel">
                    @include('frontend.component.pagination', ['model' => $products])
                </div>
           </div>
        </div>
    </div>
@endsection