@extends('admin.layouts.admin')

@section('title','Buat Item Receipt Baru')

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">

            {{ Form::open(['route'=>['admin.item_receipts.store'],'method' => 'post','class'=>'form-horizontal form-label-left']) }}

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
                    Item Receipt No
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="code" type="text" class="form-control col-md-7 col-xs-12 @if($errors->has('code')) parsley-error @endif"
                           name="code" value="{{ $autoNumber }}" disabled="disabled">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="code">

                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" class="flat" id="auto_number" name="auto_number" checked="checked"> Auto Number
                        </label>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="delivery_order" >
                    Delivery Orders
                    <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <select id="delivery_order" name="delivery_order" class="form-control col-md-7 col-xs-12 @if($errors->has('delivery_order')) parsley-error @endif">
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="date" >
                    Date
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="date" type="text" class="form-control col-md-7 col-xs-12 @if($errors->has('date')) parsley-error @endif"
                           name="date" value="{{ old('date') }}">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="delivered_from">
                    Pengiriman Dari
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="delivered_from" type="text" class="form-control col-md-7 col-xs-12 @if($errors->has('delivered_from')) parsley-error @endif"
                           name="delivered_from" value="{{ old('delivered_from') }}">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="angkutan">
                    Angkutan
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="angkutan" type="text" class="form-control col-md-7 col-xs-12 @if($errors->has('angkutan')) parsley-error @endif"
                           name="angkutan" value="{{ old('angkutan') }}">
                </div>
            </div>

            <div class="form-group">
                <label class="text-center col-md-12 col-xs-12">Detil Barang</label>
            </div>

            <div class="form-group">
                <div class="col-lg-2 col-md-2 col-xs-0"></div>
                <div class="col-lg-8 col-md-8 col-xs-12 column">
                    <table class="table table-bordered table-hover" id="tab_logic">
                        <thead>
                        <tr >
                            <th class="text-center" style="width: 40%">
                                Nomor Item
                            </th>
                            <th class="text-center" style="width: 20%">
                                Nomor PO
                            </th>
                            <th class="text-center" style="width: 20%">
                                Jumlah
                            </th>
                            <th class="text-center" style="width: 40%">
                                Keterangan
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr id='addr0'>
                            <td class='field-item'>
                                <select id="selectItem0" name="item[]" class='form-control'></select>
                            </td>
                            <td>
                                <select id="selectPo0" name="po[]" class='form-control'></select>
                            </td>
                            <td>
                                <input type='number' name='qty[]'  placeholder='Jumlah' class='form-control'/>
                            </td>
                            <td>
                                <input type='text' name='remark[]' placeholder='Keterangan' class='form-control'/>
                            </td>
                        </tr>
                        <tr id='addr1'></tr>
                        </tbody>
                    </table>
                    <a id="add_row" class="btn btn-default pull-left">Tambah</a><a id='delete_row' class="pull-right btn btn-default">Hapus</a>
                </div>
                <div class="col-lg-2 col-md-2 col-xs-0"></div>
            </div>

            <div class="form-group">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                    <a class="btn btn-primary" href="{{ route('admin.item_receipts') }}"> Batal</a>
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
@endsection

@section('scripts')
    @parent
    {{ Html::script(mix('assets/admin/js/select2.js')) }}
    {{ Html::script(mix('assets/admin/js/bootstrap-datetimepicker.js')) }}

    <script>
        $('#date').datetimepicker({
            format: "DD MMM Y"
        });
    </script>

    <script type="text/javascript">
        var i=1;

        {{--$('#purchase_request_header').select2({--}}
            {{--placeholder: {--}}
                {{--id: '-1',--}}
                {{--text: 'Pilih No PR...'--}}
            {{--},--}}
            {{--width: '100%',--}}
            {{--minimumInputLength: 2,--}}
            {{--ajax: {--}}
                {{--url: '{{ route('select.purchase_requests') }}',--}}
                {{--dataType: 'json',--}}
                {{--data: function (params) {--}}
                    {{--return {--}}
                        {{--q: $.trim(params.term)--}}
                    {{--};--}}
                {{--},--}}
                {{--processResults: function (data) {--}}
                    {{--return {--}}
                        {{--results: data--}}
                    {{--};--}}
                {{--}--}}
            {{--}--}}
        {{--});--}}

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

         $('#delivery_order').select2({
            placeholder: {
                id: '-1',
                text: 'Pilih Delivery Order...'
            },
            width: '100%',
            minimumInputLength: 2,
            ajax: {
                url: '{{ route('select.delivery_orders') }}',
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

        $('#selectPo0').select2({
            placeholder: {
                id: '-1',
                text: 'Pilih Purchase Order...'
            },
            width: '100%',
            minimumInputLength: 2,
            ajax: {
                url: '{{ route('select.purchase_orders') }}',
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

        $('#selectItem0').select2({
            placeholder: {
                id: '-1',
                text: 'Pilih Barang...'
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

        var i=1;
        $("#add_row").click(function(){
            $('#addr'+i).html("<td class='field-item'><select id='selectItem" + i + "' name='item[]' class='form-control'></select></td><td><select id='selectPo" + i + "' name='po[]' class='form-control'></select></td><td><input type='number' name='qty[]'  placeholder='Jumlah' class='form-control'/></td><td><input type='text' name='remark[]' placeholder='Keterangan' class='form-control'/></td>");

            $('#tab_logic').append('<tr id="addr'+(i+1)+'"></tr>');

            $('#selectPo' + i).select2({
                placeholder: {
                    id: '-1',
                    text: 'Pilih Purchase Order...'
                },
                width: '100%',
                minimumInputLength: 2,
                ajax: {
                    url: '{{ route('select.purchase_orders') }}',
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

            $('#selectItem' + i).select2({
                placeholder: {
                    id: '-1',
                    text: 'Pilih Barang...'
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