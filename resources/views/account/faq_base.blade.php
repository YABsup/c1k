@extends('layouts.c1k-new')


@section('content')
<div class="content" style="place-content: center; margin-top: 38px;">
    <div class="row">

        <div class="col-auto">
            @include('account/sidebar')
        </div>

        <div class="col">
            <div class="content">
                <div class="row">

                @lang('faq_base.text')


                </div>
            </div>
        </div>
    </div>
</div>
@endsection
