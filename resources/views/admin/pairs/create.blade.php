@extends('admin.layout')

@section('title')
    Панель администратора: создание валютной пары
@endsection

@section('page_header')
    Создание валютной пары
@endsection

@section('content')
    <div class="panel panel-default">
        <div class="panel-body">
          <div class="form-group">
                    <button type="submit" class="btn btn-danger margin-r-5">При добавлении пары проверяем направление на bestchange.ru!!!</button>

                </div>
            <form action="{{ route('admin.pairs.store') }}" method="POST" autocomplete="off">
                {{ csrf_field() }}

                @component('admin.components.input-select', [
                    'initialValue' => $city_id,
                    'id'   => 'city_id',
                    'name' => 'city_id',
                    'placeholder' => 'Выберите обменный пункт',
                    'label' => 'Обменный пункт',
                    'options'=>$exchanges,
                    'style'=>'width: 50%; float: left; padding-right: 15px;',
                    'required' => true
                ])@endcomponent
                @component('admin.components.input-select', [
                    'initialValue' => '10',
                    'id'   => 'provider_id',
                    'name' => 'provider_id',
                    'placeholder' => 'Прайс провайдер',
                    'label' => 'Прайс провайдер',
                    'options'=>$price_providers,
                    'style'=>'width: 50%; float: right; padding-left: 15px;',
                    'required' => true
                ])@endcomponent


                @component('admin.components.input-select', [
                    'initialValue' => '',
                    'id'   => 'base_currency_id',
                    'name' => 'base_currency_id',
                    'placeholder' => 'Валюта 1',
                    'label' => 'Валюта 1',
                    'options'=>$coins,
                    'style'=>'width: 50%; float: left; padding-right: 15px;',
                    'required' => true
                ])@endcomponent
                @component('admin.components.input-select', [
                    'initialValue' => '',
                    'id'   => 'quote_currency_id',
                    'name' => 'quote_currency_id',
                    'placeholder' => 'Валюта 2',
                    'label' => 'Валюта 2',
                    'options'=>$coins,
                    'style'=>'width: 50%; float: right; padding-left: 15px;',
                    'required' => true
                ])@endcomponent

                @component('admin.components.input-number', [
                    'initialValue' => '1',
                    'settings'=>'min=0 value=10 step=0.0001',
                    'id'   => 'base_min',
                    'name' => 'base_min',
                    'label' => 'Минимальная сума в валюте 1',
                    'style'=>'width: 50%; float: left; padding-right: 15px;',
                    'required' => true
                ])@endcomponent
                @component('admin.components.input-number', [
                    'initialValue' => '1',
                    'settings'=>'min=0 value=10 step=0.0001',
                    'id'   => 'quote_min',
                    'name' => 'quote_min',
                    'label' => 'Минимальная сума в валюте 2',
                    'style'=>'width: 50%; float: right; padding-left: 15px;',
                    'required' => true
                ])@endcomponent

                @component('admin.components.input-number', [
                    'initialValue' => '1',
                    'settings'=>'min=0 value=100 step=0.0001',
                    'id'   => 'base_max',
                    'name' => 'base_max',
                    'label' => 'Максимальная сума в валюте 1',
                    'style'=>'width: 50%; float: left; padding-right: 15px;',
                    'required' => true
                ])@endcomponent
                @component('admin.components.input-number', [
                    'initialValue' => '1',
                    'settings'=>'min=0 value=100 step=0.0001',
                    'id'   => 'quote_max',
                    'name' => 'quote_max',
                    'label' => 'Максимальная сума в валюте 2',
                    'style'=>'width: 50%; float: right; padding-left: 15px;',
                    'required' => true
                ])@endcomponent


                @component('admin.components.input-number', [
                    'initialValue' => 1,
                    'settings'=>'min=1 step=1',
                    'id'   => 'bid_position',
                    'name' => 'bid_position',
                    'label' => 'Позиция при покупке',
                    'style'=>'width: 50%; float: left; padding-right: 15px;',
                    'required' => true
                ])@endcomponent
                @component('admin.components.input-number', [
                    'initialValue' => 1,
                    'settings'=>'min=1 step=1',
                    'id'   => 'ask_position',
                    'name' => 'ask_position',
                    'label' => 'Позиция при продаже',
                    'style'=>'width: 50%; float: right; padding-left: 15px;',
                    'required' => true
                ])@endcomponent

                @component('admin.components.input-number', [
                    'initialValue' => '1',
                    'settings'=>'min=0 max=1 value=1 step=0.0001',
                    'id'   => 'bid_coef',
                    'name' => 'bid_coef',
                    'label' => 'Коэффициент при покупке < 1',
                    'style'=>'width: 50%; float: left; padding-right: 15px;',
                    'required' => true
                ])@endcomponent
                @component('admin.components.input-number', [
                    'initialValue' => '1',
                    'settings'=>'min=1 max=100 value=1 step=0.0001',
                    'id'   => 'ask_coef',
                    'name' => 'ask_coef',
                    'label' => 'Коэффициент при продаже > 1',
                    'style'=>'width: 50%; float: right; padding-left: 15px;',
                    'required' => true
                ])@endcomponent



                <hr/>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary margin-r-5">Создать</button>
                    <a href="{{ url()->previous() }}" class="btn btn-default">Назад к списку</a>
                </div>
            </form>
        </div>
    </div>
@endsection
