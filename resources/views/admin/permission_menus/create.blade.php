.@extends('admin.layouts.admin')

@section('title', 'Tambah Otorisasi Menu' )

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            @include('partials._success')
            @include('partials._error')
            {{ Form::open(['route'=>['admin.permission_menus.store'],'method' => 'post','id' => 'general-form','class'=>'form-horizontal form-label-left']) }}

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
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="role" >
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
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="menu" >
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    @php($idx = 0)
                    <table class="table">
                        <thead>
                            <th>
                                Menu
                            </th>
                        </thead>
                        <tbody>
                            <tr>
                                @foreach($menus as $menu)
                                    @php($idx++)
                                        <td>
                                            <label>
                                                <input type="checkbox" class="flat" id="chk{{$menu->id}}" name="chk[]" onclick="changeInput('{{ $menu->id }}')" /> {{ $menu->name }}
                                                <input type="text" hidden="true" value="{{ $menu->id }}" id="{{ $menu->id }}" name="ids[]" disabled/>
                                            </label>
                                        </td>
                                    @if($idx == 3)
                                        <tr/><tr>
                                        @php($idx = 0)
                                    @endif
                                @endforeach
                            </tr>
                            <tr>
                                <td>
                                    <label>
                                        <input type="checkbox" class="flat" id="selectAll"/> Select All
                                    </label>
                                </td>
                                <td>
                                    <label>
                                        <input type="checkbox" class="flat" id="unSelectAll"/> Unselect All
                                    </label>
                                </td>
                            </tr>
                        </tbody>
                    </table>
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

    <script>
        $("#selectall").click(function(){
            $('input:checkbox').not(this).prop('checked', this.checked);
        });

        function changeInput(id){
            if(document.getElementById("chk"+id).checked == true){
                document.getElementById(id).disabled = false;
            }
            else{
                document.getElementById(id).disabled = true;
            }
        }
    </script>
@endsection