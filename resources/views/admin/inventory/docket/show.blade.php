@extends('admin.layouts.admin')

@section('title','Data Issued Docket '. $header->code)

@section('content')
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="navbar-left">
                <a class="btn btn-default" href="{{ route('admin.issued_dockets') }}"><i class="fa fa-arrow-circle-o-left fa-2x" aria-hidden="true"></i></a>
            </div>
            <div class="navbar-right">
                <a class="btn btn-default" href="{{ route('admin.issued_dockets.print',[ 'material_request' => $header->id]) }}" target="_blank">CETAK</a>
                @if($header->status_id == 3)
                    <a class="btn btn-default" href="{{ route('admin.issued_dockets.edit',[ 'material_request' => $header->id]) }}">UBAH</a>
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
                        Nomor Issued Docket
                    </label>
                    <div class="col-md-6 col-sm-3 col-xs-12">
                        : {{ $header->code }}
                    </div>
                </div>

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
                        Nomor MR
                    </label>
                    <div class="col-md-6 col-sm-3 col-xs-12">
                        @if($header->material_request_header->type == 1)
                            : <a style="text-decoration: underline;" href="{{ route('admin.material_requests.other.show', ['material_request' => $header->material_request_id]) }}" target="_blank">{{ $header->material_request_header->code }}</a>
                        @elseif($header->material_request_header->type == 2)
                            : <a style="text-decoration: underline;" href="{{ route('admin.material_requests.fuel.show', ['material_request' => $header->material_request_id]) }}" target="_blank">{{ $header->material_request_header->code }}</a>
                        @else
                            : <a style="text-decoration: underline;" href="{{ route('admin.material_requests.service.show', ['material_request' => $header->material_request_id]) }}" target="_blank">{{ $header->material_request_header->code }}</a>
                        @endif
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 col-sm-3 col-xs-12">
                        Unit Alat Berat
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        : {{ $header->machinery->code ?? '-' }}
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

                <hr>

                <div class="form-group">
                    <div class="col-lg-12 col-md-12 col-xs-12">
                        <h4 class="text-center">Detil Inventory</h4>
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th class="text-center">
                                    Nama Inventory
                                </th>
                                <th class="text-center">
                                    Kode Inventory
                                </th>
                                <th class="text-center">
                                    UOM
                                </th>
                                <th class="text-center">
                                    QTY
                                </th>
                                <th class="text-center">
                                    Remark
                                </th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($header->issued_docket_details as $detail)
                                <tr>
                                    <td class="text-center">
                                        {{ $detail->item->name }}
                                    </td>
                                    <td class="text-center">
                                        {{ $detail->item->code }}
                                    </td>
                                    <td class="text-center">
                                        {{ $detail->item->uom }}
                                    </td>
                                    <td class="text-center">
                                        {{ $detail->quantity }}
                                    </td>
                                    <td class="text-center">
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