@extends('layouts.c1k-new')

@section('head-add')
<link rel="stylesheet" href="{{ asset('css/forms.css') }}"/>
<link rel="stylesheet" href="{{ asset('css/account.css') }}"/>
<link rel="stylesheet" href="{{ asset('css/exchange.css') }}"/>
@endsection

@section('content')
<div class="main">
    <div class="container">
        <div class="row">
            @guest

            @else
            @include('account/sidebar')
            @endguest

            @lang('skrill_verify_info.text')

            </div>
        </div>
    </div>

    @endsection
