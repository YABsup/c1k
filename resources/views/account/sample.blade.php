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
                @include('account/sidebar')

<div class="col">
    <div class="Fill" style="width:100%; text-align:center;">
        <h4>{{$h4}}</h4>
        <div class="table-info-person-text">{{$text}}</div>
    </div>
</div>

            </div>
        </div>
    </div>

@endsection
