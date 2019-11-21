<link href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<!------ Include the above in your HEAD tag ---------->

<style type="text/css">
    .invoice-title h2, .invoice-title h3 {
        display: inline-block;
    }

    .table > tbody > tr > .no-line {
        border-top: none;
    }

    .table > thead > tr > .no-line {
        border-bottom: none;
    }

    .table > tbody > tr > .thick-line {
        border-top: 2px solid;
    }
</style>

<div class="container" style="width: 670px;">
    <div class="row">
        <div class="col-xs-12">
            <div class="invoice-title">
                <h2><img src="{{URL::asset('assets/admin/images/DMC Clean.jpg')}}" width="74px"/> <br/></h2>
                <h5 class="pull-right">
                    <strong>Invoice No.</strong> <br/>{{ $header->invoice_number }} <br>
                    <strong>Invoice Date.</strong> <br/>{{ $header->date_string }}
                </h5>
            </div>
            <hr style="margin-bottom: 10px; margin-top: 10px;">
            <div class="row">
                <div class="col-xs-6">
                    <address>
                        <strong>Address:</strong><br>
                        Pluit Karang Barat Blok O 6 no. 3 & 3A<br>
                        666 78781 & 666 78782<br>
                        0812 8888 258 (WA)
                    </address>
                </div>
                <div class="col-xs-6 text-right">
                    <address>
                        <strong>Billed To:</strong><br>
                        {{ $header->customer->name }}<br>
                        {{ $header->customer->address }}<br>
                        {{ $header->customer->phone }}<br/>
                        {{ $header->payment_method }}
                    </address>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><strong>Invoice summary</strong></h3>
                </div>
                <div class="panel-body">
                    <div>
                        <table class="table table-condensed" style="font-size: 12px;">
                            <thead>
                            <tr>
                                <td class="text-left"><strong>Kelas</strong></td>
                                <td class="text-center"><strong>Trainer</strong></td>
                                <td class="text-center"><strong>Tanggal Berlaku</strong></td>
                                <td class="text-center"><strong>Jumlah {{ $header->type === 2 ? 'Prorate' : 'Pertemuan' }}</strong></td>
                                <td class="text-center"><strong>Harga</strong></td>
                                {{--<td class="text-center"><strong>Diskon</strong></td>--}}
                                <td class="text-center"><strong>Subtotal</strong></td>
                            </tr>
                            </thead>
                            <tbody>
                            <!-- foreach ($order->lineItems as $line) or some such thing here -->
                            @php($total = $header->registration_fee)
                            @foreach($header->transaction_details as $detail)
                                @php( $total += $detail->subtotal )
                                <tr>
                                    <td>{{ $detail->schedule->course->name }}</td>
                                    <td class="text-center">
                                        @if($detail->schedule->course->coach->name == 'Default')
                                            Bebas
                                        @else
                                            {{ $detail->schedule->course->coach->name }}
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $detail->schedule->start_date_number }} - {{ $detail->schedule->finish_date_number }}</td>

                                    @if($header->type === 1)
                                        <td class="text-center">{{ $detail->schedule->course->meeting_amount ?? 'BEBAS' }}</td>
                                    @elseif($header->type === 2)
                                        <td class="text-center">
                                            @if($detail->prorate == 1)
                                                1/4
                                            @elseif($detail->prorate == 2)
                                                1/2
                                            @elseif($detail->prorate == 3)
                                                3/4
                                            @endif
                                        </td>
                                    @else
                                        <td class="text-center">{{ $detail->meeting_amount }}</td>
                                    @endif

                                    <td class="text-right">Rp{{ $header->type === 2 ? $detail->prorate_price_string : $detail->price_string }}</td>
                                    {{--<td class="text-right">Rp{{ $detail->discount_string }}</td>--}}
                                    <td class="text-right">Rp{{ $detail->subtotal_string }}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td>Registration Fee</td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-right">Rp{{ $header->registration_fee_string }}</td>
                            </tr>
                            <tr>
                                <td>Discount</td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-right">-Rp{{ $header->total_discount_string }}</td>
                            </tr>
                            @php( $total -= $header->total_discount )
                            <tr>
                                <td class="thick-line"></td>
                                <td class="thick-line"></td>
                                <td class="thick-line"></td>
                                <td class="thick-line"></td>
                                <td class="thick-line text-center"><strong>Total</strong></td>
                                <td class="thick-line text-right">Rp{{ number_format($total, 0, ",", ".") }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>