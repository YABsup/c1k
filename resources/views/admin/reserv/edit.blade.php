@extends('admin.layout')

@section('title')
    Панель администратора: редактирование
@endsection

@section('page_header')
    Редактирование резерва
@endsection

@section('content')
    <div class="panel panel-default">
        <div class="panel-body">
            <form action="{{ route('admin.reserv.update', $reserv) }}" method="POST" autocomplete="off">
                {{ method_field('PUT') }}
                {{ csrf_field() }}

                @component('admin.components.input-select', [
                    'initialValue' => $reserv->coin_id,
                    'id'   => 'coin_id',
                    'settings'=>'disabled',
                    'name' => 'coin_id',
                    'placeholder' => 'Выберите валюту',
                    'label' => 'Обменный пункт',
                    'options'=>$coins,
                    'style'=>'width: 50%; float: left; padding-right: 15px;',
                    'required' => true
                ])@endcomponent
                @component('admin.components.input-number', [
                    'initialValue' => $reserv->amount,
                    'settings'=>'min=0 step=0.00001',
                    'id'   => 'amount',
                    'name' => 'amount',
                    'label' => 'Резерв валюты',
                    'style'=>'width: 50%; float: right; padding-left: 15px;',
                    'required' => true
                ])@endcomponent

                <hr/>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary margin-r-5">Сохранить</button>
                    <a href="{{ url()->previous() }}" class="btn btn-default">Назад к списку</a>
                </div>
            </form>
        </div>
    </div>
@endsection
