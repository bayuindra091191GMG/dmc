<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>receipt</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/3.0.3/normalize.css">
    <style>
        @page { margin: 0 }
        body { margin: 0 }
        .sheet {
            margin: 0;
            overflow: hidden;
            position: relative;
            box-sizing: border-box;
            page-break-after: always;
            font-size: 9px;
        }

        /** Paper sizes **/
        body.A3               .sheet { width: 297mm; height: 419mm }
        body.A3.landscape     .sheet { width: 420mm; height: 296mm }
        body.A4               .sheet { width: 210mm; height: 296mm }
        body.A4.landscape     .sheet { width: 297mm; height: 209mm }
        body.A5               .sheet { width: 148mm; height: 209mm }
        body.A5.landscape     .sheet { width: 210mm; height: 147mm }
        body.letter           .sheet { width: 216mm; height: 279mm }
        body.letter.landscape .sheet { width: 280mm; height: 215mm }
        body.legal            .sheet { width: 216mm; height: 356mm }
        body.legal.landscape  .sheet { width: 357mm; height: 215mm }

        /** Padding area **/
        .sheet.padding-10mm { padding: 10mm }
        .sheet.padding-15mm { padding: 15mm }
        .sheet.padding-20mm { padding: 20mm }
        .sheet.padding-25mm { padding: 25mm }

        /** For screen preview **/
        @media screen {
            body { background: #e0e0e0 }
            .sheet {
                background: white;
                box-shadow: 0 .5mm 2mm rgba(0,0,0,.3);
                margin: 5mm auto;
            }
        }

        /** Fix for Chrome issue #273306 **/
        @media print {
            body.A3.landscape { width: 420mm }
            body.A3, body.A4.landscape { width: 297mm }
            body.A4, body.A5.landscape { width: 210mm }
            body.A5                    { width: 148mm }
            body.letter, body.legal    { width: 216mm }
            body.letter.landscape      { width: 280mm }
            body.legal.landscape       { width: 357mm }
        }

        @page { size: 58mm 80mm }
        body.receipt .sheet { width: 58mm; height: 80mm } /* sheet size */
        @media print { body.receipt { width: 58mm;
            height: 80mm;} } /* fix for Chrome */
    </style>
</head>

<body class="receipt">
<section class="sheet padding-10mm">
    <article style="text-align: center">
        <h3>Diverse Movement Crew</h3>
        <hr/>
        <p><b>Bukti Absen</b></p>

        <table style="text-align: center; width: 100%">
            <tr>
                <td width="30%">Nama</td>
                <td width="10%">:</td>
                <td width="60%">{{ $customerData->name }}</td>
            </tr>
            <tr>
                <td>Jam</td>
                <td>:</td>
                <td>{{ $date }}</td>
            </tr>
            <tr>
                <td>Package</td>
                <td>:</td>
                <td>{{ $scheduleDB->course->name }}</td>
            </tr>
            <tr>
                <td>Pertemuan Ke</td>
                <td>:</td>
                <td>{{ $attendanceCount }}</td>
            </tr>
            <tr>
                <td>Sisa Pertemuan</td>
                <td>:</td>
                @if($scheduleDB->course->type == 4)
                    <td>{{ $remainAttendance }}</td>
                @else
                    <td>{{ $scheduleDB->meeting_amount }}</td>
                @endif
            </tr>
            <tr>
                <td>DMC Point</td>
                <td>:</td>
                <td>{{ $customerData->point }}</td>
            </tr>
            <tr>
                <td>Expired</td>
                <td>:</td>
                <td>{{ $scheduleDB->finish_date_string }}</td>
            </tr>
        </table>
    </article>
</section>
</body>
</html>