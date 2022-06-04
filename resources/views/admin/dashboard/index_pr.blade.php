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

            <table id="#table_id" class="dataTable table table-striped table-bordered">
                <thead>
                <tr>
                    <th>Test</th>
                    <th>Table</th>
                    <th>Data</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>Data</td>
                    <td>Row</td>
                    <td>1</td>
                </tr>
                <tr>
                    <td>Data</td>
                    <td>Row</td>
                    <td>2</td>
                </tr>
                <tr>
                    <td>Data</td>
                    <td>Row</td>
                    <td>3</td>
                </tr>
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
