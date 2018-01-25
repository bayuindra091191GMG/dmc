@extends('admin.layouts.admin')

@section('title', 'Ubah data Role')

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            @include('partials._success')
            {{ Form::open(['route'=>['admin.permission_menus.update', $permissionMenu->id],'method' => 'put','class'=>'form-horizontal form-label-left']) }}
            {{ csrf_field() }}
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="role" >
                        Role
                        <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <select id="role" name="role" class="form-control col-md-7 col-xs-12 @if($errors->has('role')) parsley-error @endif">
                            <option value="-1" @if(empty(old('role'))) selected @endif>Pilih Role</option>
                            @foreach($roles as $role)
                                @if($role->id == $permissionMenu->role_id)
                                    <option value="{{ $role->id }}" selected >{{ $role->name }}</option>
                                @else
                                    <option value="{{ $role->id }}" {{ old('role') == $role->id ? "selected":"" }}>{{ $role->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="menu" >
                        Menu
                        <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <select id="menu" name="menu" class="form-control col-md-7 col-xs-12 @if($errors->has('menu')) parsley-error @endif">
                            <option value="-1" @if(empty(old('menu'))) selected @endif>Pilih Menu</option>
                            @foreach($menus as $menu)
                                @if($menu->id == $permissionMenu->menu_id)
                                    <option value="{{ $menu->id }}" selected >{{ $menu->name }}</option>
                                @else
                                    <option value="{{ $menu->id }}" {{ old('menu') == $menu->id ? "selected":"" }}>{{ $menu->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        <a class="btn btn-primary" href="{{ route('admin.permission_menus') }}"> Batal</a>
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