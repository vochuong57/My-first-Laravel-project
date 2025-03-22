@extends('Frontend.homepage.layout', ['popup' => false])

@section('content')
<div class="cart-container">
    <div class="uk-container uk-container-center">
        @if ($errors->any())
            <div class="uk-alert uk-alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('cart.create') }}" class="uk-form form" method="post">
            @csrf
            <div class="cart-wrapper">
                <div class="uk-grid uk-grid-medium">
                    <div class="uk-width-large-3-5">
                        <div class="panel-cart cart-left">
                            <div class="panel-head">
                                <div class="uk-flex uk-flex-middle uk-flex-space-between">
                                    <h2 class="cart-heading">
                                        <span>{{ __('payment.order_info') }}</span>
                                    </h2>
                                    <span class="has-account">{{ __('payment.have_account') }} <a href="" title="{{ __('payment.login_now') }}">{{ __('payment.login_now') }}</a></span>
                                </div>
                            </div>
                            <div class="panel-body">
                                @include('Frontend.cart.component.information')
                                @include('Frontend.cart.component.method')
                            </div>
                        </div>
                    </div>
                    <div class="uk-width-large-2-5">
                        <div class="panel-cart">
                            <div class="panel-head">
                                <h2 class="cart-heading">{{ __('payment.cart') }}</h2>
                            </div>
                            <div class="panel-body">
                                @include('Frontend.cart.component.item')
                            </div>
                            @include('Frontend.cart.component.voucher')
                            <div class="panel-foot mt30">
                                @include('Frontend.cart.component.summary')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
