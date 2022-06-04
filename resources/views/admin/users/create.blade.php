@extends('admin.layout')

@section('title')
    Панель администратора: создание пользователя
@endsection

@section('page_header')
    Создание пользователя
@endsection

@section('content')
    <div class="panel panel-default">
        <div class="panel-body">
            <form action="{{ route('admin.users.store') }}" method="POST" autocomplete="off">
                {{ csrf_field() }}

                @component('admin.components.input-text', [
                    'initialValue' => '',
                    'id'   => 'name',
                    'name' => 'new_name',
                    'placeholder' => 'Введите имя',
                    'label' => 'Имя',
                    'required' => true
                ])@endcomponent

                @component('admin.components.input-email', [
                    'initialValue' => '',
                    'id'   => 'email',
                    'name' => 'new_email',
                    'placeholder' => 'Введите email',
                    'label' => 'Email',
                    'required' => true
                ])@endcomponent

                @component('admin.components.input-password', [
                    'initialValue' => '',
                    'id'   => 'password',
                    'name' => 'new_password',
                    'placeholder' => 'Введите пароль',
                    'label' => 'Пароль',
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
