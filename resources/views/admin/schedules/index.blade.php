@extends('admin.layouts.admin')

@section('title', 'Daftar Registrasi')

@section('content')

    <div class="row">
        @include('partials._success')
        <div class="nav navbar-right">
            <a href="{{ route('admin.schedules.create') }}" class="btn btn-app">
                <i class="fa fa-plus"></i> Tambah
            </a>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="row">
        <table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0"
               width="100%" id="users-table">
            <thead>
                <tr>
                    <th class="text-center">No</th>
                    <th class="text-center">Nama Murid</th>
                    <th class="text-center">Nama Orang Tua</th>
                    <th class="text-center">Nama Kelas</th>
                    <th class="text-center">Nama Trainer</th>
                    <th class="text-center">Tanggal Dimulai</th>
                    <th class="text-center">Tanggal Berakhir</th>
                    <th class="text-center">Jumlah Pertemuan</th>
                    <th class="text-center">Jumlah Bulan</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Tanggal Dibuat</th>
                    {{--<th class="text-center">Action</th>--}}
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
            $('#users-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('datatables.schedules') !!}',
                columns: [
                    { data: 'DT_Row_Index', orderable: false, searchable: false, class: 'text-center'},
                    { data: 'customer_name', name: 'customer_name', class: 'text-center'},
                    { data: 'customer_parent_name', name: 'customer_parent_name', class: 'text-center'},
                    { data: 'course_name', name: 'course_name', class: 'text-center'},
                    { data: 'coach_name', name: 'coach_name', class: 'text-center'},
                    { data: 'start_date', name: 'start_date', class: 'text-center'},
                    { data: 'finish_date', name: 'finish_date', class: 'text-center'},
                    { data: 'meeting_amount', name: 'meeting_amount', class: 'text-center'},
                    { data: 'month_amount', name: 'month_amount', class: 'text-center'},
                    { data: 'status', name: 'status', class: 'text-center'},
                    { data: 'created_at', name: 'created_at', class: 'text-center'},
                    // { data: 'action', name:'action', orderable: false, searchable: false, class: 'text-center'}
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
    @include('partials._deleteJs', ['routeUrl' => 'admin.schedules.destroy', 'redirectUrl' => 'admin.schedules'])
@endsection
