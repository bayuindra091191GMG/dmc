@extends('admin.layouts.admin')

@section('title','Tambah Data Barang')

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">

            {{ Form::open(['route'=>['admin.items.store'],'method' => 'post','class'=>'form-horizontal form-label-left']) }}

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
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="code" >
                    Kode Inventory
                    <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="code" type="text" class="form-control col-md-7 col-xs-12 @if($errors->has('code')) parsley-error @endif"
                           name="code" value="{{ old('code') }}" required>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name" >
                    Nama Inventory
                    <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="name" type="text" class="form-control col-md-7 col-xs-12 @if($errors->has('name')) parsley-error @endif"
                           name="name" value="{{ old('name') }}" required>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="group" >
                    Kategori Inventory
                    <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <select id="group" name="group" class="form-control col-md-7 col-xs-12 @if($errors->has('group')) parsley-error @endif">
                        <option value="-1" @if(empty(old('group'))) selected @endif> - Pilih kategori inventory - </option>
                        @foreach($groups as $group)
                            <option value="{{ $group->id }}" {{ old('group') == $group->id ? "selected":"" }}>{{ $group->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="part_number">
                    Part Number Asli
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="part_number" type="text" class="form-control col-md-7 col-xs-12 @if($errors->has('part_number')) parsley-error @endif"
                           name="part_number" value="{{ old('part_number') }}">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="uom" >
                    UOM
                    <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="uom" type="text" class="form-control col-md-7 col-xs-12 @if($errors->has('uom')) parsley-error @endif"
                           name="uom" value="{{ old('uom') }}" required>
                </div>
            </div>


            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="machinery_type" >
                    Tipe Alat Berat
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="machinery_type" type="text" class="form-control col-md-7 col-xs-12 @if($errors->has('machinery_type')) parsley-error @endif"
                           name="machinery_type" value="{{ old('machinery_type') }}">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="valuation" >
                    Nilai Beli per UOM
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="valuation" type="text" class="form-control col-md-7 col-xs-12 @if($errors->has('valuation')) parsley-error @endif"
                           name="valuation" value="{{ old('valuation') }}">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="stock_min" >
                    Minimal Stok
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="stock_min" type="text" class="form-control col-md-7 col-xs-12 @if($errors->has('stock_min')) parsley-error @endif"
                           name="stock_min" value="{{ old('stock_min') }}">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="stock_notif"></label>
                <div class="col-md-4 col-sm-4 col-xs-12">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" class="flat" id="stock_notif" name="stock_notif" checked="checked"> Notifikasi Stok
                        </label>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="description" >
                    Keterangan Tambahan
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <textarea id="description" name="description" rows="5" class="form-control col-md-7 col-xs-12 @if($errors->has('description')) parsley-error @endif" style="resize: vertical">{{ old('description') }}</textarea>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Gudang dan Stok</label>
                <div class="col-lg-4 col-md-4 col-xs-12 column">
                    <table class="table table-bordered table-hover" id="tab_logic">
                        <thead>
                        <tr >
                            <th class="text-center" style="width: 60%">
                                Gudang
                            </th>
                            <th class="text-center" style="width: 40%">
                                Stok
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr id='addr0'>
                            <td class='field-item'>
                            <select id="warehouse0" name="warehouse[]" class='form-control'></select>
                            </td>
                            <td>
                            <input type='number' name='qty[]'  placeholder='Stok' class='form-control'/>
                            </td>
                        </tr>
                        </tbody>
                        <tr id='addr1'></tr>
                    </table>
                    <a id="add_row" class="btn btn-default pull-left" style="margin-bottom: 10px;">Tambah</a><a id='delete_row' class="pull-right btn btn-default">Hapus</a>
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                    <a class="btn btn-primary" href="{{ route('admin.items') }}"> Batal</a>
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
@endsection

@section('scripts')
    @parent
    {{ Html::script(mix('assets/admin/js/select2.js')) }}
    {{ Html::script(mix('assets/admin/js/autonumeric.js')) }}
    <script type="text/javascript">

        // autoNumeric
        valuationFormat = new AutoNumeric('#valuation', {
            decimalCharacter: ',',
            digitGroupSeparator: '.',
            decimalPlaces: 0
        });

        minStockFormat = new AutoNumeric('#min_stock', {
            minimumValue: '0',
            digitGroupSeparator: '',
            decimalPlaces: 0
        });

        // Select warehouses
        $('#warehouse0').select2({
            placeholder: {
                id: '-1',
                text: '- Pilih Gudang -'
            },
            width: '100%',
            ajax: {
                url: '{{ route('select.warehouses') }}',
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
            $('#addr' + i).html("<td class='field-item'><select id='warehouse" + i + "' name='warehouse[]' class='form-control'></select></td><td><input type='number' name='qty[]'  placeholder='Stok' class='form-control'/></td>");

            $('#tab_logic').append('<tr id="addr'+(i+1)+'"></tr>');

            $('#warehouse' + i).select2({
                placeholder: {
                    id: '-1',
                    text: '- Pilih Gudang -'
                },
                width: '100%',
                ajax: {
                    url: '{{ route('select.warehouses') }}',
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