.@extends('admin.layouts.admin')

@section('title', 'Tambah/Ubah Otorisasi Menu' )

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            @include('partials._success')
            @include('partials._error')
            {{ Form::open(['route'=>['admin.permission_menus.update'],'method' => 'post','class'=>'form-horizontal form-label-left']) }}

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
                    <input type="text" id="roleName" name="roleName" class="form-control col-md-7 col-xs-12" value="{{ $role->name }}" readonly />
                    <input type="text" id="role" name="role" value="{{ $role->id }}" hidden="true" />
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
                                            @php($flag = 0)
                                            @foreach($permissionMenus as $permission)
                                                @if($permission->menu_id == $menu->id)
                                                    @php($flag = 1)
                                                @endif
                                            @endforeach

                                            @if($flag == 1)
                                                <input type="checkbox" class="flat" id="chk{{$menu->id}}" name="chk[]" onclick="changeInput('{{ $menu->id }}')" checked="checked"> {{ $menu->name }}
                                                <input type="text" hidden="true" value="{{ $menu->id }}" id="ids{{ $menu->id }}" name="ids[]"/>
                                                <input type="text" hidden="true" value="{{ $menu->id }}" id="idsDelete{{ $menu->id }}" name="idsDelete[]" disabled/>
                                            @else
                                                <input type="checkbox" class="flat" id="chk{{$menu->id}}" name="chk[]" onclick="changeInput('{{ $menu->id }}')" > {{ $menu->name }}
                                                <input type="text" hidden="true" value="{{ $menu->id }}" id="ids{{ $menu->id }}" name="ids[]" disabled/>
                                                <input type="text" hidden="true" value="{{ $menu->id }}" id="idsDelete{{ $menu->id }}" name="idsDelete[]"/>
                                            @endif
                                        </label>
                                    </td>
                                    @if($idx == 3)
                                        <tr/><tr>
                                        @php($idx = 0)
                                    @endif
                                @endforeach
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
        function changeInput(id){
            if(document.getElementById("chk"+id).checked == true){
                document.getElementById("ids"+id).disabled = false;
                document.getElementById("idsDelete"+id).disabled = true;
            }
            else{
                document.getElementById("ids"+id).disabled = true;
                document.getElementById("idsDelete"+id).disabled = false;
            }
        }
    </script>
@endsection