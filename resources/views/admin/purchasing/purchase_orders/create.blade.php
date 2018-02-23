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
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="po_code" type="text" class="form-control col-md-7 col-xs-12 @if($errors->has('po_code')) parsley-error @endif"
                           name="po_code" value="{{ old('po_code') }}">
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
                </div>
                <div class="col-md-2 col-sm-2 col-xs-12">
                    <button class="btn btn-info">
                        Ambil Data
                    </button>
                </div>
            </div>

            <div class="form-group">
                <label class="text-center col-md-12 col-xs-12">Detil Barang</label>
            </div>

            <div class="form-group">
                <div class="col-lg-2 col-md-2 col-xs-0"></div>
                <div class="col-lg-8 col-md-8 col-xs-12 box-section">
                    <a class="add-modal btn btn-info">
                        <span class="glyphicon glyphicon-plus-sign"></span> Tambah
                    </a>
                    <table class="table table-bordered table-hover" id="detail_table">
                        <thead>
                        <tr >
                            <th class="text-center" style="width: 15%">
                                Nomor Part
                            </th>
                            <th class="text-center" style="width: 15%">
                                Jumlah
                            </th>
                            <th class="text-center" style="width: 20%">
                                Harga
                            </th><th class="text-center" style="width: 10%">
                                Diskon (%)
                            </th>
                            <th class="text-center" style="width: 10%">
                                Subtotal
                            </th>
                            <th class="text-center" style="width: 40%">
                                Remark
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(!empty($purchaseRequest))
                            <?php $idx = 0; ?>
                            @foreach($purchaseRequest->purchase_request_details as $detail)
                                <tr id='item{{ $idx }}'>
                                    <td class='field-item'>
                                        <input type='text' name='item_text[]' class='form-control' value='{{ $detail->item->code. ' - '. $detail->item->name }}' readonly/>
                                        <input type='hidden' name='item_value[]' value='{{ $detail->item_id }}'/>
                                    </td>
                                    <td>
                                        <input type='text' name='qty[]' class='form-control' value='{{ $detail->quantity }}' readonly/>
                                    </td>
                                    <td>
                                        <input type='text' name='price[]' class='form-control' placeholder='Klik ubah' readonly/>
                                    </td>
                                    <td>
                                        <input type='text' name='discount[]' class='form-control' placeholder='Klik ubah' readonly/>
                                    </td>
                                    <td>
                                        <input type='text' name='subtotal[]' class='form-control' placeholder='Klik ubah' readonly/>
                                    </td>
                                    <td>
                                        <input type='text' name='remark[]' class='form-control' value='{{ $detail->remark }}' readonly/>
                                    </td>
                                    <td>
                                        <button class="edit-modal btn btn-info" data-id="{{ $idx }}" data-item-id="{{ $detail->item_id }}" data-item-text="{{ $detail->item->code. ' - '. $detail->item->name }}" data-qty="{{ $detail->quantity }}" data-remark="{{ $detail->remark }}" data-price="" data-discount="">
                                            <span class="glyphicon glyphicon-edit"></span> Ubah
                                        </button>
                                        <button class="delete-modal btn btn-danger" data-id="{{ $idx }}" data-item-id="{{ $detail->item_id }}" data-item-text="{{ $detail->item->code. ' - '. $detail->item->name }}" data-qty="{{ $detail->quantity }}">
                                            <span class="glyphicon glyphicon-trash"></span> Hapus
                                        </button>
                                    </td>
                                </tr>
                                <?php $idx++; ?>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
                <div class="col-lg-2 col-md-2 col-xs-0"></div>
            </div>

            <input id="index_counter" type="hidden" value="{{ $idx }}"/>

            <div class="form-group">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                    <a class="btn btn-primary" href="{{ route('admin.purchase_requests') }}"> Batal</a>
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
                                <p class="errorItem text-center alert alert-danger hidden"></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="qty_add">Jumlah:</label>
                            <div class="col-sm-10">
                                <input type="number" class="form-control" id="qty_add" name="qty_add">
                                <p class="errorQty text-center alert alert-danger hidden"></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="price_add">Harga:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="price_add" name="price_add">
                                <p class="errorPrice text-center alert alert-danger hidden"></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="discount_add">Diskon(%):</label>
                            <div class="col-sm-10">
                                <input type="number" class="form-control" id="discount_add" name="discount_add">
                                <p class="errorDiscount text-center alert alert-danger hidden"></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="remark_add">Remark:</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" id="remark_add" name="remark_add" cols="40" rows="5"></textarea>
                                <p class="errorRemark text-center alert alert-danger hidden"></p>
                            </div>
                        </div>
                    </form>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success add" data-dismiss="modal">
                            <span id="" class='glyphicon glyphicon-check'></span> Simpan
                        </button>
                        <button type="button" class="btn btn-warning" data-dismiss="modal">
                            <span class='glyphicon glyphicon-remove'></span> Batal
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
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="qty_edit">Jumlah:</label>
                            <div class="col-sm-10">
                                <input type="number" class="form-control" id="qty_edit" name="qty_edit">
                                <p class="errorQty text-center alert alert-danger hidden"></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="price_edit">Harga:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="price_edit" name="price_edit">
                                <p class="errorPrice text-center alert alert-danger hidden"></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="discount_edit">Diskon(%):</label>
                            <div class="col-sm-10">
                                <input type="number" class="form-control" id="discount_edit" name="discount_edit">
                                <p class="errorDiscount text-center alert alert-danger hidden"></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="remark_edit">Remark:</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" id="remark_edit" name="remark_edit" cols="40" rows="5"></textarea>
                                <p class="errorRemark text-center alert alert-danger hidden"></p>
                            </div>
                        </div>
                    </form>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary edit" data-dismiss="modal">
                            <span class='glyphicon glyphicon-check'></span> Simpan
                        </button>
                        <button type="button" class="btn btn-warning" data-dismiss="modal">
                            <span class='glyphicon glyphicon-remove'></span> Batal
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
                                <input type="text" class="form-control" id="item_delete" disabled>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="qty_delete">Jumlah:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="qty_delete" disabled>
                            </div>
                        </div>
                    </form>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger delete" data-dismiss="modal">
                            <span id="" class='glyphicon glyphicon-trash'></span> Hapus
                        </button>
                        <button type="button" class="btn btn-warning" data-dismiss="modal">
                            <span class='glyphicon glyphicon-remove'></span> Batal
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
    <script type="text/javascript">
        $('#pr_code').select2({
            placeholder: {
                id: '-1',
                text: 'Pilih Nomor PR...'
            },
            width: '100%',
            minimumInputLength: 2,
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

        $('#supplier').select2({
            placeholder: {
                id: '-1',
                text: 'Pilih Vendor...'
            },
            width: '100%',
            minimumInputLength: 2,
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
        numberFormat = new AutoNumeric('#price_add', {
            decimalCharacter: ',',
            digitGroupSeparator: '.',
            decimalPlaces: 0
        });

        priceEditFormat = new AutoNumeric('#price_edit', {
            decimalCharacter: ',',
            digitGroupSeparator: '.',
            decimalPlaces: 0
        });

        // Add new detail
        $(document).on('click', '.add-modal', function() {
            var id = $(this).data('id');

            $('#item_add').select2({
                placeholder: {
                    id: '-1',
                    text: 'Pilih barang...'
                },
                width: '100%',
                minimumInputLength: 2,
                ajax: {
                    url: '{{ route('select.items.po') }}',
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
            $('#addModal').modal('show');
        });
        $('.modal-footer').on('click', '.add', function() {
            var qtyAdd = $('#qty_add').val();
            var priceAdd = $('#price_add').val();
            var discountAdd = $('#discount_add').val();
            var remarkAdd = $('#remark_add').val();

            // Split item value
            var data = $('#item_add').val();
            var splitted = data.split('#');

            // Filter variables
            var price = parseFloat(priceAdd.replace('.',''));
            var discount = 0;
            var qty = parseFloat(qtyAdd);

            // Increase idx
            var idx = $('#index_counter').val();
            idx++;
            $('#index_counter').val(idx);

            var sbAdd = new stringbuilder();


            sbAdd.append("<tr class='item" + idx + "'>");
            sbAdd.append("<td class='field-item'><input type='text' name='item_text[]' class='form-control' value='" + splitted[1] + splitted[2] + "' readonly/>")
            sbAdd.append("<input type='hidden' name='item_value[]' value='" + splitted[0] + "'/></td>");
            if(!qtyAdd && qtyAdd !== ""){
                sbAdd.append("<td><input type='text' name='qty[]' class='form-control' value='" + qtyAdd + "' readonly/></td>");
            }
            else{
                sbAdd.append("<td><input type='text' name='qty[]' class='form-control' readonly/></td>");
            }

            if(!priceAdd && priceAdd !== ""){
                sbAdd.append("<td><input type='text' name='qty[]' class='form-control' value='" + priceAdd + "' readonly/></td>");
            }
            else{
                sbAdd.append("<td><input type='text' name='qty[]' class='form-control' readonly/></td>");
            }

            if(!discountAdd && discountAdd !== ""){
                discount = parseFloat(discountAdd);
                sbAdd.append("<td><input type='text' name='qty[]' class='form-control' value='" + discountAdd + "' readonly/></td>");
            }
            else{
                sbAdd.append("<td><input type='text' name='qty[]' class='form-control' readonly/></td>");
            }

            var subtotal = 0;
            var totalPrice = price * qty;
            if(discount > 0){
                subtotal = totalPrice - (totalPrice * discount / 100);
            }
            else{
                subtotal = totalPrice;
            }

            sbAdd.append("<td><input type='text' class='form-control' value='" + subtotal + "' readonly/></td>")
            sbAdd.append("<td><input type='text' class='form-control' value='" + remarkAdd + "' readonly/></td>")

            sbAdd.append("<td>")
            sbAdd.append("<button class='edit-modal btn btn-info' data-id='" + idx + "' data-item-id='" + splitted[0] + "' data-item-text='" + splitted[1] + " " + splitted[2] + "' data-qty='" + qtyAdd + "' data-remark='" + remarkAdd + "' data-price='" + data.price + "' data-discount='" + discountAdd + "'><span class='glyphicon glyphicon-edit'></span> Ubah</button>")


            // $('#detailTable').append("<tr class='item" + id + "'><td class='field-item'>" + splitted[1] + " - " + splitted[2] + "</td>
            // <td>" + data.quantity + "</td>
            // <td>Rp" + priceString + "</td>
            // <td>" + data.discountString + "</td>
            // <td>" + data.subtotalString + "</td>
            // <td>" + remarkAdd + "</td>
            // <td>" + "<button class='edit-modal btn btn-info' data-id='" + data.id + "' data-item-id='" + data.item_id + "' data-item-text='" + data.item.code + " " + data.item.name + "' data-qty='" + data.quantity + "' data-remark='" + data.remark + "' data-price='" + data.price + "' data-discount='" + data.discount + "'><span class='glyphicon glyphicon-edit'></span> Ubah</button><button class='delete-modal btn btn-danger' data-id='" + data.id + "' data-item-id='" + data.item_id + "' data-item-text='" + data.item.code + " - "  + data.item.name + "' data-qty='" + data.quantity + "' data-price='" + data.price + "' data-discount='" + data.discount + "'><span class='glyphicon glyphicon-trash'></span> Hapus</button></td></tr>");

        });

        // Edit detail
        $(document).on('click', '.edit-modal', function() {
            id = $(this).data('id');

            $('.modal-title').text('Ubah Detail');

            $('#item_edit').select2({
                placeholder: {
                    id: $(this).data('item-id'),
                    text: $(this).data('item-text')
                },
                width: '100%',
                minimumInputLength: 2,
                ajax: {
                    url: '{{ route('select.items') }}',
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
            $('#discount_edit').val($(this).data('discount'));
            $('#editModal').modal('show');

            priceEditFormat.clear();

            priceEditFormat.set($(this).data('price'), {
                decimalCharacter: ',',
                digitGroupSeparator: '.',
                decimalPlaces: 0
            });

        });
        $('.modal-footer').on('click', '.edit', function() {
            $('.item' + data.id).replaceWith("<tr class='item" + data.id + "'><td class='field-item'>" + data.item.code + " - " + data.item.name + "</td><td>" + data.item.uomDescription + "</td><td>" + data.quantity + "</td><td>" + data.priceString + "</td><td>" + data.discountString + "</td><td>" + data.subtotalString + "</td><td>" + remarkEdit + "</td><td>" + "<button class='edit-modal btn btn-info' data-id='" + data.id + "' data-item-id='" + data.item_id + "' data-item-text='" + data.item.code + " " + data.item.name + "' data-qty='" + data.quantity + "' data-remark='" + data.remark + "' data-price='" + data.price + "' data-discount='" + data.discount + "'><span class='glyphicon glyphicon-edit'></span> Ubah</button><button class='delete-modal btn btn-danger' data-id='" + data.id + "' data-item-id='" + data.item_id + "' data-item-text='" + data.item.code + " - "  + data.item.name + "' data-qty='" + data.quantity + "' data-price='" + data.price + "' data-discount='" + data.discount + "'><span class='glyphicon glyphicon-trash'></span> hapus</button></td></tr>");
        });

        // Delete detail
        $(document).on('click', '.delete-modal', function() {
            $('.modal-title').text('Hapus Detail');
            $('#item_delete').val($(this).data('item-text'));
            $('#qty_delete').val($(this).data('qty'));
            $('#remark_delete').val($(this).data('remark'));
            $('#deleteModal').modal('show');
            deletedId = $(this).data('id')
        });
        $('.modal-footer').on('click', '.delete', function() {
            $('.item' + data['id']).remove();
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