@extends('admin.layouts.admin')

@section('content')
    <!-- page content -->

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="dashboard_graph">

                <div class="row x_title">
                    <div class="col-md-6">
                        <h3>Peringatan</h3>
                    </div>
                    {{--<div class="col-md-6">--}}
                    {{--<div id="reportrange" class="pull-right" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc">--}}
                    {{--<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>--}}
                    {{--<span>December 30, 2014 - January 28, 2015</span> <b class="caret"></b>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                </div>

                <div class="x_content">
                    <div class="error-notice">
                        @if($allWarning->count() > 0)
                            @foreach($allWarning as $warning)
                                @if($warning->course->type == 1)
                                    <div class="oaerror danger">
                                        <span>{{ $warning->customer->name }},</span>
                                        <a>{{ $warning->course->name }}</a>
                                        <span>Sudah mendekati tanggal expired paket.</span>
                                        <span>Tanggal Expired: {{ $warning->finish_date_string }}</span>
                                    </div>
                                @else
                                    <div class="oaerror danger">
                                        <span>{{ $warning->customer->name }},</span>
                                        <a>{{ $warning->course->name }}</a>
                                        <span>Sudah mendekati Tanggal Tagihan Bulanan.</span>
                                        <span>Tanggal Expired: {{ $warning->finish_date_string }}</span>
                                    </div>
                                @endif
                            @endforeach
                        @else
                            <div class="oaerror success">
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
