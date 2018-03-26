@extends('admin.layouts.admin')

@section('title','Data Surat Jalan '. $header->code)

@section('content')
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="navbar-left">
                <a class="btn btn-default" href="{{ route('admin.delivery_orders') }}"><i class="fa fa-arrow-circle-o-left fa-2x" aria-hidden="true"></i></a>
            </div>
            <div class="navbar-right">
                {{--<a class="btn btn-default" href="{{ route('admin.delivery_orders.edit',[ 'delivery_order' => $header->id]) }}">UBAH</a>--}}
                {{--<a class="btn btn-default" href="{{ route('admin.purchase_requests.edit',[ 'purchase_request' => $header->id]) }}">CETAK</a>--}}
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
                        Nomor Surat Jalan
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        : {{ $header->code }}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 col-sm-3 col-xs-12">
                        Tanggal
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        : {{ $date }}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 col-sm-3 col-xs-12">
                        Nomor PR
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        @if(!empty($header->purchase_request_id))
                            : <a style="text-decoration: underline;" href="{{ route('admin.purchase_requests.show', ['purchase_request' => $header->purchase_request_id]) }}">{{ $header->purchase_request_header->code }}</a>
                        @else
                            : -
                        @endif
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 col-sm-3 col-xs-12">
                        Site Keberangkatan
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        : {{ $header->fromWarehouse->site->name }}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 col-sm-3 col-xs-12">
                        Gudang Keberangkatan
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        : {{ $header->fromWarehouse->name }}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 col-sm-3 col-xs-12">
                        Site Tujuan
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        : {{ $header->toWarehouse->site->name }}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 col-sm-3 col-xs-12">
                        Gudang Tujuan
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        : {{ $header->toWarehouse->name }}
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
                        Keterangan
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        : {{ $header->remark ?? '-' }}
                    </div>
                </div>

                <hr/>

                <div class="form-group">
                    <div class="col-lg-12 col-md-12 col-xs-12 column">
                        <h4 class="text-center">Detil Inventory</h4>
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr >
                                <th class="text-center" style="width: 10%">
                                    No
                                </th>
                                <th class="text-center" style="width: 25%">
                                    Kode Inventory
                                </th>
                                <th class="text-center" style="width: 25%">
                                    Nama Inventory
                                </th>
                                <th class="text-center" colspan="2" style="width: 20%">
                                    QTY
                                </th>
                                <th class="text-center" style="width: 20%">
                                    Keterangan
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @php( $idx = 1 )
                            @foreach($header->delivery_order_details as $detail)
                                <tr>
                                    <td class="text-center">
                                        {{ $idx }}
                                    </td>
                                    <td class="text-center">
                                        {{ $detail->item->code }}
                                    </td>
                                    <td class="text-center">
                                        {{ $detail->item->name }}
                                    </td>
                                    <td class="text-center">
                                        {{ $detail->item->uom }}
                                    </td>
                                    <td class="text-center">
                                        {{ $detail->quantity }}
                                    </td>
                                    <td>
                                        {{ $detail->remark ?? '-' }}
                                    </td>
                                </tr>
                                @php( $idx++ )
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