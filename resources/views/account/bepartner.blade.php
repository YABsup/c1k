@extends('layouts.c1k-new')


@section('content')
<div class="content" style="place-content: center; margin-top: 38px;">
    <div class="row">

        <div class="col-auto">
            @include('account/sidebar')
        </div>

        <div class="col Fill">
            <div class="content">
                <div class="row">

                    <div class="account-profile" style="margin-bottom: 15px;">
                        <div class="col-md-12 change-text-title-person ">
                            <h4>@lang('partner.h4')</h4>
                            <div class="table-info-person-text">
                                @lang('partner.welcome')
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" style="place-content: center;">


                    @if( $ref_status == 0)

                    <div class="main_articles_block col Fill-bepartner">
                        <!-- <div class="main_articles_block_link_img">
                            <iframe src="https://www.youtube.com/embed/Td3uCaVgYIE" allowfullscreen="" class="main_articles_block_link_img_icon" alt="">
                            </iframe>
                        </div> -->
                        <div class="main_articles_block_link_decription" >
                            <div class="partner-program-title"><h5>@lang('partner.null_programm_title')</h5></div>
                            @lang('partner.null_programm_desc')
                        </div>
                        @guest
                        <a href="{{ route('register') }}"  style="margin: 10px 15%;"  class="btn btn-primary active" role="button">@lang('account.register')</a>
                        @else
                        @if($user->partner == 0)
                        <button type="button" style="margin: 10px 15%;" class="btn btn-success active">@lang('account.active')</button>
                        @endif
                        @endguest

                    </div>
                    @else

                    <div class="main_articles_block col Fill-bepartner">
                        <!-- <div class="main_articles_block_link_img">
                            <iframe src="https://www.youtube.com/embed/Td3uCaVgYIE" allowfullscreen="" class="main_articles_block_link_img_icon" alt="">
                            </iframe>
                        </div> -->
                        <div class="main_articles_block_link_decription" >
                            <div class="partner-program-title"><h5>@lang('partner.first_programm_title')</h5></div>
                            @lang('partner.first_programm_desc')
                        </div>
                        @guest
                        <a href="{{ route('register') }}" class="btn btn-primary active  w-100 mt-3" role="button">@lang('account.register')</a>
                        <a href="{{ route('faq_base') }}" class="btn btn-danger btn-xs active w-75 mt-1 float-right" role="button" style="font-size: 0.75rem;">@lang('account.details')</a>
                        @else
                        @if($user->partner == 0)
                        <button type="button" class="btn btn-success active w-100 mt-3">@lang('account.active')</button>
                        <a href="{{ route('faq_base') }}" class="btn btn-danger btn-xs active w-75 mt-1 float-right" role="button" style="font-size: 0.75rem;">@lang('account.details')</a>
                        @endif
                        @endguest
                        <!--br><a href="/faq_base" style="" class="btn btn-primary active btn-sm col-sm-6" role="button">Подробнее</a-->
                    </div>
                    @endif

                    <div class="main_articles_block  col Fill-bepartner">
                        <div class="main_articles_block_link">
                            <!-- <div class="main_articles_block_link_img">
                                <iframe src="https://www.youtube.com/embed/Td3uCaVgYIE" allowfullscreen="" class="main_articles_block_link_img_icon" alt="">
                                </iframe>
                            </div> -->
                            <div class="main_articles_block_link_decription"  >
                                <div class="partner-program-title"><h5>@lang('partner.second_programm_title')</h5></div>
                                @lang('partner.second_programm_desc')
                                <!--button type="button" style="margin: 10px 15%;" class="btn btn-success active btn-sm  col-sm-8">@lang('partner.current_programm_active')</button-->
                            </div>
                            @guest
                            <a href="{{ route('register') }}"  class="btn btn-primary active  w-100 mt-3" role="button">@lang('account.register')</a>
                            <a href="{{ route('faq_monitor') }}" class="btn btn-danger btn-xs active w-75 mt-1 float-right" role="button" style="font-size: 0.75rem;">@lang('account.details')</a>
                            @else
                            @if($user->partner == 1)
                            <button type="button" class="btn btn-success active  w-100 mt-3">@lang('account.active')</button>
                            @else
                            <a href="{{ route('account.become_monitor') }}"  class="btn btn-primary active  w-100 mt-3" role="button">@lang('account.activate')</a>
                            <a href="{{ route('faq_monitor') }}" class="btn btn-danger btn-xs active w-75 mt-1 float-right" role="button" style="font-size: 0.75rem;">@lang('account.details')</a>
                            @endif
                            @endguest

                        </div>
                    </div>
                    <div class="main_articles_block  col Fill-bepartner">
                        <div class="main_articles_block_link">
                            <!-- <div class="main_articles_block_link_img">
                                <iframe src="https://www.youtube.com/embed/Td3uCaVgYIE" allowfullscreen="" class="main_articles_block_link_img_icon" alt="">
                                </iframe>
                            </div> -->
                            <div class="main_articles_block_link_decription">
                                <div class="partner-program-title"><h5>@lang('partner.third_programm_title')</h5></div>
                                @lang('partner.third_programm_desc')
                                <!--button type="button" style="margin: 10px 15%;" class="btn btn-success active btn-sm  col-sm-8">@lang('partner.current_programm_active')</button-->
                            </div>
                            @guest
                            <a href="{{ route('register') }}"  class="btn btn-primary active  w-100 mt-3" role="button">@lang('account.register')</a>
                            <a href="{{ route('faq_lider') }}" class="btn btn-danger btn-xs active w-75 mt-1 float-right" role="button" style="font-size: 0.75rem;">@lang('account.details')</a>
                            @else
                            @if($user->partner == 2)
                            <button type="button"  class="btn btn-success active  w-100 mt-3">@lang('account.active')</button>
                            @else
                            <a href="{{ route('account.become_lider') }}" class="btn btn-primary active  w-100 mt-3" role="button">@lang('account.activate')</a>
                            <a href="{{ route('faq_lider') }}" class="btn btn-danger btn-xs active w-75 mt-1 float-right" role="button" style="font-size: 0.75rem;">@lang('account.details')</a>
                            @endif
                            @endguest




                        </div>
                    </div>

                    <div class="main_articles_block  col Fill-bepartner">
                        <div class="main_articles_block_link">
                            <!-- <div class="main_articles_block_link_img">
                                <iframe src="https://www.youtube.com/embed/Td3uCaVgYIE" allowfullscreen="" class="main_articles_block_link_img_icon" alt="">
                                </iframe>
                            </div> -->
                            <div class="main_articles_block_link_decription">
                                <div class="partner-program-title"><h5>@lang('partner.four_programm_title')</h5></div>
                                @lang('partner.four_programm_desc')
                                <!--button type="button" style="margin: 10px 15%;" class="btn btn-success active btn-sm  col-sm-8">@lang('partner.current_programm_active')</button-->
                            </div>
                            @guest
                            <a href="{{ route('register') }}"  class="btn btn-primary active  w-100 mt-3" role="button">@lang('account.register')</a>
                            <a href="{{ route('faq_manager') }}" class="btn btn-danger btn-xs active w-75 mt-1 float-right" role="button" style="font-size: 0.75rem;">@lang('account.details')</a>
                            @else
                            @if($user->partner == 3)
                            <button type="button"  class="btn btn-success active  w-100 mt-3">@lang('account.active')</button>
                            @else
                            <a href="{{ route('account.become_manager') }}"  class="btn btn-primary active w-100 mt-3" role="button">@lang('account.activate')</a>
                            <a href="{{ route('faq_manager') }}" class="btn btn-danger btn-xs active w-75 mt-1 float-right" role="button" style="font-size: 0.75rem;">@lang('account.details')</a>
                            @endif
                            @endguest



                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>
</div>
@endsection
