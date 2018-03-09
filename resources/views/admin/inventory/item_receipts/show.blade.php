@extends('admin.layouts.admin')

@section('title','Data Item Receipt '. $header->code)

@section('content')
    <div class="row">
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
            <div class="navbar-right">
                <a class="btn btn-default" href="{{ route('admin.item_receipts.edit',[ 'item_receipt' => $header->id]) }}">UBAH</a>
                <a class="btn btn-default" href="{{ route('admin.item_receipts.print',[ 'item_receipts' => $header->id]) }}">CETAK</a>
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
                        Tanggal
                    </label>
                    <div class="col-md-6 col-sm-3 col-xs-12">
                        : {{ \Carbon\Carbon::parse($header->date)->format('d M Y') }}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 col-sm-3 col-xs-12">
                        No Item Receipt
                    </label>
                    <div class="col-md-6 col-sm-3 col-xs-12">
                        : {{ $header->code }}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 col-sm-3 col-xs-12">
                        No. SJ / SPB
                    </label>
                    <div class="col-md-6 col-sm-3 col-xs-12">
                        : {{ $header->delivery_order_header->code }}
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 col-sm-3 col-xs-12">
                        Pengiriman Dari
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        : {{ $header->delivered_from }}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 col-sm-3 col-xs-12">
                        Angkutan
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        : {{ $header->angkutan }}
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
                                    No
                                </th>
                                <th class="text-center">
                                    Kode Barang
                                </th>
                                <th class="text-center">
                                    Nama Barang / Nomor Barang
                                </th>
                                <th class="text-center">
                                    Jumlah
                                </th>
                                <th clas="text-center">
                                    No Purchase Order
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
                                    <td class='field-item text-center'>
                                        {{ $detail->item->name }}
                                    </td>
                                    <td class="text-center">
                                        {{ $detail->quantity }}
                                    </td>
                                    <td class="text-center">
                                        {{ $detail->purchase_order_header->code }}
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