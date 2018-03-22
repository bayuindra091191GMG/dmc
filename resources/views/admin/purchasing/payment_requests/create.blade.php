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
                    <span class="required">*</span>
                </label>
                <div class="col-md-4 col-sm-4 col-xs-12">
                    <input id="code" type="text" class="form-control col-md-7 col-xs-12 @if($errors->has('code')) parsley-error @endif"
                           name="code" value="{{ $autoNumber }}" disabled>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="auto_number"></label>
                <div class="col-md-4 col-sm-4 col-xs-12">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" class="flat" id="auto_number" name="auto_number" checked="checked"> Auto Number
                        </label>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="type" >
                    Tipe Payment
                    <span class="required">*</span>
                </label>
                <div class="col-md-4 col-sm-4 col-xs-12">
                    <select id="type" name="type" class="form-control col-md-7 col-xs-12 @if($errors->has('type')) parsley-error @endif">
                        <option value="default">Default</option>
                        <option value="dp">Down Payment (DP)</option>
                        <option value="cbd">Cash Before Delivery (CBD)</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="date">
                    Tanggal
                    <span class="required">*</span>
                </label>
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <input id="date" type="text" class="form-control col-md-7 col-xs-12 @if($errors->has('date')) parsley-error @endif"
                           name="date" value="{{ old('date') }}" required>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="bank_name" >
                    Nama Bank
                    <span class="required">*</span>
                </label>
                <div class="col-md-4 col-sm-4 col-xs-12">
                    <input id="bank_name" type="text" class="form-control col-md-7 col-xs-12 @if($errors->has('bank_name')) parsley-error @endif"
                           name="bank_name" />
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="account_no" >
                    Nomor Rekening
                    <span class="required">*</span>
                </label>
                <div class="col-md-4 col-sm-4 col-xs-12">
                    <input id="account_no" type="number" min="0" class="form-control col-md-7 col-xs-12 @if($errors->has('account_no')) parsley-error @endif"
                           name="account_no" />
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="account_name" >
                    Nama Rekening
                    <span class="required">*</span>
                </label>
                <div class="col-md-4 col-sm-4 col-xs-12">
                    <input id="account_name" type="text" class="form-control col-md-7 col-xs-12 @if($errors->has('account_name')) parsley-error @endif"
                           name="account_name" />
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

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">
                    <b>Detil Barang</b>
                </label>
            </div>

            <div class="form-group">
                <div class="col-lg-12 col-md-12 col-xs-12 box-section">
                    <table class="table table-bordered table-hover" id="detail_table">
                        <thead>
                        @if(!empty($purchaseInvoices))
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
                                {{--<th class="text-center" style="width: 10%">--}}
                                    {{--Tindakan--}}
                                {{--</th>--}}
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
                                            {{ $detail->code }}
                                            <input type='hidden' name='item[]' value='{{ $detail->id }}'/>
                                        </td>
                                        <td class='text-center'>
                                            {{ $detail->purchase_order_header->code }}
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
                                        {{--<td>--}}
                                            {{--<?php $itemId = $detail->id. "#". $detail->code ?>--}}
                                            {{--<a class="delete-modal btn btn-danger" data-id="{{ $idx }}" data-item-id="{{ $itemId }}" data-item-text="{{ $detail->code }}">--}}
                                                {{--<span class="glyphicon glyphicon-trash"></span>--}}
                                            {{--</a>--}}
                                        {{--</td>--}}
                                    </tr>
                                @endforeach
                            </tbody>
                        @endif
                        @if(!empty($purchaseOrders))
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

            <div class="form-group">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                    <a class="btn btn-danger" href="{{ route('admin.payment_requests') }}"> Batal</a>
                    <button type="submit" class="btn btn-success"> Simpan</button>
                </div>
            </div>
            {{ Form::close() }}
        </div>
    </div>

    {{--<!-- Modal form to delete a form -->--}}
    {{--<div id="deleteModal" class="modal fade" role="dialog">--}}
        {{--<div class="modal-dialog">--}}
            {{--<div class="modal-content">--}}
                {{--<div class="modal-header">--}}
                    {{--<button type="button" class="close" data-dismiss="modal">×</button>--}}
                    {{--<h4 class="modal-title"></h4>--}}
                {{--</div>--}}
                {{--<div class="modal-body">--}}
                    {{--<h3 class="text-center">Apakah anda yakin ingin menghapus detail ini?</h3>--}}
                    {{--<br />--}}
                    {{--<form class="form-horizontal" role="form">--}}
                        {{--<div class="form-group">--}}
                            {{--<label class="control-label col-sm-2" for="item_delete">Code:</label>--}}
                            {{--<div class="col-sm-10">--}}
                                {{--<input type="text" class="form-control" id="item_delete" disabled>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        {{--<input type="hidden" name="deleted_id"/>--}}
                    {{--</form>--}}
                    {{--<div class="modal-footer">--}}
                        {{--<button type="button" class="btn btn-danger delete" data-dismiss="modal">--}}
                            {{--<span id="" class='glyphicon glyphicon-trash'></span> Hapus--}}
                        {{--</button>--}}
                        {{--<button type="button" class="btn btn-warning" data-dismiss="modal">--}}
                            {{--<span class='glyphicon glyphicon-remove'></span> Batal--}}
                        {{--</button>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}

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

        // Auto Numbering
        $('#auto_number').change(function(){
            if(this.checked){
                $('#code').val('{{ $autoNumber }}');
                $('#code').prop('disabled', true);
            }
            else{
                $('#code').val('');
                $('#code').prop('disabled', false);
            }
        });

//        // Delete detail
//        var deletedId = "0";
//        $(document).on('click', '.delete-modal', function() {
//            $('.modal-title').text('Hapus Detail');
//            deletedId = $(this).data('id');
//            $('#item_delete').val($(this).data('item-text'));
//            $('#qty_delete').val($(this).data('qty'));
//            $('#remark_delete').val($(this).data('remark'));
//            $('#deleteModal').modal('show');
//        });
//        $('.modal-footer').on('click', '.delete', function() {
//            $('.item' + deletedId).remove();
//        });
    </script>
@endsection