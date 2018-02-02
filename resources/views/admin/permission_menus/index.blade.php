@extends('admin.layouts.admin')

@section('title', 'Daftar Otorisasi Menu')

@section('content')

    <div class="row">
        @include('partials._success')
        <div class="nav navbar-right">
            <a href="{{ route('admin.permission_menus.create') }}" class="btn btn-app">
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
                    <th>No</th>
                    <th>Role</th>
                    <th>Menu</th>
                    <th>Dibuat Oleh</th>
                    <th>Tanggal Dibuat</th>
                    <th>Diubah Oleh</th>
                    <th>Tanggal Diubah</th>
                    <th>Opsi</th>
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
                ajax: '{!! route('datatables.permission_menus') !!}',
                columns: [
                    { data: 'DT_Row_Index', orderable: false, searchable: false},
                    { data: 'role', name: 'role' },
                    { data: 'menu', name: 'menu' },
                    { data: 'created_by', name: 'created_by' },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'updated_by', name: 'updated_by' },
                    { data: 'updated_at', name: 'updated_at' },
                    { data: 'action', name:'action' }
                ],
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.10.16/i18n/Indonesian-Alternative.json"
                }
            });
        });
    </script>
@endsection
