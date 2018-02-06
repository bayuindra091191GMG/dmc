@extends('admin.layouts.admin')

@section('title', 'Daftar Merek Alat Berat')

@section('content')

    <div class="row">
        @include('partials._success')
        <div class="nav navbar-right">
            <a href="{{ route('admin.machinery_brands.create') }}" class="btn btn-app">
                <i class="fa fa-plus"></i> Tambah
            </a>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="row">
        <table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0"
               width="100%" id="machinery-types-table">
            <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Kode</th>
                <th>Deskripsi</th>
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
            $('#machinery-types-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('datatables.machinery_brands') !!}',
                columns: [
                    { data: 'DT_Row_Index', orderable: false, searchable: false},
                    { data: 'name', name: 'name' },
                    { data: 'code', name: 'code' },
                    { data: 'description', name: 'description' },
                    { data: 'action', name:'action' }
                ],
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.10.16/i18n/Indonesian-Alternative.json"
                }
            });
        });
    </script>
@endsection