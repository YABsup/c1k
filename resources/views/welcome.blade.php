@extends('layouts.c1k-new')

@section('content')

    <div class="c1k-overlay" id="overlay">

        <div class="c1k-modal">
            <div class="c1k-modal__logo">
                <img src="/img/logo-for-modal.png" alt="logo">
            </div>
            <div class="c1k-modal__content">
                <p class="c1k-modal__content--text">
                    {{ __('c1k.overlay_1') }}
                </p>
                <p class="c1k-modal__content--text">
                    {{ __('c1k.overlay_2') }}
                </p>

                <button onclick="$('#overlay').hide()" class="c1k-modal__content--action">
                    {{ __('c1k.overlay_3') }}
                </button>
            </div>
        </div>
    </div>

@include("new/calc-block")



@include("new/contacts-block")


@include("/new/news-block")


@include("/new/partners-block")
@endsection


@section('footer-js')
<script src="/js/calc.js"></script>
<script src="/js/calc-get.js"></script>
<script src="/js/calc-give.js"></script>
@endsection


@section('dis')
@include("/new/partners-block")
@endsection
