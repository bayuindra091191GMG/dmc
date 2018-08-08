@extends('admin.layouts.admin')

{{--@section('title', 'Ubah data Site')--}}

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12 text-center">
            <h2>Ubah Data Kelas {{ $course->name }}</h2>
            <hr/>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            @include('partials._success')
            @include('partials._error')
            {{ Form::open(['route'=>['admin.courses.update', $course->id],'method' => 'put','id' => 'general-form','class'=>'form-horizontal form-label-left']) }}
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
                           name="name" value="{{ $course->name }}" required>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="type" >
                    Tipe Kelas
                    <span class="required">*</span>
                </label>
                <div class="col-md-3 col-sm-3 col-xs-12">
                    <select id="type" name="type" class="form-control col-md-7 col-xs-12">
                        <option value="-1"> - Pilih Tipe - </option>
                        <option value="1" @if($course->type == 1) selected @endif>Package</option>
                        <option value="2" @if($course->type == 2) selected @endif>Class</option>
                        <option value="4" @if($course->type == 4) selected @endif>Gymnastic</option>
                        <option value="3" @if($course->type == 3) selected @endif>Private</option>
                    </select>
                </div>
                <div class="col-md-3 col-sm-3 col-xs-12">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" class="flat" id="is_baby" name="is_baby"
                                   @if($course->type == 2 && $course->meeting_amount == 8) checked @endif> Kelas Bayi
                        </label>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="coach_id" >
                    Trainer
                    <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <select id="coach_id" name="coach_id" class="form-control col-md-7 col-xs-12">
                        <option value="-1"> - Pilih Trainer - </option>
                        @foreach($coaches as $coach)
                            <option value="{{ $coach->id }}" @if($coach->id == $course->coach_id) selected @endif >{{ $coach->name }}</option>
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
                           name="price" value="{{ $course->price }}">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="meeting_amount" >
                    Jumlah Pertemuan
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="meeting_amount" class="form-control col-md-7 col-xs-12 @if($errors->has('meeting_amount')) parsley-error @endif"
                           name="meeting_amount" value="{{ $course->meeting_amount }}">
                </div>
            </div>
            @if(count($course->days) > 0)
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="address" >
                    Hari Pertemuan
                </label>

                <div class="col-md-6 col-sm-6 col-xs-12">

                    <div class="row">
                        @php($chk = 0)
                        @if(count($course->days) > 0)
                            @foreach($course->days as $day)
                                @if($day->day_string == 'Senin')
                                    <div class="col-sm-2">
                                        <input type="checkbox" onchange="changeInput('hourMonday1', 'hourMonday2', 'chk1')" class="flat" id="chk1" name="chk[]" value="Senin" checked/> Senin
                                    </div>
                                    @php($chk = 1)
                                @endif
                            @endforeach
                            @if($chk == 0)
                                <div class="col-sm-2">
                                    <input type="checkbox" onchange="changeInput('hourMonday1', 'hourMonday2', 'chk1')" class="flat" id="chk1" name="chk[]" value="Senin"/> Senin
                                </div>
                                <div class="col-sm-2">
                                    <input id="hourMonday1" class="form-control" name="hourMonday1" disabled="disabled"/>
                                </div>
                                <div class="col-sm-2">
                                    <input id="hourMonday2" class="form-control" name="hourMonday2" disabled="disabled"/>
                                </div>
                            @endif
                            @foreach($course->days as $day)
                                @if($day->day_string == 'Senin')
                                    <div class="col-sm-2">
                                        <input id="hourMonday1" class="form-control" name="hourMonday1" value="{{ $day->hours[0]->hour_string }}"/>
                                    </div>
                                    @if(count($day->hours) > 1)
                                        <div class="col-sm-2">
                                            <input id="hourMonday2" class="form-control" name="hourMonday2" value="{{ $day->hours[1]->hour_string }}"/>
                                        </div>
                                    @else
                                        <div class="col-sm-2">
                                            <input id="hourMonday2" class="form-control" name="hourMonday2" disabled="disabled"/>
                                        </div>
                                    @endif
                                @endif
                            @endforeach
                        @endif
                    </div>
                    <div class="row">
                        @php($chk = 0)
                        @if(count($course->days) > 0)
                            @foreach($course->days as $day)
                                @if($day->day_string == 'Selasa')
                                    <div class="col-sm-2">
                                        <input type="checkbox" onchange="changeInput('hourTuesday1', 'hourTuesday2', 'chk2')" class="flat" id="chk2" name="chk[]" value="Selasa" checked/> Selasa
                                    </div>
                                    @php($chk = 1)
                                @endif
                            @endforeach
                            @if($chk == 0)
                                <div class="col-sm-2">
                                    <input type="checkbox" onchange="changeInput('hourTuesday1', 'hourTuesday2', 'chk2')" class="flat" id="chk2" name="chk[]" value="Selasa"/> Selasa
                                </div>
                                <div class="col-sm-2">
                                    <input id="hourTuesday1" class="form-control" name="hourTuesday1" disabled="disabled"/>
                                </div>
                                <div class="col-sm-2">
                                    <input id="hourTuesday2" class="form-control" name="hourTuesday2" disabled="disabled"/>
                                </div>
                            @endif
                            @foreach($course->days as $day)
                                @if($day->day_string == 'Selasa')
                                    <div class="col-sm-2">
                                        <input id="hourTuesday1" class="form-control" name="hourTuesday1" value="{{ $day->hours[0]->hour_string }}"/>
                                    </div>
                                    @if(count($day->hours) > 1)
                                        <div class="col-sm-2">
                                            <input id="hourTuesday2" class="form-control" name="hourTuesday2" value="{{ $day->hours[1]->hour_string }}"/>
                                        </div>
                                    @else
                                        <div class="col-sm-2">
                                            <input id="hourTuesday2" class="form-control" name="hourTuesday2" disabled="disabled"/>
                                        </div>
                                    @endif
                                @endif
                            @endforeach
                        @endif
                    </div>
                    <div class="row">
                        @php($chk = 0)
                        @if(count($course->days) > 0)
                            @foreach($course->days as $day)
                                @if($day->day_string == 'Rabu')
                                    <div class="col-sm-2">
                                        <input type="checkbox" onchange="changeInput('hourWednesday1', 'hourWednesday2', 'chk3')" class="flat" id="chk3" name="chk[]" value="Rabu" checked/> Rabu
                                    </div>
                                    @php($chk = 1)
                                @endif
                            @endforeach
                            @if($chk == 0)
                                <div class="col-sm-2">
                                    <input type="checkbox" onchange="changeInput('hourWednesday1', 'hourWednesday2', 'chk3')" class="flat" id="chk3" name="chk[]" value="Rabu"/> Rabu
                                </div>
                                <div class="col-sm-2">
                                    <input id="hourWednesday1" class="form-control" name="hourWednesday1" disabled="disabled"/>
                                </div>
                                <div class="col-sm-2">
                                    <input id="hourWednesday2" class="form-control" name="hourWednesday2" disabled="disabled"/>
                                </div>
                            @endif
                            @foreach($course->days as $day)
                                @if($day->day_string == 'Rabu')
                                    <div class="col-sm-2">
                                        <input id="hourWednesday1" class="form-control" name="hourWednesday1" value="{{ $day->hours[0]->hour_string }}"/>
                                    </div>
                                    @if(count($day->hours) > 1)
                                        <div class="col-sm-2">
                                            <input id="hourWednesday2" class="form-control" name="hourWednesday2" value="{{ $day->hours[1]->hour_string }}"/>
                                        </div>
                                    @else
                                        <div class="col-sm-2">
                                            <input id="hourWednesday2" class="form-control" name="hourWednesday2" disabled="disabled"/>
                                        </div>
                                    @endif
                                @endif
                            @endforeach
                        @endif
                    </div>
                    <div class="row">
                        @php($chk = 0)
                        @if(count($course->days) > 0)
                            @foreach($course->days as $day)
                                @if($day->day_string == 'Kamis')
                                    <div class="col-sm-2">
                                        <input type="checkbox" onchange="changeInput('hourThursday1', 'hourThursday2', 'chk4')" class="flat" id="chk4" name="chk[]" value="Kamis" checked/> Kamis
                                    </div>
                                    @php($chk = 1)
                                @endif
                            @endforeach
                            @if($chk == 0)
                                <div class="col-sm-2">
                                    <input type="checkbox" onchange="changeInput('hourThursday1', 'hourThursday2', 'chk4')" class="flat" id="chk4" name="chk[]" value="Kamis"/> Kamis
                                </div>
                                <div class="col-sm-2">
                                    <input id="hourThursday1" class="form-control" name="hourThursday1" disabled="disabled"/>
                                </div>
                                <div class="col-sm-2">
                                    <input id="hourThursday2" class="form-control" name="hourThursday2" disabled="disabled"/>
                                </div>
                            @endif
                            @foreach($course->days as $day)
                                @if($day->day_string == 'Kamis')
                                    <div class="col-sm-2">
                                        <input id="hourThursday1" class="form-control" name="hourThursday1" value="{{ $day->hours[0]->hour_string }}"/>
                                    </div>
                                    @if(count($day->hours) > 1)
                                        <div class="col-sm-2">
                                            <input id="hourThursday2" class="form-control" name="hourThursday2" value="{{ $day->hours[1]->hour_string }}"/>
                                        </div>
                                    @else
                                        <div class="col-sm-2">
                                            <input id="hourThursday2" class="form-control" name="hourThursday2" disabled="disabled"/>
                                        </div>
                                    @endif
                                @endif
                            @endforeach
                        @endif
                    </div>
                    <div class="row">
                        @php($chk = 0)
                        @if(count($course->days) > 0)
                            @foreach($course->days as $day)
                                @if($day->day_string == 'Jumat')
                                    <div class="col-sm-2">
                                        <input type="checkbox" onchange="changeInput('hourFriday1', 'hourFriday2', 'chk5')" class="flat" id="chk5" name="chk[]" value="Jumat" checked/> Jumat
                                    </div>
                                    @php($chk = 1)
                                @endif
                            @endforeach
                            @if($chk == 0)
                                <div class="col-sm-2">
                                    <input type="checkbox" onchange="changeInput('hourFriday1', 'hourFriday2', 'chk5')" class="flat" id="chk5" name="chk[]" value="Jumat"/> Jumat
                                </div>
                                <div class="col-sm-2">
                                    <input id="hourFriday1" class="form-control" name="hourFriday1" disabled="disabled"/>
                                </div>
                                <div class="col-sm-2">
                                    <input id="hourFriday2" class="form-control" name="hourFriday2" disabled="disabled"/>
                                </div>
                            @endif
                            @foreach($course->days as $day)
                                @if($day->day_string == 'Jumat')
                                    <div class="col-sm-2">
                                        <input id="hourFriday1" class="form-control" name="hourFriday1" value="{{ $day->hours[0]->hour_string }}"/>
                                    </div>
                                    @if(count($day->hours) > 1)
                                        <div class="col-sm-2">
                                            <input id="hourFriday2" class="form-control" name="hourFriday2" value="{{ $day->hours[1]->hour_string }}"/>
                                        </div>
                                    @else
                                        <div class="col-sm-2">
                                            <input id="hourFriday2" class="form-control" name="hourFriday2" disabled="disabled"/>
                                        </div>
                                    @endif
                                @endif
                            @endforeach
                        @endif
                    </div>
                    <div class="row">
                        @php($chk = 0)
                        @if(count($course->days) > 0)
                            @foreach($course->days as $day)
                                @if($day->day_string == 'Sabtu')
                                    <div class="col-sm-2">
                                        <input type="checkbox" onchange="changeInput('hourSaturday1', 'hourSaturday2', 'chk6')" class="flat" id="chk6" name="chk[]" value="Sabtu" checked/> Sabtu
                                    </div>
                                    @php($chk = 1)
                                @endif
                            @endforeach
                            @if($chk == 0)
                                <div class="col-sm-2">
                                    <input type="checkbox" onchange="changeInput('hourSaturday1', 'hourSaturday2', 'chk6')" class="flat" id="chk6" name="chk[]" value="Sabtu"/> Sabtu
                                </div>
                                <div class="col-sm-2">
                                    <input id="hourSaturday1" class="form-control" name="hourSaturday1" disabled="disabled"/>
                                </div>
                                <div class="col-sm-2">
                                    <input id="hourSaturday2" class="form-control" name="hourSaturday2" disabled="disabled"/>
                                </div>
                            @endif
                            @foreach($course->days as $day)
                                @if($day->day_string == 'Sabtu')
                                    <div class="col-sm-2">
                                        <input id="hourSaturday1" class="form-control" name="hourSaturday1" value="{{ $day->hours[0]->hour_string }}"/>
                                    </div>
                                    @if(count($day->hours) > 1)
                                        <div class="col-sm-2">
                                            <input id="hourSaturday2" class="form-control" name="hourSaturday2" value="{{ $day->hours[1]->hour_string }}"/>
                                        </div>
                                    @else
                                        <div class="col-sm-2">
                                            <input id="hourSaturday2" class="form-control" name="hourSaturday2" disabled="disabled"/>
                                        </div>
                                    @endif
                                @endif
                            @endforeach
                        @endif
                    </div>
                    <div class="row">
                        @php($chk = 0)
                        @if(count($course->days) > 0)
                            @foreach($course->days as $day)
                                @if($day->day_string == 'Minggu')
                                    <div class="col-sm-2">
                                        <input type="checkbox" onchange="changeInput('hourSunday1', 'hourSunday2', 'chk7')" class="flat" id="chk7" name="chk[]" value="Minggu" checked/> Minggu
                                    </div>
                                    @php($chk = 1)
                                @endif
                            @endforeach
                            @if($chk == 0)
                                <div class="col-sm-2">
                                    <input type="checkbox" onchange="changeInput('hourSunday1', 'hourSunday2', 'chk7')" class="flat" id="chk7" name="chk[]" value="Minggu"/> Minggu
                                </div>
                                <div class="col-sm-2">
                                    <input id="hourSunday1" class="form-control" name="hourSunday1" disabled="disabled"/>
                                </div>
                                <div class="col-sm-2">
                                    <input id="hourSunday2" class="form-control" name="hourSunday2" disabled="disabled"/>
                                </div>
                            @endif
                            @foreach($course->days as $day)
                                @if($day->day_string == 'Minggu')
                                    <div class="col-sm-2">
                                        <input id="hourSunday1" class="form-control" name="hourSunday1" value="{{ $day->hours[0]->hour_string }}"/>
                                    </div>
                                    @if(count($day->hours) > 1)
                                        <div class="col-sm-2">
                                            <input id="hourSunday2" class="form-control" name="hourSunday2" value="{{ $day->hours[1]->hour_string }}"/>
                                        </div>
                                    @else
                                        <div class="col-sm-2">
                                            <input id="hourSunday2" class="form-control" name="hourSunday2" disabled="disabled"/>
                                        </div>
                                    @endif
                                @endif
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
            @endif
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="address" >
                    Status
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <select id="status" name="status" class="form-control col-md-7 col-xs-12">
                        @if($course->status_id == 1)
                            <option value="1" selected>Aktif</option>
                            <option value="2">Non Aktif</option>
                        @else
                            <option value="1">Aktif</option>
                            <option value="2" selected>Non Aktif</option>
                        @endif
                    </select>
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                    <a class="btn btn-danger" href="{{ route('admin.courses') }}"> Batal</a>
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
    <script>
        function changeInput(ipt1, ipt2, chk){
            if(document.getElementById('' + chk).checked) {
                document.getElementById('' + ipt1).disabled = false;
                document.getElementById('' + ipt2).disabled = false;
            }
            else{
                document.getElementById('' + ipt1).disabled = true;
                document.getElementById('' + ipt2).disabled = true;
            }
        }

        $('#hourMonday1').datetimepicker({
            format: "HH:mm"
        });
        $('#hourTuesday1').datetimepicker({
            format: "HH:mm"
        });
        $('#hourWednesday1').datetimepicker({
            format: "HH:mm"
        });
        $('#hourThursday1').datetimepicker({
            format: "HH:mm"
        });
        $('#hourFriday1').datetimepicker({
            format: "HH:mm"
        });
        $('#hourSaturday1').datetimepicker({
            format: "HH:mm"
        });
        $('#hourSunday1').datetimepicker({
            format: "HH:mm"
        });

        $('#hourMonday2').datetimepicker({
            format: "HH:mm"
        });
        $('#hourTuesday2').datetimepicker({
            format: "HH:mm"
        });
        $('#hourWednesday2').datetimepicker({
            format: "HH:mm"
        });
        $('#hourThursday2').datetimepicker({
            format: "HH:mm"
        });
        $('#hourFriday2').datetimepicker({
            format: "HH:mm"
        });
        $('#hourSaturday2').datetimepicker({
            format: "HH:mm"
        });
        $('#hourSunday2').datetimepicker({
            format: "HH:mm"
        });

        // Add autonumeric
        @if(!empty($course->price))
            var price = '{{ $course->price }}'
            var priceClean = price.replace(/\./g,'');
            priceAddFormat = new AutoNumeric('#price', priceClean, {
                decimalCharacter: ',',
                digitGroupSeparator: '.',
                minimumValue: '0',
                decimalPlaces: 0
            });
        @else

            priceAddFormat = new AutoNumeric('#price', {
            decimalCharacter: ',',
            digitGroupSeparator: '.',
            minimumValue: '0',
            decimalPlaces: 0
        });
        @endif
    </script>
@endsection