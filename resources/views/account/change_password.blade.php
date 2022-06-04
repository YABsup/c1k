@extends('layouts.c1k-new')


@section('content')

<div class="content" style="place-content: center; margin-top: 38px;">
  <div class="row">

    <div class="col-auto">
      @include('account/sidebar')
    </div>

    <div class="col-md-8 Fill">
      <div class="change-text-title-person ">
        <h4>@lang('account.password_change')</h4>
      </div>

      <form id="change-password-id" method="POST" class="form">
        {{ csrf_field() }}
        <div class="form-group">
          <!-- <div class="col-md-2 col-sm-4 col-xs-6"> -->
          <label class="col-form-label" for="inputOldPassword">@lang('account.password_old')</label>
          <!-- </div> -->
          <!-- <div class="col-md-8 col-sm-4 col-xs-6"> -->
          <input type="password" class="form-control" name="oldPass" id="inputOldPassword" placeholder="@lang('account.password_old')">
          <!-- </div> -->
          <!-- <div class="postn_msg"> -->
          <!-- <div id="vl" class="change_password_oldpass"></div> -->
          <!-- </div> -->
        </div>
        <div class="form-group">
          <!-- <div class="col-md-2 col-sm-4 col-xs-6"> -->
          <label class="col-form-label" for="inputNewPassword">@lang('account.password_new')</label>
          <!-- </div> -->
          <!-- <div class="col-md-8 col-sm-4 col-xs-6"> -->
          <input type="password" class="form-control" name="newPass" id="inputNewPassword" placeholder="@lang('account.password_new')">
          <!-- </div> -->
          <!-- <div class="postn_msg"> -->
          <!-- <div id="vn" class="change_password_newpass">@lang('account.password.info.short')</div> -->
          <!-- </div> -->
        </div>
        <div class="form-group">
          <!-- <div class="col-md-2 col-sm-4 col-xs-6"> -->
          <label class="col-form-label" for="inputConfirmNewPassword">@lang('account.password_confirm')</label>
          <!-- </div> -->
          <!-- <div class="col-md-8 col-sm-4 col-xs-6"> -->
          <input type="password" class="form-control" name="newConfirmPass" id="inputConfirmNewPassword" placeholder="@lang('account.password_confirm')">
          <!-- </div> -->
          <!-- <div class="postn_msg"> -->
          <!-- <div id="vn" class="change_password_newpassconfirm">@lang('account.password.info.notmatch')</div> -->
          <!-- </div> -->
          <!-- <div class="postn_msg"> -->
          <!-- <div id="vn" class="change_update_newpassword">@lang('account.password.info.changed')</div> -->
          <!-- </div> -->
        </div>
        <div class="form-group">
          <button type="submit" class="btn btn-primary">
            @lang('account.save')
          </button>
        </div>
      </form>
    </div>

  </div>
</div>


@endsection
