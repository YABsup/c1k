<div class="popular_link_telegram">
  <div class="header__warning-text">@lang('c1k.header_warning_test')</div>
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-8 col-xl-6 offset-xl-3 offset-lg-0">
        <div class="header__center-text text-center">
          <span class="header__text">@lang('c1k.quickly_subscribe')</span>
          <a href="https://t.me/C1k_world" class="header__text header__text--blue">
            <svg id="" style="enable-background:new 0 0 100 100;" class="popular_link_telegram_icon" height="88px" fill="#1da1f2" width="40px"  viewBox="0 0 100 100"><path d="M95,9.9c-1.3-1.1-3.4-1.2-7-0.1c0,0,0,0,0,0c-2.5,0.8-24.7,9.2-44.3,17.3c-17.6,7.3-31.9,13.7-33.6,14.5  c-1.9,0.6-6,2.4-6.2,5.2c-0.1,1.8,1.4,3.4,4.3,4.7c3.1,1.6,16.8,6.2,19.7,7.1c1,3.4,6.9,23.3,7.2,24.5c0.4,1.8,1.6,2.8,2.2,3.2  c0.1,0.1,0.3,0.3,0.5,0.4c0.3,0.2,0.7,0.3,1.2,0.3c0.7,0,1.5-0.3,2.2-0.8c3.7-3,10.1-9.7,11.9-11.6c7.9,6.2,16.5,13.1,17.3,13.9  c0,0,0.1,0.1,0.1,0.1c1.9,1.6,3.9,2.5,5.7,2.5c0.6,0,1.2-0.1,1.8-0.3c2.1-0.7,3.6-2.7,4.1-5.4c0-0.1,0.1-0.5,0.3-1.2  c3.4-14.8,6.1-27.8,8.3-38.7c2.1-10.7,3.8-21.2,4.8-26.8c0.2-1.4,0.4-2.5,0.5-3.2C96.3,13.5,96.5,11.2,95,9.9z M30,58.3l47.7-31.6  c0.1-0.1,0.3-0.2,0.4-0.3c0,0,0,0,0,0c0.1,0,0.1-0.1,0.2-0.1c0.1,0,0.1,0,0.2-0.1c-0.1,0.1-0.2,0.4-0.4,0.6L66,38.1  c-8.4,7.7-19.4,17.8-26.7,24.4c0,0,0,0,0,0.1c0,0-0.1,0.1-0.1,0.1c0,0,0,0.1-0.1,0.1c0,0.1,0,0.1-0.1,0.2c0,0,0,0.1,0,0.1  c0,0,0,0,0,0.1c-0.5,5.6-1.4,15.2-1.8,19.5c0,0,0,0,0-0.1C36.8,81.4,31.2,62.3,30,58.3z"/></svg>
            @lang('c1k.news.channel')
          </a>
          <span class="header__text">@lang('c1k.quickly')</span>
        </div>
      </div>
      <div class="col-md-4 col-xl-3">
        <div class="language-and-auth">
          <!--div class="language-control">
            <select title="Выберите язык" name="language" class="language-control__select">
              <option value="ru">RUS</option>
              <option value="tr">TR</option>
              <option value="en">EN</option>
              <option value="ua">UA</option>
            </select>
          </div-->


<?php
  $loc = \Session::get('locale','ru');
?>

<form id="lang-form" action="{{ route('changelocale') }}" method="POST" >
<div class="language-control">
            <select id="locale" title="@lang('c1k.select_lang')" name="language" class="language-control__select" onclick="event.preventDefault();
            document.getElementById('lang-form').submit();">
              <option value="ru" @if($loc=='ru') selected @endif>RUS</option>
              <option value="en" @if($loc=='en') selected @endif>EN</option>
            </select>
            </div>

@csrf
</form>






            <div class="auth-control">
              @guest
              <a class="auth-control__link" href="{{ route('login') }}">{{ __('c1k.main.login') }}</a>

              @if (Route::has('register'))
              <a class="auth-control__link" href="{{ route('register') }}">{{ __('c1k.main.register') }}</a>
              @endif


              @else
              <a class="auth-control__link" href="{{ route('account.dashboard') }}">{{ __('c1k.account') }}</a>
              <a class="auth-control__link" href="{{ route('logout') }}" onclick="event.preventDefault();
              document.getElementById('logout-form').submit();">{{ __('c1k.main.logout') }}</a>
              <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
              </form>
              @endguest
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
