<div id="top-menu" class="container-fluid content ">

    <div class="row top-menu">
        <div class="col-auto top-menu-logo" id="top-menu-logo">
            <a href="/" title="{{ __('menu_header.home') }}"><img src="/img/logotype-c-1-k.svg" class="Logotype_c1k"></a>
        </div>
        <input type="checkbox" id="top-menu-mobile">
        <label for="top-menu-mobile" class="top-menu-mobile_btn">
            <span></span>
        </label>
        <div class="col-auto top-menu-links" id="top-menu-links">

            <ul class="nav nav-menu">
                @guest
                <li class="nav-item login">
                    <a href="/login" class="nav-link"><img src="/img/user-logo.png">{{ __('menu_header.login') }}</a>
                </li>

                <li class="nav-item reg">
                    <a href="/register">
                        <button type="button" class="btn btn-danger nav-link" style="background-color: #f33333; -webkit-appearance: none!important;">{{ __('menu_header.register') }}</button>
                    </a>
                </li>
                @else
                <li class="nav-item reg">
                    <a href="/account">
                        <button type="button" class="btn btn-danger nav-link" style="background-color: #f33333; -webkit-appearance: none!important;">{{ __('menu_header.cabinet') }}</button>
                    </a>
                </li>
                <li class="nav-item reg">
                    <a href="{{ route('logout') }}">
                        <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();" class="nav-link">{{ __('menu_header.logoff') }}</a>
                    </a>
                </li>

                @if( \Auth::user()->role != 'user' )
                <li class="nav-item reg">
                    <a href="/admin"><button type="button" class="btn btn-danger nav-link" style="background-color: #f33333; -webkit-appearance: none!important;">{{ __('menu_header.admin') }}</button></a>
                </li>
                @endif
                @endguest
                <li class="nav-item">
                    <a href="/oferta" class="nav-link">{{ __('menu_header.oferta') }}</a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('bepartner') }}" class="nav-link">{{ __('menu_header.bepartner') }}</a>
                </li>
                <!li class="nav-item">
                    <a href="/investing" target="_blank" class="nav-link">{{ __('menu_header.investing') }}</a>
                </li>


                <li class="nav-item">
                    <a href="{{ route('contacts') }}" class="nav-link">{{ __('menu_header.contacts') }}</a>
                </li>
                <?php
                $loc = \Session::get('locale','ru');
                ?>
                <li class="nav-item">
                    <form id="lang-form" action="{{ route('changelocale') }}" method="POST" >
                        <!-- <div class="language-control"> -->
                        <select id="locale" title="@lang('menu_header.select_lang')" name="language" class="top-menu-lang" onChange="event.preventDefault();
                        document.getElementById('lang-form').submit();">
                        <option value="ru" @if($loc=='ru') selected @endif>RUS</option>
                        <option value="en" @if($loc=='en') selected @endif>EN</option>
                        <!-- <option value="uk" @if($loc=='uk') selected @endif>UK</option> -->
                    </select>
                    <!-- </div> -->

                    @csrf
                </form>
            </li>
        </ul>
    </div>

    <div class="col-auto top-menu-login">
        <ul class="nav nav-login">
            @guest
            <li class="nav-item">
                <a href="/login" class="nav-link"><img src="/img/user-logo.png">{{ __('menu_header.login') }}</a>
            </li>

            <li class="nav-item">
                <a href="/register"><button type="button" class="btn btn-danger nav-link" style="background-color: #f33333; -webkit-appearance: none!important;">{{ __('menu_header.register') }}</button></a>
            </li>

            @else
            <li class="nav-item">
                <a href="{{ route('account.dashboard') }}" type="button" class="btn btn-danger nav-link" style="background-color: #f33333; border: 1px solid transparent; -webkit-appearance: none!important;">
                    {{ __('menu_header.cabinet') }}
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();" class="nav-link">{{ __('menu_header.logoff') }}</a>
            </li>
            @if( \Auth::user()->role != 'user' )
            <li class="nav-item">
                <a href="/admin"><button type="button" class="btn btn-danger nav-link" style="background-color: #f33333; -webkit-appearance: none!important;">{{ __('menu_header.admin') }}</button></a>
            </li>
            @endif
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>

            @endguest

        </ul>
    </div>


</div>

</div>
