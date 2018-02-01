@extends('admin.layouts.admin')

@section('title', 'Data Suppliers')

@section('content')
    <div class="nav navbar-right">
        <a href="{{ route('admin.suppliers.create') }}" class="btn btn-app">
            <i class="fa fa-plus"></i> Tambah
        </a>
    </div>
    <div class="clearfix"></div>

    <div class="row">
        <table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0"
               width="100%" id="employees-table">
            <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Kode</th>
                <th>Email</th>
                <th>Nomor Ponsel</th>
                <th>Tanggal Kontrak Mulai</th>
                <th>Tanggal Kontrak Berakhir</th>
                <th>Tanggal Dibuat</th>
                <th>Dibuat Oleh</th>
                <th>Tanggal Diubah</th>
                <th>Diubah Oleh</th>
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
            $('#employees-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('datatables.suppliers') !!}',
                columns: [
                    { data: 'DT_Row_Index', orderable: false, searchable: false},
                    { data: 'name', name: 'name' },
                    { data: 'code', name: 'code' },
                    { data: 'email', name: 'email' },
                    { data: 'phone', name: 'phone' },
                    { data: 'contract_start', name: 'contract_start' },
                    { data: 'contract_finish', name: 'contract_finish' },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'created_by', name: 'created_by' },
                    { data: 'updated_at', name: 'updated_at' },
                    { data: 'updated_by', name: 'updated_by' },
                    { data: 'action', name: 'action' }
                ]
            });
        });
    </script>
@endsection
