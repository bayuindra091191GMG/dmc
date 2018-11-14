@extends('admin.layouts.admin')

@section('title', 'Daftar Reminder')

@section('content')

    <div class="row">
        @include('partials._success')
        {{--<div class="nav navbar-right">--}}
            {{--<a href="{{ route('admin.transactions.create') }}" class="btn btn-app">--}}
                {{--<i class="fa fa-plus"></i> Tambah--}}
            {{--</a>--}}
            {{--<a href="{{ route('admin.transactions.prorate.create') }}" class="btn btn-app">--}}
                {{--<i class="fa fa-plus"></i> Tambah Prorate--}}
            {{--</a>--}}
        {{--</div>--}}
        <div class="clearfix"></div>
    </div>
    <div class="row">
        <table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0"
               width="100%" id="reminder-table">
            <thead>
            <tr>
                <th class="text-center" style="width: 10%;">No</th>
                <th class="text-center" style="width: 20%;">Nama Customer</th>
                <th class="text-center" style="width: 20%;">Nama Orang Tua Customer</th>
                <th class="text-center" style="width: 10%;">Kelas</th>
                <th class="text-center" style="width: 10%;">Sisa Pertemuan</th>
                <th class="text-center" style="width: 15%;">Sisa Hari</th>
                <th class="text-center" style="width: 15%;">Tanggal Berakhir</th>
                <th class="text-center" style="width: 15%;">Tindakan</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>

    <!-- Modal form to renew schedule -->
    <div id="renewModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">
                    <h3 class="text-center">Apakah anda yakin ingin memperbarui jadwal ini?</h3>
                    <br />
                    <form class="form-horizontal" role="form">
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="customer_name_renew">Nama Customer</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="customer_name_renew" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="customer_parent_renew">Orang Tua Customer</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="customer_parent_renew" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="course_renew">Kelas</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="course_renew" readonly>
                            </div>
                        </div>
                        <input type="hidden" id="schedule_renew_id" name="schedule_renew_id"/>
                    </form>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-warning" data-dismiss="modal">
                            <span class='glyphicon glyphicon-remove'></span> BATAL
                        </button>
                        <button type="button" class="btn btn-success renew" data-dismiss="modal">
                            <span id="" class='glyphicon glyphicon-ok'></span> PERBARUI
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal form to disable schedule -->
    <div id="disableModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">
                    <h3 class="text-center">Apakah anda yakin ingin menghapus jadwal ini?</h3>
                    <br />
                    <form class="form-horizontal" role="form">
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="customer_name_disable">Nama Customer</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="customer_name_disable" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="customer_parent_disable">Orang Tua Customer</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="customer_parent_disable" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="course_disable">Kelas</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="course_disable" readonly>
                            </div>
                        </div>
                        <input type="hidden" id="schedule_disable_id" name="schedule_disable_id"/>
                    </form>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-warning" data-dismiss="modal">
                            <span class='glyphicon glyphicon-remove'></span> BATAL
                        </button>
                        <button type="button" class="btn btn-danger disable" data-dismiss="modal">
                            <span id="" class='glyphicon glyphicon-trash'></span> HAPUS
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('styles')
    @parent
    {{ Html::style(mix('assets/admin/css/datatables.css')) }}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
@endsection

@section('scripts')
    @parent
    {{ Html::script(mix('assets/admin/js/datatables.js')) }}
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script>
        $(function() {
            $('#reminder-table').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 50,
                ajax: '{!! route('datatables.reminders') !!}',
                columns: [
                    { data: 'DT_Row_Index', orderable: false, searchable: false, class: 'text-center'},
                    { data: 'customer_name', name: 'customer_name', class: 'text-center'},
                    { data: 'customer_parent_name', name: 'customer_parent_name', class: 'text-center'},
                    { data: 'course_name', name: 'course_name', class: 'text-center'},
                    { data: 'meeting_amount', name: 'meeting_amount', class: 'text-right'},
                    { data: 'day_left', name: 'day_left', class: 'text-center'},
                    { data: 'finish_date', name: 'finish_date', class: 'text-center'},
                    { data: 'action', name:'action', orderable: false, searchable: false, class: 'text-center'}
                ],
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.10.16/i18n/Indonesian-Alternative.json"
                }
            });
        });

        // Renew schedule
        $(document).on('click', '.modal-renew', function() {
            $('#course_renew').val($(this).data('course'));
            $('#schedule_renew_id').val($(this).data('schedule-id'));
            $('#customer_name_renew').val($(this).data('customer'));
            $('#customer_parent_renew').val($(this).data('customer-parent'));

            $('#renewModal').modal('show');
        });

        $.ajaxSetup({
            headers: { 'X-CSRF-Token' : $('meta[name="csrf-token"]').attr('content') }
        });

        $('.modal-footer').on('click', '.renew', function() {
            $.ajax({
                type: 'POST',
                url: '{{ route('admin.reminders.renew') }}',
                data: {
                    'schedule_id' : $('#schedule_renew_id').val()
                },
                success: function(data) {
                    if ((data.errors)) {
                        setTimeout(function () {
                            toastr.error('Gagal ubah data!', 'Peringatan', {timeOut: 5000});
                        }, 500);
                    } else {
                        var route = '{{ route('admin.dashboard') }}';
                        window.location.replace(route);
                    }
                }
            });
        });

        // Disable schedule
        $(document).on('click', '.modal-disable', function() {
            $('#course_disable').val($(this).data('course'));
            $('#schedule_disable_id').val($(this).data('schedule-id'));
            $('#customer_name_disable').val($(this).data('customer'));
            $('#customer_parent_disable').val($(this).data('customer-parent'));

            $('#disableModal').modal('show');
        });
        $('.modal-footer').on('click', '.disable', function() {
            $.ajax({
                type: 'POST',
                url: '{{ route('admin.reminders.disable') }}',
                data: {
                    '_token': '{{ csrf_token() }}',
                    'schedule_id' : $('#schedule_disable_id').val()
                },
                success: function(data) {
                    if ((data.errors)) {
                        setTimeout(function () {
                            toastr.error('Gagal ubah data!', 'Peringatan', {timeOut: 5000});
                        }, 500);
                    } else {
                        var route = '{{ route('admin.dashboard') }}';
                        window.location.replace(route);
                    }
                }
            });
        });
    </script>
@endsection
