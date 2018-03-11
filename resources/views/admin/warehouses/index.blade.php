@extends('admin.layouts.admin')

@section('title', 'Daftar Gudang')

@section('content')

    <div class="row">
        @include('partials._success')
        <div class="nav navbar-right">
            <a href="{{ route('admin.warehouses.create') }}" class="btn btn-app">
                <i class="fa fa-plus"></i> Tambah
            </a>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="row">
        <table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0"
               width="100%" id="warehouses-table">
            <thead>
            <tr>
                <th class="text-center">No</th>
                <th class="text-center">Kode</th>
                <th class="text-center">Nama</th>
                <th class="text-center">Lokasi</th>
                <th class="text-center">Nomor Telepon</th>
                <th class="text-center">Dibuat Oleh</th>
                <th class="text-center">Tanggal Dibuat</th>
                <th class="text-center">Diubah Oleh</th>
                <th class="text-center">Tanggal Diubah</th>
                <th class="text-center">Tindakan</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>

    @include('partials._delete')
@endsection

@section('styles')
    @parent
    {{ Html::style(mix('assets/admin/css/datatables.css')) }}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
@endsection

@section('scripts')
    @parent
    {{ Html::script(mix('assets/admin/js/datatables.js')) }}
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script>
        $(function() {
            $('#warehouses-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('datatables.warehouses') !!}',
                columns: [
                    { data: 'DT_Row_Index', orderable: false, searchable: false, class: 'text-center'},
                    { data: 'code', name: 'code', class: 'text-center'},
                    { data: 'name', name: 'name', class: 'text-center'},
                    { data: 'location', name: 'location', class: 'text-center'},
                    { data: 'phone', name: 'phone', class: 'text-center'},
                    { data: 'created_by', name: 'created_by', class: 'text-center'},
                    { data: 'created_at', name: 'created_at', class: 'text-center'},
                    { data: 'updated_by', name: 'updated_by', class: 'text-center'},
                    { data: 'updated_at', name: 'updated_at', class: 'text-center'},
                    { data: 'action', name:'action', orderable: false, searchable: false, class: 'text-center'}
                ],
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.10.16/i18n/Indonesian-Alternative.json"
                }
            });
        });

        $(document).on('click', '.delete-modal', function(){
            $('#deleteModal').modal({
                backdrop: 'static',
                keyboard: false
            });

            $('#deleted-id').val($(this).data('id'));
        });
    </script>
    @include('partials._deleteJs', ['routeUrl' => 'admin.warehouses.destroy', 'redirectUrl' => 'admin.warehouses'])
@endsection