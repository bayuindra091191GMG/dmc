@extends('admin.layouts.admin')

@section('title', 'Daftar Purchase Order')

@section('content')

    <div class="row">
        @include('partials._success')
        <div class="nav navbar-right">
            <a href="{{ route('admin.purchase_requests.create') }}" class="btn btn-app">
                <i class="fa fa-plus"></i> Tambah
            </a>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="row">
        <table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0"
               width="100%" id="pr-table">
            <thead>
            <tr>
                <th class="text-center">No</th>
                <th class="text-center">Nomor PO</th>
                <th class="text-center">Nomor PR</th>
                <th class="text-center">Nama Vendor</th>
                <th class="text-center">Total Harga</th>
                <th class="text-center">Total Diskon</th>
                <th class="text-center">Ongkos Kirim</th>
                <th class="text-center">Total Pembayaran</th>
                <th class="text-center">Tanggal Dibuat</th>
                <th class="text-center">Status</th>
                <th class="text-center">Tindakan</th>
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
            $('#pr-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('datatables.purchase_orders') !!}',
                columns: [
                    { data: 'DT_Row_Index', orderable: false, searchable: false},
                    { data: 'code', name: 'code' },
                    { data: 'pr_code', name: 'pr_code' },
                    { data: 'supplier', name: 'supplier' },
                    { data: 'total_price', name: 'total_price' },
                    { data: 'total_discount', name: 'total_discount' },
                    { data: 'delivery_fee', name: 'delivery_fee' },
                    { data: 'total_payment', name: 'total_payment' },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'status', name: 'status' },
                    { data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.10.16/i18n/Indonesian-Alternative.json"
                }
            });
        });
    </script>
@endsection
