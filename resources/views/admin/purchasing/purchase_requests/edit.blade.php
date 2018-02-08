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
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="sn_chasis">
                    S/N Chasis
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="sn_chasis" type="text" class="form-control col-md-7 col-xs-12 @if($errors->has('sn_chasis')) parsley-error @endif"
                           name="sn_chasis" value="{{ $header->sn_chasis }}">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="sn_engine">
                    S/N Engine
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="sn_engine" type="text" class="form-control col-md-7 col-xs-12 @if($errors->has('sn_engine')) parsley-error @endif"
                           name="sn_engine" value="{{ $header->sn_engine  }}">
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
            <table class="table table-bordered table-hover">
                <thead>
                <tr >
                    <th class="text-center">
                        Nomor Part (Part Number)
                    </th>
                    <th clas="text-center">
                        Satuan (UOM)
                    </th>
                    <th class="text-center">
                        Jumlah (QTY)
                    </th>
                    <th class="text-center">
                        Remark
                    </th>
                    <th class="text-center">
                        Tanggal Penyerahan (Delivery Date)
                    </th>
                    <th class="text-center">
                        Opsi
                    </th>
                </tr>
                </thead>
                <tbody>

                @foreach($header->purchase_request_details as $detail)
                    <tr>
                        <td class='field-item'>
                            {{ $detail->item->code }}
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
                        <td class='field-date'>
                            {{ \Carbon\Carbon::parse($detail->delivery_date)->format('d M Y') }}
                        </td>
                        <td>
                            <button class="edit-modal btn btn-info" data-code="{{ $detail->item->code }}" data-qty="{{ $detail->quantity }}" data-remark="{{ $detail->remark }}" data-date="{{ \Carbon\Carbon::parse($detail->delivery_date)->format('d M Y') }}">
                                <span class="glyphicon glyphicon-edit"></span> Edit</button>
                        </td>
                    </tr>
                @endforeach

                </tbody>
            </table>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-0"></div>
    </div>

    <!-- Modal form to edit a form -->
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
                                <input type="text" class="form-control" id="qty_edit" name="qty">
                                <p class="errorTitle text-center alert alert-danger hidden"></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="remark_edit">Remark:</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" id="remark_edit" name="remark" cols="40" rows="5"></textarea>
                                <p class="errorContent text-center alert alert-danger hidden"></p>
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
    {{ Html::script(mix('assets/admin/js/bootstrap-datetimepicker.js')) }}
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

        var i=1;
        $("#add_row").click(function(){
            $('#addr'+i).html("<td class='field-item'><select id='select" + i + "' name='item[]' class='form-control'></select></td><td><input type='number' name='qty[]'  placeholder='Jumlah' class='form-control'/></td><td><input type='text' name='remark[]' placeholder='Keterangan' class='form-control'/></td><td class='field-date'><input type='text' id='date" + i + "' name='date[]' placeholder='Tanggal Penyerahan' class='form-control'/></td>");

            $('#tab_logic').append('<tr id="addr'+(i+1)+'"></tr>');

            $('#select' + i).select2({
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

            $('#date' + i).datetimepicker({
                format: "DD MMM Y"
            });

            i++;
        });

        $("#delete_row").click(function(){
            if(i>1){
                $("#addr"+(i-1)).html('');
                i--;
            }
        });

        $(document).on('click', '.edit-modal', function() {
            $('.modal-title').text('Ubah');
            // $('#code_edit').val($(this).data('id'));
            $('#qty_edit').val($(this).data('qty'));
            $('#remark_edit').val($(this).data('remark'));
            $('#date_edit').val($(this).data('date'));
            $('#editModal').modal('show');
        });
    </script>
@endsection