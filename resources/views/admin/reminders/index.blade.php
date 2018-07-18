@extends('admin.layouts.admin')

@section('title', 'Daftar Reminder')

@section('content')

    <div class="row">
        @include('partials._success')
        {{--<div class="nav navbar-right">--}}
            {{--<a href="{{ route('admin.transactions.create') }}" class="btn btn-app">--}}
                {{--<i class="fa fa-plus"></i> Tambah--}}
            {{--</a>--}}
            {{--<a href="{{ route('admin.transactions.prorate.create') }}" class="btn btn-app">--}}
                {{--<i class="fa fa-plus"></i> Tambah Prorate--}}
            {{--</a>--}}
        {{--</div>--}}
        <div class="clearfix"></div>
    </div>
    <div class="row">
        <table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0"
               width="100%" id="reminder-table">
            <thead>
            <tr>
                <th class="text-center" style="width: 10%;">No</th>
                <th class="text-center" style="width: 20%;">Nama Customer</th>
                <th class="text-center" style="width: 20%;">Nama Orang Tua Customer</th>
                <th class="text-center" style="width: 10%;">Kelas</th>
                <th class="text-center" style="width: 10%;">Sisa Pertemuan</th>
                <th class="text-center" style="width: 15%;">Sisa Hari</th>
                <th class="text-center" style="width: 15%;">Tanggal Berakhir</th>
                <th class="text-center" style="width: 15%;">Tindakan</th>
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
            $('#reminder-table').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 50,
                ajax: '{!! route('datatables.reminders') !!}',
                columns: [
                    { data: 'DT_Row_Index', orderable: false, searchable: false, class: 'text-center'},
                    { data: 'customer_name', name: 'customer_name', class: 'text-center'},
                    { data: 'customer_parent_name', name: 'customer_parent_name', class: 'text-center'},
                    { data: 'course_name', name: 'course_name', class: 'text-center'},
                    { data: 'meeting_amount', name: 'meeting_amount', class: 'text-right'},
                    { data: 'day_left', name: 'day_left', class: 'text-center'},
                    { data: 'finish_date', name: 'finish_date', class: 'text-center'},
                    { data: 'action', name:'action', orderable: false, searchable: false, class: 'text-center'}
                ],
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.10.16/i18n/Indonesian-Alternative.json"
                }
            });
        });
    </script>
@endsection
