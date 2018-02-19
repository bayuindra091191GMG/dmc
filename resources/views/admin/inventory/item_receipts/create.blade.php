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
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="delivery" >
                    Delivery Note
                    <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <select id="department" name="department" class="form-control col-md-7 col-xs-12 @if($errors->has('delivery_note')) parsley-error @endif">
                        <option value="-1" @if(empty(old('delivery_note'))) selected @endif> - Pilih Delivery Note - </option>
                        @foreach($deliveries as $delivery)
                            <option value="{{ $delivery->id }}" {{ old('delivery_note') == $delivery->id ? "selected":"" }}>{{ $delivery->code }}</option>
                        @endforeach
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
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="no_sj_spb">
                    No SJ/SPB
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="no_sj_spb" type="text" class="form-control col-md-7 col-xs-12 @if($errors->has('no_sj_spb')) parsley-error @endif"
                           name="no_sj_spb" value="{{ old('no_sj_spb') }}">
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
                                Nomor Part
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
                                <select id="select0" name="item[]" class='form-control'></select>
                            </td>
                            <td>
                                <input type='text' name='po[]'  placeholder='Time' class='form-control'/>
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

        $('#select0').select2({
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

        var i=1;
        $("#add_row").click(function(){
            $('#addr'+i).html("<td class='field-item'><select id='select" + i + "' name='item[]' class='form-control'></select></td><td><input type='text' name='po[]'  placeholder='Purchase Order' class='form-control'/></td><td><input type='number' name='qty[]'  placeholder='Jumlah' class='form-control'/></td><td><input type='text' name='remark[]' placeholder='Keterangan' class='form-control'/></td>");

            $('#tab_logic').append('<tr id="addr'+(i+1)+'"></tr>');

            $('#select' + i).select2({
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