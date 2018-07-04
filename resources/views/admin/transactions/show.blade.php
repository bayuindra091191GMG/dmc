@extends('admin.layouts.admin')

{{--@section('title','Data Retur '. $header->code)--}}

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12 text-center">
            <h2>Detil Transaksi {{ $header->code }}</h2>
            <hr/>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="navbar-left">
                <a class="btn btn-default" href="{{ route('admin.transactions') }}"><i class="fa fa-arrow-circle-o-left fa-2x" aria-hidden="true"></i></a>
            </div>
            <div class="navbar-right">
                <a class="btn btn-default" href="{{ route('admin.transactions.print',[ 'transaction' => $header->id]) }}" target="_blank">CETAK</a>
                <a class="btn btn-default" href="{{ route('admin.transactions.edit',[ 'transaction' => $header->id]) }}">UBAH</a>
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
                        Nomor Transaksi
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        : {{ $header->code }}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 col-sm-3 col-xs-12">
                        Nomor Invoice
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        : {{ $header->invoice_number }}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 col-sm-3 col-xs-12">
                        Tanggal
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        : {{ \Carbon\Carbon::parse($header->date)->format('d M Y') }}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 col-sm-3 col-xs-12">
                        Customer
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        : {{ $header->customer->name }}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 col-sm-3 col-xs-12">
                        Registration Fee
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        : Rp {{ $header->registration_fee_string }}
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
                        : {{ !empty($header->total_discount) && $header->total_discount > 0 ? 'Rp '. $header->total_discount_string : '-' }}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 col-sm-3 col-xs-12">
                        Total Pembayaran
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        : Rp {{ $header->total_payment_string }}
                    </div>
                </div>

                <hr>

                <div class="form-group">
                    <div class="col-lg-12 col-md-12 col-xs-12 column">
                        <h4 class="text-center">Detil Inventory</h4>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                <tr >
                                    <th class="text-center" style="width: 15%">
                                        Kelas
                                    </th>
                                    <th class="text-center" style="width: 15%">
                                        Trainer
                                    </th>
                                    <th class="text-center" style="width: 15%">
                                        Hari
                                    </th>
                                    <th class="text-center" style="width: 15%">
                                        Harga
                                    </th>
                                    <th class="text-center" style="width: 10%">
                                        Diskon
                                    </th>
                                    <th class="text-center" style="width: 15%">
                                        Subtotal
                                    </th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($header->transaction_details as $detail)
                                    <tr>
                                        <td class="text-center">
                                            {{ $detail->schedule->course->name }}
                                        </td>
                                        <td class="text-center">
                                            {{ $detail->schedule->course->coach->name }}
                                        </td>
                                        <td class="text-center">
                                            {{ $detail->schedule->day }}
                                        </td>
                                        <td class="text-right">
                                            {{ $detail->price_string }}
                                        </td>
                                        <td class="text-right">
                                            {{ $detail->discount_string }}
                                        </td>
                                        <td class="text-right">
                                            {{ $detail->subtotal_string }}
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
@endsection