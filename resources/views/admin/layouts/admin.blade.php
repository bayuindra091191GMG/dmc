@extends('layouts.app')

@section('body_class','nav-md')

@section('page')
    <div class="container body">
        <div class="main_container">
            @section('header')
                @include('admin.sections.navigation')
                @include('admin.sections.header')
            @show

            @yield('left-sidebar')

            <div class="right_col" role="main">
                <div class="row">
                    <div class="page-title">
                        <div class="title_left">
                            <h1 class="h3">@yield('title')</h1>
                        </div>
                        @if(Breadcrumbs::exists())
                            <div class="title_right">
                                <div class="pull-right">
                                    {!! Breadcrumbs::render() !!}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                @yield('content')
            </div>

            <footer>
                @include('admin.sections.footer')
            </footer>
        </div>
    </div>
@stop

@section('styles')
    {{ Html::style(mix('assets/admin/css/admin.css')) }}
    <style>
        .has_notification{
            color: red !important;
        }
    </style>
@endsection

@section('scripts')
    {{ Html::script(mix('assets/admin/js/admin.js')) }}
    <script>
        $(document).ready(function() {
            var userId = '<?php echo auth()->user()->id; ?>';
            window.Echo.private(`App.Models.Auth.User.User.` + userId)
                .notification((notification) => {
                    var read = $('#unread').html();
                    $('#notification_badge').attr('style', 'color: red !important');

                    if(read === '0'){
                        $('#notifications').html('');
                        $('#notifications').append("<li><a href='#'>" + notification.type + "</a></li>");
                    }
                    else{
                        $('#notifications').prepend("<li><a href='#'>" + notification.type + "</a></li>");
                    }
                });


        })

        function clearNotif(){
            $('#notification_badge').attr('style', 'color: #515356 !important');
        }
    </script>
@endsection