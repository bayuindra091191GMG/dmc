@extends('admin.layouts.admin')

@section('title', 'Daftar Interchange')

@section('content')

    <div class="row">
        @include('partials._success')
        <div class="nav navbar-right">
            <a href="{{ route('admin.interchanges.create') }}" class="btn btn-app">
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
                <th class="text-center">No</th>
                <th class="text-center">Kode Sebelum</th>
                <th class="text-center">Nama Sebelum</th>
                <th class="text-center">Kode Sesudah</th>
                <th class="text-center">Nama Sesudah</th>
                <th class="text-center">Dibuat Oleh</th>
                <th class="text-center">Tanggal Dibuat</th>
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
                ajax: '{!! route('datatables.interchanges') !!}',
                columns: [
                    { data: 'DT_Row_Index', orderable: false, searchable: false, class: 'text-center'},
                    { data: 'item_code_before', name: 'item_code_before', class: 'text-center' },
                    { data: 'item_name_before', name: 'item_name_before', class: 'text-center' },
                    { data: 'item_code_after', name: 'item_code_after', class: 'text-center' },
                    { data: 'item_name_after', name: 'item_name_after', class: 'text-center' },
                    { data: 'created_by', name: 'created_by', class: 'text-center' },
                    { data: 'created_at', name: 'created_at', class: 'text-center' },
                ],
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.10.16/i18n/Indonesian-Alternative.json"
                }
            });
        });
    </script>
@endsection
