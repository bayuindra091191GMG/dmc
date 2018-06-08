.@extends('admin.layouts.admin')

{{--@section('title', 'Tambah Site' )--}}

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12 text-center">
            <h2>Buat Kelas Baru </h2>
            <hr/>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            @include('partials._success')
            @include('partials._error')
            {{ Form::open(['route'=>['admin.courses.store'],'method' => 'post','id' => 'general-form','class'=>'form-horizontal form-label-left']) }}

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
                           name="name" value="{{ old('name') }}" required>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="type" >
                    Tipe Kelas
                    <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <select id="type" name="type" class="form-control col-md-7 col-xs-12">
                        <option value="1">Package</option>
                        <option value="2">Class</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="coach_id" >
                    Trainer
                    <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <select id="coach_id" name="coach_id" class="form-control col-md-7 col-xs-12">
                        <option value="-1">Bebas</option>
                        @foreach($coaches as $coach)
                            <option value="{{ $coach->id }}">{{ $coach->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="price" >
                    Harga
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="price" class="form-control col-md-7 col-xs-12 @if($errors->has('price')) parsley-error @endif"
                              name="price" value="{{ old('price') }}">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="address" >
                    Hari Pertemuan
                </label>

                <div class="col-md-6 col-sm-6 col-xs-12">

                    <div class="row">
                        <div class="col-sm-2">
                            <input type="checkbox" onchange="changeInput('hourMonday', 'chk1')" class="flat" id="chk1" name="chk[]" value="Senin"/> Senin
                        </div>
                        <div class="col-sm-4">
                            <input id="hourMonday" class="form-control" name="hour[]" disabled="disabled"/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-2">
                            <input type="checkbox" onchange="changeInput('hourTuesday', 'chk2')" class="flat" id="chk2" name="chk[]" value="Selasa"/> Selasa
                        </div>
                        <div class="col-sm-4">
                            <input id="hourTuesday" class="form-control" name="hour[]" disabled="disabled"/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-2">
                            <input type="checkbox" onchange="changeInput('hourWednesday', 'chk3')" class="flat" id="chk3" name="chk[]" value="Selasa"/> Rabu
                        </div>
                        <div class="col-sm-4">
                            <input id="hourWednesday" class="form-control" name="hour[]" disabled="disabled"/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-2">
                            <input type="checkbox" onchange="changeInput('hourThursday', 'chk4')" class="flat" id="chk4" name="chk[]" value="Selasa"/> Kamis
                        </div>
                        <div class="col-sm-4">
                            <input id="hourThursday" class="form-control" name="hour[]" disabled="disabled"/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-2">
                            <input type="checkbox" onchange="changeInput('hourFriday', 'chk5')" class="flat" id="chk5" name="chk[]" value="Selasa"/> Jumat
                        </div>
                        <div class="col-sm-4">
                            <input id="hourFriday" class="form-control" name="hour[]" disabled="disabled"/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-2">
                            <input type="checkbox" onchange="changeInput('hourSaturday', 'chk6')" class="flat" id="chk6" name="chk[]" value="Selasa"/> Sabtu
                        </div>
                        <div class="col-sm-4">
                            <input id="hourSaturday" class="form-control" name="hour[]" disabled="disabled"/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-2">
                            <input type="checkbox" onchange="changeInput('hourSunday', 'chk7')" class="flat" id="chk7" name="chk[]" value="Selasa"/> Minggu
                        </div>
                        <div class="col-sm-4">
                            <input id="hourSunday" class="form-control" name="hour[]" disabled="disabled"/>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <h4 class="col-md-12 col-xs-12" style="text-align: center">Bagian ini diisi jika Paket</h4>
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="meeting_amount" >
                    Jumlah Pertemuan
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="meeting_amount" class="form-control col-md-7 col-xs-12 @if($errors->has('meeting_amount')) parsley-error @endif"
                           name="meeting_amount" value="{{ old('meeting_amount') }}">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="valid" >
                    Valid
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="valid" class="form-control col-md-7 col-xs-12 @if($errors->has('price')) parsley-error @endif"
                           name="valid" value="{{ old('price') }}">
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                    <a class="btn btn-danger" href="{{ route('admin.coaches') }}"> Batal</a>
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
    {{ Html::script(mix('assets/admin/js/autonumeric.js')) }}
    {{ Html::script(mix('assets/admin/js/bootstrap-datetimepicker.js')) }}

    <script type="text/javascript">
        //function to enable disable
        function changeInput(ipt, chk){
            if(document.getElementById('' + chk).checked) {
                document.getElementById('' + ipt).disabled = false;
            }
            else{
                document.getElementById('' + ipt).disabled = true;
            }
        }

        $('#hourMonday').datetimepicker({
            format: "HH:mm"
        });
        $('#hourTuesday').datetimepicker({
            format: "HH:mm"
        });
        $('#hourWednesday').datetimepicker({
            format: "HH:mm"
        });
        $('#hourThursday').datetimepicker({
            format: "HH:mm"
        });
        $('#hourFriday').datetimepicker({
            format: "HH:mm"
        });
        $('#hourSaturday').datetimepicker({
            format: "HH:mm"
        });
        $('#hourSunday').datetimepicker({
            format: "HH:mm"
        });
        // Add autonumeric
        priceAddFormat = new AutoNumeric('#price', {
            decimalCharacter: ',',
            digitGroupSeparator: '.',
            minimumValue: '0',
            decimalPlaces: 0
        });
    </script>
@endsection