@extends('admin.layouts.admin')

@section('title','Data Purchase Request '. $header->code)

@section('content')
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="navbar-left">
                <a class="btn btn-default" href="{{ route('admin.purchase_requests') }}"><i class="fa fa-arrow-circle-o-left fa-2x" aria-hidden="true"></i></a>
            </div>
            <div class="navbar-right">
                @if($permission || $header->status_id != 3)
                    <a class="btn btn-default" href="{{ route('admin.purchase_requests.print',[ 'purchase_request' => $header->id]) }}" target="_blank">CETAK</a>
                @endif

                @if($header->status_id == 3 && $approveOrder)
                    <a class="btn btn-default" href="{{ route('admin.approval_rules.pr_approval',[ 'approval_rule' => $header->id]) }}">APPROVE</a>
                @endif

                @if($header->status_id == 3 && $permission && !$isPoCreated)
                    <a class="btn btn-success" href="{{ route('admin.purchase_orders.create',[ 'pr' => $header->id]) }}">PROSES PO</a>
                @endif

                @if($permission)
                    <a class="btn btn-default" href="{{ route('admin.purchase_requests.edit',[ 'purchase_request' => $header->id]) }}">UBAH</a>
                @endif

                @if($header->status_id == 3)
                    <a class="close-modal btn btn-danger" data-id="{{ $header->id }}">CLOSE</a>
                @endif
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <form class="form-horizontal form-label-left box-section">

                @if(\Illuminate\Support\Facades\Session::has('message'))
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            @include('partials._success')
                        </div>
                    </div>
                @endif

                <div class="form-group">
                    <label class="col-md-3 col-sm-3 col-xs-12">
                        STATUS
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        @if($header->status_id == 3)
                            : <span style="font-weight: bold; color: green;">OPEN</span>
                        @elseif($header->status_id == 4)
                            : <span style="font-weight: bold; color: red;">CLOSED</span>
                        @elseif($header->status_id == 11)
                            : <span style="font-weight: bold; color: red;">CLOSED MANUAL</span>
                        @endif
                    </div>
                </div>

                @if($setting == 1)
                    <div class="form-group">
                        <label class="col-md-3 col-sm-3 col-xs-12">
                            Approved
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            @if($status == 99)
                                : <span style="font-weight: bold; color: green;">Sudah Approved</span>
                            @elseif($status < 99 && $status != 0)
                                : <span style="font-weight: bold; color: #f4bf42;">Approved Sebagian</span>
                            @elseif($status == 0)
                                : <span style="font-weight: bold; color: red;">Belum Diapprove</span>
                            @endif
                        </div>
                    </div>
                @endif

                <div class="form-group">
                    <label class="col-md-3 col-sm-3 col-xs-12">
                        Nomor PR
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
                        Prioritas
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        : {{ $header->priority ?? '-' }}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 col-sm-3 col-xs-12">
                        Batas Jatuh Tempo Prioritas
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        : {{ $priorityLimitDate }} @if($header->priority_expired) <span style="font-weight: bold; color: red;">JATUH TEMPO</span> @endif
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 col-sm-3 col-xs-12">
                        Nomor MR
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">

                        @if($header->material_request_header->type == 1)
                            : <a style="text-decoration: underline;" href="{{ route('admin.material_requests.other.show', ['material_request' => $header->material_request_id]) }}" target="_blank">{{ $header->material_request_header->code }}</a>
                        @elseif($header->material_request_header->type == 2)
                            : <a style="text-decoration: underline;" href="{{ route('admin.material_requests.fuel.show', ['material_request' => $header->material_request_id]) }}" target="_blank">{{ $header->material_request_header->code }}</a>
                        @else
                            : <a style="text-decoration: underline;" href="{{ route('admin.material_requests.service.show', ['material_request' => $header->material_request_id]) }}" target="_blank">{{ $header->material_request_header->code }}</a>
                        @endif

                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 col-sm-3 col-xs-12">
                        Departemen
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        : {{ $header->department->name }}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 col-sm-3 col-xs-12">
                        Unit Alat Berat
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        : {{ $header->machinery->code ?? '-' }}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 col-sm-3 col-xs-12">
                        KM
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        : {{ $header->km ?? '-' }}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 col-sm-3 col-xs-12">
                        KM
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        : {{ $header->hm ?? '-' }}
                    </div>
                </div>

                <hr/>

                <div class="form-group">
                    <div class="col-lg-12 col-md-12 col-xs-12">
                        <h4 class="text-center">Detil Inventory</h4>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                <tr >
                                    <th class="text-center" style="width: 15%">
                                        Kode Inventory
                                    </th>
                                    <th class="text-center" style="width: 20%">
                                        Nama Inventory
                                    </th>
                                    <th class="text-center" style="width: 15%">
                                        Part Number Asli
                                    </th>
                                    <th class="text-center" style="width: 10%">
                                        UOM
                                    </th>
                                    <th class="text-center" style="width: 10%">
                                        QTY
                                    </th>
                                    <th class="text-center" style="width: 10%">
                                        QTY Ter-Invoice
                                    </th>
                                    <th class="text-center" style="width: 20%">
                                        Remark
                                    </th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($header->purchase_request_details as $detail)
                                    <tr>
                                        <td class="text-center">
                                            {{ $detail->item->code }}
                                        </td>
                                        <td class="text-center">
                                            {{ $detail->item->name }}
                                        </td>
                                        <td class="text-center">
                                            {{ $detail->item->part_number ?? '-' }}
                                        </td>
                                        <td class="text-center">
                                            {{ $detail->item->uom }}
                                        </td>
                                        <td class="text-center">
                                            {{ $detail->quantity }}
                                        </td>
                                        <td class="text-center">
                                            {{ $detail->quantity_invoiced }}
                                        </td>
                                        <td>
                                            {{ $detail->remark ?? '-' }}
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

    <div id="closeModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                </div>
                <div class="modal-body">
                    <h3 class="text-center">Apakah anda yakin ingin menutup dokumen PR ini secara manual?</h3>
                    <br />

                    <form role="form">
                        <input type="hidden" id="closed-id" name="closed-id"/>
                        <label for="reason">Alasan Hapus:</label>
                        <textarea id="reason" name="reason" rows="5" class="form-control col-md-7 col-xs-12" style="resize: vertical"></textarea>
                    </form>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-warning" data-dismiss="modal">
                            <span class='glyphicon glyphicon-remove'></span> Tidak
                        </button>
                        <button type="submit" class="btn btn-danger closed">
                            <span class='glyphicon glyphicon-trash'></span> Ya
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