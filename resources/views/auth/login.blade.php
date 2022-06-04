@extends('layouts.c1k-new')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="Fill"  style="background: transparent; width:100%">
        <div class="card-header">{{ __('c1k.main.login') }}</div>

        <div class="card-body">
          <form method="POST" action="{{ route('login') }}">
            {{ csrf_field() }}

            <div class="form-group row">
              <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail') }}</label>

              <div class="col-md-6">
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                @error('email')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
                @enderror
              </div>
            </div>

            <div class="form-group row">
              <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('c1k.main.login_password') }}</label>

              <div class="col-md-6">
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                @error('password')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
                @enderror
              </div>
            </div>
            @if(env('GOOGLE_RECAPTCHA_KEY'))
            <div class="form-group row">
              <div class="col-md-6 offset-md-4">
                <div class="g-recaptcha"
                data-sitekey="{{env('GOOGLE_RECAPTCHA_KEY')}}">
              </div>
              @error('g-recaptcha-response')
              <span class="invalid-feedback" style="display: block" role="alert">
                <strong>{{ $message }}</strong>
              </span>
              @enderror
            </div>
          </div>
          <script src='https://google.com/recaptcha/api.js'></script>
          @endif
          <!--div class="form-group row">
            <div class="col-md-6 offset-md-4">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                <label class="form-check-label" for="remember">
                  {{ __('Remember Me') }}
                </label>
              </div>
            </div>
          </div-->

          <div class="form-group row mb-0">
            <div class="col-md-8 offset-md-4">
              <button type="submit" class="btn btn-primary">
                {{ __('c1k.main.login') }}
              </button>

              @if (Route::has('password.request'))
              <a class="btn btn-link" href="{{ route('password.request') }}">
                {{ __('c1k.main.lost_pass') }}
              </a>
              @endif
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
</div>
@endsection
