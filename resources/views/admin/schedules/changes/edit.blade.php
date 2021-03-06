@extends('admin.layouts.admin')

{{--@section('title', 'Ubah data Site')--}}

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12 text-center">
            <h2>Ubah Data Kelas Gymnastic {{ $schedule->customer->first_name}} {{ $schedule->customer->last_name}}</h2>
            <hr/>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            @include('partials._success')
            @include('partials._error')
            {{ Form::open(['route'=>['admin.schedules.update-change', $schedule->id],'method' => 'put','id' => 'general-form','class'=>'form-horizontal form-label-left']) }}
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
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="date">
                    Tanggal Berakhir Paket
                    <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="date2" type="text" class="form-control col-md-7 col-xs-12 @if($errors->has('date')) parsley-error @endif"
                           name="date" value="{{ $date }}" required>
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                    <a class="btn btn-danger" href="{{ route('admin.schedules') }}"> Batal</a>
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
    {{ Html::style(mix('assets/admin/css/select2.css')) }}
    {{ Html::style(mix('assets/admin/css/bootstrap-datetimepicker.css')) }}
@endsection

@section('scripts')
    @parent
    {{ Html::script(mix('assets/admin/js/users/edit.js')) }}
    {{ Html::script(mix('assets/admin/js/select2.js')) }}
    {{ Html::script(mix('assets/admin/js/autonumeric.js')) }}
    {{ Html::script(mix('assets/admin/js/bootstrap-datetimepicker.js')) }}


    <script type="text/javascript">
        $('#date1').datetimepicker({
            format: "10 MMM Y"
        });
        $('#date2').datetimepicker({
            format: "DD MMM Y hh:mm",
            // inline: true,
            sideBySide: true
        });

        $('input[type="checkbox"]').on('change', function() {
            $('input[type="checkbox"]').not(this).prop('checked', false);
        });

        //Add kelas
        $('#course_add').select2({
            placeholder: {
                id: '{{$schedule->course_id}}',
                text: '{{$schedule->course->name}} - {{$schedule->course->coach->name}}'
            },
            val: '{{$schedule->course_id}}',
            width: '100%',
            minimumInputLength: 1,
            ajax: {
                url: '{{ route('select.courses') }}',
                dataType: 'json',
                data: function (params) {
                    return {
                        q: $.trim(params.term),
                        customer: $('#course_add').val()
                    };
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                }
            }
        });
        $('#course_add').on('select2:select', function(){
            $('#day_add').empty();
            //Get Days Options
            $.ajax({
                url: '{{ route('select.days') }}',
                dataType: 'json',
                data: {
                    'id': $('select[name=course_add]').val()
                },
                success: function (data) {
                    var i;
                    for(i=0; i<data.length; i++){

                        $('#day_add')
                            .append($("<option></option>")
                                .attr("value",data[i])
                                .text(data[i]));
                    }
                }
            });
        });
    </script>
@endsection