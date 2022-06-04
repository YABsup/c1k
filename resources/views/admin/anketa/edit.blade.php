@extends('admin.layout')

@section('title')
    Панель администратора: редактирование анкеты #{{ $anketa->id }}
@endsection

@section('page_header')
    Редактирование анкеты #{{ $anketa->id }}
@endsection

@section('content')
    <div class="panel panel-default">
        <div class="panel-body">
            <form action="{{ route('admin.anketas.update', $anketa) }}" method="POST">
                {{ method_field('PUT') }}
                {{ csrf_field() }}
                <?php
                $form_fields = array(
                'user_id',
                'username',
                'email',
                'telegram',
                'kind_of_activity',
                'auditory_type',
                'auditory_count',
                'youtube_link',
                'insta_link',
                'telegram_link',
                'additional_link',
                'additional_info',
                'platform_name',
                'platform_link',
                'platform_position',
                'platform_age',
                'verify_code',
                'status',
                'type',
                'created_at',
                'updated_at',
                );

                ?>
                @foreach($form_fields as $field)
                <div class="col-md-12 row text-person_data">
                  <div class="col-md-4 col-sm-4 col-xs-6">
                    <label class="col-form-label" for="inputFor{{ $field }}">@lang('anketa.form_'.$field)</label>
                  </div>
                  <div class="col-md-8 col-sm-4 col-xs-6">
                    <input type="text" class="form-control" name="{{ $field }}" id="input{{ $field }}" value="{{ $anketa[$field] }}" placeholder="@lang('anketa.placeholder_'.$field)"  disabled>
                  </div>
                </div>
                @endforeach
                <hr/>

                <div class="form-group">
                @if( $anketa->status == 1 )
                <button type="submit" name="anketa_approve" value="true" class="btn btn-success margin-r-5">Утвердить</button>
                <button type="submit" name="anketa_approve" value="false" class="btn btn-danger margin-r-5">Отклонить</button>
                 @endif
                    <a href="{{ url()->previous() }}" class="btn btn-default">Отмена</a>
                </div>
            </form>
        </div>
    </div>
@endsection
