@extends('Frontend.homepage.layout')

@section('content')
    <div class="product-catalogue page-wrapper">
        <div class="uk-container uk-container-center">
           @include('Frontend.component.breadcrumb', ['model' => $productCatalogue, 'breadcrumb' => $breadcrumb])

            <div class="pannel-body" style="margin-top: 10px">
                <div class="filter">
                    <div class="uk-flex uk-flex-middle uk-flex-space-between">
                        <div class="filter-widget">
                            <div class="uk-flex uk-flex-middle">
                                <a href="" class="view-grid active"><i class="fi-rs-grid"></i></a>
                                <a href="" class="view-grid view-list"><i class="fi-rs-list"></i></a>
                                <div class="filter-button ml10 mr20">
                                    <a href="" class="btn-filter uk-flex uk-flex-middle"><i class="fi-rs-filter mr5"></i><span>Bộ lọc</span></a>
                                </div>
                                <div class="perpage uk-flex uk-flex-middle">
                                    <span class="filter-text">Hiển thi:</span>
                                    <select name="perpage" class="nice-select filtering" style="">
                                        @for($i = 15; $i<=90; $i+=15)
                                        <option value="{{ $i }}">{{ $i }} sản phẩm</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="sorting">
                            <select name="sort" class="nice-select filtering">
                                <option value="">Lọc kết quả theo</option>
                                <option value="price:desc">Giá: Từ cao đến thấp</option>
                                <option value="price:asc">Giá: Từ thấp đến cao</option>
                                <option value="title:asc">Tên sản phẩm từ A-Z</option>
                                <option value="title:desc">Tên sản phẩm từ Z-A</option>
                            </select>
                        </div>
                    </div>
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
                <div class="uk-flex uk-flex-center">
                    @include('frontend.component.pagination', ['model' => $products])
                </div>
           </div>
        </div>
    </div>
@endsection