@extends('layouts.c1k-new')

@section('title')
@lang('static.partners.title')
@endsection

@section('head-add')
    <link rel="stylesheet" href="{{ asset('css/faq.css') }}"/>
@endsection

@section('content')
<div class="main">
    <div class="faq-content">
        <div class="container">
            <div class="faq-page">
                <h4>@lang('static.partners.h4')</h4>
            </div>
            @lang('static.partners.text')
        </div>
    </div>
</div>


<script src="/js/c1k/common_pattern.js"></script>

@endsection
