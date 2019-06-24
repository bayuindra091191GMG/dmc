@extends('admin.layouts.admin')

{{--@section('title', 'Ubah data Site')--}}

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12 text-center">
            <h2>Ubah Data Customer {{ $customer->name }}</h2>
            <hr/>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            @include('partials._success')
            @include('partials._error')
            {{ Form::open(['route'=>['admin.customers.update', $customer->id],'method' => 'put','id' => 'general-form','class'=>'form-horizontal form-label-left']) }}
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
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name" >
                    Nama
                    <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="name" type="text" class="form-control col-md-7 col-xs-12 @if($errors->has('name')) parsley-error @endif"
                           name="name" value="{{ $customer->name }}" required>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="age" >
                    Umur
                    <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="age" type="text" class="form-control col-md-7 col-xs-12 @if($errors->has('age')) parsley-error @endif"
                           name="age" value="{{ $customer->age }}">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email" >
                    Email
                    <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="email" type="text" class="form-control col-md-7 col-xs-12 @if($errors->has('email')) parsley-error @endif"
                           name="email" value="{{ $customer->email }}">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="phone" >
                    No Telepon
                    <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="phone" type="number" class="form-control col-md-7 col-xs-12 @if($errors->has('phone')) parsley-error @endif"
                           name="phone" value="{{ $customer->phone }}" required>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="parent_name" >
                    Nama Orang Tua
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="parent_name" type="text" class="form-control col-md-7 col-xs-12 @if($errors->has('parent_name')) parsley-error @endif"
                           name="parent_name" value="{{ $customer->parent_name }}">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="address" >
                    Alamat
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <textarea id="address" rows="5" class="form-control col-md-7 col-xs-12 @if($errors->has('address')) parsley-error @endif"
                              name="address">{{$customer->address}}</textarea>
                </div>
            </div>

                <div class="form-group">
                    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        <a class="btn btn-primary" href="{{ route('admin.customers') }}"> Batal</a>
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