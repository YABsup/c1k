<style>
.account-sidebar{
    width: max-content;
    text-align: left;

}

.sidebar-link {
    height: 45px;
    border-bottom-right-radius: 100px;
    border-top-right-radius: 100px;
    font-family: MuseoSansCyrl;
    font-size: 14px;
    font-weight: 300;
    font-style: normal;
    font-stretch: normal;
    line-height: 3.57;
    letter-spacing: normal;
    color: #ffffff;
}
.sidebar-link.active {
    background-color: #141e46;
}

.blank-88{
    width: 88px;
    display: inline-block;
}

</style>



<div class="account-sidebar">
    <?php
    $menu_items = array();
    $user = \Auth::user();

    $ref_status = 1;
    if( $user != null)
    {
        $menu_items = array(
            array( 'welcome', __('account.home')),
            array( 'account.dashboard', __('account.history')),
            array( 'bepartner', __('account.bepartner')),
        );


        if($user->referer != null )
        {
            if($user->referer->partner == 0 )
            {
                $ref_status = 0;
            }
        }

        if($user->verified == 0)
        {
            $menu_items[] = array( 'account.card_verify', __('account.verify'));
        }

        if($ref_status == 1)
        {
            $menu_items[] = array( 'account.partner', __('account.referal'));
            if( in_array( $user->id, [1,28]) || ( $user->role == 'admin' ) )
            {
                $menu_items[] = array( 'account.withdraw-list', 'Выводы');
            }
        }

        $menu_items[] = array( 'account.profile', __('account.profile'));
        $menu_items[] = array( 'account.change_password', __('account.password_change'));


        if( ( $user->partner > 0 ) || ( $user->role == 'admin' ) )
        {
            $menu_items[] = array( 'account.media', __('account.media'));
        }

        //TODO REF !!!
    }
    ?>

    @foreach($menu_items as $menu_item)
    @if (\Route::current()->getName() == $menu_item[0] )
    <div class="sidebar-link pl-2 pr-3 active"><div class="blank-88 d-block"></div>  <a href="{{ route($menu_item[0]) }}" class="account-sidebar-menu-link">{{ $menu_item[1] }}</a></div>
    @else
    <div class="sidebar-link pl-2 pr-3"><div class="blank-88 d-block"></div>  <a href="{{ route($menu_item[0]) }}" class="account-sidebar-menu-link">{{ $menu_item[1] }}</a></div>
    @endif
    @endforeach


    {{--<!--a href="{{ route('account.faq') }}" class="account-sidebar-menu-link">@lang('account.sidebar.faq')</a-->--}}

@if( $user != null)

    <div class="sidebar-link pl-2 pr-3"><div class="blank-88 d-block"></div>  <a class="account-sidebar-menu-link exit-link" href="{{ route('logout') }}" onclick="event.preventDefault();
    document.getElementById('logout-form').submit();">@lang('account.logoff')</a></div>
@endif

</div>
