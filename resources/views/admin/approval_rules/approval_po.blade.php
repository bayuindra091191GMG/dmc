@extends('admin.layouts.admin')

@section('title','Approve Purchase Request '. $header->code)

@section('content')
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="navbar-left">
                <a class="btn btn-default" href="{{ route('admin.purchase_orders.show', ['purchase_order' => $header->id]) }}"><i class="fa fa-arrow-circle-o-left fa-2x" aria-hidden="true"></i></a>
            </div>
            <div class="navbar-right">
                @if($header->is_approved == null || $header->is_approved == 0)
                    {{--<a class="btn btn-success" href="{{ route('admin.approval_rules.approve_pr',[ 'approval_rule' => $header->id]) }}">APPROVE</a>--}}
                    <button type="button" class="btn btn-success btn-lg" data-toggle="modal" data-target="#approveModal">Approve</button>
                @endif
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <form class="form-horizontal form-label-left box-section">

                @if(count($errors))
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="alert alert-danger alert-dismissible fade in" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                                <ul>
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                @if(\Illuminate\Support\Facades\Session::has('message'))
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            @include('partials._success')
                        </div>
                    </div>
                @endif

                {{--<div class="form-group">--}}
                    {{--<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">--}}
                    {{--</label>--}}
                    {{--<div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">--}}
                        {{--<div class="alert alert-danger  fade in" role="alert">--}}
                            {{--User yang harus melakukan approval pada dokumen ini adalah--}}
                            {{--<br/>--}}
                            {{--<ul>--}}
                                {{--@foreach($approvalData as $data)--}}
                                    {{--<li>{{ $data->user->name }}</li>--}}
                                {{--@endforeach--}}
                            {{--</ul>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}

                <div class="form-group">
                    <label class="col-md-3 col-sm-3 col-xs-12">
                        Approved
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        @if($status == 99)
                            :
                            <span style="font-weight: bold; color: green;">
                                @foreach($approvalData as $data)
                                    {{ $data->user->name }}
                                @endforeach
                            </span>
                        @elseif($status < 99 && $status != 0)
                            :
                            <span style="font-weight: bold; color: #f4bf42;">
                                @foreach($approvalData as $data)
                                    {{ $data->user->name }}
                                @endforeach
                            </span>
                        @elseif($status == 0)
                            : <span style="font-weight: bold; color: red;">Belum Diapprove</span>
                        @endif
                    </div>
                </div>

                    <div class="form-group">
                        <label class="col-md-3 col-sm-3 col-xs-12">
                            Nomor PO
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            : {{ $header->code }}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 col-sm-3 col-xs-12">
                            Tanggal
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            : {{ $date }}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 col-sm-3 col-xs-12">
                            Nomor PR
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            : <a style="text-decoration: underline;" href="{{ route('admin.purchase_requests.show', ['purchase_request' => $header->purchase_request_id]) }}" target="_blank">{{ $header->purchase_request_header->code }}</a>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 col-sm-3 col-xs-12">
                            Vendor
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            : <a style="text-decoration: underline;" href="{{ route('admin.suppliers.edit', ['supplier' => $header->supplier_id]) }}">{{ $header->supplier->name }}</a>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 col-sm-3 col-xs-12">
                            Total Harga
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            : Rp {{ $header->total_price_string }}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 col-sm-3 col-xs-12">
                            Total Diskon
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            : {{ !empty($header->total_discount) ? 'Rp '. $header->total_discount_string : '-' }}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 col-sm-3 col-xs-12">
                            Ongkos Kirim
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            : {{ !empty($header->delivery_fee) && $header->delivery_fee > 0 ? 'Rp '. $header->delivery_fee_string : '-' }}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 col-sm-3 col-xs-12">
                            PPN {{ !empty($header->ppn_percent) && $header->ppn_percent > 0 ? $header->ppn_percent. '%' : '' }}
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            : {{ !empty($header->ppn_percent) && $header->ppn_percent > 0 ? 'Rp '. $header->ppn_string : '-' }}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 col-sm-3 col-xs-12">
                            PPh
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            : {{ !empty($header->pph_amount) && $header->pph_amount > 0 ? 'Rp '. $header->pph_string : '-' }}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 col-sm-3 col-xs-12">
                            Total PO
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            : Rp {{ $header->total_payment_string }}
                        </div>
                    </div>

                <hr/>

                    <div class="form-group">
                        <div class="col-lg-12 col-md-12 col-xs-12 column">
                            <h4 class="text-center">Detil Inventory</h4>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                    <tr >
                                        <th class="text-center" style="width: 15%;">
                                            Kode Inventory
                                        </th>
                                        <th class="text-center" style="width: 15%;">
                                            Nama Inventory
                                        </th>
                                        <th class="text-center" style="width: 10%;">
                                            UOM
                                        </th>
                                        <th class="text-center" style="width: 10%;">
                                            QTY
                                        </th>
                                        <th class="text-center" style="width: 10%;">
                                            Harga
                                        </th>
                                        <th class="text-center" style="width: 10%;">
                                            Diskon
                                        </th>
                                        <th class="text-center" style="width: 10%;">
                                            Subtotal
                                        </th>
                                        <th class="text-center" style="width: 20%;">
                                            Remark
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @foreach($header->purchase_order_details as $detail)
                                        <tr>
                                            <td class="text-center">
                                                {{ $detail->item->code }}
                                            </td>
                                            <td class="text-center">
                                                {{ $detail->item->name }}
                                            </td>
                                            <td class="text-center">
                                                {{ $detail->item->uom }}
                                            </td>
                                            <td class="text-center">
                                                {{ $detail->quantity }}
                                            </td>
                                            <td class="text-right">
                                                {{ $detail->price_string }}
                                            </td>
                                            <td class="text-center">
                                                {{ $detail->discount_string }}
                                            </td>
                                            <td class="text-right">
                                                {{ $detail->subtotal_string }}
                                            </td>
                                            <td>
                                                {{ $detail->remark }}
                                            </td>
                                        </tr>
                                    @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
            </form>
        </div>
    </div>

    <div id="approveModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                </div>
                <div class="modal-body">
                    <h3 class="text-center">Apakah anda yakin ingin approve?</h3>
                    <br />

                    <form role="form">
                        <input type="hidden" id="approve-id" name="approve-id"/>
                    </form>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">
                            <span class='glyphicon glyphicon-remove'></span> Tidak
                        </button>
                        <a class="btn btn-warning delete" href="{{ route('admin.approval_rules.approve_po',[ 'approval_rule' => $header->id]) }}"><span class='glyphicon glyphicon-ok'></span> Ya</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    @parent
    {{ Html::style(mix('assets/admin/css/datatables.css')) }}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
    <style>
        .box-section{
            background-color: #ffffff;
            border: 1px solid #ccc;
            border-radius: 2px;
            padding: 10px;
        }
    </style>
@endsection

@section('scripts')
    @parent
    {{ Html::script(mix('assets/admin/js/datatables.js')) }}
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script type="text/javascript">
        $(document).on('click', '.close-modal', function(){
            $('#closeModal').modal({
                backdrop: 'static',
                keyboard: false
            });

            $('#closed-id').val($(this).data('id'));
        });

        $('.modal-footer').on('click', '.closed', function() {
            $.ajax({
                type: 'POST',
                url: '{{ route('admin.purchase_requests.close') }}',
                data: {
                    '_token': '{{ csrf_token() }}',
                    'id': $('#closed-id').val(),
                    'reason': $('#reason').val()
                },
                success: function(data) {
                    if ((data.errors)){
                        setTimeout(function () {
                            toastr.error('Gagal menutup PR!!', 'Peringatan', {timeOut: 6000, positionClass: "toast-top-center"});
                        }, 500);
                    }
                    else{
                        window.location.reload();
                    }
                }
            });
        });
    </script>
@endsection