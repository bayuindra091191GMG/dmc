@extends('admin.layouts.admin')

@section('title', 'Daftar Purchase Invoice')

@section('content')

    <div class="row">
        @include('partials._success')
        <div class="nav navbar-right">
            <a href="{{ route('admin.purchase_invoices.before_create') }}" class="btn btn-app">
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
                <th class="text-center">Nomor Invoice</th>
                <th class="text-center">Nomor PO</th>
                <th class="text-center">Nama Vendor</th>
                <th class="text-center">Total Harga</th>
                <th class="text-center">Total Diskon</th>
                <th class="text-center">Ongkos Kirim</th>
                <th class="text-center">Total Invoice</th>
                <th class="text-center">Repayment Amount</th>
                <th class="text-center">Tanggal</th>
                <th class="text-center">Tindakan</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>

    <div id="repaymentModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                </div>
                <div class="modal-body">
                    <h3 class="text-center">Apakah anda yakin ingin melakukan pelunasan?</h3>
                    <br />

                    <form role="form" class="form-horizontal form-label-left">
                        <input type="hidden" id="purchase-invoice-id" name="purchase-invoice-id"/>
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="amount">
                            Amount
                            <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input id="repayment-amount" type="text" class="form-control col-md-7 col-xs-12"
                                   name="repayment-amount" required/>
                        </div>
                    </form>

                    <br/>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">
                            <span class='glyphicon glyphicon-remove'></span> Batal
                        </button>
                        <button type="submit" class="btn btn-success submit">
                            <span class='glyphicon glyphicon-send'></span> Submit
                        </button>
                    </div>
                </div>
            </div>
        </div>
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
                ajax: '{!! route('datatables.purchase_invoices') !!}',
                columns: [
                    { data: 'DT_Row_Index', orderable: false, searchable: false, class: 'text-center' },
                    { data: 'code', name: 'code', class: 'text-center' },
                    { data: 'po_code', name: 'po_code', class: 'text-center' },
                    { data: 'supplier', name: 'supplier', class: 'text-center' },
                    { data: 'total_price', name: 'total_price', class: 'text-right' },
                    { data: 'total_discount', name: 'total_discount', class: 'text-right' },
                    { data: 'delivery_fee', name: 'delivery_fee', class: 'text-right' },
                    { data: 'total_payment', name: 'total_payment', class: 'text-right' },
                    { data: 'repayment_amount', name: 'repayment_amount', class: 'text-right' },
                    { data: 'created_at', name: 'created_at', class: 'text-center' },
                    { data: 'action', name: 'action', orderable: false, searchable: false, class: 'text-center' }
                ],
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.10.16/i18n/Indonesian-Alternative.json"
                }
            });
        });

        $(document).on('click', '.delete-modal', function(){
            $('#repaymentModal').modal({
                backdrop: 'static',
                keyboard: false
            });

            $('#purchase-invoice-id').val($(this).data('id'));
        });

        $('.modal-footer').on('click', '.submit', function() {
            $.ajax({
                type: 'POST',
                url: '{{ route('admin.purchase_invoices.repayment') }}',
                data: {
                    '_token': '{{ csrf_token() }}',
                    'id': $('#purchase-invoice-id').val(),
                    'repayment_amount': $('#repayment-amount').val()
                },
                success: function(data) {
                    if ((data.errors)){
                        setTimeout(function () {
                            toastr.error('Gagal menghapus, data sudah terpakai!!', 'Peringatan', {timeOut: 6000, positionClass: "toast-top-center"});
                        }, 500);
                    }
                    else{
                        window.location = '{{ route('admin.purchase_invoices') }}';
                    }
                }
            });
        });
    </script>
@endsection
