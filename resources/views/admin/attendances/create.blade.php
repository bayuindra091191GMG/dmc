@extends('admin.layouts.admin')

{{--@section('title','Buat Retur Baru')--}}

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12 text-center">
            <h2>Absensi Customer</h2>
            <hr/>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="form-horizontal form-label-left">
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
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="customer" >
                        Murid *
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <select type="text" id="customer" name="customer" class="form-control col-md-7 col-xs-12"></select>
                        <input type="hidden" id="customer" name="customer" class="form-control col-md-7 col-xs-12" value="{{ $customer->id ?? '-1' }}">
                    </div>
                </div>

                <hr/>

                @if(!empty($schedules))
                    <div class="form-group">
                        <div class="col-lg-12 col-md-12 col-xs-12 box-section">
                            <h3 class="text-center">Pilihan Kelas</h3>
                            {{--<a class="add-modal btn btn-info" style="margin-bottom: 10px;">--}}
                            {{--<span class="glyphicon glyphicon-plus-sign"></span> Tambah--}}
                            {{--</a>--}}
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover" id="detail_table">
                                    <thead>
                                    <tr>
                                        <th class="text-center" style="width: 15%;">
                                            Nama Kelas
                                        </th>
                                        <th class="text-center" style="width: 15%;">
                                            Pengajar
                                        </th>
                                        <th class="text-center" style="width: 10%;">
                                            Hari
                                        </th>
                                        <th class="text-center" style="width: 15%;">
                                            Tindakan
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($schedules as $schedule)
                                        <tr class='item{{$schedule->id}}'>
                                            <td class='text-center'>
                                                {{$schedule->Course->name}}
                                            </td>
                                            <td class='text-center'>
                                                {{$schedule->Course->Coach->name}}
                                            </td>
                                            <td class='text-center'>
                                                {{$schedule->day}}
                                            </td>
                                            <td class='text-center'>
                                                <a class='add-modal btn btn-info' data-id='{{$schedule->id}}'
                                                   data-schedule='{{$customer->name}}#{{$schedule->Course->name}}#{{$schedule->Course->Coach->name}}'
                                                   data-schedule_id='{{$customer->id}}#{{$schedule->id}}'>
                                                    <span class='glyphicon glyphicon-check'></span>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif

                <hr/>

                <div class="form-group">
                    <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                        <a class="btn btn-danger" href="{{ route('admin.attendances') }}"> Batal</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal form to delete a form -->
    <div id="addModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">
                    {{ Form::open(['route'=>['admin.attendances.store'],'method' => 'post','id' => 'general-form','class'=>'form-horizontal form-label-left','target'=>'_blank']) }}

                    <h3 class="text-center">Apakah anda yakin memproses absensi berikut ini?</h3>
                    <br />

                    {{--<form class="form-horizontal" role="form">--}}
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="schedule_delete">Murid:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="customer_delete" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="schedule_delete">Kelas:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="schedule_delete" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="trainer_delete">Trainer:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="trainer_delete" readonly>
                            </div>
                        </div>
                        <input type="hidden" id="customer_id" name="customer_id" value=""/>
                        <input type="hidden" id="schedule_id" name="schedule_id" value=""/>
                    {{--</form>--}}
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">
                            <span class='glyphicon glyphicon-remove'></span> Batal
                        </button>
                        <button id="submit" type="submit" class="btn btn-success">
                            <span id="" class='glyphicon glyphicon-check'></span> Hadir
                        </button>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>

@endsection

@section('styles')
    @parent
    {{ Html::style(mix('assets/admin/css/select2.css')) }}
    {{ Html::style(mix('assets/admin/css/bootstrap-datetimepicker.css')) }}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
    <style>
        .box-section{
            background-color: #ffffff;
            border: 1px solid #ccc;
            border-radius: 2px;
            padding: 10px;
        }
    </style>
@endsection

@section('scripts')
    @parent
    {{ Html::script(mix('assets/admin/js/select2.js')) }}
    {{ Html::script(mix('assets/admin/js/autonumeric.js')) }}
    {{ Html::script(mix('assets/admin/js/stringbuilder.js')) }}
    {{ Html::script(mix('assets/admin/js/bootstrap-datetimepicker.js')) }}
    <script type="text/javascript">
        // Date Picker
        $('#date').datetimepicker({
            format: "DD MMM Y"
        });


        $('#customer').select2({
            placeholder: {
                id: '{{ $customer->id ?? '-1' }}',
                text: '{{ $customerPlaceholder ?? ' - Ketik Nama, atau Email atau nomor Handphone - '}}'
            },
            width: '100%',
            minimumInputLength: 1,
            ajax: {
                url: '{{ route('select.customer_attendances') }}',
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
            window.location.replace('/admin/attendances/create?customer=' + data.id);
        });



        // Delete detail
        var deletedId = "0";
        $(document).on('click', '.add-modal', function() {
            $('.modal-title').text('Proses Absensi');
            deletedId = $(this).data('id');
            var scheduleDelete = $(this).data('schedule');
            var splitted = scheduleDelete.split('#');
            $('#customer_delete').val(splitted[0]);
            $('#schedule_delete').val(splitted[1]);
            $('#trainer_delete').val(splitted[2]);


            var scheduleID = $(this).data('schedule_id');
            var splittedId = scheduleID.split('#');
            $('#customer_id').val(splittedId[0]);
            $('#schedule_id').val(splittedId[1]);

            $('#addModal').modal('show');
        });
        // $('.modal-footer').on('click', '.delete', function() {
        //     var scheduleDelete = $(this).data('schedule');
        //     $.ajax({
        //         url: 'attendances/store',
        //         dataType: 'json',
        //         data: {
        //             'customer_id': splittedId[0],
        //             'schedule_id': splittedId[1]
        //         },
        //         success: function (data) {
        //
        //         }
        //     });
        //
        // });

    </script>
@endsection