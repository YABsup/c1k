@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="Fill"  style="background: transparent; width:100%">
        <div class="card-header">2FA - code</div>

        <div class="card-body">
          <form method="POST" action="/admin/to_factor">
            {{ csrf_field() }}

            <div class="form-group row">
              <label for="to_factor" class="col-md-4 col-form-label text-md-right">2fa</label>

              <div class="col-md-6">
                <input id="to_factor" type="text" class="form-control @error('to_factor') is-invalid @enderror" name="to_factor" value="{{ old('to_factor') }}" required autofocus>

                @error('to_factor')
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

          <div class="form-group row mb-0">
            <div class="col-md-8 offset-md-4">
              <button type="submit" class="btn btn-primary">
                Send
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
</div>
@endsection
