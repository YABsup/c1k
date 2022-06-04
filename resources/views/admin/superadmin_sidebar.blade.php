<aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- search form (Optional) -->
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search...">
                <span class="input-group-btn">
                    <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                    </button>
                </span>
            </div>
        </form>
        <!-- /.search form -->

        <!-- Sidebar Menu -->
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">Dashboard</li>
            <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-users"></i> <span>Главная</span></a></li>
            <li><a href="{{ route('admin.orders') }}"><i class="fa fa-users"></i> <span>Заявки на обмен</span></a></li>
            <li><a href="{{ route('admin.anketas.index') }}"><i class="fa fa-users"></i> <span>Заявки на партнерство</span></a></li>

            @if( request()->user()->email != 'inkovalexey@gmail.com' )
                <li><a href="{{ route('admin.withdraw.index') }}"><i class="fa fa-users"></i> <span>Заявки на вывод</span></a></li>
            @endif

            <li><a href="/admin/users_statistics"><i class="fa fa-users"></i> <span>Пользователи (маркетинг)</span></a></li>

            <li class="treeview {{ in_array(url()->current(),[route('admin.account_verifications'),route('admin.card_verifications')]) ?'menu-open':'' }}">
              <a href="#">
                <i class="fa fa-dashboard"></i> <span>Верификации</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu" style="display: {{ in_array(url()->current(),[route('admin.account_verifications'),route('admin.card_verifications')]) ?'block':'none' }}">
                <li><a href="{{ route('admin.account_verifications') }}"><i class="fa fa-users"></i> <span>Аккаунты</span></a></li>
                <li><a href="{{ route('admin.card_verifications') }}"><i class="fa fa-users"></i> <span>Карты</span></a></li>
              </ul>
            </li>

            <li class="treeview menu-open">
                <a href="#">
                    <i class="fa fa-dashboard"></i> <span>Настройки системы</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu" style="display: block">
                    <li><a href="{{ route('admin.users.index') }}"><i class="fa fa-users"></i> <span>Пользователи</span></a></li>
                    <li><a href="{{ route('admin.city.index') }}"><i class="fa fa-users"></i> <span>Города</span></a></li>
                    <li><a href="{{ route('admin.coin.index') }}"><i class="fa fa-users"></i> <span>Валюты</span></a></li>
                    <li><a href="{{ route('admin.pairs.index') }}"><i class="fa fa-users"></i> <span>Валютные пары</span></a></li>
                    <li><a href="{{ route('admin.reserv.index') }}"><i class="fa fa-users"></i> <span>Резервы валют</span></a></li>
                </ul>
            </li>

            <?php

            $menu_exchanges = \DB::select("
            SELECT DISTINCT `city_id`, `cities`.`name`
            FROM `pairs`,`cities`
            WHERE `cities`.`id`=`pairs`.`city_id`
            LIMIT 50
            ");

            $menu_coins = array(
                'CNY/ALPCNY/WIRECNY'=>'89_145_158',
                'USDT'=>'14_184_186',
                'Криптовалюта'=>'1_2_7_5_6_9',
                'Наличный обмен'=>'178_179_180_181_182_183',
                'Privat24'=>'124_125',
                'Advanced Cash'=>'59_60_61_62',
                'Epayments'=>'81_82',
                'EXMO Codes'=>'97_98_99_100',
                'Monobank'=>'130',
                'Perfect Money'=>'51_52_53',
                'PayPal'=>'55_56_57_58',
                'Skrill'=>'66_67_68',
                'Payeer'=>'63_64_65',
                'Neteller'=>'76_77',
                'Capitalist'=>'73_74',
                'LiveCoin Codes'=>'102_103',
                'VISA,MASTERCARD/USD,EUR'=>'137_138_139_140_141_142_143_144_145_146',
            );

            ?>
            @if( request()->user()->email != 'inkovalexey@gmail.com' )
                @isset($menu_coins)
                    <li class="treeview menu-open">
                        <a href="#">
                            <i class="fa fa-dashboard"></i> <span>Направления обмена</span>

                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu" style="display: block">

                            @foreach($menu_coins as $menu_coins_title=>$menu_coins_link)
                                <li>
                                    <a href="{{ route('admin.pairs.index', array('coin_id'=>$menu_coins_link) ) }}"><i class="fa fa-users"></i> <span>{{ $menu_coins_title }}</span></a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                @endisset


                @isset($menu_exchanges)
                    <li class="treeview menu-open">
                        <a href="#">
                            <i class="fa fa-dashboard"></i> <span>Обменные пункты</span>

                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu" style="display: block">

                            @foreach($menu_exchanges as $menu_exchange)
                                <li>
                                    <a href="{{ route('admin.pairs.index', array('city_id'=>$menu_exchange->city_id) ) }}"><i class="fa fa-users"></i> <span>{{ $menu_exchange->name }}</span></a>
                                </li>
                            @endforeach

                        </ul>
                    </li>
                @endisset
            @endif



            <li class="treeview menu-open">
                <a href="#">
                    <i class="fa fa-dashboard"></i> <span>Экспортные данные</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu" style="display: block">
                    <li><a href="{{ route('exportxmlbest') }}"><i class="fa fa-users"></i> <span>Экспорт BestChange.ru</span></a></li>
                    <li><a href="{{ route('exportxmlbestfile') }}"><i class="fa fa-users"></i> <span>Файл BestChange.ru</span></a></li>
                    <li><a href="{{ route('export_exchangesumo') }}"><i class="fa fa-users"></i> <span>Экспорт ExchangeSumo</span></a></li>
                    <li><a href="{{ route('exportxmlokfile') }}"><i class="fa fa-users"></i> <span>Файл Okchange</span></a></li>
                    <li><a href="{{ route('exportxmlglazok') }}"><i class="fa fa-users"></i> <span>Экспорт Glazok</span></a></li>
                    <li><a href="{{ route('exportxmlglazokfile') }}"><i class="fa fa-users"></i> <span>Файл Glazok</span></a></li>
                </ul>
            </li>


        </ul>
        <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
</aside>
