<!DOCTYPE html>
<html lang="en">
<head>
    <title>Purchase Request Report</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container" style="font-family: 'Times New Roman', Times, serif; width: 800px;">
		<span style='position:absolute;z-index:5;margin-left:0px;margin-top:16px;width:204px;
		height:42px'>
			<img width=204 height=42 src="http://bayu159753.com/public/assets/images/image001.png">
		</span>
    <br/>
    <h4 style="text-align: center;"><b><u>Material Request</u></b></h4>
    <h5 style="text-align: center; margin-top: -10px;">(Permintaan Barang)</h5>

    <table>
        <tr>
            <td width="50%">
                No. MR/Date
            </td>
            <td>
                : {{ $materialRequest->code }} {{ $materialRequest->date_string }}
            </td>
        </tr>
        <tr>
            <td width="50%">
                Departemen
            </td>
            <td>
                : {{ $materialRequest->department->name }}
            </td>
        </tr>
        <tr>
            <td width="50%">
                Unit Type
            </td>
            <td>
                : {{ $materialRequest->machinery->machinery_type_name ?? '-' }}
            </td>
        </tr>
        <tr>
            <td width="50%">
                Unit Code
            </td>
            <td>
                : {{ $materialRequest->machinery->code ?? '-' }}
            </td>
        </tr>
        <tr>
            <td width="50%">
                S/N Chasis
            </td>
            <td>
                : {{ $materialRequest->machinery->sn_chasis ?? '-' }}
            </td>
        </tr>
        <tr>
            <td width="50%">
                S/N Engine
            </td>
            <td>
                : {{ $materialRequest->machinery->sn_engine ?? '-' }}
            </td>
        </tr>
        <tr>
            <td width="50%">
                HM
            </td>
            <td>
                : {{ $materialRequest->hm ?? '-' }}
            </td>
        </tr>
        <tr>
            <td width="50%">
                KM
            </td>
            <td>
                : {{ $materialRequest->km ?? '-' }}
            </td>
        </tr>
    </table>
    <br/>

    <table class="table" border="1">
        <thead>
        <tr align="center">
            <td><b>NO<br/>(No)</b></td>
            <td width="35%"><b>DESCRIPTION<br/>(Uraian)</b></td>
            <td width="15%"><b>PART NUMBER<br/>(Nomor Part)</b></td>
            <td><b>UNIT<br/>(Satuan)</b></td>
            <td><b>QTY<br/>(Jumlah)</b></td>
            <td><b>REMARKS<br/>(Keterangan)</b></td>
        </tr>
        </thead>
        <tbody>
        @php($i=1)
        @foreach($materialRequest->material_request_details as $detail)
            <tr align="center">
                <td>{{ $i }}</td>
                <td>{{ $detail->item->name }}</td>
                <td>{{ $detail->item->code }}</td>
                <td>{{ $detail->item->uom }}</td>
                <td>{{ $detail->quantity }}</td>
                <td>{{ $detail->remark ?? '-' }}</td>
            </tr>
            @php($i++)
        @endforeach
        </tbody>
    </table>

    <table class="table" border="1" style="font-weight: bold; text-align: center;">
        <tr>
            <td>Requested by,</td>
            <td>Checked by,</td>
            <td>Checked by,</td>
            <td>Knowledge by,</td>
            <td>Approved by,</td>
            <td>Approved by,</td>
        </tr>
        <tr>
            <td height="80px;">&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
    </table>
</div>

</body>
</html>
