@extends('admin.layouts.admin')

{{--@section('title','Buat Retur Baru')--}}

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12 text-center">
            <h2>Langkah 1 - Registrasi Student {{ $courseType }}</h2>
            <hr/>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">

            {{ Form::open(['route'=>['admin.registration.step-one.store'],'method' => 'post','id' => 'general-form','class'=>'form-horizontal form-label-left']) }}

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

            <input type="hidden" id="type" name="type" value="{{ $type }}"/>

            <div class="form-group" id="form_existing_student">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="customer" >
                    Student Terdaftar
                </label>
                <div class="col-md-4 col-sm-4 col-xs-12">
                    <select type="text" id="customer" name="customer" class="form-control col-md-7 col-xs-12">
                        @if(!empty($student))
                            <option value="{{ $student->id }}">{{ $student->name }}</option>
                        @endif
                    </select>
                    <input type="hidden" id="customer_id" name="customer_id" class="form-control col-md-7 col-xs-12" value="{{ $student->id ?? '-1' }}">
                </div>
                <div class="col-md-2 col-sm-2 col-xs-12">
                    @if(!empty($student))
                        <a href="{{ route('admin.customers.show', ['customer' => $student->id]) }}" target="_blank" class="btn btn-primary" id="schedule_check">Cek Jadwal Teregistrasi Student</a>
                    @else
                        <a href="javascript:void(0)" target="_blank" class="btn btn-primary" id="schedule_check">Cek Jadwal Teregistrasi Student</a>
                    @endif

                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="auto_number"></label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" class="flat" id="is_new_student" name="is_new_student" value="true"> Student Baru
                        </label>
                    </div>
                </div>
            </div>

            <div id="form_new_student" style="display: none;">
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="student_name">
                        Nama Student
                        <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="student_name" type="text" class="form-control col-md-7 col-xs-12 @if($errors->has('student_name')) parsley-error @endif"
                               name="student_name" value="{{ old('student_name') }}">
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="student_email">
                        Alamat Email
                        <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="student_email" type="text" class="form-control col-md-7 col-xs-12 @if($errors->has('student_email')) parsley-error @endif"
                               name="student_email" value="{{ old('student_email') }}">
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="age">
                        Usia
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="age" type="text" class="form-control col-md-7 col-xs-12 @if($errors->has('age')) parsley-error @endif"
                               name="age" value="{{ old('age') }}">
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="dob">
                        Tanggal Lahir
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="dob" type="text" class="form-control col-md-7 col-xs-12 @if($errors->has('dob')) parsley-error @endif"
                               name="dob" value="{{ old('dob') }}">
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="student_phone">
                        Nomor Telepon/Ponsel
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="student_phone" type="text" class="form-control col-md-7 col-xs-12 @if($errors->has('student_phone')) parsley-error @endif"
                               name="student_phone" value="{{ old('student_phone') }}">
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="student_parent_name">
                        Nama Orang Tua
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="student_parent_name" type="text" class="form-control col-md-7 col-xs-12 @if($errors->has('student_parent_name')) parsley-error @endif"
                               name="student_parent_name" value="{{ old('student_parent_name') }}">
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="address" >
                        Alamat Rumah
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                    <textarea id="student_address" rows="5" class="form-control col-md-7 col-xs-12 @if($errors->has('student_address')) parsley-error @endif"
                              name="student_address">{{ old('student_address') }}</textarea>
                    </div>
                </div>
            </div>

            {{--            <div class="form-group" id="registration_alert">--}}
            {{--                <div class="col-md-3 col-sm-3 col-xs-12"></div>--}}
            {{--                <div class="col-md-6 col-sm-6 col-xs-12">--}}
            {{--                    <div class="alert alert-warning alert-dismissible fade in" role="alert">--}}
            {{--                        <span>Student </span>--}}
            {{--                        <span id="warning_student_name"></span>--}}
            {{--                    </div>--}}
            {{--                </div>--}}
            {{--            </div>--}}

            <input id="index_counter" name="index_counter" type="hidden" value="0"/>

            <hr/>

            <div class="form-group">
                <div class="col-md-12 col-sm-12 col-xs-12 text-center">
{{--                    <a class="btn btn-danger" href="{{ route('admin.transactions') }}"> Batal</a>--}}
                    <input type="submit" class="btn btn-success" value="Lanjutkan">
                </div>
            </div>
            {{ Form::close() }}
        </div>
    </div>

@endsection

@section('styles')
    @parent
    {{ Html::style(mix('assets/admin/css/select2.css')) }}
    {{ Html::style(mix('assets/admin/css/bootstrap-datetimepicker.css')) }}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
@endsection

@section('scripts')
    @parent
    {{ Html::script(mix('assets/admin/js/select2.js')) }}
    {{ Html::script(mix('assets/admin/js/autonumeric.js')) }}
    {{ Html::script(mix('assets/admin/js/stringbuilder.js')) }}
    {{ Html::script(mix('assets/admin/js/bootstrap-datetimepicker.js')) }}
    <script type="text/javascript">

        $('#dob').datetimepicker({
            format: "DD MMM Y"
        });

        @if(!empty(old('is_new_student')))
            $('#form_new_student').show();
        @endif

        // Show Create New Student Form
        $('#is_new_student').change(function(){
            if(this.checked){
                $('#form_new_student').show();
                $("#customer").prop("disabled", true);
                //$('#form_existing_student').hide();
            }
            else{
                $('#form_new_student').hide();
                $("#customer").prop("disabled", false);
                //$('#form_existing_student').show();
            }
        });

        $('#customer').select2({
            placeholder: {
                id: '{{ $customer->id ?? '-1' }}',
                text: '{{ $customer->name ?? ' - Pilih Customer - ' }}'
            },
            width: '100%',
            minimumInputLength: 0,
            ajax: {
                url: '{{ route('select.customers') }}',
                dataType: 'json',
                data: function (params) {
                    return {
                        q: $.trim(params.term)
                    };
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                }
            }
        });

        $('#customer').on('select2:select', function (e) {
            var data = e.params.data;
            $('#customer_id').val(data.id);
            //window.location.replace('/admin/transactions/create?customer=' + data.id);

            // Update schedule check a href
            var scheduleLinkBtn = document.getElementById("schedule_check");
            scheduleLinkBtn.setAttribute("href","/admin/customers/show/" + data.id);
        });
    </script>
@endsection