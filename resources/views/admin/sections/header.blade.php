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
                            <a href="{{ route('admin.notifications') }}">
                                Notifikasi
                            </a>
                        </li>
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
                       aria-expanded="false" @if(!$isRead) style="color: red !important;" @endif>
                        <span class="fa fa-bell fa-lg"></span>
                    </a>
                    <div id="unread" style="display: none;">{{ $notifications->count() }}</div>
                    <ul id="notifications" class="dropdown-menu dropdown-usermenu pull-right" style="width: auto;">
                        @if($notifications->count() > 0)
                            @foreach($notifications as $notif)
                                <li @if($notif->unread()) style="background-color: #e8ebef;" @endif>
                                    @if($notif->type === 'App\Notifications\MaterialRequestCreated')
                                        <a href="{{ route('admin.material_requests.other.show', ['material_request' => $notif->data['mr_id']]) }}">MR {{ $notif->data['code'] }} telah dibuat, mohon buat PR</a>
                                    @elseif($notif->type === 'App\Notifications\PurchaseRequestCreated')
                                        @if(auth()->user()->roles->pluck('id')[0] === 13)
                                            <a href="{{ route('admin.purchase_requests.show', ['purchase_request' => $notif->data['pr_id']]) }}">PR {{ $notif->data['code'] }} telah dibuat</a>
                                        @else
                                            @if($notif->data['receiver_is_creator'] === 'true')
                                                @php( $mrType = 'default' )
                                                @if($notif->data['mr_type'] === 1)
                                                    @php( $mrType = 'other' )
                                                @elseif($notif->data['mr_type'] === 2)
                                                    @php( $mrType = 'fuel' )
                                                @elseif($notif->data['mr_type'] === 3)
                                                    @php( $mrType = 'oil' )
                                                @else
                                                    @php( $mrType = 'service' )
                                                @endif
                                                @php( $routeStr = 'admin.material_requests.'. $mrType. '.show' )
                                                <a href="{{ route($routeStr, ['material_request' => $notif->data['mr_id']]) }}">MR {{ $notif->data['mr_code'] }} anda telah diproses ke PR</a>
                                            @else
                                                <a href="{{ route('admin.purchase_requests.show', ['purchase_request' => $notif->data['pr_id']]) }}">PR {{ $notif->data['code'] }} telah dibuat. mohon buat PO</a>
                                            @endif
                                        @endif
                                    @elseif($notif->type === 'App\Notifications\PurchaseOrderCreated')
                                        @if(auth()->user()->roles->pluck('id')[0] === 13)
                                            <a href="{{ route('admin.purchase_orders.show', ['purchase_order' => $notif->data['po_id']]) }}">PO {{ $notif->data['code'] }} telah dibuat</a>
                                        @else
                                            @if($notif->data['receiver_is_mr_creator'] === 'true')
                                                @php( $mrType = 'default' )
                                                @if($notif->data['mr_type'] === 1)
                                                    @php( $mrType = 'other' )
                                                @endif
                                                @php( $routeStr = 'admin.material_requests.'. $mrType. '.show' )
                                                <a href="{{ route($routeStr, ['material_request' => $notif->data['mr_id']]) }}">MR {{ $notif->data['mr_code'] }} anda telah diproses ke PO</a>
                                            @endif
                                        @endif
                                    @endif
                                </li>
                            @endforeach
                            <li style="border-top: 1px solid #ccc;">
                                <a href="{{ route('admin.notifications') }}" style="text-align: center;">Lihat Semua Notifikasi</a>
                            </li>
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

