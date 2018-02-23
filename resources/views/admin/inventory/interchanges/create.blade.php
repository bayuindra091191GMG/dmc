@extends('admin.layouts.admin')

@section('title','Tambah Interchange')

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">

            {{ Form::open(['route'=>['admin.interchanges.store'],'method' => 'post','class'=>'form-horizontal form-label-left']) }}

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
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="item_id_before">
                    Barang Sebelumnya
                    <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <select id="item_id_before" name="item_id_before" class="form-control col-md-7 col-xs-12 @if($errors->has('item_id_before')) parsley-error @endif">
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name" >
                    Nama Barang
                    <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="name" type="text" class="form-control col-md-7 col-xs-12 @if($errors->has('name')) parsley-error @endif"
                           name="name" value="{{ old('name') }}" required>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">
                    Part Number
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="code" type="text" class="form-control col-md-7 col-xs-12 @if($errors->has('code')) parsley-error @endif"
                           name="code" value="{{ old('code') }}">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="uom" >
                    UOM
                    <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <select id="uom" name="uom" class="form-control col-md-7 col-xs-12 @if($errors->has('uom')) parsley-error @endif">
                        <option value="-1" @if(empty(old('uom'))) selected @endif> - Pilih satuan unit - </option>
                        @foreach($uoms as $uom)
                            <option value="{{ $uom->id }}" {{ old('uom') == $uom->id ? "selected":"" }}>{{ $uom->description }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="group" >
                    Group
                    <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <select id="group" name="group" class="form-control col-md-7 col-xs-12 @if($errors->has('group')) parsley-error @endif">
                        <option value="-1" @if(empty(old('group'))) selected @endif> - Pilih group - </option>
                        @foreach($groups as $group)
                            <option value="{{ $group->id }}" {{ old('group') == $group->id ? "selected":"" }}>{{ $group->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="valuation" >
                    Nilai Awal per UOM
                    <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12 price-format">
                    <input id="valuation" type="text" class="form-control col-md-7 col-xs-12 @if($errors->has('code')) parsley-error @endif"
                           name="valuation" value="{{ old('valuation') }}">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="warehouse" >
                    Gudang
                    <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <select id="warehouse" name="warehouse" class="form-control col-md-7 col-xs-12 @if($errors->has('warehouse')) parsley-error @endif">
                        <option value="-1" @if(empty(old('warehouse'))) selected @endif> - Pilih gudang - </option>
                        @foreach($warehouses as $warehouse)
                            <option value="{{ $warehouse->id }}" {{ old('warehouse') == $warehouse->id ? "selected":"" }}>{{ $warehouse->name }}</option>
                        @endforeach
                    </select>
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
    {{ Html::style(mix('assets/admin/css/bootstrap-datetimepicker.css')) }}
@endsection

@section('scripts')
    @parent
    {{ Html::script(mix('assets/admin/js/select2.js')) }}
    {{ Html::script(mix('assets/admin/js/autonumeric.js')) }}
    <script type="text/javascript">

        $('#item_id_before').select2({
            placeholder: {
                id: '-1',
                text: 'Pilih No Barang...'
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

        // autoNumeric
        numberFormat = new AutoNumeric('.price-format > input', {
            decimalCharacter: ',',
            digitGroupSeparator: '.',
            decimalPlaces: 0
        });
    </script>
@endsection