@extends('layouts.c1k-new')

@section('head-add')
    <link rel="stylesheet" href="{{ asset('css/forms.css') }}"/>
    <link rel="stylesheet" href="{{ asset('css/account.css') }}"/>
    <link rel="stylesheet" href="{{ asset('css/exchange.css') }}"/>
@endsection

@section('content')
<div class="main">
        <div class="container">
            <div class="account">
                @include('account/sidebar')

<div class="account-profile">
    <div class="col-md-12 change-text-title-person ">
        <h4>@lang('account.faq.h4')</h4>
        <div class="table-info-person-text">@lang('account.faq.text')</div>
    </div>
</div>

            </div>
        </div>
    </div>

@endsection
