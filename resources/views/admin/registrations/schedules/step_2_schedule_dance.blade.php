.@extends('admin.layouts.admin')

{{--@section('title', 'Tambah Site' )--}}

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12 text-center">
            <h2>Langkah 2 - Registrasi Jadwal Sesi/Kelas {{ $courseType }}</h2>
            <hr/>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            @include('partials._success')
            {{ Form::open(['route'=>['admin.registration.step-two.store'],'method' => 'post','id' => 'general-form','class'=>'form-horizontal form-label-left']) }}

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
            <input type="hidden" id="customer_id" name="customer_id" value="{{ $student->id }}"/>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="student" >
                    Murid
                </label>
                <div class="col-md-4 col-sm-4 col-xs-12">
                    <input id="student" type="text" class="form-control col-md-7 col-xs-12"
                           name="student" value="{{ $student->name }}" readonly>
                </div>
                <div class="col-md-2 col-sm-2 col-xs-12">
                    <a href="{{ route('admin.customers.show', ['customer' => $student->id]) }}" target="_blank" class="btn btn-primary" id="schedule_check">Cek Jadwal Teregistrasi Student</a>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">
                    Transaksi
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="radio">
                        <label><input type="radio" name="transaction_type" value="normal" checked>Normal</label>
                    </div>
                    <div class="radio">
                        <label><input type="radio" name="transaction_type" value="prorate">Prorate</label>
                    </div>
                    <div class="radio">
                        <label><input type="radio" name="transaction_type" value="cuti">Cuti</label>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="control-label col-md-3 col-sm-3 col-xs-12"></div>
                <div class="col-md-2 col-sm-2 col-xs-12">
                    <a href="{{ route('admin.registration.course.info', ['type' => $type]) }}" target="_blank" class="btn btn-primary">Cek Info Kelas Dance</a>
                </div>
            </div>

            <!-- Kelas -->
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 box-section">
                    <h3 class="text-center">Kelas</h3>
                    <div class="add-modal btn btn-info">
                        <span class="glyphicon glyphicon-plus-sign"></span> Tambah
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="detailTable">
                            <thead>
                            <tr >
                                <th class="text-center">
                                    Nama Kelas
                                </th>
                                <th class="text-center">
                                    Trainer
                                </th>
                                <th class="text-center">
                                    Hari
                                </th>
                                <th class="text-center" style="width: 15%;">
                                    Tindakan
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                    <a class="btn btn-danger" href="{{ route($backRoute, ['student_id' => $student->id]) }}"> Kembali</a>
                    <a class="btn btn-success" onclick="confirmModal();" style="cursor: pointer;"> Lanjutkan</a>
                </div>
            </div>
            {{ Form::close() }}
        </div>
    </div>

    <!-- Modal form to add new detail -->
    <div id="addModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">
                    <div class="form-horizontal" role="form">
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="course_add">Kelas</label>
                            <div class="col-sm-10">
                                <select class="form-control" id="course_add" name="course_add"></select>
                                <p class="errorItem text-center alert alert-danger hidden"></p>
                            </div>
                        </div>
                        <div class="form-group" id="day_add_section" style="display: none;">
                            <label class="control-label col-sm-2" for="day_add">Hari</label>
                            <div class="col-sm-10">
                                <select id="day_add" name="day_add" class="form-control col-md-7 col-xs-12">
                                </select>
                                <p class="errorQty text-center alert alert-danger hidden"></p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-warning" data-dismiss="modal">
                            <span class='glyphicon glyphicon-remove'></span> Batal
                        </button>
                        <button type="button" class="btn btn-success add" data-dismiss="modal">
                            <span id="" class='glyphicon glyphicon-check'></span> Simpan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal form to confirm submitting schedule -->
    <div id="confirmModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    {{--                    <h4 class="modal-title">Modal Header</h4>--}}
                </div>
                <div class="modal-body text-center">
                    <h4>Apakah anda yakin ingin menyimpan jadwal?</h4>
                    <h4 class="text-warning">(Pembuatan jadwal tidak bisa dibatalkan)</h4>
                </div>
                <div class="modal-footer text-center">
                    <button type="button" class="btn btn-warning" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-success" onclick="submitForm();">Simpan</button>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('styles')
    @parent
    {{ Html::style(mix('assets/admin/css/select2.css')) }}
    {{ Html::style(mix('assets/admin/css/bootstrap-datetimepicker.css')) }}
    {{ Html::style(mix('assets/admin/css/users/edit.css')) }}
@endsection

@section('scripts')
    @parent
    {{ Html::script(mix('assets/admin/js/select2.js')) }}
    {{ Html::script(mix('assets/admin/js/autonumeric.js')) }}
    {{ Html::script(mix('assets/admin/js/users/edit.js')) }}
    {{ Html::script(mix('assets/admin/js/bootstrap-datetimepicker.js')) }}

    <script type="text/javascript">

        // Confirm submit form modal
        function confirmModal(){
            $('#confirmModal').modal('show');
        }

        function submitForm(){
            $('#general-form').submit();
        }

        // Add new detail
        $(document).on('click', '.add-modal', function() {
            $('#day_add_section').hide();
            $('#day_add').empty();
            $('#course_add').select2({
                placeholder: {
                    id: '-1',
                    text: ' - Pilih Kelas - '
                },
                width: '100%',
                minimumInputLength: 0,
                ajax: {
                    url: '{{ route('select.extended.courses') }}',
                    dataType: 'json',
                    data: function (params) {
                        return {
                            q: $.trim(params.term),
                            type: 2
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: data
                        };
                    }
                }
            });

            $('#course_add').on('select2:select', function(e){
                let data = e.params.data;
                let splitted = data.id.split('#');

                // Get Days Options
                $('#day_add').empty();
                $.ajax({
                    url: '{{ route('select.days') }}',
                    dataType: 'json',
                    data: {
                        'id': splitted[0]
                    },
                    success: function (data) {
                        var i;
                        $('#day_add').empty();
                        for(i=0; i<data.length; i++){
                            $('#day_add')
                                .append($("<option></option>")
                                    .attr("value",data[i])
                                    .text(data[i]));
                        }
                        $('#day_add_section').show();
                    }
                });
            });

            $('.modal-title').text('Tambah Detail');
            $('#addModal').modal('show');
        });

        $("#addModal").on('hide.bs.modal', function () {
            $('#day_add').empty();
            $('#course_add').val(null).trigger('change');
        });

        var i = 1;
        $('.modal-footer').on('click', '.add', function() {

            if($('select[name=day_add]').val() != null && $('#course_add').val() != null){

                let value = $('#course_add').val();
                let splitted = value.split('#');

                $('#detailTable').append("<tr id='" + i + "' class='item" + splitted[0] + "' >" +
                    "<td><input type='text' name='course[]' class='form-control' value='"+ splitted[1] + "' readonly/> <input type='hidden' name='course_id[]' value='" + splitted[0] +"'/></td>" +
                    "<td>" + splitted[2] + "</td>" +
                    "<td><input type='text' name='day[]' class='form-control' value='" + $('select[name=day_add]').val() +"' readonly/></td>" +
                    "<td class='text-center'><a class='delete-schedule btn btn-danger' data-id='" + i + "'><span class='glyphicon glyphicon-trash'></span></a></td></tr>");

                $('#day_add').empty();
                $('#course_add').empty();

                i++;
            }
        });

        $(document).on('click', '.delete-schedule', function() {
            var deletedId = $(this).data('id');

            $('#' + deletedId).remove();
        });
    </script>
@endsection