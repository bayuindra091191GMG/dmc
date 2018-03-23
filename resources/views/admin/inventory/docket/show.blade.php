@extends('admin.layouts.admin')

@section('title','Data Issued Docket '. $header->code)

@section('content')
    <div class="row">
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
            <div class="navbar-right">
                <a class="btn btn-default" href="{{ route('admin.issued_dockets.edit',[ 'issued_docket' => $header->id]) }}">UBAH</a>
                <a class="btn btn-default" href="{{ route('admin.issued_dockets.print',[ 'issued_docket' => $header->id]) }}">CETAK</a>
                <a class="btn btn-default" href="{{ route('admin.issued_dockets.download',[ 'issued_docket' => $header->id]) }}">DOWNLOAD</a>
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
                        Hari/Tgl/Bln/Thn
                    </label>
                    <div class="col-md-6 col-sm-3 col-xs-12">
                        : {{ \Carbon\Carbon::parse($header->date)->format('d M Y') }}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 col-sm-3 col-xs-12">
                        No Issued Docket
                    </label>
                    <div class="col-md-6 col-sm-3 col-xs-12">
                        : {{ $header->code }}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 col-sm-3 col-xs-12">
                        No PR
                    </label>
                    <div class="col-md-6 col-sm-3 col-xs-12">
                        : {{ $header->purchase_request_header->code }}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 col-sm-3 col-xs-12">
                        Unit Alat Berat
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        : {{ $header->machinery->code }}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 col-sm-3 col-xs-12">
                        HM
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        : {{ $header->hm ?? '-' }}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 col-sm-3 col-xs-12">
                        KM
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        : {{ $header->km ?? '-'}}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 col-sm-3 col-xs-12">
                        Departemen
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        : {{ $header->department->name }}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 col-sm-3 col-xs-12" for="sn_chasis">
                        Divisi
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        : {{ $header->division ?? '-' }}
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
                            <tr>
                                <th class="text-center">
                                    Nama Barang
                                </th>
                                <th class="text-center">
                                    Nomor Part (Part Number)
                                </th>
                                <th clas="text-center">
                                    Satuan (UOM)
                                </th>
                                <th class="text-center">
                                    Jumlah (QTY)
                                </th>
                                <th class="text-center">
                                    Remark
                                </th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($header->issued_docket_details as $detail)
                                <tr>
                                    <td>
                                        {{ $detail->item->name }}
                                    </td>
                                    <td class='field-item'>
                                        {{ $detail->item->code }}
                                    </td>
                                    <td>
                                        {{ $detail->item->uom }}
                                    </td>
                                    <td>
                                        {{ $detail->quantity }}
                                    </td>
                                    <td>
                                        {{ $detail->remarks ?? '-' }}
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