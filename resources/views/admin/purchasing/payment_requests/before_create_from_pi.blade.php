@extends('admin.layouts.admin')

@section('title', 'Pilih Purchase Invoice!')

@section('content')
    {{ Form::open(['route'=>['admin.payment_requests.create_from_pi'],'method' => 'post','class'=>'form-horizontal form-label-left']) }}
        <input type="hidden" name="supplier" id="supplier" value="{{ $supplier->id }}"/>
        <div class="row">
            @if(\Illuminate\Support\Facades\Session::has('error'))
                <div class="alert alert-danger alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                    </button>
                    <strong>{{ \Illuminate\Support\Facades\Session::get('error') }}</strong>
                </div>
            @endif
        </div>
        <div class="row">
            <button type="submit" class="btn btn-success"> Next</button>
            <table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0"
                   width="100%" id="pi-table">
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
                    <th class="text-center">Tanggal</th>
                    <th class="text-center">Perlunasan</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    {{ Form::close() }}
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
            $('#pi-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: {
                    details: {
                        display: $.fn.dataTable.Responsive.display.childRowImmediate,
                        type: ''
                    }
                },
                ajax: {
                    url: '{!! route('datatables.purchase_invoices') !!}',
                    data: {
                        'mode': 'before_create',
                        'supplier': '{{ $supplier->id }}'
                    }
                },
                columns: [
                    { data: 'DT_Row_Index', orderable: false, searchable: false, class: 'text-center' },
                    { data: 'code', name: 'code', class: 'text-center' },
                    { data: 'po_code', name: 'po_code', class: 'text-center' },
                    { data: 'supplier', name: 'supplier', class: 'text-center' },
                    { data: 'total_price', name: 'total_price', class: 'text-right' },
                    { data: 'total_discount', name: 'total_discount', class: 'text-right' },
                    { data: 'delivery_fee', name: 'delivery_fee', class: 'text-right' },
                    { data: 'total_payment', name: 'total_payment', class: 'text-right' },
                    { data: 'created_at', name: 'created_at', class: 'text-center' },
                    { data: 'action', name: 'action', orderable: false, searchable: false, class: 'text-center' }
                ],
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.10.16/i18n/Indonesian-Alternative.json"
                }
            });
        });

        function changeInput(id){
            if(document.getElementById("chk"+id).checked == true){
                $('#' + id).val(id);
            }
            else{
                $('#' + id).val('');
            }
        }
    </script>
@endsection
