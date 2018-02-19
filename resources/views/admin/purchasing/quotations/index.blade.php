@extends('admin.layouts.admin')

@section('title', 'Daftar Quotation Vendor')

@section('content')

    <div class="row">
        @include('partials._success')
        <div class="nav navbar-right">
            <a href="{{ route('admin.quotations.create') }}" class="btn btn-app">
                <i class="fa fa-plus"></i> Tambah
            </a>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="row">
        <table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0"
               width="100%" id="quot-table">
            <thead>
            <tr>
                <th>No</th>
                <th>Nomor Quotation</th>
                <th>Nomor PR</th>
                <th>Vendor</th>
                <th>Total Harga</th>
                <th>Diskon</th>
                <th>Total Pembayaran</th>
                <th>Tanggal Dibuat</th>
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
            $('#quot-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('datatables.quotations') !!}',
                columns: [
                    { data: 'DT_Row_Index', orderable: false, searchable: false},
                    { data: 'code', name: 'code' },
                    { data: 'pr_code', name: 'pr_code' },
                    { data: 'vendor', name: 'vendor' },
                    { data: 'total_price', name: 'total_price' },
                    { data: 'discount', name: 'discount' },
                    { data: 'total_payment', name: 'total_payment' },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'action', name: 'action' }
                ],
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.10.16/i18n/Indonesian-Alternative.json"
                }
            });
        });
    </script>
@endsection
