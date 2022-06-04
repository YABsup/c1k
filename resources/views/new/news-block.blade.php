<?php


$loc = \Session::get('locale','ru');

$redis = new \Redis();
$redis->connect('127.0.0.1', 6379);

$news_id = $redis->get('news_id');
if( $news_id == null )
{
    $news_id = [1461,1460,1459];
}else{
    $news_id = json_decode($news_id, true);
}

?>


@if( ($loc == 'ru') || ($loc == 'uk') )

<div class="row news-wrap">
    <h3 class="news-title">@lang('about_partners.news')</h3>
</div>
<div class="row news-wrap">


    @foreach( $news_id as $news)

    <div class="news-column">
        <script async src="https://telegram.org/js/telegram-widget.js?7" data-telegram-post="C1k_world/{{$news}}" data-width="90%" data-userpic="false"></script>
    </div>

    @endforeach

</div>
</div>
@endif
