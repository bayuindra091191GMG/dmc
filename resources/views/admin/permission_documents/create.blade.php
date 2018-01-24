.@extends('admin.layouts.admin')

@section('title', 'Tambah Otorisasi Dokumen' )

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            @include('partials._success')
            {{ Form::open(['route'=>['admin.permission_documents.store'],'method' => 'post','class'=>'form-horizontal form-label-left']) }}

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="department" >
                    Role
                    <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <select id="role" name="role" class="form-control col-md-7 col-xs-12 @if($errors->has('role')) parsley-error @endif">
                        <option value="-1" @if(empty(old('role'))) selected @endif>Pilih Role</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ old('role') == $role->id ? "selected":"" }}>{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="department" >
                    Dokumen
                    <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <select id="document" name="document" class="form-control col-md-7 col-xs-12 @if($errors->has('document')) parsley-error @endif">
                        <option value="-1" @if(empty(old('document'))) selected @endif>Pilih Dokumen</option>
                        @foreach($documents as $document)
                            <option value="{{ $document->id }}" {{ old('document') == $document->id ? "selected":"" }}>{{ $document->description }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name" >
                    Melihat
                    <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="checkbox" name="read" id="read" value="read" data-parsley-mincheck="2" class="flat" />
                    @if($errors->has('read'))
                        <ul class="parsley-errors-list filled">
                            @foreach($errors->get('read') as $error)
                                <li class="parsley-required">{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name" >
                    Membuat
                    <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="checkbox" name="create" id="create" value="create" data-parsley-mincheck="2" class="flat" />
                    @if($errors->has('create'))
                        <ul class="parsley-errors-list filled">
                            @foreach($errors->get('create') as $error)
                                <li class="parsley-required">{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name" >
                    Mengubah
                    <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="checkbox" name="update" id="update" value="update" data-parsley-mincheck="2" class="flat" />
                    @if($errors->has('update'))
                        <ul class="parsley-errors-list filled">
                            @foreach($errors->get('update') as $error)
                                <li class="parsley-required">{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name" >
                    Menghapus
                    <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="checkbox" name="delete" id="delete" value="delete" data-parsley-mincheck="2" class="flat" />
                    @if($errors->has('read'))
                        <ul class="parsley-errors-list filled">
                            @foreach($errors->get('read') as $error)
                                <li class="parsley-required">{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name" >
                    Mencetak
                    <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="checkbox" name="print" id="print" value="print" data-parsley-mincheck="2" class="flat" />
                    @if($errors->has('print'))
                        <ul class="parsley-errors-list filled">
                            @foreach($errors->get('print') as $error)
                                <li class="parsley-required">{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                    <a class="btn btn-primary" href="{{ route('admin.permission_documents') }}"> Batal</a>
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