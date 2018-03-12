@extends('admin.layouts.admin')

@section('title', 'Ubah data Site')

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            @include('partials._success')
            @include('partials._error')
            {{ Form::open(['route'=>['admin.sites.update', $site->id],'method' => 'put','class'=>'form-horizontal form-label-left']) }}
            {{ csrf_field() }}

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
                           name="code" value="{{ $site->code }}" required>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name" >
                    Nama
                    <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="name" type="text" class="form-control col-md-7 col-xs-12 @if($errors->has('name')) parsley-error @endif"
                           name="name" value="{{ $site->name }}" required>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="location" >
                    Lokasi
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="location" type="text" class="form-control col-md-7 col-xs-12 @if($errors->has('location')) parsley-error @endif"
                           name="location" value="{{ $site->location }}">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="phone" >
                    Nomor Telpon
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="phone" type="text" class="form-control col-md-7 col-xs-12 @if($errors->has('phone')) parsley-error @endif"
                           name="phone" value="{{ $site->phone }}">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="pic" >
                    PIC
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="pic" type="text" class="form-control col-md-7 col-xs-12 @if($errors->has('pic')) parsley-error @endif"
                           name="pic" value="{{ $site->pic  }}">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="warehouse_code" >
                    Kode Gudang
                    <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="warehouse_code" type="text" class="form-control col-md-7 col-xs-12 @if($errors->has('warehouse_code')) parsley-error @endif"
                           name="warehouse_code" value="{{ $site->warehouse->code }}" required>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="warehouse_name" >
                    Nama Gudang
                    <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="warehouse_name" type="text" class="form-control col-md-7 col-xs-12 @if($errors->has('warehouse_name')) parsley-error @endif"
                           name="warehouse_name" value="{{ $site->warehouse->name }}" required>
                </div>
            </div>

                <div class="form-group">
                    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        <a class="btn btn-primary" href="{{ route('admin.sites') }}"> Batal</a>
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
@endsection

@section('scripts')
    @parent
    {{ Html::script(mix('assets/admin/js/users/edit.js')) }}
@endsection