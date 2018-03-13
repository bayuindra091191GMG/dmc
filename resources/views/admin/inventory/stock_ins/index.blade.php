@extends('admin.layouts.admin')

@section('title', 'Daftar Stock In')

@section('content')

    <div class="row">
        @include('partials._success')
        <div class="nav navbar-right">
            <a href="{{ route('admin.stock_ins.create') }}" class="btn btn-app">
                <i class="fa fa-plus"></i> Tambah
            </a>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="row">
        <table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0"
               width="100%" id="stock-in-table">
            <thead>
            <tr>
                <th>No</th>
                <th>Kode Item</th>
                <th>Nama Item</th>
                <th>Jumlah Penambahan</th>
                <th>Gudang</th>
                <th>Dibuat Oleh</th>
                <th>Tanggal Dibuat</th>
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
            $('#stock-in-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('datatables.stock_ins') !!}',
                columns: [
                    { data: 'DT_Row_Index', orderable: false, searchable: false},
                    { data: 'item_code', name: 'item_code' },
                    { data: 'item', name: 'item' },
                    { data: 'increase', name: 'increase' },
                    { data: 'warehouse', name: 'warehouse' },
                    { data: 'created_by', name: 'created_by' },
                    { data: 'created_at', name: 'created_at' }
                ],
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.10.16/i18n/Indonesian-Alternative.json"
                }
            });
        });
    </script>
@endsection
