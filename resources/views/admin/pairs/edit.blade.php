@extends('admin.layout')

@section('title')
Панель администратора: редактирование
@endsection

@section('page_header')
Редактирование валютной пары валютной пары
@endsection

@section('content')
<div class="panel panel-default">
  <div class="panel-body">
    <form action="{{ route('admin.pairs.update', $pair) }}" method="POST" autocomplete="off" {{ $pair->city->ref_city ? 'disabled' : ''}}>
      {{ method_field('PUT') }}
      {{ csrf_field() }}

      @component('admin.components.input-select', [
      'initialValue' => $pair->active,
      'settings'=>'min=0 max=1 step=1',
      'options'=>json_decode('[{"id":0,"name":"Нет"},{"id":1,"name":"Да"}]'),
      'id'   => 'active',
      'name' => 'active',
      'label' => 'Пара активна',
      'style'=>'width: 100%; float: right;',
      'required' => true
      ])@endcomponent
      
      @component('admin.components.input-select', [
      'initialValue' => $pair->city_id,
      'id'   => 'city_id',
      'settings'=>'disabled',
      'name' => 'city_id',
      'placeholder' => 'Выберите обменный пункт',
      'label' => 'Обменный пункт',
      'options'=>$exchanges,
      'style'=>'width: 50%; float: left; padding-right: 15px;',
      'required' => true
      ])@endcomponent
      @component('admin.components.input-select', [
      'initialValue' => $pair->provider_id,
      'settings'=>$pair->city->ref_city_id ? ' disabled' : '',
      'id'   => 'provider_id',
      'name' => 'provider_id',
      'placeholder' => 'Прайс провайдер',
      'label' => 'Прайс провайдер',
      'options'=>$price_providers,
      'style'=>'width: 50%; float: right; padding-left: 15px;',
      'required' => true
      ])@endcomponent


      @component('admin.components.input-select', [
      'initialValue' => $pair->base_currency_id,
      'id'   => 'base_currency_id',
      'name' => 'base_currency_id',
      'settings'=>'disabled',
      'placeholder' => 'Валюта 1',
      'label' => 'Валюта 1',
      'options'=>$coins,
      'style'=>'width: 50%; float: left; padding-right: 15px;',
      'required' => true
      ])@endcomponent
      @component('admin.components.input-select', [
      'initialValue' => $pair->quote_currency_id,
      'id'   => 'quote_currency_id',
      'name' => 'quote_currency_id',
      'settings'=>'disabled',
      'placeholder' => 'Валюта 2',
      'label' => 'Валюта 2',
      'options'=>$coins,
      'style'=>'width: 50%; float: right; padding-left: 15px;',
      'required' => true
      ])@endcomponent

      @component('admin.components.input-number', [
      'initialValue' => $pair->base_min,
      'settings'=>'min=0 step=0.0001',
      'id'   => 'base_min',
      'name' => 'base_min',
      'label' => 'Минимальная сума в валюте 1',
      'style'=>'width: 50%; float: left; padding-right: 15px;',
      'required' => true
      ])@endcomponent
      @component('admin.components.input-number', [
      'initialValue' => $pair->quote_min,
      'settings'=>'min=0 step=0.0001',
      'id'   => 'quote_min',
      'name' => 'quote_min',
      'label' => 'Минимальная сума в валюте 2',
      'style'=>'width: 50%; float: right; padding-left: 15px;',
      'required' => true
      ])@endcomponent

      @component('admin.components.input-number', [
      'initialValue' => $pair->base_max,
      'settings'=>'min=0 step=0.0001',
      'id'   => 'base_max',
      'name' => 'base_max',
      'label' => 'Максимальная сума в валюте 1',
      'style'=>'width: 50%; float: left; padding-right: 15px;',
      'required' => true
      ])@endcomponent
      @component('admin.components.input-number', [
      'initialValue' => $pair->quote_max,
      'settings'=>'min=0 step=0.0001',
      'id'   => 'quote_max',
      'name' => 'quote_max',
      'label' => 'Максимальная сума в валюте 2',
      'style'=>'width: 50%; float: right; padding-left: 15px;',
      'required' => true
      ])@endcomponent


      @component('admin.components.input-number', [
      'initialValue' => $pair->bid_position,
      'settings'=>'min=1 step=1'.($pair->city->ref_city_id ? ' disabled' : ''),
      'id'   => 'bid_position',
      'name' => 'bid_position',
      'label' => 'Позиция при покупке',
      'style'=>'width: 50%; float: left; padding-right: 15px;',
      'required' => true
      ])@endcomponent
      @component('admin.components.input-number', [
      'initialValue' => $pair->ask_position,
      'settings'=>'min=1 step=1'.($pair->city->ref_city_id ? ' disabled' : ''),
      'id'   => 'ask_position',
      'name' => 'ask_position',
      'label' => 'Позиция при продаже',
      'style'=>'width: 50%; float: right; padding-left: 15px;',
      'required' => true
      ])@endcomponent

      @component('admin.components.input-number', [
      'initialValue' => $pair->bid_coef,
      'settings'=>'min=0 max=100 step=0.0001'.($pair->city->ref_city_id ? ' disabled' : ''),
      'id'   => 'bid_coef',
      'name' => 'bid_coef',
      'label' => 'Коэффициент при покупке',
      'style'=>'width: 50%; float: left; padding-right: 15px;',
      'required' => true
      ])@endcomponent
      @component('admin.components.input-number', [
      'initialValue' => $pair->ask_coef,
      'settings'=>'min=0 max=100 step=0.0001'.($pair->city->ref_city_id ? ' disabled' : ''),
      'id'   => 'ask_coef',
      'name' => 'ask_coef',
      'label' => 'Коэффициент при продаже',
      'style'=>'width: 50%; float: right; padding-left: 15px;',
      'required' => true
      ])@endcomponent


      @component('admin.components.input-select', [
      'initialValue' => $pair->buy_enable,
      'settings'=>'min=0 max=1 step=1',
      'options'=>json_decode('[{"id":0,"name":"Нет"},{"id":1,"name":"Да"}]'),
      'id'   => 'buy_enable',
      'name' => 'buy_enable',
      'label' => 'Покупка активна',
      'style'=>'width: 50%; float: left; padding-right: 15px;',
      'required' => true
      ])@endcomponent

      @component('admin.components.input-select', [
      'initialValue' => $pair->sell_enable,
      'settings'=>'min=0 max=1 step=1',
      'options'=>json_decode('[{"id":0,"name":"Нет"},{"id":1,"name":"Да"}]'),
      'id'   => 'sell_enable',
      'name' => 'sell_enable',
      'label' => 'Продажа активна',
      'style'=>'width: 50%; float: right; padding-left: 15px;',
      'required' => true
      ])@endcomponent

      @component('admin.components.input-number', [
      'initialValue' => $pair->bid_step,
      'settings'=>'min=0.000000001 max=1000000 step=0.000000001'.($pair->city->ref_city_id ? ' disabled' : ''),
      'id'   => 'bid_step',
      'name' => 'bid_step',
      'label' => 'Шаг для торгов валюта 1',
      'style'=>'width: 50%; float: left; padding-right: 15px;',
      'required' => true
      ])@endcomponent
      @component('admin.components.input-number', [
      'initialValue' => $pair->ask_step,
      'settings'=>'min=0.000000001 max=1000000 step=0.000000001'.($pair->city->ref_city_id ? ' disabled' : ''),
      'id'   => 'ask_step',
      'name' => 'ask_step',
      'label' => 'Шаг для торгов валюта 2',
      'style'=>'width: 50%; float: right; padding-left: 15px;',
      'required' => true
      ])@endcomponent

      <?php $best_rate = \App\Http\Controllers\Rates\CurrentRate::get_rate_best($pair); ?>
      <?php $limit_rate = \App\Http\Controllers\Rates\CurrentRate::get_rate_limit($pair, $pair->provider->code ); ?>

      <?php $final_rate = \App\Http\Controllers\Rates\CurrentRate::get_rate_final($pair, $best_rate, $limit_rate ); ?>

      @if( $best_rate )
      <div class="form-group " style=" width: 50%; float: left; padding-right: 15px; ">
        <label class="control-label" for="bid_position">bestchange.ru: Покупка</label>
        <input type="text" class="form-control" value="{{number_format($best_rate['bid'],8,'.', ' ').': '.$best_rate['bid_position']}}" disabled>
      </div>
      <div class="form-group " style=" width: 50%; float: right; padding-left: 15px; ">
        <label class="control-label" for="bid_position">bestchange.ru: Продажа</label>
        <input type="text" class="form-control" value="{{number_format($best_rate['ask'],8,'.',' ').': '.$best_rate['ask_position']}}" disabled>
      </div>
      @endif
      @if( $limit_rate )
      <div class="form-group " style=" width: 50%; float: left; padding-right: 15px; ">
        <label class="control-label" for="bid_position">Планка: Покупка</label>
        <input type="text" class="form-control" value="{{ number_format($limit_rate['bid']*( $pair->provider_id != 0 ? $pair->bid_coef : 1),8,'.', ' ') }}" disabled>
      </div>
      <div class="form-group " style=" width: 50%; float: right; padding-left: 15px; ">
        <label class="control-label" for="bid_position">Планка: Продажа</label>
        <input type="text" class="form-control" value="{{ number_format($limit_rate['ask']*( $pair->provider_id != 0 ? $pair->ask_coef : 1),8,'.',' ') }}" disabled>
      </div>
      @endif

      @if( $final_rate )
      <div class="form-group " style=" width: 50%; float: left; padding-right: 15px; ">
        <label class="control-label" for="bid_position">Курс c1k: Покупка</label>
        <input type="text" class="form-control" value="{{ number_format($final_rate['bid'],8,'.', ' ') }}" disabled>
      </div>
      <div class="form-group " style=" width: 50%; float: right; padding-left: 15px; ">
        <label class="control-label" for="bid_position">Курс c1k: Продажа</label>
        <input type="text" class="form-control" value="{{ number_format($final_rate['ask'],8,'.',' ') }}" disabled>
      </div>
      @endif



      <hr/>

      <div class="form-group">
        <button type="submit" class="btn btn-primary margin-r-5">Сохранить</button>
        <a href="{{ url()->previous() }}" class="btn btn-default">Назад к списку</a>
      </div>
    </form>
  </div>
</div>
@endsection
