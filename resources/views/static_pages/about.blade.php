@extends('layouts.c1k-new')

@section('title')
@lang('static.about.title')
@endsection

@section('head-add')
    <link rel="stylesheet" href="{{ asset('css/c1k/faq.css') }}"/>
    <link rel="stylesheet" href="{{ asset('css/account.css') }}"/>
    <link rel="stylesheet" href="{{ asset('css/exchange.css') }}"/>
@endsection

@section('content')
<div class="main">
    <div class="faq-content">
        <div class="container">
            <div class="faq-page">
                <h4>@lang('static.about.h4')</h4>
            </div>
@lang('static.about.text')
        </div>
    </div>
</div>

<script src="/js/c1k/common_pattern.js"></script>

@endsection
