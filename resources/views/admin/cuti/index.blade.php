@extends('admin.layouts.admin')

@section('title', 'Daftar Cuti')

@section('content')

    <div class="row">
        @include('partials._success')
        <div class="nav navbar-right">
            <a href="{{ route('admin.transactions.cuti.create') }}" class="btn btn-app">
                <i class="fa fa-plus"></i> Tambah Cuti
            </a>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="row">
        <table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0"
               width="100%" id="datatable_table">
            <thead>
            <tr>
                <th class="text-center" style="width: 10%;">Tanggal Mulai</th>
                <th class="text-center" style="width: 10%;">Tanggal Berakhir</th>
                <th class="text-center" style="width: 10%;">Transaksi Cuti</th>
                <th class="text-center" style="width: 20%;">Nama Student</th>
                <th class="text-center" style="width: 20%;">Ortu Student</th>
                <th class="text-center" style="width: 15%;">Kelas</th>
                <th class="text-center" style="width: 15%;">Trainer</th>
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
            $('#datatable_table').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 50,
                ajax: '{!! route('datatables.cuti') !!}',
                order: [ [0, 'desc'] ],
                columns: [
                    { data: 'start_date', name: 'start_date', class: 'text-center',
                        render: function ( data, type, row ){
                            if ( type === 'display' || type === 'filter' ){
                                return moment(data).format('DD MMM YYYY');
                            }
                            return data;
                        }
                    },
                    { data: 'end_date', name: 'end_date', class: 'text-center',
                        render: function ( data, type, row ){
                            if ( type === 'display' || type === 'filter' ){
                                return moment(data).format('DD MMM YYYY');
                            }
                            return data;
                        }
                    },
                    { data: 'transaction', name: 'transaction', class: 'text-center'},
                    { data: 'student_name', name: 'student_name', class: 'text-center'},
                    { data: 'student_parent', name: 'student_parent', class: 'text-center'},
                    { data: 'class', name: 'class', class: 'text-center'},
                    { data: 'coach', name: 'coach', class: 'text-center'}
                ],
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.10.16/i18n/Indonesian-Alternative.json"
                }
            });
        });
    </script>
@endsection
