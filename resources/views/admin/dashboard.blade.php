@extends('admin.layouts.admin')

@section('content')
    <!-- page content -->
    <!-- top tiles -->
    <div id="testNotif" class="row tile_count">
        <h1>DASHBOARD</h1>
    </div>
    <div class="row tile_count">
        <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
            <span class="count_top"> Total PR Aktif</span>
            {{--<div class="count green">{{ $counts['users'] }}</div>--}}
            <div class="count green">0</div>
        </div>
        <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
            <span class="count_top"> Total Surat Jalan Aktif</span>
            <div>
                <span class="count green">0</span>
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
    <!-- /top tiles -->

    {{--<div class="row">--}}
        {{--<div class="col-md-12 col-sm-12 col-xs-12">--}}
            {{--<div id="log_activity" class="dashboard_graph">--}}

                {{--<div class="row x_title">--}}
                    {{--<div class="col-md-6">--}}
                        {{--<h3>{{ __('views.admin.dashboard.sub_title_0') }}</h3>--}}
                    {{--</div>--}}
                    {{--<div class="col-md-6">--}}
                        {{--<div class="date_piker pull-right"--}}
                             {{--style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc">--}}
                            {{--<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>--}}
                            {{--<span class="date_piker_label">--}}
                                {{--{{ \Carbon\Carbon::now()->addDays(-6)->format('F j, Y') }} - {{ \Carbon\Carbon::now()->format('F j, Y') }}--}}
                            {{--</span>--}}
                            {{--<b class="caret"></b>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}

                {{--<div class="col-md-9 col-sm-9 col-xs-12">--}}
                    {{--<div class="chart demo-placeholder" style="width: 100%; height:460px;"></div>--}}
                {{--</div>--}}


                {{--<div class="col-md-3 col-sm-3 col-xs-12 bg-white">--}}
                    {{--<div class="x_title">--}}
                        {{--<h2>{{ __('views.admin.dashboard.sub_title_1') }}</h2>--}}
                        {{--<div class="clearfix"></div>--}}
                    {{--</div>--}}

                    {{--<div class="col-md-12 col-sm-12 col-xs-6">--}}
                        {{--<div>--}}
                            {{--<p>{{ __('views.admin.dashboard.log_level_0') }}</p>--}}
                            {{--<div class="">--}}
                                {{--<div class="progress progress_sm" style="width: 76%;">--}}
                                    {{--<div class="progress-bar log-emergency" role="progressbar" data-transitiongoal="0"></div>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        {{--<div>--}}
                            {{--<p>{{ __('views.admin.dashboard.log_level_1') }}</p>--}}
                            {{--<div class="">--}}
                                {{--<div class="progress progress_sm" style="width: 76%;">--}}
                                    {{--<div class="progress-bar log-alert" role="progressbar" data-transitiongoal="0"></div>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        {{--<div>--}}
                            {{--<p>{{ __('views.admin.dashboard.log_level_2') }}</p>--}}
                            {{--<div class="">--}}
                                {{--<div class="progress progress_sm" style="width: 76%;">--}}
                                    {{--<div class="progress-bar log-critical" role="progressbar" data-transitiongoal="0"></div>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        {{--<div>--}}
                            {{--<p>{{ __('views.admin.dashboard.log_level_3') }}</p>--}}
                            {{--<div class="">--}}
                                {{--<div class="progress progress_sm" style="width: 76%;">--}}
                                    {{--<div class="asdasdasd"></div>--}}
                                    {{--<div class="progress-bar log-error" role="progressbar" data-transitiongoal="0"></div>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        {{--<div>--}}
                            {{--<p>{{ __('views.admin.dashboard.log_level_4') }}</p>--}}
                            {{--<div class="">--}}
                                {{--<div class="progress progress_sm" style="width: 76%;">--}}
                                    {{--<div class="progress-bar log-warning" role="progressbar" data-transitiongoal="0"></div>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        {{--<div>--}}
                            {{--<p>{{ __('views.admin.dashboard.log_level_5') }}</p>--}}
                            {{--<div class="">--}}
                                {{--<div class="progress progress_sm" style="width: 76%;">--}}
                                    {{--<div class="progress-bar log-notice" role="progressbar" data-transitiongoal="0"></div>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        {{--<div>--}}
                            {{--<p>{{ __('views.admin.dashboard.log_level_6') }}</p>--}}
                            {{--<div class="">--}}
                                {{--<div class="progress progress_sm" style="width: 76%;">--}}
                                    {{--<div class="progress-bar log-info" role="progressbar" data-transitiongoal="0"></div>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        {{--<div>--}}
                            {{--<p>{{ __('views.admin.dashboard.log_level_7') }}</p>--}}
                            {{--<div class="">--}}
                                {{--<div class="progress progress_sm" style="width: 76%;">--}}
                                    {{--<div class="progress-bar log-debug" role="progressbar" data-transitiongoal="0"></div>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}

                {{--<div class="clearfix"></div>--}}
            {{--</div>--}}
        {{--</div>--}}

    {{--</div>--}}
    {{--<br />--}}

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel tile overflow_hidden">
                <div class="x_title">
                    <h2>Status PR</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="error-notice">
                        @if($prWarning->count() > 0)
                            @foreach($prWarning as $pr)
                                @if($pr->priority_expired)
                                    <div class="oaerror danger">
                                        <span>Prioritas {{ $pr->priority }} - Nomor PR</span>
                                        <a style="text-decoration: underline;" href="{{ route('admin.purchase_requests.show', ['purchase_request' => $pr->id]) }}" target="_blank">{{ $pr->code }}</a>
                                        <span>sudah jatuh tempo</span>
                                    </div>
                                @else
                                    @if($pr->day_left == 1)
                                        <div class="oaerror warning">
                                            <span>Prioritas {{ $pr->priority }} - Nomor PR</span>
                                            <a style="text-decoration: underline;" href="{{ route('admin.purchase_requests.show', ['purchase_request' => $pr->id]) }}" target="_blank">{{ $pr->code }}</a>
                                            <span>jatuh tempo 1 hari lagi</span>
                                        </div>
                                    @elseif($pr->day_left == 0)
                                        <div class="oaerror warning">
                                            <span>Prioritas {{ $pr->priority }} - Nomor PR</span>
                                            <a style="text-decoration: underline;" href="{{ route('admin.purchase_requests.show', ['purchase_request' => $pr->id]) }}" target="_blank">{{ $pr->code }}</a>
                                            <span>jatuh tempo hari ini</span>
                                        </div>
                                    @endif
                                @endif
                            @endforeach
                        @else
                            <div class="oaerror success">
                                <strong>Tidak ada peringatan PR</strong>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{--<div class="row">--}}
        {{--<div class="col-md-4 col-sm-4 col-xs-12">--}}
            {{--<div id="registration_usage" class="x_panel tile fixed_height_320 overflow_hidden">--}}
                {{--<div class="x_title">--}}
                    {{--<h2>{{  __('views.admin.dashboard.sub_title_2') }}</h2>--}}
                    {{--<ul class="nav navbar-right panel_toolbox">--}}
                        {{--<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>--}}
                        {{--</li>--}}
                        {{--<li class="dropdown">--}}
                            {{--<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">--}}
                                {{--<i class="fa fa-wrench"></i>--}}
                            {{--</a>--}}
                        {{--</li>--}}
                        {{--<li><a class="close-link"><i class="fa fa-close"></i></a>--}}
                        {{--</li>--}}
                    {{--</ul>--}}
                    {{--<div class="clearfix"></div>--}}
                {{--</div>--}}
                {{--<div class="x_content">--}}
                    {{--<table class="" style="width:100%">--}}
                        {{--<tr>--}}
                            {{--<th></th>--}}
                            {{--<th>--}}
                                {{--<div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">--}}
                                    {{--<p class="">{{  __('views.admin.dashboard.sub_title_3') }}</p>--}}
                                {{--</div>--}}
                                {{--<div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">--}}
                                    {{--<p class="">{{  __('views.admin.dashboard.sub_title_4') }}</p>--}}
                                {{--</div>--}}
                            {{--</th>--}}
                        {{--</tr>--}}
                        {{--<tr>--}}
                            {{--<td>--}}
                                {{--<canvas class="canvasChart" height="140" width="140" style="margin: 15px 10px 10px 0">--}}
                                {{--</canvas>--}}
                            {{--</td>--}}
                            {{--<td>--}}
                                {{--<table class="tile_info">--}}
                                    {{--<tr>--}}
                                        {{--<td>--}}
                                            {{--<p><i class="fa fa-square aero"></i>--}}
                                                {{--<span class="tile_label">--}}
                                                     {{--{{ __('views.admin.dashboard.source_0') }}--}}
                                                {{--</span>--}}
                                            {{--</p>--}}
                                        {{--</td>--}}
                                        {{--<td id="registration_usage_from"></td>--}}
                                    {{--</tr>--}}
                                    {{--<tr>--}}
                                        {{--<td>--}}
                                            {{--<p><i class="fa fa-square red"></i>--}}
                                                {{--<span class="tile_label">--}}
                                                    {{--{{ __('views.admin.dashboard.source_1') }}--}}
                                                {{--</span>--}}
                                            {{--</p>--}}
                                        {{--</td>--}}
                                        {{--<td id="registration_usage_google"></td>--}}
                                    {{--</tr>--}}
                                    {{--<tr>--}}
                                        {{--<td>--}}
                                            {{--<p><i class="fa fa-square blue"></i>--}}
                                                {{--<span class="tile_label">--}}
                                                    {{--{{ __('views.admin.dashboard.source_2') }}--}}
                                                {{--</span>--}}
                                            {{--</p>--}}
                                        {{--</td>--}}
                                        {{--<td id="registration_usage_facebook"></td>--}}
                                    {{--</tr>--}}
                                    {{--<tr>--}}
                                        {{--<td>--}}
                                            {{--<p><i class="fa fa-square grren"></i>--}}
                                                {{--<span class="tile_label">--}}
                                                     {{--{{ __('views.admin.dashboard.source_3') }}--}}
                                                {{--</span>--}}
                                            {{--</p>--}}
                                        {{--</td>--}}
                                        {{--<td id="registration_usage_twitter"></td>--}}
                                    {{--</tr>--}}
                                {{--</table>--}}
                            {{--</td>--}}
                        {{--</tr>--}}
                    {{--</table>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}

    {{--Carousel--}}
    {{--<div class="row">--}}
        {{--<div class="col-md-12 col-sm-12 col-xs-12">--}}
            {{--<div class="x_panel">--}}
                {{--<div class="row x_title">--}}
                    {{--<div class="col-md-6">--}}
                        {{--<h3>--}}
                            {{--{!! __('views.admin.dashboard.sub_title_5',['href'=>'https://photolancer.zone']) !!}--}}
                        {{--</h3>--}}
                    {{--</div>--}}
                {{--</div>--}}
                {{--<div class="x_content">--}}
                    {{--<div class="col-md-12">--}}
                        {{--<div class="jcarousel">--}}
                            {{--<div class="loading">{{ __('views.admin.dashboard.loading') }}</div>--}}
                        {{--</div>--}}

                    {{--</div>--}}
                    {{--<div class="col-md-12 text-center jcarousel-control">--}}
                        {{--<a href="#" class="jcarousel-control-prev">&lsaquo;</a>--}}
                        {{--<a href="#" class="jcarousel-control-next">&rsaquo;</a>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}
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
