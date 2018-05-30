<!DOCTYPE html>
<html lang="en">
<head>
    <title>Purchase Order Report</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

</head>
<body>

<div class="container">
    <h3>Laporan Transaksi</h3>
    <span style="font-size: 10px;">Tanggal: {{ $start_date }} - {{ $finish_date }}</span><br/>
    <table class="table" style="font-size: 10px;">
        <thead>
        <tr>
            <th class="text-center" style="width: 10%;">Kelas</th>
            <th class="text-center" style="width: 20%;">Trainer</th>
            <th class="text-center" style="width: 20%;">Tanggal Berlaku</th>
            <th class="text-center" style="width: 10%;">Harga</th>
            <th class="text-center" style="width: 10%;">Diskon</th>
            <th class="text-right" style="width: 10%;">Subtotal</th>
            <th></th>
        </tr>
        {{--<tr>--}}
        {{--<th class="text-center">No</th>--}}
        {{--<th class="text-center">Nomor PO</th>--}}
        {{--<th class="text-center">Nomor PR</th>--}}
        {{--<th class="text-center">Nomor RFQ</th>--}}
        {{--<th class="text-center">Vendor</th>--}}
        {{--<th class="text-center">Status</th>--}}
        {{--<th class="text-center">Tanggal</th>--}}
        {{--<th class="text-center">Tanggal Closed</th>--}}
        {{--<th class="text-center">Total PO</th>--}}
        {{--</tr>--}}
        </thead>
        <tbody>
        @php($i=1)
        @foreach($header as $item)
            <tr>
                <td colspan="7"><b>{{ $item->code }} - {{ $item->date_string }} - {{ $item->invoice_number }} - Dibuat Oleh: {{ $item->createdBy->email }}</b></td>
            </tr>
            @foreach($item->transaction_details as $detail)
                <tr>
                    <td class="text-center">{{ $detail->schedule->course->name }}</td>
                    <td class="text-center">{{ $detail->schedule->course->coach->name }}</td>
                    <td class="text-center">{{ $detail->schedule->start_date_string }} - {{ $detail->schedule->finish_date_string }}</td>
                    <td class="text-center">Rp{{ $detail->price_string }}</td>
                    <td class="text-center">Rp{{ $detail->discount_string }}</td>
                    <td class="text-right">Rp{{ $detail->subtotal_string }}</td>
                    <td></td>
                </tr>
            @endforeach

            <tr>
                <td colspan="4"></td>
                <td class="text-right">Total Transaction:</td>
                <td class="text-right">Rp{{ $item->total_payment_string }}</td>
                <td></td>
            </tr>
            <tr>
                <td colspan="6"></td>
                <td class="text-right">Rp{{ $item->total_payment_string }}</td>
            </tr>
        @endforeach
        <tr>
            <td colspan="6" class="text-right">
                <b>Total Semua Transaksi</b>
            </td>
            <td class="text-right">
                Rp{{ $total }}
            </td>
        </tr>
        </tbody>
    </table>
</div>
<script type="text/php">
    if ( isset($pdf) ) {
        // OLD
        // $font = Font_Metrics::get_font("helvetica", "bold");
        // $pdf->page_text(72, 18, "{PAGE_NUM} of {PAGE_COUNT}", $font, 6, array(255,0,0));
        // v.0.7.0 and greater
        $x = 520;
        $y = 800;
        $text = "{PAGE_NUM} of {PAGE_COUNT}";
        $font = $fontMetrics->get_font("helvetica", "bold");
        $size = 8;
        $color = array(0,0,0);
        $word_space = 0.0;  //  default
        $char_space = 0.0;  //  default
        $angle = 0.0;   //  default
        $pdf->page_text($x, $y, $text, $font, $size, $color, $word_space, $char_space, $angle);
    }
</script>
</body>
</html>
