@extends('admin.layouts.admin')

@section('title','Buat Surat Jalan Baru')

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">

            {{ Form::open(['route'=>['admin.delivery_orders.store'],'method' => 'post','class'=>'form-horizontal form-label-left']) }}

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
                    Nomor SJ
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
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="from_site" >
                    Site Keberangkatan
                    <span class="required">*</span>
                </label>
                <div class="col-md-4 col-sm-4 col-xs-12">
                    <select id="from_site" name="from_site" class="form-control col-md-7 col-xs-12 @if($errors->has('from_site')) parsley-error @endif">
                        <option value="-1" @if(empty(old('from_site'))) selected @endif> - Pilih site - </option>
                        @foreach($sites as $site)
                            <option value="{{ $site->id }}" {{ old('from_site') == $site->id ? "selected":"" }}>{{ $site->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="to_site" >
                    Site Tujuan
                    <span class="required">*</span>
                </label>
                <div class="col-md-4 col-sm-4 col-xs-12">
                    <select id="to_site" name="to_site" class="form-control col-md-7 col-xs-12 @if($errors->has('from_site')) parsley-error @endif">
                        <option value="-1" @if(empty(old('to_site'))) selected @endif> - Pilih site - </option>
                        @foreach($sites as $site)
                            <option value="{{ $site->id }}" {{ old('to_site') == $site->id ? "selected":"" }}>{{ $site->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="pr_code" >
                    Nomor PR
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
                            Set Ulang Data
                        </a>
                    @endif
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="machinery" >
                    Unit Alat Berat
                </label>
                <div class="col-md-4 col-sm-4 col-xs-12">
                    <select id="machinery" name="machinery" class="form-control col-md-7 col-xs-12 @if($errors->has('machinery')) parsley-error @endif">
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="remark_header" >
                    Keterangan
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <textarea id="remark_header" name="remark_header" rows="5" style="resize: vertical;" class="form-control col-md-7 col-xs-12">{{ old('remark_header') }}</textarea>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">
                    <b>Detil Barang</b>
                </label>
            </div>

            <div class="form-group">
                <div class="col-lg-12 col-md-12 col-xs-12 box-section">
                    <a class="add-modal btn btn-info" style="margin-bottom: 10px;">
                        <span class="glyphicon glyphicon-plus-sign"></span> Tambah
                    </a>
                    <table class="table table-bordered table-hover" id="detail_table">
                        <thead>
                        <tr >
                            <th class="text-center">
                                No
                            </th>
                            <th class="text-center" style="width: 15%">
                                Part Number
                            </th>
                            <th class="text-center" style="width: 20%">
                                Part Name
                            </th>
                            <th class="text-center" colspan="2" style="width: 20%">
                                QTY
                            </th>
                            <th class="text-center" style="width: 30%">
                                Remarks
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
                                    <td class='text-center'>
                                        {{ $idx }}
                                    </td>
                                    <td class='text-center'>
                                        {{ $detail->item->code }}
                                        <input type='hidden' name='item[]' value='{{ $detail->item_id }}'/>
                                    </td>
                                    <td class='text-center'>
                                        {{ $detail->item->name }}
                                    </td>
                                    <td class='text-center'>
                                        {{ $detail->quantity }}
                                        <input type='hidden' name='qty[]' value='{{ $detail->quantity }}'/>
                                    </td>
                                    <td class='text-center'>
                                        {{ $detail->item->uomDescription }}
                                    </td>
                                    <td>
                                        {{ $detail->remark }}
                                        <input type='hidden' name='remark[]' value='$detail->remark'/>
                                    </td>
                                    <td>
                                        <?php $itemId = $detail->item_id. "#". $detail->item->code. "#". $detail->item->name ?>
                                        <a class="edit-modal btn btn-info" data-id="{{ $idx }}" data-item-id="{{ $itemId }}" data-item-text="{{ $detail->item->code. ' - '. $detail->item->name }}" data-qty="{{ $detail->quantity }}" data-remark="{{ $detail->remark }}">
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

            <div class="form-group">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                    <a class="btn btn-danger" href="{{ route('admin.delivery_orders') }}"> Batal</a>
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
                            <label class="control-label col-sm-2" for="qty_add">Jumlah:</label>
                            <div class="col-sm-10">
                                <input type="number" class="form-control" id="qty_add" name="qty_add">
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
                            <label class="control-label col-sm-2" for="qty_edit">Jumlah:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="qty_edit" name="qty_edit">
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
                                <input type="text" class="form-control" id="item_delete" disabled>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="qty_delete">Jumlah:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="qty_delete" disabled>
                            </div>
                        </div>
                        <input type="hidden" name="deleted_id"/>
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

        @if(!empty($purchaseRequest))
            $('#pr_code').select2({
                placeholder: {
                    id: '{{ $purchaseRequest->id }}',
                    text: '{{ $purchaseRequest->code }}'
                },
                width: '100%',
                minimumInputLength: 1,
                allowClear: true,
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
                    text: 'Pilih Nomor PR...'
                },
                width: '100%',
                minimumInputLength: 1,
                allowClear: true,
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

        // Get machinery data
        $('#machinery').select2({
            placeholder: {
                id: '-1',
                text: 'Pilih Alat Berat...'
            },
            width: '100%',
            minimumInputLength: 1,
            allowClear: true,
            ajax: {
                url: '{{ route('select.machineries') }}',
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

        // Get selected PR data
        $(document).on('click', '.get-pr-data', function(){
            var url = '{{ route('admin.delivery_orders.create') }}';
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
            var url = '{{ route('admin.delivery_orders.create') }}';
            window.location = url;
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

            var remarkAdd = $('#remark_add').val();

            // Split item value
            var splitted = itemAdd.split('#');

            // Increase idx
            var idx = $('#index_counter').val();
            idx++;
            $('#index_counter').val(idx);

            var sbAdd = new stringbuilder();

            sbAdd.append("<tr class='item" + idx + "'>");
            sbAdd.append("<td class='text-center'>" + idx + "</td>");

            sbAdd.append("<td class='text-center'>" + splitted[1]);
            sbAdd.append("<input type='hidden' name='item[]' value='" + splitted[0] + "'/></td>");

            sbAdd.append("<td class='text-center'>" + splitted[2] + "</td>");

            sbAdd.append("<td class='text-center'>" + qtyAdd);
            sbAdd.append("<input type='hidden' name='qty[]' value='" + qtyAdd + "'/></td>");

            sbAdd.append("<td class='text-center'>" + splitted[3] + "</td>");

            sbAdd.append("<td>" + remarkAdd);
            sbAdd.append("<input type='hidden' name='remark[]' value='" + remarkAdd + "'/></td>");

            sbAdd.append("<td>");
            sbAdd.append("<a class='edit-modal btn btn-info' data-id='" + idx + "' data-item-id='" + itemAdd + "' data-item-text='" + splitted[1] + " " + splitted[2] + "' data-qty='" + qtyAdd + "' data-remark='" + remarkAdd + "'><span class='glyphicon glyphicon-edit'></span></a>");
            sbAdd.append("<a class='delete-modal btn btn-danger' data-id='" + idx + "' data-item-id='" + itemAdd + "' data-item-text='" + splitted[1] + " " + splitted[2] + "' data-qty='" + qtyAdd + "' data-remark='" + remarkAdd + "'><span class='glyphicon glyphicon-trash'></span></a>");
            sbAdd.append("</td>");
            sbAdd.append("</tr>");

            $('#detail_table').append(sbAdd.toString());

            // Reset add form modal
            $('#qty_add').val('');
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
            $('#editModal').modal('show');
        });
        $('.modal-footer').on('click', '.edit', function() {
            var itemEdit = $('#item_edit').val();
            var qtyEdit = $('#qty_edit').val();
            var remarkEdit = $('#remark_edit').val();

            // Split item value
            var data = "default";
            if(itemEdit && itemEdit !== ''){
                data = itemEdit;
            }
            else {
                data = $('#item_old_value').val();
            }

            var splitted = data.split('#');

            var sbEdit = new stringbuilder();

            sbEdit.append("<tr class='item" + id + "'>");
            sbEdit.append("<td class='text-center'>" + id + "</td>");

            sbEdit.append("<td class='text-center'>" + splitted[1]);
            sbEdit.append("<input type='hidden' name='item[]' value='" + splitted[0] + "'/></td>");

            sbEdit.append("<td class='text-center'>" + splitted[2] + "</td>");

            sbEdit.append("<td class='text-center'>" + qtyEdit);
            sbEdit.append("<input type='hidden' name='qty[]' value='" + qtyEdit + "'/></td>");

            sbEdit.append("<td class='text-center'>" + splitted[3] + "</td>");

            sbEdit.append("<td>" + remarkEdit);
            sbEdit.append("<input type='hidden' name='remark[]' value='" + remarkEdit + "'/></td>");

            sbEdit.append("<td>");
            sbEdit.append("<a class='edit-modal btn btn-info' data-id='" + id + "' data-item-id='" + itemEdit + "' data-item-text='" + splitted[1] + " " + splitted[2] + "' data-qty='" + qtyEdit + "' data-remark='" + remarkEdit + "'><span class='glyphicon glyphicon-edit'></span></a>");
            sbEdit.append("<a class='delete-modal btn btn-danger' data-id='" + id + "' data-item-id='" + itemEdit + "' data-item-text='" + splitted[1] + " " + splitted[2] + "' data-qty='" + qtyEdit + "' data-remark='" + remarkEdit + "'><span class='glyphicon glyphicon-trash'></span></a>");
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
            $('.item' + deletedId).remove();
        });
    </script>
@endsection