.@extends('admin.layouts.admin')

{{--@section('title', 'Tambah Site' )--}}

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12 text-center">
            <h2>Registrasi Jadwal Baru</h2>
            <hr/>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            @include('partials._success')
            @include('partials._error')
            {{ Form::open(['route'=>['admin.schedules.store'],'method' => 'post','id' => 'general-form','class'=>'form-horizontal form-label-left']) }}

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
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="customer_id" >
                    Murid
                    <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <select id="customer_id" name="customer_id" class="form-control col-md-7 col-xs-12">
                    </select>
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
                                <th class="text-center" style="width: 15%;">
                                    Nama Kelas - Pengajar
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
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                    <a class="btn btn-danger" href="{{ route('admin.schedules') }}"> Batal</a>
                    <button type="submit" class="btn btn-success"> Simpan</button>
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
                        <div class="form-group">
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
        //Add Murid
        $('#customer_id').select2({
            placeholder: {
                id: '-1',
                text: 'Pilih Murid...'
            },
            width: '100%',
            minimumInputLength: 1,
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
        // Add new detail
        $(document).on('click', '.add-modal', function() {
            $('#day_add').empty();
            $('#course_add').select2({
                placeholder: {
                    id: '-1',
                    text: 'Pilih Kelas...'
                },
                width: '100%',
                minimumInputLength: 0,
                ajax: {
                    url: '{{ route('select.courses') }}',
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
                            $('#day_add').empty();
                            $('#day_add')
                                .append($("<option></option>")
                                    .attr("value",data[i])
                                    .text(data[i]));
                        }
                    }
                });
            });

            $('.modal-title').text('Tambah Detail');
            $('#addModal').modal('show');
        });

        var i = 1;
        $('.modal-footer').on('click', '.add', function() {
            $('#detailTable').append("<tr id='" + i + "' class='item" + $('select[name=course_add]').val() + "' align='center'>" +
                "<td><input type='text' name='course[]' class='form-control' value='"+ $('select[name=course_add]').text() + "' readonly/> <input type='hidden' name='course_id[]' value='" + $('select[name=course_add]').val() +"'/></td>" +
                "<td><input type='text' name='day[]' class='form-control' value='" + $('select[name=day_add]').val() +"' readonly/></td>" +
                "<td><span id='delete_row" + i + "' class='delete-modal btn btn-danger'><span class='glyphicon glyphicon-trash'></span></span></td></tr>");

            $('#day_add').empty();
            $('#course_add').empty();

            $("#delete_row" + i).click(function(){
                if(i>1){
                    $("#"+(i-1)).html('');
                    i--;
                }
            });

            i++;
        });
    </script>
@endsection