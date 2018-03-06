@extends('admin.layouts.admin')

@section('title','Tambah Data Vendor')

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">

            {{ Form::open(['route'=>['admin.suppliers.store'],'method' => 'post','class'=>'form-horizontal form-label-left']) }}
            @include('partials._success')

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
                    Kode
                    <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="code" type="text" class="form-control col-md-7 col-xs-12 @if($errors->has('code')) parsley-error @endif"
                           name="code" value="{{ old('code') }}" required>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name" >
                    Nama
                    <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="name" type="text" class="form-control col-md-7 col-xs-12 @if($errors->has('name')) parsley-error @endif"
                           name="name" value="{{ old('name') }}" required>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">
                    Email
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="email" type="email" class="form-control col-md-7 col-xs-12 @if($errors->has('email')) parsley-error @endif"
                           name="email" value="{{ old('email') }}">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="contact_person" >
                    Contact Person
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="contact_person" type="text" class="form-control col-md-7 col-xs-12 @if($errors->has('contact_person')) parsley-error @endif"
                           name="contact_person" value="{{ old('contact_person') }}">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="phone" >
                    Telpon
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="phone" type="text" class="form-control col-md-7 col-xs-12 @if($errors->has('phone')) parsley-error @endif"
                           name="phone" value="{{ old('phone') }}">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="fax" >
                    Fax
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="fax" type="text" class="form-control col-md-7 col-xs-12 @if($errors->has('fax')) parsley-error @endif"
                           name="fax" value="{{ old('fax') }}">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="cellphone" >
                    Handphone
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="cellphone" type="text" class="form-control col-md-7 col-xs-12 @if($errors->has('cellphone')) parsley-error @endif"
                           name="cellphone" value="{{ old('cellphone') }}">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="city" >
                    Kota
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="city" type="text" class="form-control col-md-7 col-xs-12 @if($errors->has('city')) parsley-error @endif"
                           name="city" value="{{ old('city') }}">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="address" >
                    Alamat
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <textarea id="address" name="address" rows="5" class="form-control col-md-7 col-xs-12 @if($errors->has('address')) parsley-error @endif" style="resize: vertical">{{ old('address') }}</textarea>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="remark" >
                    Keterangan Tambahan
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <textarea id="remark" name="remark" rows="5" class="form-control col-md-7 col-xs-12 @if($errors->has('remark')) parsley-error @endif" style="resize: vertical">{{ old('remark') }}</textarea>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="npwp" >
                    NPWP
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="npwp" type="text" class="form-control col-md-7 col-xs-12 @if($errors->has('npwp')) parsley-error @endif"
                           name="npwp" value="{{ old('npwp') }}">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="bank_name" >
                    Nama Bank
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="bank_name" type="text" class="form-control col-md-7 col-xs-12 @if($errors->has('bank_name')) parsley-error @endif"
                           name="bank_name" value="{{ old('bank_name') }}">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="bank_account_number" >
                    No. Rekening Bank
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="bank_account_number" type="text" class="form-control col-md-7 col-xs-12 @if($errors->has('bank_account_number')) parsley-error @endif"
                           name="bank_account_number" value="{{ old('bank_account_number') }}">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="bank_account_name" >
                    Nama Rekening
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="bank_account_name" type="text" class="form-control col-md-7 col-xs-12 @if($errors->has('bank_account_name')) parsley-error @endif"
                           name="bank_account_name" value="{{ old('bank_account_name') }}">
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                    <a class="btn btn-primary" href="{{ route('admin.suppliers') }}"> Batal</a>
                    <button type="submit" class="btn btn-success"> Simpan</button>
                </div>
            </div>
            {{ Form::close() }}
        </div>
    </div>
@endsection

{{--@section('styles')--}}
    {{--@parent--}}
    {{--{{ Html::style(mix('assets/admin/css/bootstrap-datetimepicker.css')) }}--}}
{{--@endsection--}}

{{--@section('scripts')--}}
    {{--@parent--}}
    {{--{{ Html::script(mix('assets/admin/js/bootstrap-datetimepicker.js')) }}--}}
    {{--<script>--}}
        {{--$('#contract_start').datetimepicker({--}}
            {{--format: "DD MMM Y"--}}
        {{--});--}}
        {{--$('#contract_finish').datetimepicker({--}}
            {{--format: "DD MMM Y"--}}
        {{--});--}}
    {{--</script>--}}
{{--@endsection--}}