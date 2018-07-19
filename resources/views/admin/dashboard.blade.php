@extends('admin.layouts.admin')

@section('content')
    <!-- page content -->
    <!-- top tiles -->
    <div id="testNotif" class="row tile_count">
        <h1>DASHBOARD</h1>
    </div>
    <div class="row tile_count">
        <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
            <span class="count_top"> Total Customer</span>
            {{--<div class="count green">{{ $counts['users'] }}</div>--}}
            <div class="count green">{{ $totalCustomer }}</div>
        </div>
        <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
            <span class="count_top"> Total Kelas</span>
            <div>
                <span class="count green">{{ $totalClass }}</span>
                {{--<span class="count green">{{  $counts['users'] - $counts['users_unconfirmed'] }}</span>--}}
                {{--<span class="count">/</span>--}}
                {{--<span class="count red">{{ $counts['users_unconfirmed'] }}</span>--}}
            </div>
        </div>
        <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
            {{--<span class="count_top"><i class="fa fa-user-times "></i> {{ __('views.admin.dashboard.count_2') }}</span>--}}
            {{--<div>--}}
                {{--<span class="count green">{{  $counts['users'] - $counts['users_inactive'] }}</span>--}}
                {{--<span class="count">/</span>--}}
                {{--<span class="count red">{{ $counts['users_inactive'] }}</span>--}}
            {{--</div>--}}
        </div>
        <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
            {{--<span class="count_top"><i class="fa fa-lock"></i> {{ __('views.admin.dashboard.count_3') }}</span>--}}
            {{--<div>--}}
                {{--<span class="count green">{{  $counts['protected_pages'] }}</span>--}}
            {{--</div>--}}
        </div>
    </div>


    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="dashboard_graph">

                <div class="row x_title">
                    <div class="col-md-6">
                        <h3>Selamat Datang</h3>
                    </div>
                    {{--<div class="col-md-6">--}}
                    {{--<div id="reportrange" class="pull-right" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc">--}}
                    {{--<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>--}}
                    {{--<span>December 30, 2014 - January 28, 2015</span> <b class="caret"></b>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                </div>

                <div class="col-md-9 col-sm-9 col-xs-12">

                    {{--@if($scheduleFinishCount->count() > 0)--}}
                        {{--<div class="alert alert-warning alert-dismissible fade in" role="alert">--}}
                            {{--Terdapat {{ $scheduleFinishCount->count() }} jadwal Customer hampir selesai.--}}
                        {{--</div>--}}
                    {{--@endif--}}
                    <div class="alert alert-success alert-dismissible fade in" role="alert">
                        Absensi Customer, klik <a style="color: red;" href="{{ route('admin.attendances.create') }}"><strong>disini</strong></a>
                    </div>
                    <div class="alert alert-success alert-dismissible fade in" role="alert">
                        Tambah Customer baru, klik <a style="color: red;" href="{{ route('admin.customers.create') }}"><strong>disini</strong></a>
                    </div>

                </div>


                <div class="clearfix"></div>
            </div>
        </div>
    </div>
    <br/>

    {{--<div class="row">--}}
        {{--<div class="col-md-12 col-sm-12 col-xs-12">--}}
            {{--<div class="dashboard_graph">--}}

                {{--<div class="row x_title">--}}
                    {{--<div class="col-md-6">--}}
                        {{--<h3>Peringatan</h3>--}}
                    {{--</div>--}}
                    {{--<div class="col-md-6">--}}
                    {{--<div id="reportrange" class="pull-right" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc">--}}
                    {{--<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>--}}
                    {{--<span>December 30, 2014 - January 28, 2015</span> <b class="caret"></b>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}

                {{--<div class="x_content">--}}
                    {{--<div class="col-md-9 col-sm-9 col-xs-12">--}}
                        {{--@if($scheduleFinishCount->count() > 0)--}}
                            {{--@foreach($scheduleFinishCount as $warning)--}}
                                {{--@if($warning->course->type == 1)--}}
                                    {{--<div class="alert alert-danger fade in" role="alert">--}}
                                        {{--<span>{{ $warning->customer->name }},</span>--}}
                                        {{--<strong>{{ $warning->course->name }}</strong>--}}
                                        {{--<span>Sudah mendekati tanggal expired paket.</span>--}}
                                        {{--<span>Tanggal Expired: {{ $warning->finish_date_string }}</span>--}}
                                    {{--</div>--}}
                                {{--@else--}}
                                    {{--<div class="alert alert-danger fade in" role="alert">--}}
                                        {{--<span>{{ $warning->customer->name }},</span>--}}
                                        {{--<strong>{{ $warning->course->name }}</strong>--}}
                                        {{--<span>Sudah mendekati Tanggal Tagihan Bulanan.</span>--}}
                                        {{--<span>Tanggal Expired: {{ $warning->finish_date_string }}</span>--}}
                                    {{--</div>--}}
                                {{--@endif--}}
                            {{--@endforeach--}}
                            {{--<div>--}}
                                {{--<span><a style="text-decoration: underline;" href="{{ route('admin.warnings') }}" target="_blank">Klik di sini untuk melihat semua</a></span>--}}
                            {{--</div>--}}
                        {{--@else--}}
                            {{--<div class="alert alert-success fade in" role="alert">--}}
                                {{--<strong>Tidak ada peringatan</strong>--}}
                            {{--</div>--}}
                        {{--@endif--}}
                    {{--</div>--}}
                {{--</div>--}}

                {{--<div class="clearfix"></div>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            @include('partials._success')
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="dashboard_graph">

                <div class="row x_title">
                    <div class="col-md-6">
                        <h3>Package Reminder</h3>
                    </div>
                    {{--<div class="col-md-6">--}}
                    {{--<div id="reportrange" class="pull-right" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc">--}}
                    {{--<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>--}}
                    {{--<span>December 30, 2014 - January 28, 2015</span> <b class="caret"></b>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                </div>

                <div class="x_content">
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        @if($packageReminders->count() > 0)
                            @foreach($packageReminders as $package)
                                @if($package->course->type == 1)
                                    <div class="alert alert-danger fade in" role="alert">
                                        <span>{{ $package->customer->name }}, </span>
                                        <strong>kelas {{ $package->course->name }}. </strong>
                                        <span>Sisa petemuan sebanyak {{ $package->meeting_amount }} petemuan.</span>
                                        <span>Tanggal Expired: {{ $package->finish_date_string }}.</span>
                                        <button class="btn btn-success modal-renew" data-schedule-id="{{ $package->id }}" data-course="{{ $package->course->name }}" data-customer="{{ $package->customer->name }}" data-customer-parent="{{ $package->customer->parent_name }}">PERBARUI</button>
                                        <button class="btn btn-warning modal-disable" data-schedule-id="{{ $package->id }}" data-course="{{ $package->course->name }}" data-customer="{{ $package->customer->name }}" data-customer-parent="{{ $package->customer->parent_name }}">HAPUS</button>
                                    </div>
                                @else
                                    <div class="alert alert-danger fade in" role="alert">
                                        <span>{{ $package->customer->name }}  ,</span>
                                        <strong>kelas{{ $package->course->name }}. </strong>
                                        <span>Sudah mendekati Tanggal Tagihan Bulanan.</span>
                                        <span>Tanggal Expired: {{ $package->finish_date_string }}.</span>
                                        <button class="btn btn-success modal-renew" data-schedule-id="{{ $package->id }}" data-course="{{ $package->course->name }}" data-customer="{{ $package->customer->name }}" data-customer-parent="{{ $package->customer->parent_name }}">PERBARUI</button>
                                        <button class="btn btn-warning modal-disable" data-schedule-id="{{ $package->id }}" data-course="{{ $package->course->name }}" data-customer="{{ $package->customer->name }}" data-customer-parent="{{ $package->customer->parent_name }}">HAPUS</button>
                                    </div>
                                @endif
                            @endforeach
                            <div>
                                <span><a style="text-decoration: underline;" href="{{ route('admin.reminders') }}" target="_blank">Klik di sini untuk melihat semua</a></span>
                            </div>
                        @else
                            <div class="alert alert-success fade in" role="alert">
                                <strong>Tidak ada peringatan</strong>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="clearfix"></div>
            </div>
        </div>
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

@section('scripts')
    @parent
    {{ Html::script(mix('assets/admin/js/dashboard.js')) }}
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script>

        // Renew schedule
        $(document).on('click', '.modal-renew', function() {
            $('#course_renew').val($(this).data('course'));
            $('#schedule_renew_id').val($(this).data('schedule-id'));
            $('#customer_name_renew').val($(this).data('customer'));
            $('#customer_parent_renew').val($(this).data('customer-parent'));

            $('#renewModal').modal('show');
        });
        $('.modal-footer').on('click', '.renew', function() {
            $.ajax({
                type: 'POST',
                url: '{{ route('admin.reminders.renew') }}',
                data: {
                    '_token': '{{ csrf_token() }}',
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

@section('styles')
    @parent
    {{ Html::style(mix('assets/admin/css/dashboard.css')) }}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
    <style>
        .error-notice {
            margin: 5px 5px; /* Making sure to keep some distance from all side */
        }

        .oaerror {
            width: 90%; /* Configure it fit in your design  */
            margin: 0 auto; /* Centering Stuff */
            background-color: #FFFFFF; /* Default background */
            padding: 20px;
            border: 1px solid #eee;
            border-left-width: 5px;
            border-radius: 3px;
            margin: 0 auto;
            font-family: 'Open Sans', sans-serif;
            font-size: 16px;
        }

        .danger {
            border-left-color: #d9534f; /* Left side border color */
            background-color: rgba(217, 83, 79, 0.1); /* Same color as the left border with reduced alpha to 0.1 */
        }

        .danger strong {
            color:  #d9534f;
        }

        .warning {
            border-left-color: #f0ad4e;
            background-color: rgba(240, 173, 78, 0.1);
        }

        .warning strong {
            color: #f0ad4e;
        }

        .info {
            border-left-color: #5bc0de;
            background-color: rgba(91, 192, 222, 0.1);
        }

        .info strong {
            color: #5bc0de;
        }

        .success {
            border-left-color: #2b542c;
            background-color: rgba(43, 84, 44, 0.1);
        }

        .success strong {
            color: #2b542c;
        }
    </style>
@endsection
