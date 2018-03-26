@extends('admin.layouts.admin')

@section('title','Buat Purchase Order Baru')

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">

            {{ Form::open(['route'=>['admin.purchase_orders.store'],'method' => 'post','class'=>'form-horizontal form-label-left']) }}

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
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="po_code">
                    Nomor PO
                    <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="po_code" type="text" class="form-control col-md-7 col-xs-12 @if($errors->has('po_code')) parsley-error @endif"
                           name="po_code" value="{{ $autoNumber }}" readonly>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="auto_number"></label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" class="flat" id="auto_number" name="auto_number" checked="checked"> Auto Number
                        </label>
                    </div>
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
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="pr_code" >
                    Nomor PR
                    <span class="required">*</span>
                </label>
                <div class="col-md-4 col-sm-4 col-xs-12">
                    <select id="pr_code" name="pr_code" class="form-control col-md-7 col-xs-12 @if($errors->has('pr_code')) parsley-error @endif">
                    </select>
                    <input type="hidden" id="pr_id" name="pr_id" @if(!empty($purchaseRequest)) value="{{ $purchaseRequest->id }} @endif">
                </div>
                <div class="col-md-2 col-sm-2 col-xs-12">
                    <a class="get-pr-data btn btn-info">
                        Ambil Data
                    </a>
                    @if(!empty($purchaseRequest))
                        <a class="clear-pr-data btn btn-info">
                            Reset Data
                        </a>
                    @endif
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="supplier" >
                    Vendor
                    <span class="required">*</span>
                </label>
                <div class="col-md-4 col-sm-4 col-xs-12">
                    <select id="supplier" name="supplier" class="form-control col-md-7 col-xs-12 @if($errors->has('supplier')) parsley-error @endif">
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="delivery_fee">
                    Ongkos Kirim
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="delivery_fee" type="text" class="form-control col-md-7 col-xs-12 @if($errors->has('delivery_fee')) parsley-error @endif"
                           name="delivery_fee" value="{{ old('delivery_fee') }}">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="ppn">
                    Tambah PPN
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" class="flat" id="ppn" name="ppn"> PPN sekarang: 10%
                        </label>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="pph">
                    PPh
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="pph" type="text" class="form-control col-md-7 col-xs-12 @if($errors->has('pph')) parsley-error @endif"
                           name="pph" value="{{ old('pph') }}">
                </div>
            </div>

            <hr/>

            <div class="form-group">
                <div class="col-lg-12 col-md-12 col-xs-12 box-section">
                    <h3 class="text-center">Detil Inventory</h3>
                    @if(empty($purchaseRequest))
                        <a class="add-modal btn btn-info" style="margin-bottom: 10px;">
                            <span class="glyphicon glyphicon-plus-sign"></span> Tambah
                        </a>
                    @endif

                    <table class="table table-bordered table-hover" id="detail_table">
                        <thead>
                        <tr>
                            <th class="text-center" style="width: 20%">
                                Nomor Part
                            </th>
                            <th class="text-center" style="width: 10%">
                                QTY
                            </th>
                            <th class="text-center" style="width: 15%">
                                Harga
                            </th>
                            <th class="text-center" style="width: 10%">
                                Diskon (%)
                            </th>
                            <th class="text-center" style="width: 15%">
                                Subtotal
                            </th>
                            <th class="text-center" style="width: 15%">
                                Remark
                            </th>
                            <th class="text-center" style="width: 15%">
                                Tindakan
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $idx = 0; ?>
                        @if(!empty($purchaseRequest))
                            @foreach($purchaseRequest->purchase_request_details as $detail)
                                <?php $idx++; ?>
                                <tr class='item{{ $idx }}'>
                                    <td class='field-item'>
                                        <input type='text' name='item_text[]' class='form-control' value='{{ $detail->item->code. ' - '. $detail->item->name }}' readonly/>
                                        <input type='hidden' name='item_value[]' value='{{ $detail->item_id }}'/>
                                    </td>
                                    <td>
                                        <input type='text' name='qty[]' class='form-control' value='{{ $detail->quantity }}' readonly/>
                                    </td>
                                    <td>
                                        <input type='text' name='price[]' class='form-control' value='0' readonly/>
                                    </td>
                                    <td>
                                        <input type='text' name='discount[]' class='form-control' value='0' readonly/>
                                    </td>
                                    <td>
                                        <input type='text' name='subtotal[]' class='form-control' value='0' readonly/>
                                    </td>
                                    <td>
                                        <input type='text' name='remark[]' class='form-control' value='{{ $detail->remark }}' readonly/>
                                    </td>
                                    <td class='text-center'>
                                        <?php $itemId = $detail->item_id. "#". $detail->item->code. "#". $detail->item->name ?>
                                        <a class="edit-modal btn btn-info" data-id="{{ $idx }}" data-item-id="{{ $itemId }}" data-item-text="{{ $detail->item->code. ' - '. $detail->item->name }}" data-qty="{{ $detail->quantity }}" data-remark="{{ $detail->remark }}" data-price="0" data-discount="0">
                                            <span class="glyphicon glyphicon-edit"></span>
                                        </a>
                                        <a class="delete-modal btn btn-danger" data-id="{{ $idx }}" data-item-id="{{ $itemId }}" data-item-text="{{ $detail->item->code. ' - '. $detail->item->name }}" data-qty="{{ $detail->quantity }}">
                                            <span class="glyphicon glyphicon-trash"></span>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <input id="index_counter" type="hidden" value="{{ $idx }}"/>

            <hr/>

            <div class="form-group">
                <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                    <a class="btn btn-danger" href="{{ route('admin.purchase_orders') }}"> Batal</a>
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
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="item_add">Barang:</label>
                            <div class="col-sm-10">
                                <select class="form-control" id="item_add" name="item_add"></select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="qty_add">QTY:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="qty_add" name="qty_add">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="price_add">Harga:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="price_add" name="price_add">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="discount_add">Diskon(%):</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="discount_add" name="discount_add">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="remark_add">Remark:</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" id="remark_add" name="remark_add" cols="40" rows="5"></textarea>
                            </div>
                        </div>
                    </form>
                    <div class="modal-footer">
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
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="item_edit">Barang:</label>
                            <div class="col-sm-10">
                                <select class="form-control" id="item_edit" name="item_edit"></select>
                                <input type="hidden" id="item_old_value"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="qty_edit">QTY:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="qty_edit" name="qty_edit">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="price_edit">Harga:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="price_edit" name="price_edit">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="discount_edit">Diskon(%):</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="discount_edit" name="discount_edit">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="remark_edit">Remark:</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" id="remark_edit" name="remark_edit" cols="40" rows="5"></textarea>
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
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="item_delete">Barang:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="item_delete" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="qty_delete">QTY:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="qty_delete" readonly>
                            </div>
                        </div>
                        <input type="hidden" name="deleted_id"/>
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
        // Date Picker
        $('#date').datetimepicker({
            format: "DD MMM Y"
        });

        // Auto Numbering
        $('#auto_number').change(function(){
            if(this.checked){
                $('#po_code').val('{{ $autoNumber }}');
                $('#po_code').prop('readonly', true);
            }
            else{
                $('#po_code').val('');
                $('#po_code').prop('readonly', false);
            }
        });

        @if(!empty($purchaseRequest))
            $('#pr_code').select2({
                placeholder: {
                    id: '{{ $purchaseRequest->id }}',
                    text: '{{ $purchaseRequest->code }}'
                },
                width: '100%',
                minimumInputLength: 1,
                ajax: {
                    url: '{{ route('select.purchase_requests') }}',
                    dataType: 'json',
                    data: function (params) {
                        return {
                            q: $.trim(params.term)
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: data
                        };
                    }
                }
            });
        @else
            $('#pr_code').select2({
                placeholder: {
                    id: '-1',
                    text: ' - Pilih Nomor PR - '
                },
                width: '100%',
                minimumInputLength: 1,
                ajax: {
                    url: '{{ route('select.purchase_requests') }}',
                    dataType: 'json',
                    data: function (params) {
                        return {
                            q: $.trim(params.term)
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: data
                        };
                    }
                }
            });
        @endif


        $('#supplier').select2({
            placeholder: {
                id: '-1',
                text: ' - Pilih Vendor - '
            },
            width: '100%',
            minimumInputLength: 1,
            ajax: {
                url: '{{ route('select.suppliers') }}',
                dataType: 'json',
                data: function (params) {
                    return {
                        q: $.trim(params.term),
                        _token: $('input[name=_token]').val()
                    };
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                }
            }
        });

        // Add autonumeric
        qtyAddFormat = new AutoNumeric('#qty_add', {
            minimumValue: '0',
            digitGroupSeparator: '',
            decimalPlaces: 0
        });

        qtyEditFormat = new AutoNumeric('#qty_edit', {
            minimumValue: '0',
            digitGroupSeparator: '',
            decimalPlaces: 0
        });

        pphFormat = new AutoNumeric('#pph', {
            decimalCharacter: ',',
            digitGroupSeparator: '.',
            minimumValue: '0',
            decimalPlaces: 0
        });

        numberFormat = new AutoNumeric('#price_add', {
            decimalCharacter: ',',
            digitGroupSeparator: '.',
            minimumValue: '0',
            decimalPlaces: 0
        });

        deliveryFeeFormat = new AutoNumeric('#delivery_fee', {
            decimalCharacter: ',',
            digitGroupSeparator: '.',
            minimumValue: '0',
            decimalPlaces: 0
        });

        priceEditFormat = new AutoNumeric('#price_edit', {
            decimalCharacter: ',',
            digitGroupSeparator: '.',
            minimumValue: '0',
            decimalPlaces: 0
        });

        discountAddFormat = new AutoNumeric('#discount_add', {
            maximumValue: '100',
            minimumValue: '0',
            decimalPlaces: 0
        });

        discountEditFormat = new AutoNumeric('#discount_edit', {
            maximumValue: '100',
            minimumValue: '0',
            decimalPlaces: 0
        });

        // Get selected PR data
        $(document).on('click', '.get-pr-data', function(){
            var url = '{{ route('admin.purchase_orders.create') }}';
            if($('#pr_code').val() && $('#pr_code').val() !== ""){
                url += "?pr=" + $('#pr_code').val();
                window.location = url;
            }
            else{
                if($('#pr_id').val() && $('#pr_id').val() !== ""){
                    url += "?pr=" + $('#pr_id').val();
                    window.location = url;
                }
            }
        });

        // Clear selected PR data
        $(document).on('click', '.clear-pr-data', function(){
            var url = '{{ route('admin.purchase_orders.create') }}';
            window.location = url;
        });

        // Add new detail
        $(document).on('click', '.add-modal', function() {
            $('#item_add').select2({
                placeholder: {
                    id: '-1',
                    text: ' - Pilih Inventory - '
                },
                width: '100%',
                minimumInputLength: 1,
                ajax: {
                    url: '{{ route('select.extended_items') }}',
                    dataType: 'json',
                    data: function (params) {
                        return {
                            q: $.trim(params.term)
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: data
                        };
                    }
                }
            });

            $('.modal-title').text('Tambah Detail');
            $('#addModal').modal({
                backdrop: 'static',
                keyboard: false
            });
        });
        $('.modal-footer').on('click', '.add', function() {
            var qtyAdd = $('#qty_add').val();
            var itemAdd = $('#item_add').val();

            if(!itemAdd || itemAdd === ""){
                alert('Mohon pilih barang...');
                return false;
            }

            if(!qtyAdd || qtyAdd === "" || qtyAdd === "0"){
                alert('Mohon isi jumlah...')
                return false;
            }

            var priceAdd = $('#price_add').val();

            if(!priceAdd || priceAdd === "" || priceAdd === "0"){
                alert('Mohon isi harga...')
                return false;
            }

            var discountAdd = $('#discount_add').val();
            var remarkAdd = $('#remark_add').val();

            // Split item value
            var splitted = itemAdd.split('#');

            // Filter variables
            var price = 0;
            if(priceAdd && priceAdd !== "" && priceAdd !== "0"){
                price = parseFloat(priceAdd.replace('.',''));
            }
            var discount = 0;
            if(discountAdd && discountAdd !== "" && discountAdd !== "0"){
                discount = parseFloat(discountAdd);
            }
            var qty = parseFloat(qtyAdd);

            // Increase idx
            var idx = $('#index_counter').val();
            idx++;
            $('#index_counter').val(idx);

            var sbAdd = new stringbuilder();

            sbAdd.append("<tr class='item" + idx + "'>");
            sbAdd.append("<td class='field-item'><input type='text' name='item_text[]' class='form-control' value='" + splitted[1] + " - " + splitted[2] + "' readonly/>")
            sbAdd.append("<input type='hidden' name='item_value[]' value='" + splitted[0] + "'/></td>");
            if(qtyAdd && qtyAdd !== ""){
                sbAdd.append("<td><input type='text' name='qty[]' class='form-control' value='" + qtyAdd + "' readonly/></td>");
            }
            else{
                sbAdd.append("<td><input type='text' name='qty[]' class='form-control' readonly/></td>");
            }

            if(priceAdd && priceAdd !== "" && priceAdd !== "0"){
                sbAdd.append("<td><input type='text' name='price[]' class='form-control' value='" + priceAdd + "' readonly/></td>");
            }
            else{
                sbAdd.append("<td><input type='text' name='price[]' class='form-control' value='0' readonly/></td>");
            }

            if(discountAdd && discountAdd !== "" && discountAdd !== "0"){
                discount = parseFloat(discountAdd);
                sbAdd.append("<td><input type='text' name='discount[]' class='form-control' value='" + discountAdd + "' readonly/></td>");
            }
            else{
                sbAdd.append("<td><input type='text' name='discount[]' class='form-control' value='0' readonly/></td>");
            }

            var subtotal = 0;
            var totalPrice = price * qty;
            if(discount > 0){
                subtotal = totalPrice - (totalPrice * discount / 100);
            }
            else{
                subtotal = totalPrice;
            }
            var subtotalString = rupiahFormat(subtotal);

            sbAdd.append("<td><input type='text' class='form-control' value='" + subtotalString + "' readonly/></td>");
            sbAdd.append("<td><input type='text' name='remark[]' class='form-control' value='" + remarkAdd + "' readonly/></td>");

            sbAdd.append("<td class='text-center'>");
            sbAdd.append("<a class='edit-modal btn btn-info' data-id='" + idx + "' data-item-id='" + itemAdd + "' data-item-text='" + splitted[1] + " " + splitted[2] + "' data-qty='" + qtyAdd + "' data-remark='" + remarkAdd + "' data-price='" + price + "' data-discount='" + discount + "'><span class='glyphicon glyphicon-edit'></span></a>");
            sbAdd.append("<a class='delete-modal btn btn-danger' data-id='" + idx + "' data-item-id='" + itemAdd + "' data-item-text='" + splitted[1] + " " + splitted[2] + "' data-qty='" + qtyAdd + "' data-remark='" + remarkAdd + "' data-price='" + price + "' data-discount='" + discount + "'><span class='glyphicon glyphicon-trash'></span></a>");
            sbAdd.append("</td>");
            sbAdd.append("</tr>");

            $('#detail_table').append(sbAdd.toString());

            // Reset add form modal
            $('#qty_add').val('');
            $('#price_add').val('');
            $('#discount_add').val('');
            $('#remark_add').val('');
            $('#item_add').val(null).trigger('change');
        });

        // Edit detail
        $(document).on('click', '.edit-modal', function() {
            id = $(this).data('id');

            $('.modal-title').text('Ubah Detail');

            $('#item_old_value').val($(this).data('item-id'));
            $('#item_edit').select2({
                placeholder: {
                    id: $(this).data('item-id'),
                    text: $(this).data('item-text')
                },
                width: '100%',
                minimumInputLength: 1,
                ajax: {
                    url: '{{ route('select.extended_items') }}',
                    dataType: 'json',
                    data: function (params) {
                        return {
                            q: $.trim(params.term)
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: data
                        };
                    }
                }
            });

            $('#qty_edit').val($(this).data('qty'));
            $('#remark_edit').val($(this).data('remark'));

            priceEditFormat.clear();

            priceEditFormat.set($(this).data('price'), {
                decimalCharacter: ',',
                digitGroupSeparator: '.',
                decimalPlaces: 0
            });

            discountEditFormat.clear();

            discountEditFormat.set($(this).data('discount'), {
                maximumValue: '100',
                minimumValue: '0',
                decimalPlaces: 0
            });

            $('#editModal').modal('show');
        });
        $('.modal-footer').on('click', '.edit', function() {
            var itemEdit = $('#item_edit').val();
            var qtyEdit = $('#qty_edit').val();
            var priceEdit = $('#price_edit').val();
            var discountEdit = $('#discount_edit').val();
            var remarkEdit = $('#remark_edit').val();

            if(!qtyEdit || qtyEdit === "" || qtyEdit === "0"){
                alert('Mohon isi jumlah...')
                return false;
            }

            if(!priceEdit || priceEdit === "" || priceEdit === "0"){
                alert('Mohon isi harga...')
                return false;
            }

            // Split item value
            var data = "default";
            if(itemEdit && itemEdit !== ''){
                alert(itemEdit);
                data = itemEdit;
            }
            else {
                data = $('#item_old_value').val();
            }

            var splitted = data.split('#');

            // Filter variables
            var price = 0;
            if(priceEdit && priceEdit !== "" && priceEdit !== "0"){
                price = parseFloat(priceEdit.replace('.',''));
            }
            var discount = 0;
            if(discountEdit && discountEdit !== "" && discountEdit !== "0"){
                discount = parseFloat(discountEdit);
            }
            var qty = parseFloat(qtyEdit);

            var sbEdit = new stringbuilder();

            sbEdit.append("<tr class='item" + id + "'>");
            sbEdit.append("<td class='field-item'><input type='text' name='item_text[]' class='form-control' value='" + splitted[1] + ' - ' + splitted[2] + "' readonly/>")
            sbEdit.append("<input type='hidden' name='item_value[]' value='" + splitted[0] + "'/></td>");
            if(qtyEdit && qtyEdit !== ""){
                sbEdit.append("<td><input type='text' name='qty[]' class='form-control' value='" + qtyEdit + "' readonly/></td>");
            }
            else{
                sbEdit.append("<td><input type='text' name='qty[]' class='form-control' readonly/></td>");
            }

            if(priceEdit && priceEdit !== "" && priceEdit !== "0"){
                sbEdit.append("<td><input type='text' name='price[]' class='form-control' value='" + priceEdit + "' readonly/></td>");
            }
            else{
                sbEdit.append("<td><input type='text' name='price[]' class='form-control' value='0' readonly/></td>");
            }

            if(discountEdit && discountEdit !== "" && discountEdit !== "0"){
                discount = parseFloat(discountEdit);
                sbEdit.append("<td><input type='text' name='discount[]' class='form-control' value='" + discountEdit + "' readonly/></td>");
            }
            else{
                sbEdit.append("<td><input type='text' name='discount[]' class='form-control' value='0' readonly/></td>");
            }

            var subtotal = 0;
            var totalPrice = price * qty;
            if(discount > 0){
                subtotal = totalPrice - (totalPrice * discount / 100);
            }
            else{
                subtotal = totalPrice;
            }
            var subtotalString = rupiahFormat(subtotal);

            sbEdit.append("<td><input type='text' class='form-control' value='" + subtotalString + "' readonly/></td>");
            sbEdit.append("<td><input type='text' name='remark[]' class='form-control' value='" + remarkEdit + "' readonly/></td>");

            sbEdit.append("<td class='text-center'>");
            sbEdit.append("<a class='edit-modal btn btn-info' data-id='" + id + "' data-item-id='" + data + "' data-item-text='" + splitted[1] + " " + splitted[2] + "' data-qty='" + qtyEdit + "' data-remark='" + remarkEdit + "' data-price='" + price + "' data-discount='" + discount + "'><span class='glyphicon glyphicon-edit'></span></a>");
            sbEdit.append("<a class='delete-modal btn btn-danger' data-id='" + id + "' data-item-id='" + data + "' data-item-text='" + splitted[1] + " " + splitted[2] + "' data-qty='" + qtyEdit + "' data-remark='" + remarkEdit + "' data-price='" + price + "' data-discount='" + discount + "'><span class='glyphicon glyphicon-trash'></span></a>");
            sbEdit.append("</td>");
            sbEdit.append("</tr>");

            $('.item' + id).replaceWith(sbEdit.toString());
        });

        // Delete detail
        var deletedId = "0";
        $(document).on('click', '.delete-modal', function() {
            $('.modal-title').text('Hapus Detail');
            deletedId = $(this).data('id');
            $('#item_delete').val($(this).data('item-text'));
            $('#qty_delete').val($(this).data('qty'));
            $('#remark_delete').val($(this).data('remark'));
            $('#deleteModal').modal('show');
        });
        $('.modal-footer').on('click', '.delete', function() {
            // Decrease idx
            var idx = $('#index_counter').val();
            idx--;
            $('#index_counter').val(idx);

            $('.item' + deletedId).remove();
        });

        function rupiahFormat(nStr) {
            nStr += '';
            x = nStr.split(',');
            x1 = x[0];
            x2 = x.length > 1 ? '.' + x[1] : '';
            var rgx = /(\d+)(\d{3})/;
            while (rgx.test(x1)) {
                x1 = x1.replace(rgx, '$1' + '.' + '$2');
            }
            return x1 + x2;
        }
    </script>
@endsection