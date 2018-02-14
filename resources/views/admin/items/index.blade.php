@extends('admin.layouts.admin')

@section('title', 'Data Status')

@section('content')

    <div class="row">
        @include('partials._success')
        <div class="nav navbar-right">
            <a href="{{ route('admin.items.create') }}" class="btn btn-app">
                <i class="fa fa-plus"></i> Tambah
            </a>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="row">
        <table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0"
               width="100%" id="items-table">
            <thead>
            <tr>
                <th>No</th>
                <th>Nama Barang</th>
                <th>Part Number</th>
                <th>Satuan Unit</th>
                <th>Group</th>
                <th>Gudang</th>
                <th>Stock</th>
                <th>Deskripsi</th>
                <th>Tanggal Dibuat</th>
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
            $('#items-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('datatables.items') !!}',
                columns: [
                    { data: 'DT_Row_Index', orderable: false, searchable: false},
                    { data: 'name', name: 'name' },
                    { data: 'code', name: 'code' },
                    { data: 'uom', name: 'uom' },
                    { data: 'group', name: 'group' },
                    { data: 'warehouse', name: 'warehouse' },
                    { data: 'stock', name: 'stock' },
                    { data: 'description', name: 'decription' },
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
