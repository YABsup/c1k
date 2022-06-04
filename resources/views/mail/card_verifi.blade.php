<?php
?>

Пользователь отправил фото на верификацию:<br>
Анкета:<br>
ФИО: {{ $sample_mail_text['request']['first_name'] }}<br>
Телефон: {{ $sample_mail_text['request']['tel'] }}<br>
email: {{ $sample_mail_text['request']['email'] }}<br>
Номер карты: {{ $sample_mail_text['request']['card'] }}<br>

Профиль на сайте: <a href="https://c1k.world/admin/users/{{ $sample_mail_text['user']['id'] }}">{{ $sample_mail_text['user']['email'] }}</a>


<hr> <br>
 <br>
@lang('mail_order.our_projects') <br>
Crypto Exchange: <a href="https://c1k.world">https://c1k.world</a> <br>
Crypto credit: <a href="https://c1k-fin.world">https://c1k-fin.world</a> <br>

<?php
