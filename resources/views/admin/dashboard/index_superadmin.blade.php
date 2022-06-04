@extends('admin.layout')

@section('title')
    Панель администратора: главная
@endsection

@section('head')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
@endsection

@section('page_header')
    Главная
@endsection

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            Data table example
        </div>
        <div class="panel-body table-responsive">



          <div class="form-group">
    	    <a href="{{ $site_mode[0] }}" class="btn {{$site_mode[2]}} btn-sm">{{$site_mode[1]}}</a>
          </div>
          <div class="form-group">
    	    <a href="{{ $best_mode[0] }}" class="btn {{$best_mode[2]}} btn-sm">{{$best_mode[1]}}</a>
          </div>
          <div class="form-group">
            <a href="{{ $cash_mode[0] }}" class="btn {{$cash_mode[2]}} btn-sm">{{$cash_mode[1]}}</a>
          </div>

            <table id="#table_id" class="dataTable table table-striped table-bordered">
                <thead>
                <tr>
                    <th>Ip</th>
                    <th>Date</th>
                </tr>
                </thead>
                <tbody>

               @foreach($ip_logs as $ip_log)
                <tr>
                    <td>{{$ip_log->user_ip}}</td>
                    <td>{{$ip_log->created_at->format('Y-m-d H:i:s') }}</td>
                </tr>
                @endforeach


                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('javascript')
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
    <script>
        $(document).ready( function () {
            $('#table_id').DataTable();
        } );
    </script>
@endsection
