@extends('admin.layouts.admin')

@section('title', 'Daftar Mutasi Barang')

@section('content')

    <div class="row">
        @include('partials._success')
        <div class="nav navbar-right">
            <a href="{{ route('admin.item_mutations.create') }}" class="btn btn-app">
                <i class="fa fa-plus"></i> Tambah
            </a>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="row">
        <table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0"
               width="100%" id="item-mutation-table">
            <thead>
            <tr>
                <th>No</th>
                <th>Kode Item</th>
                <th>Nama Item</th>
                <th>Gudang Asal</th>
                <th>Gudang Tujuan</th>
                <th>Total Barang</th>
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
            $('#item-mutation-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('datatables.item_mutations') !!}',
                columns: [
                    { data: 'DT_Row_Index', orderable: false, searchable: false},
                    { data: 'item_code', name: 'item_code' },
                    { data: 'item', name: 'item' },
                    { data: 'from_warehouse', name: 'from_warehouse' },
                    { data: 'to_warehouse', name: 'to_warehouse' },
                    { data: 'mutation_quantity', name: 'mutation_quantity' },
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
