@extends('admin.layouts.admin')

@section('title', 'Pilih Purchase Orders!')

@section('content')
    {{ Form::open(['route'=>['admin.payment_requests.create-from-po'],'method' => 'post','class'=>'form-horizontal form-label-left']) }}
        <div class="row">
            <button type="submit" class="btn btn-success"> Next</button>
            <table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0"
                   width="100%" id="po-table">
                <thead>
                <tr>
                    <th class="text-center">No</th>
                    <th class="text-center">Nomor PO</th>
                    <th class="text-center">Nomor PR</th>
                    <th class="text-center">Nama Vendor</th>
                    <th class="text-center">Total Harga</th>
                    <th class="text-center">Total Diskon</th>
                    <th class="text-center">Ongkos Kirim</th>
                    <th class="text-center">Total PO</th>
                    <th class="text-center">Tanggal</th>
                    <th class="text-center">Status</th>
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
                    url: '{!! route('datatables.purchase_orders') !!}',
                    data: {
                        'mode': 'before_create',
                        'supplier': '{{ $supplier->id }}'
                    }
                },
                columns: [
                    { data: 'DT_Row_Index', orderable: false, searchable: false, class: 'text-center' },
                    { data: 'code', name: 'code', class: 'text-center' },
                    { data: 'pr_code', name: 'pr_code', class: 'text-center' },
                    { data: 'supplier', name: 'supplier', class: 'text-center' },
                    { data: 'total_price', name: 'total_price', class: 'text-right' },
                    { data: 'total_discount', name: 'total_discount', class: 'text-right' },
                    { data: 'delivery_fee', name: 'delivery_fee', class: 'text-right' },
                    { data: 'total_payment', name: 'total_payment', class: 'text-right' },
                    { data: 'created_at', name: 'created_at', class: 'text-center' },
                    { data: 'status', name: 'status', class: 'text-center' },
                    { data: 'action', name: 'action', orderable: false, searchable: false, class: 'text-center' }
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
