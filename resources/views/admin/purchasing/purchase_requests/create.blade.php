@extends('admin.layouts.admin')

@section('title','Buat Purchase Request Baru')

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">

            {{ Form::open(['route'=>['admin.purchase_requests.store'],'method' => 'post','class'=>'form-horizontal form-label-left']) }}

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
                        <option value="-1" @if(empty(old('uom'))) selected @endif> - Pilih departemen - </option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}" {{ old('department') == $department->id ? "selected":"" }}>{{ $department->name }}</option>
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
                           name="sn_chasis" value="{{ old('sn_chasis') }}">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="sn_engine">
                    S/N Engine
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="sn_engine" type="text" class="form-control col-md-7 col-xs-12 @if($errors->has('sn_engine')) parsley-error @endif"
                           name="sn_engine" value="{{ old('sn_engine') }}">
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
                            <th class="text-center">
                                Nomor Part (Part Number)
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
                        </tr>
                        </thead>
                        <tbody>
                        <tr id='addr0'>
                            <td class='field-item'>
                                <select id="select0" name="item[]" class='form-control'></select>
                            </td>
                            <td>
                                <input type='number' name='qty[]'  placeholder='Jumlah' class='form-control'/>
                            </td>
                            <td>
                                <input type='text' name='remark[]' placeholder='Keterangan' class='form-control'/>
                            </td>
                            <td class='field-date'>
                                <input type='text' id='date0' name='date[]' placeholder='Tanggal Penyerahan' class='form-control'/>
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
                    <a class="btn btn-primary" href="{{ route('admin.purchase_requests') }}"> Batal</a>
                    <button type="submit" class="btn btn-success"> </button>
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
    <script type="text/javascript">
        var i=1;

        $('#machinery').select2({
            placeholder: {
                id: '-1',
                text: 'Pilih Alat Berat...'
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
    </script>
@endsection