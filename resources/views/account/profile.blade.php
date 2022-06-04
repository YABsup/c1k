@extends('layouts.c1k-new')



@section('content')

<!-- <div class="content" style="place-content: center; margin-top: 38px;"> -->
<div class="row" style="place-content: center; margin-top: 38px;">

  <div class="col-auto">
    @include('account/sidebar')
  </div>

  <div class="col Fill">
    <div class="col-md-12 change-text-title-person ">
      <h4>@lang('account.personal_info')</h4>
    </div>
    <form id="profile-save-id" method="POST" style="margin-top: 38px;" class="form">
      {{ csrf_field() }}
      <div class="text-person_data">
        <label class="col-form-label" for="inputFirstName">@lang('account.first_name')</label>
        <input type="text" class="form-control" name="username" id="inputFirstName" value="{{ $user->name }}">
      </div>
      <div class="text-person_data">
        <label class="col-form-label" for="inputPhoneName">@lang('account.phone')</label>
        <input type="text" class="form-control" name="phone" id="inputPhoneName" value="{{ $user->phone }}" placeholder="@lang('account.phone')">
      </div>
      <div class="text-person_data">
        <label class="col-form-label" for="inputViberName">Viber</label>
        <input type="text" class="form-control" name="viber" id="inputViberName" value="{{ $user->viber }}" placeholder="Viber">
      </div>
      <div class="text-person_data">
        <label class="col-form-label" for="inputTelegramName">Telegram</label>
        <input type="text" class="form-control" name="telegram" id="inputTelegramName" value="{{ $user->telegram }}" placeholder="Telegram">
      </div>
      <div class="text-person_data">
        <label class="col-form-label" for="inputWhatsAppName">WhatsApp</label>
        <input type="text" class="form-control" name="whatsapp" id="inputWhatsAppName" value="{{ $user->whatsapp }}" placeholder="Whatsapp">
      </div>
      <div class="text-person_data">
        <label class="col-form-label" for="inputEmail">Email</label>
        <input type="text" class="form-control" name="email" id="inputEmail" value="{{ $user->email }}" readonly="">
      </div>
      <div class="account_profile_errors"></div>
      <button type="submit" class="btn btn-primary">
        @lang('account.save')
      </button>
    </form>
  </div>

</div>
<!-- </div> -->


@endsection
