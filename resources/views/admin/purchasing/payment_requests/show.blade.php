@extends('admin.layouts.admin')

@section('title','Data Invoice '. $header->code)

@section('content')
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="navbar-left">
                <a class="btn btn-default" href="{{ route('admin.purchase_invoices') }}"><i class="fa fa-arrow-circle-o-left fa-2x" aria-hidden="true"></i></a>
            </div>
            <div class="navbar-right">
                <a class="btn btn-default" href="{{ route('admin.purchase_invoices.edit',[ 'purchase_invoice' => $header->id]) }}">UBAH</a>
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
                        Nomor Payment Request
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        : {{ $header->code }}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 col-sm-3 col-xs-12">
                        Tipe Payment
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        : {{ $header->type }}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 col-sm-3 col-xs-12">
                        Tanggal
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        : {{ $header->date_string }}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 col-sm-3 col-xs-12">
                        Nama Bank
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        : {{ $header->requester_bank_name }}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 col-sm-3 col-xs-12">
                        Nomor Rekening
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        : {{ $header->requester_bank_account }}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 col-sm-3 col-xs-12">
                        Nama Rekening
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        : {{ $header->requester_account_name }}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 col-sm-3 col-xs-12">
                        Nama Rekening
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        : {{ $header->note }}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 col-sm-3 col-xs-12">
                        Total Harga
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        : {{ $header->amount_string }}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 col-sm-3 col-xs-12">
                        PPN
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        : {{ $header->ppn_string ?? '-' }}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 col-sm-3 col-xs-12">
                        PPH 23
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        : {{ $header->pph_23_string ?? '-' }}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 col-sm-3 col-xs-12">
                        Total Harga
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        : {{ $header->total_amount_string }}
                    </div>
                </div>

                <hr>

                @php($i = 0)
                @if($flag == "pi")
                    <div class="form-group">
                        <label class="text-center col-lg-12 col-md-12 col-xs-12">Detil Purchase Invoice</label>
                    </div>

                    <div class="form-group">
                        <div class="col-lg-12 col-md-12 col-xs-12 column">
                            <table class="table table-bordered table-hover">
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
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($purchaseInvoices as $detail)
                                        <tr>
                                            <td>
                                                {{ $i }}
                                            </td>
                                            <td class="text-center">
                                                {{ $detail->purchase_invoice_header->code }}
                                            </td>
                                            <td class="text-center">
                                                {{ $detail->purchase_invoice_header->purchase_order_header->code }}
                                            </td>
                                            <td class="text-center">
                                                {{ $detail->purchase_invoice_header->purchase_order_header->supplier->name }}
                                            </td>
                                            <td class="text-center">
                                                {{ $detail->purchase_invoice_header->total_price_string }}
                                            </td>
                                            <td class="text-center">
                                                {{ $detail->purchase_invoice_header->total_discount_string }}
                                            </td>
                                            <td class="text-center">
                                                {{ $detail->purchase_invoice_header->delivery_fee_string }}
                                            </td>
                                            <td class="text-center">
                                                {{ $detail->purchase_invoice_header->total_payment_string }}
                                            </td>
                                            <td class="text-center">
                                                {{ $detail->purchase_invoice_header->date_string }}
                                            </td>
                                        </tr>
                                        @php($i++)
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                @if($flag == "po")
                    <div class="form-group">
                        <label class="text-center col-lg-12 col-md-12 col-xs-12">Detil Purchase Invoice</label>
                    </div>

                    <div class="form-group">
                        <div class="col-lg-12 col-md-12 col-xs-12 column">
                            <table class="table table-bordered table-hover">
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
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($purchaseOrders as $detail)
                                        <tr>
                                            <td>
                                                {{ $i }}
                                            </td>
                                            <td class="text-center">
                                                {{ $detail->purchase_order_header->code }}
                                            </td>
                                            <td class="text-center">
                                                {{ $detail->purchase_order_header->purchase_request->code }}
                                            </td>
                                            <td class="text-center">
                                                {{ $detail->purchase_order_header->supplier->name }}
                                            </td>
                                            <td class="text-center">
                                                {{ $detail->purchase_order_header->total_price_string }}
                                            </td>
                                            <td class="text-center">
                                                {{ $detail->purchase_order_header->total_discount_string }}
                                            </td>
                                            <td class="text-center">
                                                {{ $detail->purchase_order_header->delivery_fee_string }}
                                            </td>
                                            <td class="text-center">
                                                {{ $detail->purchase_order_header->total_payment_string }}
                                            </td>
                                            <td class="text-center">
                                                {{ $detail->purchase_order_header->date_string }}
                                            </td>
                                            <td class="text-center">
                                                {{ $detail->purchase_order_header->status->description }}
                                            </td>
                                        </tr>
                                        @php($i++)
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
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