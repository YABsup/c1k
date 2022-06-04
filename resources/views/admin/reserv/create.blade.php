@extends('admin.layout')

@section('title')
    Панель администратора: создание резерва для валюты
@endsection

@section('page_header')
    Создание резерва валюты
@endsection

@section('content')
    <div class="panel panel-default">
        <div class="panel-body">
            <form action="{{ route('admin.reserv.store') }}" method="POST" autocomplete="off">
                {{ csrf_field() }}

                @component('admin.components.input-select', [
                    'initialValue' => '',
                    'id'   => 'coin_id',
                    'name' => 'coin_id',
                    'placeholder' => 'Выберите валюту',
                    'label' => 'Выберите валюту',
                    'options'=>$coins,
                    'style'=>'width: 50%; float: left; padding-right: 15px;',
                    'required' => true
                ])@endcomponent

                @component('admin.components.input-number', [
                    'initialValue' => '100',
                    'settings'=>'min=0 value=10 step=0.00001',
                    'id'   => 'amount',
                    'name' => 'amount',
                    'label' => 'Резерв валюты',
                    'style'=>'width: 50%; float: left; padding-right: 15px;',
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
