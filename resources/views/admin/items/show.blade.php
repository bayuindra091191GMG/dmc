@extends('admin.layouts.admin')

@section('title','Data Barang '. $selectedItem->name. ' ('. $selectedItem->code.')')

@section('content')
    {{--<div class="row">--}}
        {{--<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">--}}
            {{--<div class="navbar-right">--}}
                {{--<a class="btn btn-default" href="{{ route('admin.items.edit',[ 'quotation' => $selectedItem->id]) }}">UBAH</a>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}
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
                        Kode Item
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        : {{ $selectedItem->code }}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 col-sm-3 col-xs-12">
                        Nama Item
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        : {{ $selectedItem->name }}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 col-sm-3 col-xs-12">
                        Total Stok
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        : {{ $selectedItem->stock  }}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 col-sm-3 col-xs-12">
                        Satuan
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        : {{ $selectedItem->uom }}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 col-sm-3 col-xs-12">
                        Grup Barang
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        : {{ $selectedItem->group->name }}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 col-sm-3 col-xs-12">
                        Tanggal Dibuat
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        : {{ \Carbon\Carbon::parse($selectedItem->created_at)->format('d M Y') }}
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
                                    Nama Gudang
                                </th>
                                <th class="text-center">
                                    Jumlah Stok
                                </th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($selectedItem->item_stocks as $detail)
                                <tr>
                                    <td class='field-item'>
                                        {{ $detail->warehouse->name }}
                                    </td>
                                    <td>
                                        {{ $detail->stock }}
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