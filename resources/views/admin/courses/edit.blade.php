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
                    @php($idx = 0)
                    <table class="table">
                        <tbody>
                        <tr>
                            <td>
                                <label>
                                    <input type="checkbox" class="flat" id="chk1" name="chk[]" value="Senin" @if(strpos($course->day, 'Senin') !== false) checked @endif> Senin
                                    {{--<input type="checkbox" class="flat" id="chk1" name="chk[]" value="Senin" onchange="changeInput('1')" > Senin--}}
                                    {{--<input type="text" hidden="true" value="Senin" id="ids1" name="ids[]" disabled/>--}}
                                    {{--<input type="text" hidden="true" value="Senin" id="idsDelete1" name="idsDelete[]"/>--}}
                                </label>
                            </td>
                            <td>
                                <label>
                                    <input type="checkbox" class="flat" id="chk2" name="chk[]" value="Selasa" @if(strpos($course->day, 'Selasa') !== false) checked @endif> Selasa
                                    {{--<input type="checkbox" class="flat" id="chk2" name="chk[]" value="Selasa" onchange="changeInput('2')" > Selasa--}}
                                    {{--<input type="text" hidden="true" value="Selasa" id="ids2" name="ids[]" disabled/>--}}
                                    {{--<input type="text" hidden="true" value="Selasa" id="idsDelete2" name="idsDelete[]"/>--}}
                                </label>
                            </td>
                            <td>
                                <label>
                                    <input type="checkbox" class="flat" id="chk3" name="chk[]" value="Rabu" @if(strpos($course->day, 'Rabu') !== false) checked @endif> Rabu
                                    {{--<input type="checkbox" class="flat" id="chk3" name="chk[]" value="Rabu" onchange="changeInput('3')" > Rabu--}}
                                    {{--<input type="text" hidden="true" value="Rabu" id="ids3" name="ids[]" disabled/>--}}
                                    {{--<input type="text" hidden="true" value="Rabu" id="idsDelete3" name="idsDelete[]"/>--}}
                                </label>
                            </td>
                            <td>
                                <label>
                                    <input type="checkbox" class="flat" id="chk4" name="chk[]" value="Kamis" @if(strpos($course->day, 'Kamis') !== false) checked @endif> Kamis
                                    {{--<input type="checkbox" class="flat" id="chk4" name="chk[]" value="Kamis" onchange="changeInput('4')" > Kamis--}}
                                    {{--<input type="text" hidden="true" value="Kamis" id="ids4" name="ids[]" disabled/>--}}
                                    {{--<input type="text" hidden="true" value="Kamis" id="idsDelete4" name="idsDelete[]"/>--}}
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label>
                                    <input type="checkbox" class="flat" id="chk5" name="chk[]" value="Jumat" @if(strpos($course->day, 'Jumat') !== false) checked @endif> Jumat
                                    {{--<input type="checkbox" class="flat" id="chk5" name="chk[]" value="Jumat" onchange="changeInput('5')" > Jumat--}}
                                    {{--<input type="text" hidden="true" value="Jumat" id="ids5" name="ids[]" disabled/>--}}
                                    {{--<input type="text" hidden="true" value="Jumat" id="idsDelete5" name="idsDelete[]"/>--}}
                                </label>
                            </td>
                            <td>
                                <label>
                                    <input type="checkbox" class="flat" id="chk6" name="chk[]" value="Sabtu" @if(strpos($course->day, 'Sabtu') !== false) checked @endif> Sabtu
                                    {{--<input type="checkbox" class="flat" id="chk6" name="chk[]" value="Sabtu" onchange="changeInput('6')" > Sabtu--}}
                                    {{--<input type="text" hidden="true" value="Sabtu" id="ids6" name="ids[]" disabled/>--}}
                                    {{--<input type="text" hidden="true" value="Sabtu" id="idsDelete6" name="idsDelete[]"/>--}}
                                </label>
                            </td>
                            <td>
                                <label>
                                    <input type="checkbox" class="flat" id="chk7" name="chk[]" value="Minggu" @if(strpos($course->day, 'Minggu') !== false) checked @endif> Minggu
                                    {{--<input type="checkbox" class="flat" id="chk7" name="chk[]" value="Minggu" onchange="changeInput('7')" > Minggu--}}
                                    {{--<input type="text" hidden="true" value="Minggu" id="ids7" name="ids[]" disabled/>--}}
                                    {{--<input type="text" hidden="true" value="Minggu" id="idsDelete7" name="idsDelete[]"/>--}}
                                </label>
                            </td>
                        </tr>
                        </tbody>
                    </table>
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
@endsection

@section('scripts')
    @parent
    {{ Html::script(mix('assets/admin/js/users/edit.js')) }}
@endsection