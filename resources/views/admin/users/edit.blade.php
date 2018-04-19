@extends('admin.layouts.admin')

@section('title', 'Ubah data user ', $employee->code )

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            {{ Form::open(['route'=>['admin.users.update', $user->id],'method' => 'put','class'=>'form-horizontal form-label-left', 'enctype'=>'multipart/form-data']) }}
            {{ csrf_field()}}

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
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">
                    ID Login Akses
                    <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="email" type="text" class="form-control col-md-7 col-xs-12 @if($errors->has('email')) parsley-error @endif"
                           name="email" value="{{ $user->email }}" required>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name" >
                    Nama Lengkap
                    <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="name" type="text" class="form-control col-md-7 col-xs-12 @if($errors->has('name')) parsley-error @endif"
                           name="name" value="{{ $employee->name }}" required>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email_address">
                    Alamat Email
                    <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="email_address" type="text" class="form-control col-md-7 col-xs-12 @if($errors->has('email_address')) parsley-error @endif"
                           name="email_address" value="{{ $user->email_address }}" required>
                </div>
            </div>

            {{--<div class="form-group">--}}
                {{--<label class="control-label col-md-3 col-sm-3 col-xs-12" for="employee">--}}
                    {{--Sambung ke karyawan--}}
                {{--</label>--}}
                {{--<div class="col-md-6 col-sm-6 col-xs-12">--}}
                    {{--<select id="employee" name="employee" class="form-control col-md-7 col-xs-12">--}}
                    {{--</select>--}}
                {{--</div>--}}
            {{--</div>--}}

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="role">
                    Level Akses
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <select id="role" name="role" class="form-control col-md-7 col-xs-12">
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" @if($user->roles->pluck('id')[0] == $role->id) selected @endif>{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="password">
                    Kata Sandi
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="password" type="password" class="form-control col-md-7 col-xs-12 @if($errors->has('password')) parsley-error @endif"
                           name="password">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="password_confirmation">
                    Ulang Kata Sandi
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="password_confirmation" type="password" class="form-control col-md-7 col-xs-12 @if($errors->has('password_confirmation')) parsley-error @endif"
                           name="password_confirmation">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="department" >
                    Departemen
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <select id="department" name="department" class="form-control col-md-7 col-xs-12 @if($errors->has('department')) parsley-error @endif">
                        @foreach($departments as $dep)
                            <option value="{{ $dep->id }}" {{ $employee->department_id == $dep->id ? "selected":"" }}>{{ $dep->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="site" >
                    Site
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <select id="site" name="site" class="form-control col-md-7 col-xs-12 @if($errors->has('site')) parsley-error @endif">
                        @foreach($sites as $site)
                            <option value="{{ $site->id }}" {{ $employee->site_id == $site->id ? "selected":"" }}>{{ $site->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="address" >
                    Alamat
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <textarea id="address" name="address" rows="5" class="form-control col-md-7 col-xs-12 @if($errors->has('address')) parsley-error @endif" style="resize: vertical">{{ $employee->address }}</textarea>
                </div>
            </div>


            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="status" >
                    Status
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <select id="status" name="status" class="form-control col-md-7 col-xs-12">
                        <option value="1" @if($user->status_id == 1) selected @endif>Aktif</option>
                        <option value="2" @if($user->status_id == 2) selected @endif>Non Aktif</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="address" >
                    Gambar Tanda Tangan
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <img src="{{ URL::asset('storage/img_sign/'.$user->img_path) }}" width="200px"/>
                    {!! Form::file('user_image', array('id' => 'photo', 'class' => 'file')) !!}
                </div>
            </div>

            <input type="hidden" id="employee_id" name="employee_id" value="{{ $employee->id }}"/>

            <div class="form-group">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                    <a class="btn btn-primary" href="{{ route('admin.users') }}">Batal</a>
                    <button type="submit" class="btn btn-success">Simpan</button>
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