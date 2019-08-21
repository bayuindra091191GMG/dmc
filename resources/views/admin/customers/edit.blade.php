@extends('admin.layouts.admin')

{{--@section('title', 'Ubah data Site')--}}

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12 text-center">
            <h2>Ubah Data Customer {{ $customer->name }}</h2>
            <hr/>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            @include('partials._success')
            {{ Form::open(['route'=>['admin.customers.update', $customer->id],'method' => 'put','id' => 'general-form','class'=>'form-horizontal form-label-left']) }}
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
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="photo">
                    Foto
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    @if(!empty($customer->photo_path))
                        <img src="{{ asset('storage/students/'. $customer->photo_path) }}" id="existing_photo" style="width: 400px; height: auto;">
                    @endif
                    <div id="my_camera"></div>
                    <br/>
                    <input type="button" class="btn btn-primary" id="btn_prepare_photo" value="AMBIL FOTO" onClick="prepare_photo()">
                    <input type="button" class="btn btn-primary" id="btn_snapshot" value="FOTO" style="display: none;" onClick="take_snapshot()">
                    <input type="hidden" name="photo" class="image-tag">
                    <div id="results"></div>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="member_id">
                    ID Member
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="member_id" type="text" class="form-control col-md-7 col-xs-12 @if($errors->has('member_id')) parsley-error @endif"
                           name="member_id" value="{{ $customer->member_id ?? '' }}">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="barcode">
                    Kode Barcode
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="barcode" type="text" class="form-control col-md-7 col-xs-12 @if($errors->has('barcode')) parsley-error @endif"
                           name="barcode" value="{{ $customer->barcode ?? '' }}">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name" >
                    Nama
                    <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="name" type="text" class="form-control col-md-7 col-xs-12 @if($errors->has('name')) parsley-error @endif"
                           name="name" value="{{ $customer->name }}" required>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email" >
                    Alamat Email
                    <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="email" type="text" class="form-control col-md-7 col-xs-12 @if($errors->has('email')) parsley-error @endif"
                           name="email" value="{{ $customer->email }}">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="age" >
                    Usia
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="age" type="text" class="form-control col-md-7 col-xs-12 @if($errors->has('age')) parsley-error @endif"
                           name="age" value="{{ $customer->age }}">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="phone" >
                    Nomor Telepon
                    <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="phone" type="number" class="form-control col-md-7 col-xs-12 @if($errors->has('phone')) parsley-error @endif"
                           name="phone" value="{{ $customer->phone }}">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="parent_name" >
                    Nama Orang Tua
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="parent_name" type="text" class="form-control col-md-7 col-xs-12 @if($errors->has('parent_name')) parsley-error @endif"
                           name="parent_name" value="{{ $customer->parent_name }}">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="address" >
                    Alamat
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <textarea id="address" rows="5" class="form-control col-md-7 col-xs-12 @if($errors->has('address')) parsley-error @endif"
                              name="address">{{$customer->address}}</textarea>
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                    <a class="btn btn-warning" href="{{ route('admin.customers') }}"> Batal</a>
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
    <script src="{{ asset('assets/admin/js/webcam.min.js') }}"></script>
    <script>
        Webcam.set({
            width: 490,
            height: 390,
            image_format: 'jpeg',
            jpeg_quality: 90
        });

        function prepare_photo(){
            $('#existing_photo').hide();
            Webcam.attach( '#my_camera' );
            $('#btn_prepare_photo').hide();
            $('#btn_snapshot').show();
        }

        function take_snapshot() {
            Webcam.snap( function(data_uri) {
                $('#my_camera').hide();
                $('#btn_snapshot').hide();
                $(".image-tag").val(data_uri);
                document.getElementById('results').innerHTML = '<img src="'+data_uri+'"/>';
                Webcam.reset();
            } );
        }
    </script>
@endsection