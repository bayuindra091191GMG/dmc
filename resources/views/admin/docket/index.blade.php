@extends('admin.layouts.admin')

@section('title', 'Daftar Issued Docket')

@section('content')

    <div class="row">
        @include('partials._success')
        <div class="nav navbar-right">
            <a href="{{ route('admin.issued_docket.create') }}" class="btn btn-app">
                <i class="fa fa-plus"></i> Tambah
            </a>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="row">
        <table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0"
               width="100%" id="pr-table">
            <thead>
            <tr>
                <th>No</th>
                <th>No Issued Docket</th>
                <th>No PR</th>
                <th>No Unit</th>
                <th>Department</th>
                <th>Divisi</th>
                <th>Tanggal Dibuat</th>
                <th>Dibuat Oleh</th>
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
            $('#pr-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('datatables.issued_docket') !!}',
                columns: [
                    { data: 'DT_Row_Index', orderable: false, searchable: false},
                    { data: 'no_issued_docket', name: 'no_issued_docket' },
                    { data: 'no_pr', name: 'no_pr' },
                    { data: 'no_unit', name: 'no_unit' },
                    { data: 'department', name: 'department' },
                    { data: 'division', name: 'division' },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'created_by', name: 'created_by' },
                    { data: 'action', name: 'action' }
                ],
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.10.16/i18n/Indonesian-Alternative.json"
                }
            });
        });
    </script>
@endsection
