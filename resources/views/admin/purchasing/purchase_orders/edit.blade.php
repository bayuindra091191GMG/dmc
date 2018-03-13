@extends('admin.layouts.admin')

@section('title','Ubah Purchase Order '. $header->code)

@section('content')
    <div class="row" style="margin-bottom: 10px;">
        <div class="col-md-12 col-sm-12 col-xs-12">

            {{ Form::open(['route'=>['admin.purchase_orders.update', $header->id],'method' => 'put','class'=>'form-horizontal form-label-left']) }}

            @if(\Illuminate\Support\Facades\Session::has('message'))
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        @include('partials._success')
                    </div>
                </div>
            @endif

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
                <div class="col-md-4 col-sm-4 col-xs-12">
                    <input type="text" id="po_code" name="po_code" class="form-control col-md-12 col-xs-12" value="{{ $header->code }}" readonly/>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="pr_code">
                    Nomor PR
                    <span class="required">*</span>
                </label>
                <div class="col-md-4 col-sm-4 col-xs-12">
                    <select id="pr_code" name="pr_code" class="form-control col-md-12 col-xs-12 @if($errors->has('pr_code')) parsley-error @endif">
                    </select>
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
                <div class="col-md-4 col-sm-4 col-xs-12">
                    <input id="delivery_fee" type="text" class="form-control col-md-7 col-xs-12 @if($errors->has('delivery_fee')) parsley-error @endif"
                           name="delivery_fee">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="ppn">
                    Tambah PPN
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" class="flat" id="ppn" name="ppn" @if(!empty($header->ppn_percent) && $header->ppn_percent > 0) checked @endif>
                            PPN sekarang: 15%@if(!empty($header->ppn_percent) && $header->ppn_percent > 0), PPN tersimpan: {{ $header->ppn_percent }}% @endif
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
                           name="pph">
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                    <a class="btn btn-danger" href="{{ route('admin.purchase_orders.show', ['purchase_order' => $header->id]) }}"> Batal</a>
                    <button type="submit" class="btn btn-success"> Simpan</button>
                </div>
            </div>

            {{ Form::close() }}

        </div>
    </div>

    <hr/>

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 box-section">
            <h3>Detil Barang</h3>
            <button class="add-modal btn btn-info" data-header-id="{{ $header->id }}">
                <span class="glyphicon glyphicon-plus-sign"></span> Tambah
            </button>
            <table class="table table-bordered table-hover" id="detailTable">
                <thead>
                <tr >
                    <th class="text-center" style="width: 15%;">
                        Nomor Part
                    </th>
                    <th class="text-center" style="width: 5%;">
                        Satuan
                    </th>
                    <th class="text-center" style="width: 10%;">
                        Jumlah
                    </th>
                    <th class="text-center" style="width: 10%;">
                        Harga
                    </th>
                    <th class="text-center" style="width: 10%;">
                        Diskon
                    </th>
                    <th class="text-center" style="width: 10%;">
                        Subtotal
                    </th>
                    <th class="text-center" style="width: 15%;">
                        Remark
                    </th>
                    <th class="text-center" style="width: 15%;">
                        Tindakan
                    </th>
                </tr>
                </thead>
                <tbody>

                @foreach($header->purchase_order_details as $detail)
                    <tr class="item{{ $detail->id }}">
                        <td class='field-item'>
                            {{ $detail->item->code }} - {{ $detail->item->name }}
                        </td>
                        <td class="text-center">
                            {{ $detail->item->uom->description }}
                        </td>
                        <td>
                            {{ $detail->quantity }}
                        </td>
                        <td>
                            {{ $detail->price_string }}
                        </td>
                        <td class="text-center">
                            {{ $detail->discount_string ?? '-' }}
                        </td>
                        <td>
                            {{ $detail->subtotal_string }}
                        </td>
                        <td>
                            {{ $detail->remark ?? '-' }}
                        </td>
                        <td class='text-center'>
                            <button class="edit-modal btn btn-info" data-id="{{ $detail->id }}" data-item-id="{{ $detail->item_id }}" data-item-text="{{ $detail->item->code. ' - '. $detail->item->name }}" data-qty="{{ $detail->quantity }}" data-remark="{{ $detail->remark }}" data-price="{{ $detail->price }}" data-discount="{{ $detail->discount }}">
                                <span class="glyphicon glyphicon-edit"></span>
                            </button>
                            <button class="delete-modal btn btn-danger" data-id="{{ $detail->id }}" data-item-id="{{ $detail->item_id }}" data-item-text="{{ $detail->item->code. ' - '. $detail->item->name }}" data-qty="{{ $detail->quantity }}">
                                <span class="glyphicon glyphicon-trash"></span>
                            </button>
                        </td>
                    </tr>
                @endforeach

                </tbody>
            </table>
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
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
    {{ Html::script(mix('assets/admin/js/bootstrap-datetimepicker.js')) }}
    {{ Html::script(mix('assets/admin/js/autonumeric.js')) }}
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script type="text/javascript">
        var i=1;

        $('#pr_code').select2({
            placeholder: {
                id: '{{ $header->purchase_request_id }}',
                text: '{{ $header->purchase_request_header->code }}'
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

        $('#supplier').select2({
            placeholder: {
                id: '{{ $header->supplier_id }}',
                text: '{{ $header->supplier->name }}'
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

        $('#select0').select2({
            placeholder: {
                id: '-1',
                text: 'Pilih barang...'
            },
            width: '100%',
            minimumInputLength: 1,
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

        // Add autonumeric
        pphFormat = new AutoNumeric('#pph', {
            decimalCharacter: ',',
            digitGroupSeparator: '.',
            decimalPlaces: 0
        });

        @if(!empty($header->pph_amount) && $header->pph_amount > 0)
            pphFormat.clear();

            pphFormat.set('{{ $header->pph_amount }}', {
                decimalCharacter: ',',
                digitGroupSeparator: '.',
                decimalPlaces: 0
            });
        @endif

        numberFormat = new AutoNumeric('#price_add', {
            decimalCharacter: ',',
            digitGroupSeparator: '.',
            decimalPlaces: 0
        });

        deliveryFeeFormat = new AutoNumeric('#delivery_fee', {
            decimalCharacter: ',',
            digitGroupSeparator: '.',
            decimalPlaces: 0
        });

        @if(!empty($header->delivery_fee))
            deliveryFeeFormat.clear();

            deliveryFeeFormat.set('{{ $header->delivery_fee }}', {
                decimalCharacter: ',',
                digitGroupSeparator: '.',
                decimalPlaces: 0
            });
        @endif

        priceEditFormat = new AutoNumeric('#price_edit', {
            decimalCharacter: ',',
            digitGroupSeparator: '.',
            decimalPlaces: 0
        });

        // Add new detail
        $(document).on('click', '.add-modal', function() {
            $('#item_add').select2({
                placeholder: {
                    id: '-1',
                    text: 'Pilih barang...'
                },
                width: '100%',
                minimumInputLength: 1,
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

            $('.modal-title').text('Tambah Detail');
            $('#addModal').modal('show');
        });
        $('.modal-footer').on('click', '.add', function() {
            $.ajax({
                type: 'POST',
                url: '{{ route('admin.purchase_order_details.store') }}',
                data: {
                    '_token': $('input[name=_token]').val(),
                    'header_id': '{{ $header->id }}',
                    'item': $('#item_add').val(),
                    'qty': $('#qty_add').val(),
                    'price': $('#price_add').val(),
                    'discount': $('#discount_add').val(),
                    'remark': $('#remark_add').val()
                },
                success: function(data) {
                    $('.errorItem').addClass('hidden');
                    $('.errorQty').addClass('hidden');
                    $('.errorPrice').addClass('hidden');
                    $('.errorDiscount').addClass('hidden');
                    $('.errorRemark').addClass('hidden');

                    if ((data.errors)) {
                        setTimeout(function () {
                            $('#addModal').modal('show');
                            toastr.error('Gagal simpan data!', 'Peringatan', {timeOut: 5000});
                        }, 500);

                        if (data.errors.item) {
                            $('.errorItem').removeClass('hidden');
                            $('.errorItem').text(data.errors.item);
                        }
                        if (data.errors.qty) {
                            $('.errorQty').removeClass('hidden');
                            $('.errorQty').text(data.errors.qty);
                        }
                        if (data.errors.price) {
                            $('.errorQty').removeClass('hidden');
                            $('.errorQty').text(data.errors.qty);
                        }
                        if (data.errors.discount) {
                            $('.errorQty').removeClass('hidden');
                            $('.errorQty').text(data.errors.qty);
                        }
                        if (data.errors.remark) {
                            $('.errorRemark').removeClass('hidden');
                            $('.errorRemark').text(data.errors.remark);
                        }
                    } else {
                        toastr.success('Berhasil simpan detail!', 'Sukses', {timeOut: 5000});
                        var remarkAdd = '-';
                        if (data.remark !== null) {
                            remarkAdd = data.remark;
                        }
                        $('#detailTable').append("<tr class='item" + data.id + "'><td class='field-item'>" + data.item.code + " - " + data.item.name + "</td><td class='text-center'>" + data.item.uomDescription + "</td><td>" + data.quantity + "</td><td>" + data.price_string + "</td><td class='text-center'>" + data.discount_string + "</td><td>" + data.subtotal_string + "</td><td>" + remarkAdd + "</td><td class='text-center'>" + "<button class='edit-modal btn btn-info' data-id='" + data.id + "' data-item-id='" + data.item_id + "' data-item-text='" + data.item.code + " " + data.item.name + "' data-qty='" + data.quantity + "' data-remark='" + data.remark + "' data-price='" + data.price + "' data-discount='" + data.discount + "'><span class='glyphicon glyphicon-edit'></span></button><button class='delete-modal btn btn-danger' data-id='" + data.id + "' data-item-id='" + data.item_id + "' data-item-text='" + data.item.code + " - "  + data.item.name + "' data-qty='" + data.quantity + "' data-price='" + data.price + "' data-discount='" + data.discount + "'><span class='glyphicon glyphicon-trash'></span></button></td></tr>");

                        // Reset add form modal
                        $('#qty_add').val('');
                        $('#price_add').val('');
                        $('#discount_add').val('');
                        $('#remark_add').val('');
                        $('#item_add').val(null).trigger('change');
                    }
                },
            });
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
                minimumInputLength: 1,
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
            $.ajax({
                type: 'PUT',
                url: '{{ route('admin.purchase_order_details.update') }}',
                data: {
                    '_token': $('input[name=_token]').val(),
                    'id' : id,
                    'item': $("#item_edit").val(),
                    'qty': $('#qty_edit').val(),
                    'price': $('#price_edit').val(),
                    'discount': $('#discount_edit').val(),
                    'remark': $('#remark_edit').val()
                },
                success: function(data) {
                    $('.errorQty').addClass('hidden');
                    $('.errorRemark').addClass('hidden');
                    $('.errorPrice').addClass('hidden');
                    $('.errorDiscount').addClass('hidden');

                    if ((data.errors)) {
                        setTimeout(function () {
                            $('#editModal').modal('show');
                            toastr.error('Gagal ubah detail!', 'Peringatan', {timeOut: 5000});
                        }, 500);

                        if (data.errors.qty) {
                            $('.errorQty').removeClass('hidden');
                            $('.errorQty').text(data.errors.qty);
                        }
                        if (data.errors.price) {
                            $('.errorQty').removeClass('hidden');
                            $('.errorQty').text(data.errors.qty);
                        }
                        if (data.errors.discount) {
                            $('.errorQty').removeClass('hidden');
                            $('.errorQty').text(data.errors.qty);
                        }
                        if (data.errors.remark) {
                            $('.errorRemark').removeClass('hidden');
                            $('.errorRemark').text(data.errors.remark);
                        }
                    } else {
                        toastr.success('Berhasil ubah data!', 'Sukses', {timeOut: 5000});
                        var remarkEdit = '-';
                        if (data.remark !== null) {
                            remarkEdit = data.remark;
                        }
                        $('.item' + data.id).replaceWith("<tr class='item" + data.id + "'><td class='field-item'>" + data.item.code + " - " + data.item.name + "</td><td class='text-center'>" + data.item.uomDescription + "</td><td>" + data.quantity + "</td><td>" + data.price_string + "</td><td class='text-center'>" + data.discount_string + "</td><td>" + data.subtotal_string + "</td><td>" + remarkEdit + "</td><td class='text-center'>" + "<button class='edit-modal btn btn-info' data-id='" + data.id + "' data-item-id='" + data.item_id + "' data-item-text='" + data.item.code + " " + data.item.name + "' data-qty='" + data.quantity + "' data-remark='" + data.remark + "' data-price='" + data.price + "' data-discount='" + data.discount + "'><span class='glyphicon glyphicon-edit'></span></button><button class='delete-modal btn btn-danger' data-id='" + data.id + "' data-item-id='" + data.item_id + "' data-item-text='" + data.item.code + " - "  + data.item.name + "' data-qty='" + data.quantity + "' data-price='" + data.price + "' data-discount='" + data.discount + "'><span class='glyphicon glyphicon-trash'></span></button></td></tr>");
                    }
                }
            });
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
            $.ajax({
                type: 'POST',
                url: '{{ route('admin.purchase_order_details.delete') }}',
                data: {
                    '_token': $('input[name=_token]').val(),
                    'id': deletedId,
                    'header_id': '{{ $header->id }}'
                },
                success: function(data) {
                    if ((data.errors)){
                        setTimeout(function () {
                            toastr.error('Gagal hapus detail!', 'Peringatan', {timeOut: 5000});
                        }, 500);
                    }
                    else{
                        toastr.success('Berhasil menghapus detail!', 'Sukses', {timeOut: 5000});
                        $('.item' + data['id']).remove();
                    }
                }
            });
        });
    </script>
@endsection