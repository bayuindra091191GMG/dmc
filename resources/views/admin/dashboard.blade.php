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
                                        <a href="#" class="btn btn-warning"></a>
                                    </div>
                                @else
                                    <div class="alert alert-danger fade in" role="alert">
                                        <span>{{ $package->customer->name }}  ,</span>
                                        <strong>kelas{{ $package->course->name }}. </strong>
                                        <span>Sudah mendekati Tanggal Tagihan Bulanan.</span>
                                        <span>Tanggal Expired: {{ $package->finish_date_string }}.</span>
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
@endsection

@section('scripts')
    @parent
    {{ Html::script(mix('assets/admin/js/dashboard.js')) }}
    <script>

        // window.Echo.channel('test').listen('TestEvent', function(e) {
        //     alert('TEST');
        // });
    </script>
@endsection

@section('styles')
    @parent
    {{ Html::style(mix('assets/admin/css/dashboard.css')) }}
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
