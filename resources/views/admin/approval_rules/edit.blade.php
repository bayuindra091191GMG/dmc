@extends('admin.layouts.admin')

@section('title', 'Ubah data Pengaturan Approval')

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            @include('partials._success')
            @include('partials._error')
            {{ Form::open(['route'=>['admin.approval_rules.update', $approvalRule->id],'method' => 'put','class'=>'form-horizontal form-label-left']) }}
            {{ csrf_field() }}
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="user" >
                        User
                        <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <select id="user" name="user" class="form-control col-md-7 col-xs-12 @if($errors->has('user')) parsley-error @endif">
                            <option value="-1" @if(empty(old('user'))) selected @endif>Pilih User</option>
                            @foreach($users as $user)
                                @if($user->id == $approvalRule->user_id)
                                    <option value="{{ $user->id }}" selected >{{ $user->email }} - {{ $user->name }}</option>
                                @else
                                    <option value="{{ $user->id }}" {{ old('user') == $user->id ? "selected":"" }}>{{ $user->email }} - {{ $user->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="document" >
                        Dokumen
                        <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <select id="document" name="document" class="form-control col-md-7 col-xs-12 @if($errors->has('document')) parsley-error @endif">
                            <option value="-1" @if(empty(old('document'))) selected @endif>Pilih Dokumen</option>
                            @foreach($documents as $document)
                                @if($document->id == $approvalRule->document_id)
                                    <option value="{{ $document->id }}" selected >{{ $document->description }}</option>
                                @else
                                    <option value="{{ $document->id }}" {{ old('document') == $document->id ? "selected":"" }}>{{ $document->description }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        <a class="btn btn-primary" href="{{ route('admin.approval_rules') }}"> Batal</a>
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