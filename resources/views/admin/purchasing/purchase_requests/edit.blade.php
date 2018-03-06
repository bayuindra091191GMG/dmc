@extends('admin.layouts.admin')

@section('title','Ubah Purchase Request '. $header->code)

@section('content')
    <div class="row" style="margin-bottom: 10px;">
        <div class="col-md-12 col-sm-12 col-xs-12">

            {{ Form::open(['route'=>['admin.purchase_requests.update', $header->id],'method' => 'put','class'=>'form-horizontal form-label-left']) }}

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
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="department" >
                    Departemen
                    <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <select id="department" name="department" class="form-control col-md-7 col-xs-12 @if($errors->has('department')) parsley-error @endif">
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}" {{ $header->department_id == $department->id ? "selected":"" }}>{{ $department->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="machinery" >
                    Unit Alat Berat
                    <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <select id="machinery" name="machinery" class="form-control col-md-7 col-xs-12 @if($errors->has('machinery')) parsley-error @endif">
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="priority">
                    Prioritas
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <select id="priority" name="priority" class="form-control col-md-7 col-xs-12 @if($errors->has('department')) parsley-error @endif">
                        <option value="-1" @if(empty($header->priority)) selected @endif> - Pilih prioritas - </option>
                        <option value="1" {{ $header->priority == "1" ? "selected":"" }}>1</option>
                        <option value="2" {{ $header->priority == "2" ? "selected":"" }}>2</option>
                        <option value="3" {{ $header->priority == "3" ? "selected":"" }}>3</option>
                        <option value="4" {{ $header->priority == "4" ? "selected":"" }}>4</option>
                        <option value="5" {{ $header->priority == "5" ? "selected":"" }}>5</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="km">
                    KM
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="km" type="text" class="form-control col-md-7 col-xs-12 @if($errors->has('km')) parsley-error @endif"
                           name="km" value="{{ $header->km }}">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="hm">
                    HM
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="hm" type="text" class="form-control col-md-7 col-xs-12 @if($errors->has('hm')) parsley-error @endif"
                           name="hm" value="{{ $header->hm  }}">
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                    <a class="btn btn-primary" href="{{ route('admin.purchase_requests') }}"> Batal</a>
                    <button type="submit" class="btn btn-success"> Simpan</button>
                </div>
            </div>

            {{ Form::close() }}

        </div>
    </div>
    <div class="row">
        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-0"></div>
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 box-section">
            <h3>Detil Barang</h3>
            <button class="add-modal btn btn-info" data-header-id="{{ $header->id }}">
                <span class="glyphicon glyphicon-plus-sign"></span> Tambah
            </button>
            <table class="table table-bordered table-hover" id="detailTable">
                <thead>
                <tr >
                    <th class="text-center">
                        Nomor Part
                    </th>
                    <th class="text-center">
                        Satuan
                    </th>
                    <th class="text-center">
                        Jumlah
                    </th>
                    <th class="text-center" style="width: 30%;">
                        Remark
                    </th>
                    <th class="text-center" style="width: 20%;">
                        Tindakan
                    </th>
                </tr>
                </thead>
                <tbody>

                @foreach($header->purchase_request_details as $detail)
                    <tr class="item{{ $detail->id }}">
                        <td class='field-item'>
                            {{ $detail->item->code }} - {{ $detail->item->name }}
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
                        <td>
                            <button class="edit-modal btn btn-info" data-id="{{ $detail->id }}" data-item-id="{{ $detail->item_id }}" data-item-text="{{ $detail->item->code. ' - '. $detail->item->name }}" data-qty="{{ $detail->quantity }}" data-remark="{{ $detail->remark }}">
                                <span class="glyphicon glyphicon-edit"></span> Ubah
                            </button>
                            <button class="delete-modal btn btn-danger" data-id="{{ $detail->id }}" data-item-id="{{ $detail->item_id }}" data-item-text="{{ $detail->item->code. ' - '. $detail->item->name }}" data-qty="{{ $detail->quantity }}">
                                <span class="glyphicon glyphicon-trash"></span> Hapus
                            </button>
                        </td>
                    </tr>
                @endforeach

                </tbody>
            </table>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-0"></div>
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
                                <select class="form-control" id="item_edit" name="item"></select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="qty_edit">Jumlah:</label>
                            <div class="col-sm-10">
                                <input type="number" class="form-control" id="qty_edit" name="qty">
                                <p class="errorQty text-center alert alert-danger hidden"></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="remark_edit">Remark:</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" id="remark_edit" name="remark" cols="40" rows="5"></textarea>
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
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script type="text/javascript">
        var i=1;

        $('#machinery').select2({
            placeholder: {
                id: '{{ $header->machinery_id ?? '-1' }}',
                text: '{{ $header->machinery_id !== null ? $header->machinery->code : 'Pilih alat berat...' }}'
            },
            width: '100%',
            minimumInputLength: 2,
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

        $('#select0').select2({
            placeholder: {
                id: '-1',
                text: 'Pilih barang...'
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

        $('#date0').datetimepicker({
            format: "DD MMM Y"
        });

        $('#date_edit').datetimepicker({
            format: "DD MMM Y"
        });

        // Add new detail
        $(document).on('click', '.add-modal', function() {
            $('#item_add').select2({
                placeholder: {
                    id: '-1',
                    text: 'Pilih barang...'
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

            $('.modal-title').text('Tambah Detail');
            $('#addModal').modal('show');
        });
        $('.modal-footer').on('click', '.add', function() {
            $.ajax({
                type: 'POST',
                url: '{{ route('admin.purchase_request_details.store') }}',
                data: {
                    '_token': $('input[name=_token]').val(),
                    'header_id': '{{ $header->id }}',
                    'item': $('#item_add').val(),
                    'qty': $('#qty_add').val(),
                    'remark': $('#remark_add').val()
                },
                success: function(data) {
                    $('.errorItem').addClass('hidden');
                    $('.errorQty').addClass('hidden');
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
                        if (data.errors.remark_add) {
                            $('.errorRemark').removeClass('hidden');
                            $('.errorRemark').text(data.errors.remark);
                        }
                    } else {
                        toastr.success('Berhasil simpan detail!', 'Sukses', {timeOut: 5000});
                        var remarkAdd = '-';
                        if (data.remark !== null) {
                            remarkAdd = data.remark;
                        }
                        $('#detailTable').append("<tr class='item" + data.id + "'><td class='field-item'>" + data.item.code + " - " + data.item.name + "</td><td>" + data.item.uomDescription + "</td><td>" + data.quantity + "</td><td>" + remarkAdd + "</td><td>" + "<button class='edit-modal btn btn-info' data-id='" + data.id + "' data-item-id='" + data.item_id + "' data-item-text='" + data.item.code + " " + data.item.name + "' data-qty='" + data.quantity + "' data-remark='" + data.remark + "'><span class='glyphicon glyphicon-edit'></span> Ubah</button><button class='delete-modal btn btn-danger' data-id='" + data.id + "' data-item-id='" + data.item_id + "' data-item-text='" + data.item.code + " - "  + data.item.name + "' data-qty='" + data.quantity + "'><span class='glyphicon glyphicon-trash'></span> Hapus</button></td></tr>");

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
            $('#date_edit').val($(this).data('date'));
            $('#editModal').modal('show');
        });
        $('.modal-footer').on('click', '.edit', function() {
            $.ajax({
                type: 'PUT',
                url: '{{ route('admin.purchase_request_details.update') }}',
                data: {
                    '_token': $('input[name=_token]').val(),
                    'id' : id,
                    'item': $("#item_edit").val(),
                    'qty': $('#qty_edit').val(),
                    'remark': $('#remark_edit').val()
                },
                success: function(data) {
                    $('.errorQty').addClass('hidden');
                    $('.errorRemark').addClass('hidden');

                    if ((data.errors)) {
                        setTimeout(function () {
                            $('#editModal').modal('show');
                            toastr.error('Gagal ubah data!', 'Peringatan', {timeOut: 5000});
                        }, 500);

                        if (data.errors.qty) {
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
                        $('.item' + data.id).replaceWith("<tr class='item" + data.id + "'><td class='field-item'>" + data.item.code + " - " + data.item.name + "</td><td>" + data.item.uomDescription + "</td><td>" + data.quantity + "</td><td>" + remarkEdit + "</td><td>" + "<button class='edit-modal btn btn-info' data-id='" + data.id + "' data-item-id='" + data.item_id + "' data-item-text='" + data.item.code + " " + data.item.name + "' data-qty='" + data.quantity + "' data-remark=" + data.remark + "><span class='glyphicon glyphicon-edit'></span> Ubah</button><button class='delete-modal btn btn-danger' data-id='" + data.id + "' data-item-id='" + data.item_id + "' data-item-text='" + data.item.code + " - "  + data.item.name + "' data-qty='" + data.quantity + "'><span class='glyphicon glyphicon-trash'></span> hapus</button></td></tr>");
                        // $('.item' + data.id).replaceWith("<tr class='item" + data.id + "'><td>" + data.id + "</td><td>" + data.title + "</td><td>" + data.content + "</td><td class='text-center'><input type='checkbox' class='edit_published' data-id='" + data.id + "'></td><td>Right now</td><td><button class='show-modal btn btn-success' data-id='" + data.id + "' data-title='" + data.title + "' data-content='" + data.content + "'><span class='glyphicon glyphicon-eye-open'></span> Show</button> <button class='edit-modal btn btn-info' data-id='" + data.id + "' data-title='" + data.title + "' data-content='" + data.content + "'><span class='glyphicon glyphicon-edit'></span> Edit</button> <button class='delete-modal btn btn-danger' data-id='" + data.id + "' data-title='" + data.title + "' data-content='" + data.content + "'><span class='glyphicon glyphicon-trash'></span> Delete</button></td></tr>");

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
                url: '{{ route('admin.purchase_request_details.delete') }}',
                data: {
                    '_token': $('input[name=_token]').val(),
                    'id': deletedId
                },
                success: function(data) {
                    toastr.success('Berhasil menghapus detail!', 'Sukses', {timeOut: 5000});
                    $('.item' + data['id']).remove();
                }
            });
        });
    </script>
@endsection