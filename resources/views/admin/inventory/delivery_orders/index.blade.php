@extends('admin.layouts.admin')

@section('title', 'Daftar Surat Jalan')

@section('content')

    <div class="row">
        @include('partials._success')
        <div class="nav navbar-right">
            <a href="{{ route('admin.delivery_orders.create') }}" class="btn btn-app">
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
                <th>Nomor SJ</th>
                <th>Site Keberangkatan</th>
                <th>Site Tujuan</th>
                <th>Alat Berat</th>
                <th>Nomor PR</th>
                <th>Keterangan</th>
                <th>Tanggal Dibuat</th>
                <th>Tindakan</th>
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
                ajax: '{!! route('datatables.delivery_orders') !!}',
                columns: [
                    { data: 'DT_Row_Index', orderable: false, searchable: false},
                    { data: 'code', name: 'code' },
                    { data: 'from_site', name: 'from_site' },
                    { data: 'to_site', name: 'to_site' },
                    { data: 'machinery', name: 'machinery' },
                    { data: 'pr_code', name: 'pr_code' },
                    { data: 'remark', name: 'remark' },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'action', name: 'action' }
                ],
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.10.16/i18n/Indonesian-Alternative.json"
                }
            });
        });
    </script>
@endsection
