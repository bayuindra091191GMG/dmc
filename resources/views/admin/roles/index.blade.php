@extends('admin.layouts.admin')

@section('title', 'Data Level Akses')

@section('content')

    <div class="row">
        @include('partials._success')
        <div class="nav navbar-right">
            <a href="{{ route('admin.roles.create') }}" class="btn btn-app">
                <i class="fa fa-plus"></i> Tambah
            </a>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="row">
        <table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0"
               width="100%" id="users-table">
            <thead>
                <tr>
                    <th class="text-center">Nama</th>
                    <th class="text-center">Opsi</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>

@endsection

@section('styles')
    @parent
    {{ Html::style(mix('assets/admin/css/datatables.css')) }}
@endsection

@section('scripts')
    @parent
    {{ Html::script(mix('assets/admin/js/datatables.js')) }}
    <script>
        $(function() {
            $('#users-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('datatables.roles') !!}',
                columns: [
                    { data: 'description', name: 'description', class: 'text-center' },
                    { data: 'action', name:'action', class: 'text-center' }
                ],
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.10.16/i18n/Indonesian-Alternative.json"
                }
            });
        });
    </script>
@endsection
