@extends('admin.layouts.admin')

@section('title', 'Daftar Absensi')

@section('content')

    <div class="row">
        @include('partials._success')
        <div class="nav navbar-right">
            <a href="{{ route('admin.attendances.create') }}" class="btn btn-app">
                <i class="fa fa-plus"></i> Tambah
            </a>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="row">
        <table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0"
               width="100%" id="transaction-table">
            <thead>
                <tr>
                    <th class="text-center" style="width: 10%;">No</th>
                    <th class="text-center" style="width: 10%;">Nama Customer</th>
                    <th class="text-center" style="width: 10%;">Kelas</th>
                    <th class="text-center" style="width: 10%;">Trainer</th>
                    <th class="text-center" style="width: 10%;">Pertemuan ke-</th>
                    <th class="text-center" style="width: 15%;">Tanggal</th>
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
    {{ HTML::script(mix('assets/admin/js/datatables.js')) }}
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script>
        $(function() {
            $('#transaction-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{!! route('datatables.attendances') !!}',
                    data: {
                        'course': '{{$selectedCourse}}'
                    }
                },
                columns: [
                    { data: 'DT_Row_Index', orderable: false, searchable: false, class: 'text-center'},
                    { data: 'name', name: 'name', class: 'text-center'},
                    { data: 'course_name', name: 'course_name', class: 'text-center'},
                    { data: 'coach_name', name: 'coach_name', class: 'text-center'},
                    { data: 'meeting_number', name: 'meeting_number', class: 'text-center'},
                    { data: 'date', name: 'date', class: 'text-center'},
                    { data: 'action', name:'action', orderable: false, searchable: false, class: 'text-center'}
                ],
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.10.16/i18n/Indonesian-Alternative.json"
                }
            });
        });
    </script>
@endsection
