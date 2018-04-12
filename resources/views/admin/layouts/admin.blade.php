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

                    var notifString = "default";
                    var route = "default";
                    if(notification.type === "App\\Notifications\\MaterialRequestCreated"){
                        if(notification.data['document_type'] === "Material Request Part & Non-Part"){
                            route = "/admin/material_requests/inventory/detil/" + notification.data["mr_id"];
                        }
                        notifString = "<li><a href='" + route +"'>MR " + notification.data['code'] +" telah dibuat, mohon buat PR</a></li>"
                    }

                    if(read === '0'){
                        $('#notifications').html('');
                        $('#notifications').append(notifString);
                    }
                    else{
                        $('#notifications').prepend(notifString);
                    }
                });


        })

        function clearNotif(){
            $('#notification_badge').attr('style', 'color: #515356 !important');

            $.ajax({
                type: 'POST',
                url: '{{ route('admin.notifications.read') }}',
                data: {
                    '_token': $('input[name=_token]').val()
                },
                success: function(data) {

                }
            });

        }
    </script>
@endsection