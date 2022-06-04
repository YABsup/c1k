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
                    <div style="
                    text-align: center;
                    "><h3>@lang('about_partners.contacts')</h3>
                    <p>
                        @lang('about_partners.ahtung')
                    </p>
                </div>
                <div class="wrapper-qr">
                    <div class="wrapp-qr rounded">
                        <div class="qrcode">
                            <div class="img rounded">
                                <img src="/img/qrcodes/ex_c1k.gif" class="telegram rounded">
                            </div>
                            <div class="title">
                                @lang('about_partners.central')
                            </div>
                            <div class="link">
                                <a href="https://t.me/ex_c1k"><span style="color:red">Перейти</span></a>
                            </div>
                        </div>

                        <div class="qrcode">
                            <div class="img rounded">
                                <img src="/img/qrcodes/noncash_c1k.gif" class="telegram rounded">
                            </div>
                            <div class="title">
                                @lang('about_partners.cashless')
                            </div>
                            <div class="link">
                                <a href="https://t.me/noncash_c1k"><span style="color:red">Перейти</span></a>
                            </div>
                        </div>

                        <div class="qrcode">
                            <div class="img rounded">
                                <img src="/img/qrcodes/whatsapp.gif" class="telegram rounded">
                            </div>

                            <div class="title">
                                @lang('about_partners.whatsapp')
                            </div>
                            <div class="link">
                                <a href="https://wa.me/380987585242">+38 (098) 758-52-42<a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
</div>


@endsection
