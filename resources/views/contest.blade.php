@extends('layouts.c1k-new')

@section('styles')
    <link rel="stylesheet" href="/css/contest.css">
@endsection

@section('content')
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
                    Проведите обмен на нашем сайте <a href="http://c1k.world" style="color:red"><b>c1k.world</b></a> и сохраните номер заявки.
                </li>
                <li class="contest-conditions__list--item">
                    Напишите отзыв на мониторинговом сайте <a href="http://bestchange.ru" target="_blank" style="color:red"><b>bestchange.ru</b></a> .
                </li>
                <li class="contest-conditions__list--item">
                    Подтвердите свой отзыв указав номер заявки.
                </li>
                <li class="contest-conditions__list--item">
                    После получения письма на ваш почтовый ящик от
                    <a href="http://bestchange.ru" target="_blank" style="color:red"><b>bestchange.ru</b></a>
                    перейдите по ссылке для публикации отзыва.
                </li>
                <li class="contest-conditions__list--item">
                    Подпишитесь на наш <a href="https://t.me/C1k_world" style="color:red"><b>Telegram канал.</b></a>
                </li>
                <li class="contest-conditions__list--item">
                    Отправьте скриншот опубликованного отзыва с номером вашей заявки на обмен одному из наших
                    сотрудников в Telegram <a href="https://t.me/ex_c1k" target="_blank" style="color:red"><b>@ex_c1k</b></a> , <a href="https://t.me/noncash_c1k" target="_blank" style="color:red"><b>Telegram</b></a> или в чат поддержки на нашем сайте.
                </li>
                <li class="contest-conditions__list--item">
                    Наши сотрудники присвоят вам порядковый номер для участия в розыгрыше
                </li>
                <li class="contest-conditions__list--item">
                    В день розыгрыша посетите наш <a href="https://t.me/C1k_world" style="color:red"><b>телеграм канал</b></a>, чтобы узнать номер победителя.
                </li>
            </ul>

            <div class="contest-winner">
                <p>
                    Выбор победителя будет производится посредством использования рандомайзера.
                    В нашем <a href="https://t.me/C1k_world" style="color:red"><b>Telegram канале</b></a> будет размещен видео повтор розыгрыша.
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

    </div>
@endsection
