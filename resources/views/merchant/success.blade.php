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
                @guest

                @else
                @include('account/sidebar')
                @endguest

<div class="account-profile">
    <div class="col-md-12 change-text-title-person ">
        <h4>Success</h4>
        <div class="table-info-person-text">Pay success</div>
    </div>
</div>

            </div>
        </div>
    </div>

@endsection
