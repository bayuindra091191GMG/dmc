@extends('admin.layouts.admin')

@section('title','Buat Quotation Vendor Baru')

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
                    Nomor Quotation
                    <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="quot_code" type="text" class="form-control col-md-7 col-xs-12 @if($errors->has('quot_code')) parsley-error @endif"
                           name="quot_code" value="{{ old('quot_code') }}">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="pr_code">
                    Nomor PR
                    <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <select id="pr_code" name="pr_code" class="form-control col-md-7 col-xs-12 @if($errors->has('pr_code')) parsley-error @endif">
                    </select>
                </div>
                {{--<div id="check-pr-section" class="col-md-2 col-sm-2 col-xs-12">--}}
                    {{--<button class="check-pr btn btn-info">Lihat PR</button>--}}
                {{--</div>--}}
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
                <label class="text-center col-md-12 col-xs-12">Detil Barang</label>
            </div>

            <div class="form-group">
                <div class="col-lg-1 col-md-1 col-xs-0"></div>
                <div class="col-lg-10 col-md-10 col-xs-12 column">
                    <table class="table table-bordered table-hover" id="tab_logic">
                        <thead>
                        <tr >
                            <th class="text-center" style="width: 15%">
                                Nomor Part
                            </th>
                            <th class="text-center" style="width: 15%">
                                Jumlah
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
                                <input type='number' name='qty[]'  placeholder='Jumlah' class='form-control'/>
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
                    <a id="add_row" class="btn btn-default pull-left">Tambah</a><a id='delete_row' class="pull-right btn btn-default">Hapus</a>
                </div>
                <div class="col-lg-1 col-md-1 col-xs-0"></div>
            </div>

            <div class="form-group">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                    <a class="btn btn-primary" href="{{ route('admin.quotations') }}"> Batal</a>
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
        $('#pr_code').select2({
            placeholder: {
                id: '-1',
                text: 'Pilih Nomor PR...'
            },
            width: '100%',
            minimumInputLength: 2,
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
                text: 'Pilih Vendor...'
            },
            width: '100%',
            minimumInputLength: 2,
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

        var i=1;
        $("#add_row").click(function(){
            $('#addr'+i).html("<td class='field-item'><select id='select" + i + "' name='item[]' class='form-control'></select></td><td><input type='number' name='qty[]'  placeholder='Jumlah' class='form-control'/></td><td><input type='text' id='price" + i + "' name='price[]'  placeholder='Harga' class='form-control'/></td><td><input type='number' name='discount[]'  placeholder='Diskon' class='form-control'/></td><td><input type='text' name='remark[]' placeholder='Keterangan' class='form-control'/></td>");

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