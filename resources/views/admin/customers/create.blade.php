.@extends('admin.layouts.admin')

{{--@section('title', 'Tambah Site' )--}}

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12 text-center">
            <h2>Buat Student Baru</h2>
            <hr/>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            @include('partials._success')
            @include('partials._error')
            {{ Form::open(['route'=>['admin.customers.store'],'method' => 'post','id' => 'general-form','class'=>'form-horizontal form-label-left']) }}

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
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="member_id">
                    ID Member
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="member_id" type="text" class="form-control col-md-7 col-xs-12 @if($errors->has('member_id')) parsley-error @endif"
                           name="member_id" value="{{ $memberId }}" readonly>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="barcode">
                    Kode Barcode
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="barcode" type="text" class="form-control col-md-7 col-xs-12 @if($errors->has('barcode')) parsley-error @endif"
                           name="barcode" value="{{ old('barcode') }}">
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
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email" >
                    Alamat Email
                    <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="email" type="text" class="form-control col-md-7 col-xs-12 @if($errors->has('email')) parsley-error @endif"
                           name="email" value="{{ old('email') }}">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="age" >
                    Usia
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="age" type="number" class="form-control col-md-7 col-xs-12 @if($errors->has('age')) parsley-error @endif"
                           name="age" value="{{ old('age') }}">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="dob">
                    Tanggal Lahir
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="dob" type="text" class="form-control col-md-7 col-xs-12 @if($errors->has('dob')) parsley-error @endif"
                           name="dob" value="{{ old('dob') }}">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="phone" >
                    Nomor Telepon
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="phone" type="number" class="form-control col-md-7 col-xs-12 @if($errors->has('phone')) parsley-error @endif"
                           name="phone" value="{{ old('phone') }}" required>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="parent_name" >
                    Nama Orang Tua
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="parent_name" type="text" class="form-control col-md-7 col-xs-12 @if($errors->has('parent_name')) parsley-error @endif"
                           name="parent_name" value="{{ old('parent_name') }}">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="address" >
                    Alamat
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <textarea id="address" rows="5" class="form-control col-md-7 col-xs-12 @if($errors->has('address')) parsley-error @endif"
                              name="address">{{ old('address') }}</textarea>
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                    <a class="btn btn-danger" href="{{ route('admin.customers') }}"> Batal</a>
                    <button type="submit" class="btn btn-success"> Simpan</button>
                </div>
            </div>
            {{ Form::close() }}
        </div>
    </div>
@endsection

@section('styles')
    @parent
    {{ Html::style(mix('assets/admin/css/users/edit.css')) }}
    {{ Html::style(mix('assets/admin/css/bootstrap-datetimepicker.css')) }}
@endsection

@section('scripts')
    @parent
    {{ Html::script(mix('assets/admin/js/users/edit.js')) }}
    {{ Html::script(mix('assets/admin/js/bootstrap-datetimepicker.js')) }}
    <script>
        $('#dob').datetimepicker({
            format: "DD MMM Y"
        });
    </script>
@endsection