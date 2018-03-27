@extends('admin.layouts.admin')

@section('title','Buat Payment Request Baru')

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">

            {{ Form::open(['route'=>['admin.payment_requests.store'],'method' => 'post','class'=>'form-horizontal form-label-left']) }}

            @if(count($errors))
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12 alert alert-danger alert-dismissible fade in" role="alert">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="code">
                    Nomor Payment Request
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="code" type="text" class="form-control col-md-7 col-xs-12 @if($errors->has('code')) parsley-error @endif"
                           name="code" value="{{ $header->code }}" readonly>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="date">
                    Tanggal
                    <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="date" type="text" class="form-control col-md-7 col-xs-12 @if($errors->has('date')) parsley-error @endif"
                           name="date" value="{{ $date }}" required>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="type" >
                    Tipe Payment
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <select id="type" name="type" class="form-control col-md-7 col-xs-12 @if($errors->has('type')) parsley-error @endif">
                        <option value="default" {{ $header->type == 'default' ? 'selected' : '' }}>Default</option>
                        <option value="dp" {{ $header->type == 'dp' ? 'selected' : '' }}>Down Payment (DP)</option>
                        <option value="cbd" {{ $header->type == 'cbd' ? 'selected' : '' }}>Cash Before Delivery (CBD)</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="bank_name" >
                    Nama Bank
                    <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="bank_name" type="text" class="form-control col-md-7 col-xs-12 @if($errors->has('bank_name')) parsley-error @endif"
                           name="bank_name" value="{{ $header->requester_bank_name }}" required />
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="account_no" >
                    Nomor Rekening
                    <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="account_no" type="number" min="0" class="form-control col-md-7 col-xs-12 @if($errors->has('account_no')) parsley-error @endif"
                           name="account_no" value="{{ $header->requester_bank_account }}" required />
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="account_name" >
                    Nama Rekening
                    <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="account_name" type="text" class="form-control col-md-7 col-xs-12 @if($errors->has('account_name')) parsley-error @endif"
                           name="account_name" value="{{ $header->requester_account_name }}" required />
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="note" >
                    Subject
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <textarea id="note" name="note" rows="5" style="resize: vertical;" class="form-control col-md-7 col-xs-12">{{ old('note') }}</textarea>
                </div>
            </div>

            <hr/>

            <div class="form-group">
                <div class="col-lg-12 col-md-12 col-xs-12 box-section">
                    <h3 class="text-center">Detil Inventory</h3>
                    <table class="table table-bordered table-hover" id="detail_table">
                        <thead>
                        @if(!empty($purchaseInvoices) && $purchaseInvoices->count() > 0)
                            <input type="hidden" value="pi" name="flag" />
                            <tr >
                                <th class="text-center">
                                    No
                                </th>
                                <th class="text-center" style="width: 10%">
                                    Nomor Invoice
                                </th>
                                <th class="text-center" style="width: 10%">
                                    Nomor PO
                                </th>
                                <th class="text-center" style="width: 10%">
                                    Nama Vendor
                                </th>
                                <th class="text-center" style="width: 10%">
                                    Total Harga
                                </th>
                                <th class="text-center" style="width: 10%">
                                    Total Diskon
                                </th>
                                <th class="text-center" style="width: 10%">
                                    Ongkos Kirim
                                </th>
                                <th class="text-center" style="width: 10%">
                                    Total Invoice
                                </th>
                                <th class="text-center" style="width: 10%">
                                    Tanggal
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php $idx = 0; ?>

                        @foreach($purchaseInvoices as $detail)
                            <?php $idx++; ?>
                            <tr class='item{{ $idx }}'>
                                <td class='text-center'>
                                    {{ $idx }}
                                </td>
                                <td class='text-center'>
                                    <a style="text-decoration: underline" href="{{ route('admin.purchase_invoices.show', ['purchase_invoice' => $detail->id]) }}" target="_blank">{{ $detail->code }}</a>
                                    <input type='hidden' name='item[]' value='{{ $detail->id }}'/>
                                </td>
                                <td class='text-center'>
                                    <a style="text-decoration: underline" href="{{ route('admin.purchase_orders.show', ['purchase_order' => $detail->purchase_order_id]) }}" target="_blank">{{ $detail->purchase_order_header->code }}</a>
                                </td>
                                <td class='text-center'>
                                    {{ $detail->purchase_order_header->supplier->name }}
                                </td>
                                <td class='text-center'>
                                    {{ $detail->total_price }}
                                </td>
                                <td class='text-center'>
                                    {{ $detail->total_discount }}
                                </td>
                                <td class='text-center'>
                                    {{ $detail->delivery_fee }}
                                </td>
                                <td class='text-center'>
                                    {{ $detail->total_payment }}
                                </td>
                                <td class='text-center'>
                                    {{ $detail->date_string }}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                        @endif
                        @if(!empty($purchaseOrders) && $purchaseOrders->count() > 0)
                            <thead>
                            <input type="hidden" value="po" name="flag" />
                            <tr >
                                <th class="text-center">
                                    No
                                </th>
                                <th class="text-center" style="width: 10%">
                                    Nomor PO
                                </th>
                                <th class="text-center" style="width: 10%">
                                    Nomor PR
                                </th>
                                <th class="text-center" style="width: 10%">
                                    Nama Vendor
                                </th>
                                <th class="text-center" style="width: 10%">
                                    Total Harga
                                </th>
                                <th class="text-center" style="width: 10%">
                                    Total Diskon
                                </th>
                                <th class="text-center" style="width: 10%">
                                    Ongkos Kirim
                                </th>
                                <th class="text-center" style="width: 10%">
                                    Total PO
                                </th>
                                <th class="text-center" style="width: 10%">
                                    Tanggal
                                </th>
                                {{--<th class="text-center" style="width: 10%">--}}
                                {{--Tindakan--}}
                                {{--</th>--}}
                            </tr>
                            </thead>
                            <tbody>
                            <?php $idx = 0; ?>

                            @foreach($purchaseOrders as $detail)
                                <?php $idx++; ?>
                                <tr class='item{{ $idx }}'>
                                    <td class='text-center'>
                                        {{ $idx }}
                                    </td>
                                    <td class='text-center'>
                                        {{ $detail->code }}
                                        <input type='hidden' name='item[]' value='{{ $detail->id }}'/>
                                    </td>
                                    <td class='text-center'>
                                        {{ $detail->purchase_request_header->code }}
                                    </td>
                                    <td class='text-center'>
                                        {{ $detail->supplier->name }}
                                    </td>
                                    <td class='text-center'>
                                        {{ $detail->total_price_string }}
                                    </td>
                                    <td class='text-center'>
                                        {{ $detail->total_discount_string  }}
                                    </td>
                                    <td class='text-center'>
                                        {{ $detail->delivery_fee_string  }}
                                    </td>
                                    <td class='text-center'>
                                        {{ $detail->total_payment_string  }}
                                    </td>
                                    <td class='text-center'>
                                        {{ $detail->date_string }}
                                    </td>
                                    {{--<td>--}}
                                    {{--<?php $itemId = $detail->id. "#". $detail->code ?>--}}
                                    {{--<a class="delete-modal btn btn-danger" data-id="{{ $idx }}" data-item-id="{{ $itemId }}" data-item-text="{{ $detail->code }}">--}}
                                    {{--<span class="glyphicon glyphicon-trash"></span>--}}
                                    {{--</a>--}}
                                    {{--</td>--}}
                                </tr>
                            @endforeach
                        @endif
                    </table>
                </div>
            </div>

            <input id="index_counter" type="hidden" value="{{ $idx }}"/>

            <hr/>

            <div class="form-group">
                <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                    <a class="btn btn-danger" href="{{ route('admin.payment_requests') }}"> Batal</a>
                    <button type="submit" class="btn btn-success"> Simpan</button>
                </div>
            </div>
            {{ Form::close() }}
        </div>
    </div>

    <!-- Modal form to add new detail -->
    <div id="addModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" role="form">
                        <div class="form-group" id="pi_add_form">
                            <label class="control-label col-sm-2" for="pi_add">Nomor PI:</label>
                            <div class="col-sm-10">
                                <select class="form-control" id="pi_add" name="pi_add"></select>
                                <p class="errorPi text-center alert alert-danger hidden"></p>
                            </div>
                        </div>
                        <div class="form-group" id="po_add_form">
                            <label class="control-label col-sm-2" for="po_add">Nomor PO:</label>
                            <div class="col-sm-10">
                                <select class="form-control" id="po_add" name="po_add"></select>
                                <p class="errorPo text-center alert alert-danger hidden"></p>
                            </div>
                        </div>
                    </form>
                    <div class="modal-footer">\
                        <button type="button" class="btn btn-warning" data-dismiss="modal">
                            <span class='glyphicon glyphicon-remove'></span> Batal
                        </button>
                        <button type="button" class="btn btn-success add" data-dismiss="modal">
                            <span id="" class='glyphicon glyphicon-check'></span> Simpan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal form to edit a detail -->
    <div id="editModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" role="form">
                        <div class="form-group" id="pi_edit_form">
                            <label class="control-label col-sm-2" for="pi_edit">Nomor PI:</label>
                            <div class="col-sm-10">
                                <select class="form-control" id="pi_edit" name="pi_edit"></select>
                                <p class="errorPi text-center alert alert-danger hidden"></p>
                            </div>
                        </div>
                        <div class="form-group" id="po_edit_form">
                            <label class="control-label col-sm-2" for="po_edit">Nomor PO:</label>
                            <div class="col-sm-10">
                                <select class="form-control" id="po_edit" name="po_edit"></select>
                                <p class="errorPo text-center alert alert-danger hidden"></p>
                            </div>
                        </div>
                    </form>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-warning" data-dismiss="modal">
                            <span class='glyphicon glyphicon-remove'></span> Batal
                        </button>
                        <button type="button" class="btn btn-primary edit" data-dismiss="modal">
                            <span class='glyphicon glyphicon-check'></span> Simpan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal form to delete a form -->
    <div id="deleteModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">
                    <h3 class="text-center">Apakah anda yakin ingin menghapus detail ini?</h3>
                    <br />
                    <form class="form-horizontal" role="form">
                        <div class="form-group" id="pi_add_form">
                            <label class="control-label col-sm-2" for="pi_delete">Nomor PI:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="pi_delete" readonly>
                            </div>
                        </div>
                        <div class="form-group" id="po_add_form">
                            <label class="control-label col-sm-2" for="po_delete">Nomor PO:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="po_delete" readonly>
                            </div>
                        </div>
                    </form>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-warning" data-dismiss="modal">
                            <span class='glyphicon glyphicon-remove'></span> Batal
                        </button>
                        <button type="button" class="btn btn-danger delete" data-dismiss="modal">
                            <span id="" class='glyphicon glyphicon-trash'></span> Hapus
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('styles')
    @parent
    {{ Html::style(mix('assets/admin/css/select2.css')) }}
    {{ Html::style(mix('assets/admin/css/bootstrap-datetimepicker.css')) }}
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
    {{ Html::script(mix('assets/admin/js/select2.js')) }}
    {{ Html::script(mix('assets/admin/js/autonumeric.js')) }}
    {{ Html::script(mix('assets/admin/js/stringbuilder.js')) }}
    {{ Html::script(mix('assets/admin/js/bootstrap-datetimepicker.js')) }}
    <script type="text/javascript">
        $('#date').datetimepicker({
            format: "DD MMM Y"
        });


    </script>
@endsection