@extends('admin.layout')

@section('title')
    Панель администратора: редактирование пользователя #{{ $user->id }}
@endsection

@section('page_header')
    Редактирование пользователя #{{ $user->id }}
@endsection

@section('content')
    <div class="panel panel-default">
        <div class="panel-body">
            <form action="{{ route('admin.users.update', $user) }}" method="POST">
                {{ method_field('PUT') }}
                {{ csrf_field() }}

                @component('admin.components.input-text', [
                    'initialValue' => $user->name,
                    'id'   => 'name',
                    'name' => 'name',
                    'placeholder' => 'Введите имя',
                    'label' => 'Имя',
                    'required' => true
                ])@endcomponent

                @component('admin.components.input-email', [
                    'initialValue' => $user->email,
                    'id'   => 'email',
                    'name' => 'email',
                    'placeholder' => 'Введите email',
                    'label' => 'Email',
                    'required' => true
                ])@endcomponent

                <div class="form-group ">
                            <label class="control-label" for="balance">Balance</label>
                        <input id="balance" type="number" class="form-control" name="balance" step="0.01" placeholder="" value="{{ $user->balance}}">
                    </div>

                @component('admin.components.input-select', [
                'initialValue' => $user->verified,
                'settings'=>'min=0 max=1 step=1',
                'options'=>json_decode('[{"id":0,"name":"Нет"},{"id":1,"name":"Да"}]'),
                'id'   => 'verified',
                'name' => 'verified',
                'label' => 'Верифицирован',
                'style'=>'width: 100%; float: right;',
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
