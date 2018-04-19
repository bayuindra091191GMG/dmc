@extends('admin.layouts.admin')

@section('title','Buat RFQ Vendor Baru')

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">

            {{ Form::open(['route'=>['admin.quotations.store'],'method' => 'post','class'=>'form-horizontal form-label-left']) }}

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
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="quot_code">
                    Nomor RFQ
                    <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="quot_code" type="text" class="form-control col-md-7 col-xs-12 @if($errors->has('quot_code')) parsley-error @endif"
                           name="quot_code" value="{{ $autoNumber}}" readonly>
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
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="pr_code" >
                    Nomor PR
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="pr_code" type="text" class="form-control col-md-7 col-xs-12 @if($errors->has('pph')) parsley-error @endif"
                           name="pr_code" value="{{ $purchaseRequest->code }}" readonly>
                    <input type="hidden" id="pr_id" name="pr_id" value="{{ $purchaseRequest->id }}">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="supplier" >
                    Vendor
                    <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
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
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="tab_logic">
                            <thead>
                            <tr >
                                <th class="text-center" style="width: 15%">
                                    Nomor Part
                                </th>
                                <th class="text-center" style="width: 15%">
                                    QTY
                                </th><th class="text-center" style="width: 20%">
                                    Harga
                                </th><th class="text-center" style="width: 10%">
                                    Diskon (%)
                                </th>
                                <th class="text-center" style="width: 40%">
                                    Remark
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr id='addr0'>
                                <td class='field-item'>
                                    <select id="select0" name="item[]" class='form-control'></select>
                                </td>
                                <td>
                                    <input type='number' name='qty[]'  placeholder='QTY' class='form-control'/>
                                </td>
                                <td>
                                    <input id="price0" type='text' name='price[]'  placeholder='Harga' class='form-control'/>
                                </td>
                                <td>
                                    <input type='number' name='discount[]'  placeholder='Diskon' class='form-control'/>
                                </td>
                                <td>
                                    <input type='text' name='remark[]' placeholder='Keterangan' class='form-control'/>
                                </td>
                            </tr>
                            <tr id='addr1'></tr>
                            </tbody>
                        </table>
                    </div>
                    <a id="add_row" class="btn btn-default pull-left">Tambah</a><a id='delete_row' class="pull-right btn btn-default">Hapus</a>
                </div>
            </div>

            <hr/>

            <div class="form-group">
                <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                    <a class="btn btn-danger" href="{{ route('admin.quotations') }}"> Batal</a>
                    <button type="submit" class="btn btn-success"> Simpan</button>
                </div>
            </div>
            {{ Form::close() }}
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
    {{ Html::script(mix('assets/admin/js/bootstrap-datetimepicker.js')) }}
    <script type="text/javascript">
        // Auto Numbering
        $('#auto_number').change(function(){
            if(this.checked){
                $('#quot_code').val('{{ $autoNumber }}');
                $('#quot_code').prop('readonly', true);
            }
            else{
                $('#quot_code').val('');
                $('#quot_code').prop('readonly', false);
            }
        });

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

        $('#supplier').select2({
            placeholder: {
                id: '-1',
                text: ' - Pilih Vendor -'
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

        // autoNumeric
        numberFormat = new AutoNumeric('#price0', {
            decimalCharacter: ',',
            digitGroupSeparator: '.',
            decimalPlaces: 0
        });

        $('#select0').select2({
            placeholder: {
                id: '-1',
                text: ' - Pilih Inventory - '
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

        var i=1;
        $("#add_row").click(function(){
            $('#addr'+i).html("<td class='field-item'><select id='select" + i + "' name='item[]' class='form-control'></select></td><td><input type='number' name='qty[]'  placeholder='Jumlah' class='form-control'/></td><td><input type='text' id='price" + i + "' name='price[]'  placeholder='Harga' class='form-control'/></td><td><input type='number' name='discount[]'  placeholder='Diskon' class='form-control'/></td><td><input type='text' name='remark[]' placeholder='Keterangan' class='form-control'/></td>");

            $('#tab_logic').append('<tr id="addr'+(i+1)+'"></tr>');

            $('#select' + i).select2({
                placeholder: {
                    id: '-1',
                    text: ' - Pilih Inventory - '
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

            // autoNumeric
            numberFormat = new AutoNumeric('#price' + i, {
                decimalCharacter: ',',
                digitGroupSeparator: '.',
                decimalPlaces: 0
            });

            i++;
        });

        $("#delete_row").click(function(){
            if(i>1){
                $("#addr"+(i-1)).html('');
                i--;
            }
        });
    </script>
@endsection