<!DOCTYPE html>
<html lang="en">
<head>
    <title>Request for Quotation</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container" style="width: 680px;">
	<span style='z-index:5;margin-left:0px;margin-top:16px;width:204px;
	height:42px'>
		<img width=204 height=42 src="http://bayu159753.com/public/assets/images/image001.png">
	</span>
    <br/>
    <table width="100%" style="font-size: 10px;">
        <tr>
            <td width="70%"><b>PT. Victor Dua Tiga Mega</b></td>
            <td style="font-size: 18px; text-align: center;"><b>Request for Quotation</b></td>
        </tr>
        <tr>
            <td>Rukan Arta Gading Niaga Blok F No. 19</td>
        </tr>
        <tr>
            <td>Kelapa Gading , Jakarta utara, 14240</td>
        </tr>
        <tr>
            <td>Tlp. 021-45860888, Fax: 021-45849888</td>
        </tr>
        <tr>
            <td><b>NPWP : 02.062.623.0-018.000</b></td>
        </tr>
    </table>
    <br/>

    <table width="100%">
        <tr>
            <td width="55%">To: {{ $quotHeader->supplier->name }}</td>
            <td width="11%">No RFQ</td>
            <td>: {{ $quotHeader->code ?? ''}}</td>
        </tr>
    </table>

    <table width="50%" border="1" style="float: left;">
        <tr>
            <td height="120px">
                Attn:
                <br/>
                Telp: {{ $quotHeader->supplier->phone ?? '-' }}
            </td>
        </tr>
    </table>
    <table width="45%" style="float: right;">
        <tr>
            <td width="25%">Tanggal</td>
            <td>: {{ $quotHeader->date_string }}</td>
        </tr>
        <tr>
            <td>No PR</td>
            <td>: {{ $quotHeader->purchase_request_header->code }}</td>
        </tr>
        <tr>
            <td>Tgl PR</td>
            <td>: {{ $quotHeader->purchase_request_header->date_string }}</td>
        </tr>
        <tr>
            <td>Currency</td>
            <td>: Rupiah</td>
        </tr>
        <tr>
            <td>Kurs</td>
            <td>: -</td>
        </tr>
    </table>

    <br/>
    <br/>
    <br/>
    <br/>
    <br/>
    <br/>
    <br/>
    <h5>Please supply the following items.</h5>
    <table class="table" border="1" width="100%">
        <tr>
            <th class="text-center" width="10%">No</th>
            <th class="text-center" width="15%">Part Number</th>
            <th class="text-center" width="20%">Nama Inventory</th>
            <th class="text-center" width="10%">QTY</th>
            <th class="text-center" width="10%">UOM</th>
            <th class="text-center" width="10%">Harga</th>
            <th class="text-center" width="10%">Diskon</th>
            <th class="text-center" width="15%">Subtotal</th>
        </tr>
        @php($i = 1)
        @foreach($quotHeader->quotation_details as $detail)
            <tr>
                <td class="text-center">{{ $i }}</td>
                <td class="text-center">{{ $detail->item->code }}</td>
                <td class="text-center">{{ $detail->item->name }}</td>
                <td class="text-center">{{ $detail->quantity }}</td>
                <td class="text-center">{{ $detail->item->uom }}</td>
                <td class="text-right">{{ !empty($detail->price) && $detail->price > 0 ? $detail->price_string : '' }}</td>
                <td class="text-center">{{ !empty($detail->discount) && $detail->discount > 0 ? $detail->discount_string : '' }}</td>
                <td class="text-right">{{ !empty($detail->price) && $detail->price > 0 ? $detail->subtotal_string : ''}}</td>
            </tr>
            @php($i++)
        @endforeach
        @if($i < 6)
            @for($a = 0; $a < 6-$i; $a++)
                <tr>
                    <td>&nbsp;</td>
                    <td> </td>
                    <td> </td>
                    <td> </td>
                    <td> </td>
                    <td> </td>
                    <td> </td>
                    <td> </td>
                </tr>
            @endfor
        @endif

        <tr>
            <td colspan="2">Packing</td>
            <td colspan="3">&nbsp;</td>
            <td colspan="2">Sub Total</td>
            <td class="text-right">{{ !empty($quotHeader->total_price) ? $quotHeader->total_price_string : '' }}</td>
        </tr>
        <tr>
            <td colspan="2">Delivery Time</td>
            <td colspan="3">&nbsp;</td>
            <td colspan="2">Delivery Charge</td>
            <td class="text-right">{{ !empty($quotHeader->delivery_fee) ? $quotHeader->delivery_fee_string : '' }}</td>
        </tr>
        <tr>
            <td colspan="2">Delivery Place</td>
            <td colspan="3">&nbsp;</td>
            <td colspan="2">PPh ps 23</td>
            <td class="text-right">{{ !empty($quotHeader->pph_amount) && $quotHeader->pph_amount > 0 ? $quotHeader->pph_string : ''}}</td>
        </tr>
        <tr>
            <td colspan="2">Payment Term</td>
            <td colspan="3">&nbsp;</td>
            <td colspan="2">PPN 10%</td>
            <td class="text-right">{{ !empty($quotHeader->ppn_amount) && $quotHeader->ppn_amount > 0 ? $quotHeader->ppn_string : ''}}</td>
        </tr>
        <tr>
            <td colspan="2">Special Note</td>
            <td colspan="3">&nbsp;</td>
            <td colspan="2">After Tax Total</td>
            <td class="text-right">{{ !empty($quotHeader->total_payment) && $quotHeader->total_payment > 0 ? $quotHeader->total_payment_string : ''}}</td>
        </tr>
        {{--<tr>--}}
        {{--<td colspan="5" rowspan="5" align="left">--}}
        {{--Packing&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <br/><br/>--}}
        {{--Delivery Time&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <br/><br/>--}}
        {{--Delivery Place&nbsp;&nbsp;&nbsp;&nbsp;: <br/><br/>--}}
        {{--Payment Term&nbsp;&nbsp;&nbsp;&nbsp;: <br/><br/>--}}
        {{--Special Note&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:--}}
        {{--</td>--}}
        {{--<td rowspan="4" align="left">--}}
        {{--Sub Total <br/><br/>--}}
        {{--Delivery Charge <br/><br/>--}}
        {{--PPh ps 23<br/><br/>--}}
        {{--PPN 10%--}}
        {{--</td>--}}
        {{--<td align="left" height="10px"><b>9.000.000</b></td>--}}
        {{--</tr>--}}
        {{--<tr>--}}
        {{--<td height="10px">20.000</td>--}}
        {{--</tr>--}}
        {{--<tr>--}}
        {{--<td height="10px">100.000</td>--}}
        {{--</tr>--}}
        {{--<tr>--}}
        {{--<td height="10px">900.000</td>--}}
        {{--</tr>--}}
        {{--<tr>--}}
        {{--<td><b>After Tax Total</b></td>--}}
        {{--<td>10.020.000</td>--}}
        {{--</tr>--}}
    </table>

    <br/>
    {{--<table class="table" border="1" style="font-weight: bold; text-align: center;">--}}
        {{--<tr>--}}
            {{--<td>Prepared by,</td>--}}
            {{--<td>Checked by,</td>--}}
            {{--<td>Approved by,</td>--}}
            {{--<td>Approved by,</td>--}}
        {{--</tr>--}}
        {{--<tr>--}}
            {{--<td height="80px;">&nbsp;</td>--}}
            {{--<td>&nbsp;</td>--}}
            {{--<td>&nbsp;</td>--}}
            {{--<td>&nbsp;</td>--}}
        {{--</tr>--}}
        {{--<tr>--}}
            {{--<td>Ginanjar</td>--}}
            {{--<td>Ginanjar</td>--}}
            {{--<td>Ginanjar</td>--}}
            {{--<td>Ginanjar</td>--}}
        {{--</tr>--}}
        {{--<tr>--}}
            {{--<td>{{ $now }}</td>--}}
            {{--<td>{{ $now }}</td>--}}
            {{--<td>{{ $now }}</td>--}}
            {{--<td>{{ $now }}</td>--}}
        {{--</tr>--}}
    {{--</table>--}}
</div>

</body>
</html>
