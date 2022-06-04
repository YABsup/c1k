@extends('layouts.c1k-new')

@section('title')
@lang('static.oferta.title')
@endsection

@section('head-add')
<link rel="stylesheet" href="{{ asset('css/faq.css') }}"/>
@endsection

@section('content')

<div class="main">
    <div class="container">
        <div class="row">
            @include('account/sidebar')

            <div class="col">
                <div class="Fill" style="width:100%;">
                    @lang('oferta.text')
                </div>
            </div>
        </div>
    </div>
</div>

<script src="/js/c1k/common_pattern.js"></script>

@endsection
