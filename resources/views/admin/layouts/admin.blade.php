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
    {{--<script>--}}
        {{--$(document).ready(function() {--}}

            {{--window.Echo.private(`App.Models.Auth.User.User.` + userId)--}}
                {{--.notification((notification) => {--}}
                    {{--var read = $('#unread').html();--}}
                    {{--$('#notification_badge').attr('style', 'color: red !important');--}}

                    {{--var notifString = "<li style='background-color: #e8ebef;'>";--}}
                    {{--var route = "default";--}}
                    {{--if(notification.type === "App\\Notifications\\MaterialRequestCreated"){--}}
                        {{--if(notification.data['document_type'] === "Material Request Part & Non-Part"){--}}
                            {{--route = "/admin/material_requests/inventory/detil/" + notification.data["mr_id"];--}}
                            {{--notifString += "<a href='" + route +"'>MR " + notification.data['code'] +" telah dibuat, mohon buat PR</a>"--}}
                        {{--}--}}

                    {{--}--}}
                    {{--else if(notification.type === "App\\Notifications\\PurchaseRequestCreated"){--}}
                        {{--route = "/admin/purchase_requests/detil/" + notification.data["pr_id"];--}}
                        {{--if(roleId === '13'){--}}
                            {{--notifString += "<a href='" + route +"'>PR " + notification.data['code'] +" telah dibuat</a>"--}}
                        {{--}--}}
                        {{--else{--}}
                            {{--if(notification.data['receiver_is_creator'] === 'true'){--}}
                                {{--mrType = 'default';--}}
                                {{--if(notification.data['mr_type'] === 1){--}}
                                    {{--mrType = 'inventory';--}}
                                {{--}--}}
                                {{--else if(notification.data['mr_type'] === 2){--}}
                                    {{--mrType = 'bbm';--}}
                                {{--}--}}
                                {{--else if(notification.data['mr_type'] === 3){--}}
                                    {{--mrType = 'oli';--}}
                                {{--}--}}
                                {{--else{--}}
                                    {{--mrType = 'servis';--}}
                                {{--}--}}

                                {{--mrRoute = "/admin/material_requests/" + mrType + "/detil/" + notification.data['mr_id'];--}}
                                {{--notifString += "<a href='" + mrRoute + "'>MR " + notification.data['mr_code'] + " anda telah diproses ke PR</a>"--}}
                            {{--}--}}
                            {{--else{--}}
                                {{--notifString += "<a href='" + route + "'>PR " + notification.data['code'] +" telah dibuat, mohon buat PO</a>"--}}
                            {{--}--}}
                        {{--}--}}
                    {{--}--}}
                    {{--else if(notification.type === "App\\Notifications\\PurchaseOrderCreated"){--}}
                        {{--route = "/admin/purchase_orders/detil/" + notification.data["po_id"];--}}
                        {{--if(roleId === '13'){--}}
                            {{--notifString += "<a href='" + route +"'>PO " + notification.data['code'] +" telah dibuat</a>"--}}
                        {{--}--}}
                        {{--else{--}}
                            {{--if(notification.data['receiver_is_mr_creator'] === 'true'){--}}
                                {{--mrType = 'default';--}}
                                {{--if(notification.data['mr_type'] === 1){--}}
                                    {{--mrType = 'inventory';--}}
                                {{--}--}}
                                {{--else if(notification.data['mr_type'] === 2){--}}
                                    {{--mrType = 'bbm';--}}
                                {{--}--}}
                                {{--else if(notification.data['mr_type'] === 3){--}}
                                    {{--mrType = 'oli';--}}
                                {{--}--}}
                                {{--else{--}}
                                    {{--mrType = 'servis';--}}
                                {{--}--}}

                                {{--mrRoute = "/admin/material_requests/" + mrType + "/detil/" + notification.data['mr_id'];--}}
                                {{--notifString += "<a href='" + mrRoute + "'>MR " + notification.data['mr_code'] + " anda telah diproses ke PO</a>"--}}
                            {{--}--}}
                        {{--}--}}
                    {{--}--}}
                    {{--else if(notification.type === "App\\Notifications\\GoodsReceiptCreated"){--}}
                        {{--route = "/admin/item_receipts/detil/" + notification.data["gr_id"];--}}
                        {{--if(roleId === '13'){--}}
                            {{--notifString += "<a href='" + route +"'>GR " + notification.data['code'] +" telah dibuat</a>"--}}
                        {{--}--}}
                        {{--else{--}}
                            {{--if(notification.data['receiver_is_mr_creator'] === 'true'){--}}
                                {{--mrType = 'default';--}}
                                {{--if(notification.data['mr_type'] === 1){--}}
                                    {{--mrType = 'inventory';--}}
                                {{--}--}}
                                {{--else if(notification.data['mr_type'] === 2){--}}
                                    {{--mrType = 'bbm';--}}
                                {{--}--}}
                                {{--else if(notification.data['mr_type'] === 3){--}}
                                    {{--mrType = 'oli';--}}
                                {{--}--}}
                                {{--else{--}}
                                    {{--mrType = 'servis';--}}
                                {{--}--}}

                                {{--mrRoute = "/admin/material_requests/" + mrType + "/detil/" + notification.data['mr_id'];--}}
                                {{--notifString += "<a href='" + mrRoute + "'>MR " + notification.data['mr_code'] + " anda telah diproses ke GR</a>"--}}
                            {{--}--}}
                            {{--else if(notification.data['receiver_is_pr_creator'] === 'true'){--}}
                                {{--prRoute = "/admin/purchase_requests/detil/" + notification.data['pr_id'];--}}
                                {{--notifString += "<a href='" + prRoute + "'>PR " + notification.data['pr_code'] + " anda telah diproses ke GR</a>"--}}
                            {{--}--}}
                        {{--}--}}
                    {{--}--}}

                    {{--notifString += "</li>";--}}

                    {{--if(read === '0'){--}}
                        {{--$('#notifications').html('');--}}
                        {{--$('#notifications').append(notifString);--}}
                        {{--var readInt = parseInt(read);--}}
                        {{--readInt++;--}}
                        {{--$('#unread').html(readInt);--}}
                    {{--}--}}
                    {{--else{--}}
                        {{--$('#notifications').prepend(notifString);--}}
                    {{--}--}}
                {{--});--}}


        {{--});--}}

        {{--function clearNotif(){--}}
            {{--$('#notification_badge').attr('style', 'color: #515356 !important');--}}

            {{--$.ajax({--}}
                {{--type: 'POST',--}}
                {{--url: '{{ route('admin.notifications.read') }}',--}}
                {{--data: {--}}
                    {{--_token: '{!! csrf_token() !!}',--}}
                {{--},--}}
                {{--success: function(data) {--}}

                {{--}--}}
            {{--});--}}
        {{--}--}}

        {{--// Disable enter submit--}}
        {{--$('#general-form').keypress(--}}
            {{--function(event){--}}
                {{--if (event.which == '13') {--}}
                    {{--event.preventDefault();--}}
                {{--}--}}
            {{--});--}}
    {{--</script>--}}
@endsection