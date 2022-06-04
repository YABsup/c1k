@extends('layouts.c1k-new')



@section('content')
<!-- <div class="content" style="place-content: center; margin-top: 38px;"> -->
<div class="row" style="place-content: center; margin-top: 38px;">

    <div class="col-auto">
        @include('account/sidebar')
    </div>

    <div class="col Fill" style="width: auto;">
        <div class="col-md-12 change-text-title-person ">
            <h4>@lang('anketa.h4_monitor')</h4>
            <div class="table-info-person-text">@lang('anketa.welcome_monitor')</div>
        </div>
        <!-- <div class="main_articles_wrapper">
        <div class="main_articles_block">
        <div class="main_articles_block_link">
        <div class="main_articles_block_link_img">
        <iframe src="https://www.youtube.com/embed/Td3uCaVgYIE" allowfullscreen="" class="main_articles_block_link_img_icon" alt="">
    </iframe>
</div>
<div class="main_articles_block_link_decription"  style="color: black; margin: 5px; padding: 5px;">
@lang('anketa.desc_monitor')
</div>
</div>
</div>
</div> -->

<h5>@lang('anketa.form_title')</h5>
<?php
$form_fields = array(
    'username',
    'email',
    'telegram',
    'platform_position',
    'platform_name',
    'platform_link',
    'platform_age',
);

?>
<form method="POST" action="{{ route('account.anketa') }}"  class="form">
    {{ csrf_field() }}
    <input type="hidden" name="type" value="monitor">
    @foreach($form_fields as $field)
    <div class="text-person_data">
        <label for="inputFor{{ $field }}">@lang('anketa.form_'.$field)</label>
        <input type="text" class="form-control" name="{{ $field }}" id="input{{ $field }}"  placeholder="@lang('anketa.placeholder_'.$field)"  required>
    </div>
    @endforeach
    <div class="exchange_recaptcha">
        <div class="g-recaptcha" data-sitekey="{{env('GOOGLE_RECAPTCHA_KEY')}}"></div>
    </div></br>
    <button type="submit" class="btn btn-primary active form-control">@lang('anketa.get_code')</button>

</form>
</div>

</div>
<!-- </div> -->

@endsection
