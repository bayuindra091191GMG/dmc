@extends('admin.layouts.admin')

@section('title', 'Pilih Purchase Invoice!')

@section('content')
    {{ Form::open(['route'=>['admin.payment_requests.create-from-pi'],'method' => 'post','class'=>'form-horizontal form-label-left']) }}
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
                    <th class="text-center">Tindakan</th>
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
                ajax: {
                    url: '{!! route('datatables.purchase_invoices') !!}',
                    data: {
                        'mode': 'before_create',
                        'supplier': '{{ $supplier->id }}'
                    }
                },
                columns: [
                    { data: 'DT_Row_Index', orderable: false, searchable: false},
                    { data: 'code', name: 'code' },
                    { data: 'po_code', name: 'po_code' },
                    { data: 'supplier', name: 'supplier' },
                    { data: 'total_price', name: 'total_price' },
                    { data: 'total_discount', name: 'total_discount' },
                    { data: 'delivery_fee', name: 'delivery_fee' },
                    { data: 'total_payment', name: 'total_payment' },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.10.16/i18n/Indonesian-Alternative.json"
                }
            });
        });

        function changeInput(id){
            if(document.getElementById("chk"+id).checked == true){
                document.getElementById(id).disabled = false;
            }
            else{
                document.getElementById(id).disabled = true;
            }
        }
    </script>
@endsection
