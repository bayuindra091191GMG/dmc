@extends('admin.layouts.admin')

@section('title', 'Data Otorisasi Dokumen')

@section('content')
    <div class="nav navbar-right">
        <a href="{{ route('admin.permission_documents.create') }}" class="btn btn-app">
            <i class="fa fa-plus"></i> Tambah
        </a>
    </div>
    <div class="clearfix"></div>

    <div class="row">
        <table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0"
               width="100%" id="users-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Role</th>
                    <th>Document</th>
                    <th>Melihat</th>
                    <th>Membuat</th>
                    <th>Mengubah</th>
                    <th>Mencetak</th>
                    <th>Menghapus</th>
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
                ajax: '{!! route('datatables.permission_documents') !!}',
                columns: [
                    { data: 'DT_Row_Index', orderable: false, searchable: false},
                    { data: 'role', name: 'role' },
                    { data: 'document', name: 'document' },
                    { data: 'read', name: 'read' },
                    { data: 'create', name: 'create' },
                    { data: 'update', name: 'update' },
                    { data: 'print', name: 'print' },
                    { data: 'delete', name: 'delete' },
                    { data: 'created_by', name: 'created_by' },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'updated_by', name: 'updated_by' },
                    { data: 'updated_at', name: 'updated_at' },
                    { data: 'action', name:'action' }
                ]
            });
        });
    </script>
@endsection
