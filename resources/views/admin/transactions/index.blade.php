@extends('admin.layouts.admin')

@section('title', 'Daftar Transaksi')

@section('content')

    <div class="row">
        @include('partials._success')
        <div class="nav navbar-right">
            <a href="{{ route('admin.transactions.create') }}" class="btn btn-app">
                <i class="fa fa-plus"></i> Tambah
            </a>
            <a href="{{ route('admin.transactions.prorate.create') }}" class="btn btn-app">
                <i class="fa fa-plus"></i> Tambah Prorate
            </a>
            <a href="{{ route('admin.transactions.private.create') }}" class="btn btn-app">
                <i class="fa fa-plus"></i> Tambah Private
            </a>
            <a href="{{ route('admin.transactions.cuti.create') }}" class="btn btn-app">
                <i class="fa fa-plus"></i> Tambah Cuti
            </a>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="row">
        <table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0"
               width="100%" id="transaction-table">
            <thead>
                <tr>
                    <th class="text-center" style="width: 5%;">No</th>
                    <th class="text-center" style="width: 10%;">Nomor Transaksi</th>
                    <th class="text-center" style="width: 5%;">Nomor Invoice</th>
                    <th class="text-center" style="width: 5%;">Jenis Transaksi</th>
                    <th class="text-center" style="width: 10%;">Tanggal</th>
                    <th class="text-center" style="width: 10%;">Metode Pembayaran</th>
                    <th class="text-center" style="width: 10%;">Registration Fee</th>
                    <th class="text-center" style="width: 10%;">Total Harga</th>
                    {{--<th class="text-center" style="width: 10%;">Total Diskon</th>--}}
                    <th class="text-center" style="width: 10%;">Total Pembayaran</th>
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
                pageLength: 50,
                ajax: '{!! route('datatables.transactions') !!}',
                order: [ [4, 'desc'] ],
                columns: [
                    { data: 'DT_Row_Index', orderable: false, searchable: false, class: 'text-center'},
                    { data: 'code', name: 'code', class: 'text-center'},
                    { data: 'invoice', name: 'invoice', class: 'text-center'},
                    { data: 'type', name: 'type', class: 'text-center'},
                    { data: 'date', name: 'date', class: 'text-center',
                        render: function ( data, type, row ){
                            if ( type === 'display' || type === 'filter' ){
                                return moment(data).format('DD MMM YYYY');
                            }
                            return data;
                        }
                    },
                    { data: 'payment_method', name: 'payment_method', class: 'text-center'},
                    { data: 'fee', name: 'fee', class: 'text-right',
                        render: function ( data, type, row ){
                            if ( type === 'display' || type === 'filter' ){
                                return data.toLocaleString(
                                    "de-DE",
                                    {minimumFractionDigits: 0}
                                );
                            }
                            return data;
                        }
                    },
                    { data: 'total_price', name: 'total_price', class: 'text-right',
                        render: function ( data, type, row ){
                            if ( type === 'display' || type === 'filter' ){
                                return data.toLocaleString(
                                    "de-DE",
                                    {minimumFractionDigits: 0}
                                );
                            }
                            return data;
                        }
                    },
                    // { data: 'total_discount', name: 'total_discount', class: 'text-right'},
                    { data: 'total_payment', name: 'total_payment', class: 'text-right',
                        render: function ( data, type, row ){
                            if ( type === 'display' || type === 'filter' ){
                                return data.toLocaleString(
                                    "de-DE",
                                    {minimumFractionDigits: 0}
                                );
                            }
                            return data;
                        }
                    },
                    { data: 'action', name:'action', orderable: false, searchable: false, class: 'text-center'}
                ],
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.10.16/i18n/Indonesian-Alternative.json"
                }
            });
        });
    </script>
@endsection
