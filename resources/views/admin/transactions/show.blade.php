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
                @if($header->type === 1)
                    <a class="btn btn-default" href="{{ route('admin.transactions.edit',[ 'transaction' => $header->id]) }}">UBAH</a>
                @elseif($header->type === 2)
                    <a class="btn btn-default" href="{{ route('admin.transactions.prorate.edit',[ 'prorate' => $header->id]) }}">UBAH</a>
                @else
                    <a class="btn btn-default" href="{{ route('admin.transactions.private.edit',[ 'private' => $header->id]) }}">UBAH</a>
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
                        Nomor Transaksi
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        : {{ $header->code }}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 col-sm-3 col-xs-12">
                        Jenis Transaksi
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        :
                        @if($header->type === 1)
                            NORMAL
                        @elseif($header->type === 2)
                            PRORATE
                        @elseif($header->type === 3)
                            PRIVATE
                        @else
                            CUTI
                        @endif
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
                        Metode Pembayaran
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        : {{ $header->payment_method }}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 col-sm-3 col-xs-12">
                        Student
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


                @if($header->type === 2)
                    <div class="form-group">
                        <label class="col-md-3 col-sm-3 col-xs-12">
                            Total harga Prorate
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            : Rp {{ $header->total_prorate_price_string }}
                        </div>
                    </div>
                @else
                    <div class="form-group">
                        <label class="col-md-3 col-sm-3 col-xs-12">
                            Total Harga
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            : Rp {{ $header->total_price_string }}
                        </div>
                    </div>
                @endif

                {{--<div class="form-group">--}}
                    {{--<label class="col-md-3 col-sm-3 col-xs-12">--}}
                        {{--Total Diskon--}}
                    {{--</label>--}}
                    {{--<div class="col-md-6 col-sm-6 col-xs-12">--}}
                        {{--: {{ !empty($header->total_discount) && $header->total_discount > 0 ? 'Rp '. $header->total_discount_string : '-' }}--}}
                    {{--</div>--}}
                {{--</div>--}}

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
                        <h4 class="text-center">Detil Transaksi</h4>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                <tr >
                                    @if($header->type === 1)
                                        <th class="text-center">
                                            Kelas
                                        </th>
                                        <th class="text-center">
                                            Trainer
                                        </th>
                                        <th class="text-center">
                                            Hari
                                        </th>
                                        <th class="text-center" style="width: 10%">
                                            Jumlah Pertemuan
                                        </th>
                                        <th class="text-center" style="width: 15%">
                                            Harga
                                        </th>
                                        {{--<th class="text-center" style="width: 10%">--}}
                                            {{--Diskon--}}
                                        {{--</th>--}}
                                        <th class="text-center" style="width: 15%">
                                            Subtotal
                                        </th>
                                    @elseif($header->type === 2)
                                        <th class="text-center" style="width: 15%">
                                            Kelas
                                        </th>
                                        <th class="text-center" style="width: 15%">
                                            Trainer
                                        </th>
                                        <th class="text-center" style="width: 10%">
                                            Hari
                                        </th>
                                        <th class="text-center" style="width: 15%">
                                            Prorate
                                        </th>
                                        <th class="text-center" style="width: 15%">
                                            Harga Prorate
                                        </th>
                                        {{--<th class="text-center" style="width: 10%">--}}
                                            {{--Diskon--}}
                                        {{--</th>--}}
                                        <th class="text-center" style="width: 15%">
                                            Subtotal
                                        </th>
                                    @elseif($header->type ===  3)
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
                                            Jumlah Pertemuan
                                        </th>
                                        <th class="text-center" style="width: 15%">
                                            Subtotal
                                        </th>
                                    @else
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
                                            Lama Cuti
                                        </th>
                                        <th class="text-center" style="width: 15%">
                                            Subtotal
                                        </th>
                                    @endif
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($header->transaction_details as $detail)
                                    <tr>
                                        @if($header->type === 1)
                                            <td class="text-center">
                                                {{ $detail->schedule->course->name }}
                                            </td>
                                            <td class="text-center">
                                                {{ $detail->schedule->course->coach_id === 0 ? 'Tidak Ada Coach' : $detail->schedule->course->coach->name }}
                                            </td>
                                            <td class="text-center">
                                                {{ $detail->schedule->day }}
                                            </td>
                                            <td class="text-center">
                                                {{ $detail->schedule->course->meeting_amount }}
                                            </td>
                                            <td class="text-right">
                                                {{ $detail->price_string }}
                                            </td>
                                            {{--<td class="text-right">--}}
                                                {{--{{ $detail->discount_string ?? '0' }}--}}
                                            {{--</td>--}}
                                            <td class="text-right">
                                                {{ $detail->subtotal_string }}
                                            </td>
                                        @elseif($header->type === 2)
                                            <td class="text-center">
                                                {{ $detail->schedule->course->name }}
                                            </td>
                                            <td class="text-center">
                                                {{ $detail->schedule->course->coach->name }}
                                            </td>
                                            <td class="text-center">
                                                {{ $detail->schedule->day }}
                                            </td>
                                            <td class="text-center">
                                                {{ $detail->prorate }} Pertemuan
                                            </td>
                                            <td class="text-right">
                                                {{ $detail->prorate_price_string }}
                                            </td>
                                            {{--<td class="text-right">--}}
                                                {{--{{ $detail->discount_string ?? '0' }}--}}
                                            {{--</td>--}}
                                            <td class="text-right">
                                                {{ $detail->subtotal_string }}
                                            </td>
                                        @elseif($header->type === 3)
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
                                            <td class="text-center">
                                                {{ $detail->meeting_amount }}
                                            </td>
                                            <td class="text-right">
                                                {{ $detail->subtotal_string }}
                                            </td>
                                        @else
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
                                            <td class="text-center">
                                                {{ $detail->month_amount }} Bulan
                                            </td>
                                            <td class="text-right">
                                                {{ $detail->subtotal_string }}
                                            </td>
                                        @endif
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