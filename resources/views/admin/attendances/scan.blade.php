@extends('admin.layouts.admin')
@section('content')
    <div class="row" style="margin-bottom: 10px;">
        <div class="col-md-12">
            <div class="dashboard_graph">
                <form class="form-horizontal form-label-left">
                    <div class="row x_title">
                        <div class="col-md-12">
                            <h3>Data Student</h3>
                        </div>
                    </div>
                    <div class="row" style="margin-bottom: 20px;">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="scan_input" >
                                    BARCODE SCAN
                                </label>
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                    <input type="text" id="scan_input" name="scan_input" class="form-control">
                                </div>
                            </div>
                            @if(count($errors))
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12">
                                    </label>
                                    <div class="col-md-5 col-sm-5 col-xs-12 alert alert-danger alert-dismissible fade in" role="alert">
                                        <ul>
                                            @foreach($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-xs-12">
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name" >
                                    Nama &nbsp;
                                </label>
                                <div class="col-md-9 col-sm-9 col-xs-12">
                                    <input type="text" id="name" name="name" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email" >
                                    Email &nbsp;
                                </label>
                                <div class="col-md-9 col-sm-9 col-xs-12">
                                    <input type="text" id="email" name="email" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="parent_name" >
                                    Nama Orang Tua &nbsp;
                                </label>
                                <div class="col-md-9 col-sm-9 col-xs-12">
                                    <input type="text" id="parent_name" name="parent_name" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="phone" >
                                    Nomor Telpon &nbsp;
                                </label>
                                <div class="col-md-9 col-sm-9 col-xs-12">
                                    <input type="text" id="phone" name="phone" class="form-control" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-xs-12 text-center  ">
                            <img src="{{ asset('custom/photo-default.png') }}" id="photo" style="width: 180px; height: auto;">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-xs-12">
            <div class="dashboard_graph">
                <div class="row x_title">
                    <div class="col-md-12">
                        <h3>Absensi Student</h3>
                    </div>
                </div>
                <div class="row x_title">
                    <div class="col-md-12">
                        <table id="table_attendance" class="table table-striped table-bordered dt-responsive nowrap">
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
                            <tbody id="content_schedule">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal form to create attendance -->
    <div id="addModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">
                    {{ Form::open(['route'=>['admin.attendances.store'],'method' => 'post','id' => 'general-form','class'=>'form-horizontal form-label-left', 'target'=>'_blank']) }}

                    <h3 class="text-center">Apakah anda yakin memproses absensi berikut ini?</h3>
                    <br />

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
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">
                            <span class='glyphicon glyphicon-remove'></span> Batal
                        </button>
                        <a id="submit" onclick="formsubmit()" class="btn btn-success">
                            <span id="" class='glyphicon glyphicon-check'></span> Hadir
                        </a>
                    </div>

                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    @parent
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
@endsection

@section('scripts')
    @parent
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    {{ Html::script(mix('assets/admin/js/stringbuilder.js')) }}
    <script src="{{ asset('custom/jquery.scannerdetection.js') }}"></script>
    <script>
        var defaultPhotoUrl = '{{ asset('custom/photo-default.png') }}';

        // Attend modal
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

        function formsubmit(){
            $('#general-form').submit();
        }

        $(document).scannerDetection({

            //https://github.com/kabachello/jQuery-Scanner-Detection

            timeBeforeScanTest: 200, // wait for the next character for upto 200ms
            avgTimeByChar: 40, // it's not a barcode if a character takes longer than 100ms
            preventDefault: true,

            endChar: [13],

            onComplete: function(barcode, qty){
                validScan = true;
                $('#scan_input').val (barcode);

                $.ajax({
                    type: 'POST',
                    url: '{{ route('admin.attendances.scan.submit') }}',
                    data: {
                        '_token': '{{ csrf_token() }}',
                        'barcode': barcode,
                    },
                    success: function(data) {
                        if ((data.errors)) {
                            setTimeout(function () {
                                toastr.error('Kode barcode member tidak ditemukan!', 'Peringatan', {timeOut: 5000});

                                $('#name').val ('');
                                $('#email').val ('');
                                $('#parent_name').val ('');
                                $('#phone').val ('');

                                // Empty schedule table
                                $('#content_schedule').html('');

                                $("#photo").attr("src", defaultPhotoUrl);
                            }, 500);
                        } else {
                            $('#name').val (data.name);
                            $('#email').val (data.email);
                            $('#parent_name').val (data.parent_name);
                            $('#phone').val (data.phone);

                            if(data.photo_path !== 'EMPTY'){
                                $("#photo").attr("src", data.photo_path);
                            }

                            // Empty schedule table
                            $('#content_schedule').html('');

                            // Add to schedule table
                            let sbSchedule = new stringbuilder();

                            let schedules = data.schedules;
                            if(schedules.length > 0){
                                for(let j = 0; j < schedules.length; j++){
                                    sbSchedule.append("<tr class='item" + schedules[j].schedule_id +"'>")
                                    sbSchedule.append("<td class='text-center'>" + schedules[j].course_name +"</td>");
                                    sbSchedule.append("<td class='text-center'>" + schedules[j].coach +"</td>");
                                    sbSchedule.append("<td class='text-center'>" + schedules[j].day +"</td>");
                                    sbSchedule.append("<td class='text-center'>");
                                    sbSchedule.append("<a class='add-modal btn btn-info'");
                                    sbSchedule.append("data-id='" + schedules[j].schedule_id + "'");
                                    sbSchedule.append("data-schedule='" + data.name + "#" + schedules[j].course_name + "#" + schedules[j].coach + "'");
                                    sbSchedule.append("data-schedule_id='" + data.student_id + "#" + schedules[j].schedule_id + "'>");
                                    sbSchedule.append("<span class='glyphicon glyphicon-check' style='color: #fff;'></span>");
                                    sbSchedule.append("</tr>");
                                }
                            }
                            else{
                                sbSchedule.append("<tr><td colspan='4' class='text-center' style='color: red; font-weight: bold;'>TIDAK ADA DATA JADWAL</td></tr>")
                            }

                            $('#table_attendance').append(sbSchedule.toString());

                        }
                    },
                });
            } // main callback function	,
            ,
            onError: function(string, qty) {

            }
        });

        $("#general-form").on("submit", function(){
            $('#addModal').modal('hide');
        })
    </script>
@endsection