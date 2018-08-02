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
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <select id="type" name="type" class="form-control col-md-7 col-xs-12">
                        <option value="-1"> - Pilih Tipe - </option>
                        <option value="1" @if($course->type == 1) selected @endif>Package</option>
                        <option value="2" @if($course->type == 2) selected @endif>Class</option>
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

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="address" >
                    Hari Pertemuan
                </label>

                <div class="col-md-6 col-sm-6 col-xs-12">

                    <div class="row">
                        <div class="col-sm-2">
                            <input type="checkbox" onchange="changeInput('hourMonday', 'chk1')" class="flat" id="chk1" name="chk[]" value="Senin" @if(strpos($course->day, 'Senin') !== false) checked @endif/> Senin
                        </div>
                        <div class="col-sm-4">
                            @if(strpos($course->day, 'Senin') !== false)
                                @php($ctr = 0)
                                @foreach($days as $day)
                                    @if($day === 'Senin')
                                        @break
                                    @else
                                        @php($ctr++)
                                    @endif
                                @endforeach
                                <input id="hourMonday" class="form-control" name="hour[]" value="{{ $hours[$ctr] }}"/>
                            @else
                                <input id="hourMonday" class="form-control" name="hour[]" disabled="disabled"/>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-2">
                            <input type="checkbox" onchange="changeInput('hourTuesday', 'chk2')" class="flat" id="chk2" name="chk[]" value="Selasa" @if(strpos($course->day, 'Selasa') !== false) checked @endif/> Selasa
                        </div>
                        <div class="col-sm-4">
                            @if(strpos($course->day, 'Selasa') !== false)
                                @php($ctr = 0)
                                @foreach($days as $day)
                                    @if($day === 'Selasa')
                                        @break
                                    @else
                                        @php($ctr++)
                                    @endif
                                @endforeach
                                <input id="hourTuesday" class="form-control" name="hour[]" value="{{ $hours[$ctr] }}"/>
                            @else
                                <input id="hourTuesday" class="form-control" name="hour[]" disabled="disabled"/>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-2">
                            <input type="checkbox" onchange="changeInput('hourWednesday', 'chk3')" class="flat" id="chk3" name="chk[]" value="Rabu" @if(strpos($course->day, 'Rabu') !== false) checked @endif /> Rabu
                        </div>
                        <div class="col-sm-4">
                            @if(strpos($course->day, 'Rabu') !== false)
                                @php($ctr = 0)
                                @foreach($days as $day)
                                    @if($day === 'Rabu')
                                        @break
                                    @else
                                        @php($ctr++)
                                    @endif
                                @endforeach
                                <input id="hourWednesday" class="form-control" name="hour[]" value="{{ $hours[$ctr] }}"/>
                            @else
                                <input id="hourWednesday" class="form-control" name="hour[]" disabled="disabled"/>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-2">
                            <input type="checkbox" onchange="changeInput('hourThursday', 'chk4')" class="flat" id="chk4" name="chk[]" value="Kamis" @if(strpos($course->day, 'Kamis') !== false) checked @endif/> Kamis
                        </div>
                        <div class="col-sm-4">
                            @if(strpos($course->day, 'Kamis') !== false)
                                @php($ctr = 0)
                                @foreach($days as $day)
                                    @if($day === 'Kamis')
                                        @break
                                    @else
                                        @php($ctr++)
                                    @endif
                                @endforeach
                                <input id="hourThursday" class="form-control" name="hour[]" value="{{ $hours[$ctr] }}"/>
                            @else
                                <input id="hourThursday" class="form-control" name="hour[]" disabled="disabled"/>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-2">
                            <input type="checkbox" onchange="changeInput('hourFriday', 'chk5')" class="flat" id="chk5" name="chk[]" value="Jumat" @if(strpos($course->day, 'Jumat') !== false) checked @endif/> Jumat
                        </div>
                        <div class="col-sm-4">
                            @if(strpos($course->day, 'Jumat') !== false)
                                @php($ctr = 0)
                                @foreach($days as $day)
                                    @if($day === 'Jumat')
                                        @break
                                    @else
                                        @php($ctr++)
                                    @endif
                                @endforeach
                                <input id="hourFriday" class="form-control" name="hour[]" value="{{ $hours[$ctr] }}"/>
                            @else
                                <input id="hourFriday" class="form-control" name="hour[]" disabled="disabled"/>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-2">
                            <input type="checkbox" onchange="changeInput('hourSaturday', 'chk6')" class="flat" id="chk6" name="chk[]" value="Sabtu" @if(strpos($course->day, 'Sabtu') !== false) checked @endif/> Sabtu
                        </div>
                        <div class="col-sm-4">
                            @if(strpos($course->day, 'Sabtu') !== false)
                                @php($ctr = 0)
                                @foreach($days as $day)
                                    @if($day === 'Sabtu')
                                        @break
                                    @else
                                        @php($ctr++)
                                    @endif
                                @endforeach
                                <input id="hourSaturday" class="form-control" name="hour[]" value="{{ $hours[$ctr] }}"/>
                            @else
                                <input id="hourSaturday" class="form-control" name="hour[]" disabled="disabled"/>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-2">
                            <input type="checkbox" onchange="changeInput('hourSunday', 'chk7')" class="flat" id="chk7" name="chk[]" value="Minggu" @if(strpos($course->day, 'Minggu') !== false) checked @endif/> Minggu
                        </div>
                        <div class="col-sm-4">
                            @if(strpos($course->day, 'Minggu') !== false)
                                @php($ctr = 0)
                                @foreach($days as $day)
                                    @if($day === 'Minggu')
                                        @break
                                    @else
                                        @php($ctr++)
                                    @endif
                                @endforeach
                                <input id="hourSunday" class="form-control" name="hour[]" value="{{ $hours[$ctr] }}"/>
                            @else
                                <input id="hourSunday" class="form-control" name="hour[]" disabled="disabled"/>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="address" >
                    Status
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <select id="status" name="status" class="form-control col-md-7 col-xs-12">
                        <option value="1" selected>Aktif</option>
                        <option value="2">Non Aktif</option>
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
    {{ Html::script(mix('assets/admin/js/bootstrap-datetimepicker.js')) }}
    <script>
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
    </script>
@endsection