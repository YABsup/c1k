@extends('admin.layout')

@section('title')
    Панель администратора: редактирование города #{{ $data->id }}
@endsection

@section('page_header')
    Редактирование города #{{ $data->id }}
@endsection

@section('content')
    <div class="panel panel-default">
        <div class="panel-body">
            <form action="{{ route('admin.city.update', $data) }}" method="POST">
                {{ method_field('PUT') }}
                {{ csrf_field() }}

                @component('admin.components.input-text', [
                    'initialValue' => $data->name,
                    'id'   => 'name',
                    'name' => 'name',
                    'placeholder' => 'Название',
                    'label' => 'Имя',
                    'required' => false,
                    'settings'=>'disabled',
                ])@endcomponent

                @component('admin.components.input-select', [
                'initialValue' => $data->ref_city_id,
                'id'   => 'ref_city_id',
                //'settings'=>'disabled',
                'name' => 'ref_city_id',
                'placeholder' => 'Выберите город референс',
                'label' => 'Город референс',
                'options'=>$cityes,
                //'style'=>'width: 50%; float: left; padding-right: 15px;',
                'required' => true
                ])@endcomponent


                @component('admin.components.input-number', [
                'initialValue' => $data->ref_bid_coef,
                'settings'=>'min=0 max=100 step=0.0001',
                'id'   => 'ref_bid_coef',
                'name' => 'ref_bid_coef',
                'label' => 'Коэффициент при покупке',
                'style'=>'width: 50%; float: left; padding-right: 15px;',
                'required' => true
                ])@endcomponent
                @component('admin.components.input-number', [
                'initialValue' => $data->ref_ask_coef,
                'settings'=>'min=0 max=100 step=0.0001',
                'id'   => 'ref_ask_coef',
                'name' => 'ref_ask_coef',
                'label' => 'Коэффициент при продаже',
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
