@extends('admin.layouts.admin')

@section('title', 'Daftar Alat Berat')

@section('content')

    <div class="row">
        @include('partials._success')
        <div class="nav navbar-right">
            <a href="{{ route('admin.machineries.create') }}" class="btn btn-app">
                <i class="fa fa-plus"></i> Tambah
            </a>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="row">
        <table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0"
               width="100%" id="machineries-table">
            <thead>
            <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Tipe</th>
                <th>Merek</th>
                <th>Kategori</th>
                <th>Status</th>
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
            $('#machineries-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('datatables.machineries') !!}',
                columns: [
                    { data: 'DT_Row_Index', orderable: false, searchable: false},
                    { data: 'code', name: 'code' },
                    { data: 'type', name: 'type' },
                    { data: 'brand', name: 'brand' },
                    { data: 'category', name: 'category' },
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