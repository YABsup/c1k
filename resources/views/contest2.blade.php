<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>C1K contest</title>
    <link rel="stylesheet" href="/css/contest.css">
      <link rel="stylesheet" href="/css/sidebar.css"/>
</head>

<body>
    <div class="c1k-contest-app">

        <div class="contest-container">

            <div class="contest-baner">
                <img src="/img/contest-baner.jpg" alt="contest-baner">
            </div>

            <div class="contest-description">
                <p class="contest-description--head">
                    Уважаемые клиенты и просто посетители! <br>
                    Мы рады сообщить Вам, что командой С1К было принято нововведение.
                </p>

                <h1 class="contest-description--footer">
                    30 числа каждого месяца, в 17:00 по МСК будет проводиться
                    <span>розыгрыш 100 usdt</span>. Получить их Вы сможете при выполнении следующих условий:
                </h1>
            </div>

            <ul class="contest-conditions__list">
                <li class="contest-conditions__list--item">
                    Проведите обмен на нашем сайте <a href="http://c1k.world">c1k.world</a> и сохраните номер заявки.
                </li>
                <li class="contest-conditions__list--item">
                    Напишите отзыв на мониторинговом сайте <a href="http://bestchange.ru" target="_blank">bestchange.ru</a> .
                </li>
                <li class="contest-conditions__list--item">
                    Подтвердите свой отзыв указав номер заявки.
                </li>
                <li class="contest-conditions__list--item">
                    После получения письма на ваш почтовый ящик от
                    <a href="http://bestchange.ru" target="_blank">bestchange.ru</a>
                    перейдите по ссылке для публикации отзыва.
                </li>
                <li class="contest-conditions__list--item">
                    Подпишитесь на наш Telegram канал.
                </li>
                <li class="contest-conditions__list--item">
                    Отправьте скриншот опубликованного отзыва с номером вашей заявки на обмен одному из наших
                    сотрудников в Telegram <a href="https://t.me/ex_c1k" target="_blank">Telegram</a> , <a href="https://t.me/noncash_c1k" target="_blank">Telegram</a> или в чат поддержки на нашем сайте.
                </li>
                <li class="contest-conditions__list--item">
                    Наши сотрудники присвоят вам порядковый номер для участия в розыгрыше
                </li>
                <li class="contest-conditions__list--item">
                    В день розыгрыша посетите наш телеграм канал, чтобы узнать номер победителя.
                </li>
            </ul>

            <div class="contest-winner">
                <p>
                    Выбор победителя будет производится посредством использования рандомайзера.
                    В нашем Telegram канале будет размещен видео повтор розыгрыша.
                </p>
                <p>
                    Мы свяжемся с победителем в 20:00 по Мск и перечислим выигранные средства удобным для Вас способом.
                </p>
                <p>
                    Помните, больше комментариев- больше шансов на победу!
                    Желаем удачи!
                </p>
            </div>

        </div>


    <script src="/dist/jquery.js"></script>
    <script src="/dist/js/bootstrap.js"></script>
    <script>

    function show_left_sidebar()
    {
      $('#left_sidebar_small').hide();
      $('#left_sidebar_main').show();
    }

    function hide_left_sidebar()
    {

      $('#left_sidebar_main').hide();
      $('#left_sidebar_small').show();

    }
    </script>

    <div id="left_sidebar_small" class="left_sidebar_small" onclick="show_left_sidebar()">
      <div>
        {{ __('menu_header.our_ways') }}
      </div>

    </div>

    <div id="left_sidebar_main" class="left_sidebar">
      <div class="left_side">
        {{ __('menu_header.select_way') }}
      </div>

      <div class="left_c1k_logo">
        <a href="https://c1k.world"><img src="/img/c-1-k-finance-logo-copy-2.svg"></a>
        <div class="left_c1k_link">
          <a href="https://c1k.world">BUY/SELL</a>
        </div>
      </div>

      <div class="left_c1k_logo">
        <a href="https://c1k-fin.world"><img src="/img/c-1-k-finance-logo-copy-2.svg"></a>

        <div class="left_c1k_link">
          <a href="https://c1k-fin.world">FINANCE</a></a>
        </div>
      </div>
      <div class="left_c1k_logo">
         <a href="https://c1k-consulting.world"><img src="/img/c-1-k-finance-logo-copy-2.svg">

        <div class="left_c1k_link">
          <a href="https://c1k-consulting.world">CONSULTING</a>
        </div>
      </div>


      <div class="left_c1k_logo">
        <a href="https://c1k-development.world"><img src="/img/c-1-k-finance-logo-copy-2.svg"></a>
        <div class="left_c1k_link">
          <a href="https://c1k-development.world">DEVELOPMENT</a>
        </div>
      </div>

      <div class="left_side_down" onclick="hide_left_sidebar()">
        {{ __('menu_header.svernut') }} <img src="img/mask.svg" class="Mask">
      </div>
    </div>

    </div>
</body>

</html>
