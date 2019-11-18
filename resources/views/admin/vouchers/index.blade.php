@extends('admin.layouts.admin')

@section('title', 'Data Voucher')

@section('content')

    <div class="row">
        @include('partials._success')
        <div class="nav navbar-right">
            <a href="{{ route('admin.vouchers.create') }}" class="btn btn-app">
                <i class="fa fa-plus"></i> Tambah
            </a>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="row">
        <table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0"
               width="100%" id="coaches-table">
            <thead>
                <tr>
                    <th class="text-center" style="width: 10%;">No</th>
                    <th class="text-center" style="width: 10%;">Nama</th>
                    <th class="text-center" style="width: 10%;">Deskripsi</th>
                    <th class="text-center" style="width: 10%;">Tipe</th>
                    <th class="text-center" style="width: 20%;">Point yang dibutuhkan</th>
                    <th class="text-center" style="width: 20%;">Status</th>
                    <th class="text-center" style="width: 10%;">Tanggal Dibuat</th>
                    <th class="text-center" style="width: 10%;">Action</th>
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
            $('#coaches-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('datatables.vouchers') !!}',
                columns: [
                    { data: 'DT_Row_Index', orderable: false, searchable: false, class: 'text-center'},
                    { data: 'name', name: 'name', class: 'text-center'},
                    { data: 'description', name: 'description', class: 'text-center'},
                    { data: 'type', name: 'type', class: 'text-center'},
                    { data: 'point_needed', name: 'point_needed', class: 'text-center'},
                    { data: 'status', name: 'status', class: 'text-center'},
                    { data: 'created_at', name: 'created_at', class: 'text-center'},
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
    @include('partials._deleteJs', ['routeUrl' => 'admin.vouchers.destroy', 'redirectUrl' => 'admin.vouchers'])
@endsection
