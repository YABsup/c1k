@extends('layouts.c1k-new')


@section('content')
<!-- <div class="content" style="place-content: center; margin-top: 38px;"> -->
<div class="row" style="place-content: center; margin-top: 38px;">

  <div class="col-auto">
    @include('account/sidebar')
  </div>

  <div class="col Fill" style="width: auto;">
    <div class="col-md-12 change-text-title-person ">
      <h4>@lang('anketa.h4_manager')</h4>
      <div class="table-info-person-text">@lang('anketa.welcome_manager')</div>
    </div>
    <!-- <div class="main_articles_wrapper">
      <div class="main_articles_block">
        <div class="main_articles_block_link">
          <div class="main_articles_block_link_img">
            <iframe src="https://www.youtube.com/embed/Td3uCaVgYIE" allowfullscreen="" class="main_articles_block_link_img_icon" alt="">
            </iframe>
          </div>
          <div class="main_articles_block_link_decription"  style="color: black; margin: 5px; padding: 5px;">
            @lang('anketa.desc_lider')
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
      'kind_of_activity',
      'auditory_type',
      'auditory_count',
      'youtube_link',
      'insta_link',
      'telegram_link',
      'additional_link',
      'additional_info',
    );

    ?>
    <form method="POST" action="{{ route('account.anketa') }}"  class="form">
      {{ csrf_field() }}
      <input type="hidden" name="type" value="manager">
      @foreach($form_fields as $field)
      <div class="text-person_data">
        <label for="inputFor{{ $field }}">@lang('anketa.form_'.$field)</label>
        <input type="text" class="form-control" name="{{ $field }}" id="input{{ $field }}"  placeholder="@lang('anketa.placeholder_'.$field)"  required>
      </div>
      @endforeach
      <div class="exchange_recaptcha">
        <div class="g-recaptcha" data-sitekey="{{env('GOOGLE_RECAPTCHA_KEY')}}"></div>
      </div>
    </br>
      <button type="submit" class="btn btn-primary active form-control">@lang('anketa.get_code')</button>

    </form>
  </div>

</div>
<!-- </div> -->

@endsection
