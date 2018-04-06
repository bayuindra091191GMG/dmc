<div class="top_nav">
    <div class="nav_menu">
        <nav>
            <div class="nav toggle">
                <a id="menu_toggle"><i class="fa fa-bars"></i></a>
            </div>

            <ul class="nav navbar-nav navbar-right">
                <li class="">
                    <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown"
                       aria-expanded="false">
                        {{--<img src="{{ auth()->user()->avatar }}" alt="">{{ auth()->user()->name }}--}}
                        <span class="fa fa-user fa-lg"></span>
                        <span class=" fa fa-angle-down"></span>
                    </a>
                    <ul class="dropdown-menu dropdown-usermenu pull-right">
                        <li>
                            <a href="{{ route('admin.settings.edit') }}">
                                Setting
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('logout') }}">
                                <i class="fa fa-sign-out pull-right"></i> {{ __('views.backend.section.header.menu_0') }}
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="">
                    <a id="notification_badge" href="javascript:;" class="dropdown-toggle" data-toggle="dropdown"
                       aria-expanded="false" onclick="clearNotif();">
                        <span class="fa fa-bell fa-lg"></span>
                    </a>
                    <div id="unread" style="display: none;">{{ $notifications->count() }}</div>
                    <ul id="notifications" class="dropdown-menu dropdown-usermenu pull-right" style="width: auto;">
                        @if($notifications->count() > 0)
                            @foreach($notifications as $notif)
                                <li>
                                    @if($notif->type == 'App\Notifications\MaterialRequestCreated')
                                        <a href="{{ route('admin.material_requests.show', ['material_request' => $notif->data['mr_id']]) }}">MR {{ $notif->data['code'] }} telah dibuat, mohon buat PR</a>
                                    @endif
                                </li>
                            @endforeach
                        @else
                            <li>
                                <a href="#">Tidak ada notifikasi</a>
                            </li>
                        @endif
                    </ul>
                </li>
            </ul>
        </nav>
    </div>
</div>

