@extends('admin.layouts.admin')

@section('title','Data Purchase Request '. $header->code)

@section('content')
    <div class="row">
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
            <div class="navbar-right">
                <a class="btn btn-default" href="{{ route('admin.purchase_requests.edit',[ 'purchase_request' => $header->id]) }}">UBAH</a>
                {{--<a class="btn btn-default" href="{{ route('admin.purchase_requests.edit',[ 'purchase_request' => $header->id]) }}">CETAK</a>--}}
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
            <form class="form-horizontal form-label-left box-section">
                <div class="form-group">
                    <label class="col-md-3 col-sm-3 col-xs-12">
                        Departemen
                        <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        : {{ $header->department->name }}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 col-sm-3 col-xs-12">
                        Unit Alat Berat
                        <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        : {{ $header->machinery->code }}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 col-sm-3 col-xs-12" for="sn_chasis">
                        S/N Chasis
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        : {{ $header->sn_chasis ?? '-' }}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 col-sm-3 col-xs-12" for="sn_engine">
                        S/N Engine
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        : {{ $header->sn_engine ?? '-' }}
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
                                <th clas="text-center">
                                    Satuan (UOM)
                                </th>
                                <th class="text-center">
                                    Jumlah (QTY)
                                </th>
                                <th class="text-center">
                                    Remark
                                </th>
                                <th class="text-center">
                                    Tanggal Penyerahan (Delivery Date)
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
                                        {{ $detail->remark ?? '-' }}
                                    </td>
                                    <td class='field-date'>
                                        {{ \Carbon\Carbon::parse($detail->delivery_date)->format('d M Y') }}
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