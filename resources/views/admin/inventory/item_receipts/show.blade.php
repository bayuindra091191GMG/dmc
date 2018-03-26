@extends('admin.layouts.admin')

@section('title','Data Goods Receipt '. $header->code)

@section('content')
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="navbar-left">
                <a class="btn btn-default" href="{{ route('admin.item_receipts') }}"><i class="fa fa-arrow-circle-o-left fa-2x" aria-hidden="true"></i></a>
            </div>
            <div class="navbar-right">
                <a class="btn btn-default" href="{{ route('admin.item_receipts.print',[ 'material_request' => $header->id]) }}" target="_blank">CETAK</a>
                @if($header->status_id == 3)
                    <a class="btn btn-default" href="{{ route('admin.item_receipts.edit',[ 'material_request' => $header->id]) }}">UBAH</a>
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
                        Nomor Goods Receipt
                    </label>
                    <div class="col-md-6 col-sm-3 col-xs-12">
                        : {{ $header->code }}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 col-sm-3 col-xs-12">
                        Tanggal
                    </label>
                    <div class="col-md-6 col-sm-3 col-xs-12">
                        : {{ \Carbon\Carbon::parse($header->date)->format('d M Y') }}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 col-sm-3 col-xs-12">
                        Nomor PO
                    </label>
                    <div class="col-md-6 col-sm-3 col-xs-12">
                        : <a style="text-decoration: underline;" href="{{ route('admin.purchase_orders.show', ['purchase_order' => $header->purchase_order_id]) }}" target="_blank">{{ $header->purchase_order_header->code }}</a>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 col-sm-3 col-xs-12">
                        Nomor SJ / SPB
                    </label>
                    <div class="col-md-6 col-sm-3 col-xs-12">
                        : {{ $header->delivery_order_vendor }}
                    </div>
                </div>

                <hr>

                <div class="form-group">
                    <div class="col-lg-12 col-md-12 col-xs-12 box-section">
                        <h3 class="text-center">Detil Inventory</h3>
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr >
                                <th class="text-center">
                                    No
                                </th>
                                <th class="text-center">
                                    Kode Inventory
                                </th>
                                <th class="text-center">
                                    Nama Inventory
                                </th>
                                <th class="text-center">
                                    QTY
                                </th>
                                <th class="text-center">
                                    Keterangan
                                </th>
                            </tr>
                            </thead>
                            <tbody>

                            @php($i = 1)
                            @foreach($header->item_receipt_details as $detail)
                                <tr>
                                    <td class="text-center">
                                        {{ $i }}
                                    </td>
                                    <td class="text-center">
                                        {{ $detail->item->code }}
                                    </td>
                                    <td class='text-center'>
                                        {{ $detail->item->name }}
                                    </td>
                                    <td class="text-center">
                                        {{ $detail->quantity }}
                                    </td>
                                    <td class="text-center">
                                        {{ $detail->remarks ?? '-' }}
                                    </td>
                                </tr>
                                @php($i++)
                            @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('styles')
    @parent
    {{ Html::style(mix('assets/admin/css/datatables.css')) }}
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
    <script type="text/javascript">

    </script>
@endsection