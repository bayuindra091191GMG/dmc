@extends('admin.layouts.admin')

@section('title', 'Tambah Data Status' )

@section('content')
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        {{ Form::open(['route'=>['admin.statuses.store'],'method' => 'post','id' => 'general-form','class'=>'form-horizontal form-label-left']) }}

        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name" >
                Nama Status
                <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input id="description" type="text" class="form-control col-md-7 col-xs-12 @if($errors->has('description')) parsley-error @endif"
                       name="description" value="{{ old('description') }}" required>
                @if($errors->has('description'))
                <ul class="parsley-errors-list filled">
                    @foreach($errors->get('description') as $error)
                    <li class="parsley-required">{{ $error }}</li>
                    @endforeach
                </ul>
                @endif
            </div>
        </div>


        <div class="form-group">
            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                <a class="btn btn-primary" href="{{ route('admin.statuses') }}"> Batal</a>
                <button type="submit" class="btn btn-success"> Simpan</button>
            </div>
        </div>
        {{ Form::close() }}
    </div>
</div>
@endsection