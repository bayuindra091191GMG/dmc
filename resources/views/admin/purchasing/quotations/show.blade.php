@extends('admin.layouts.admin')

@section('title','Data Quotation Vendor '. $header->code)

@section('content')
    <div class="row">
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
            <div class="navbar-right">
                <a class="btn btn-default" href="{{ route('admin.quotations.edit',[ 'quotation' => $header->id]) }}">UBAH</a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
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
                        Nomor Quotation
                        <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        : {{ $header->code }}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 col-sm-3 col-xs-12">
                        Vendor
                        <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        : <a href="{{ route('admin.suppliers.edit', ['supplier' => $header->supplier_id]) }}">{{ $header->vendor->name }}</a>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 col-sm-3 col-xs-12">
                        Total Harga
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        : {{ $header->total_price }}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 col-sm-3 col-xs-12">
                        Total Diskon
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        : {{ $header->total_discount ?? '-' }}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 col-sm-3 col-xs-12">
                        Total Pembayaran
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        : {{ $header->total_payment ?? '-' }}
                    </div>
                </div>

                <hr>

                <div class="form-group">
                    <label class="text-center col-lg-12 col-md-12 col-xs-12">Detil Barang</label>
                </div>

                <div class="form-group">
                    <div class="col-lg-12 col-md-12 col-xs-12 column">
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr >
                                <th class="text-center">
                                    Nomor Part (Part Number)
                                </th>
                                <th class="text-center">
                                    Satuan (UOM)
                                </th>
                                <th class="text-center">
                                    Jumlah (QTY)
                                </th>
                                <th class="text-center">
                                    Harga
                                </th>
                                <th class="text-center">
                                    Diskon
                                </th>
                                <th class="text-center">
                                    Subtotal
                                </th>
                                <th class="text-center">
                                    Remark
                                </th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($header->purchase_request_details as $detail)
                                <tr>
                                    <td class='field-item'>
                                        {{ $detail->item->code }}
                                    </td>
                                    <td>
                                        {{ $detail->item->uom->description }}
                                    </td>
                                    <td>
                                        {{ $detail->quantity }}
                                    </td>
                                    <td>
                                        {{ $detail->price }}
                                    </td>
                                    <td>
                                        {{ $detail->discount }}
                                    </td>
                                    <td>
                                        {{ $detail->subtotal }}
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