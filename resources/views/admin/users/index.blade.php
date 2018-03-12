@extends('admin.layouts.admin')

@section('title', 'Daftar User')

@section('content')
    <div class="row">
        @include('partials._success')
        <div class="nav navbar-right">
            <a href="{{ route('admin.users.create') }}" class="btn btn-app">
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
                <th class="text-center">ID</th>
                <th class="text-center">Email</th>
                <th class="text-center">Nama</th>
                <th class="text-center">Nomor Telpon</th>
                <th class="text-center">Departemen</th>
                <th class="text-center">Site</th>
                <th class="text-center">Roles</th>
                <th class="text-center">Status</th>
                <th class="text-center">Tanggal Dibuat</th>
                <th class="text-center">Tindakan</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>

@endsection

@section('styles')
    @parent
    {{ Html::style(mix('assets/admin/css/users/index.css')) }}
@endsection

@section('scripts')
    @parent
    {{ Html::script(mix('assets/admin/js/users/index.js')) }}
    <script>
        $(function() {
            $('#users-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('datatables.users') !!}',
                columns: [
                    { data: 'code', name: 'code' },
                    { data: 'email', name: 'email' },
                    { data: 'name', name: 'name' },
                    { data: 'phone', name: 'phone' },
                    { data: 'department', name: 'department' },
                    { data: 'site', name: 'site' },
                    { data: 'role', name: 'role' },
                    { data: 'status', name: 'status' },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'action', name:'action' }
                ],
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.10.16/i18n/Indonesian-Alternative.json"
                }
            });
        });
    </script>
@endsection
