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
                        <span class="fa fa-user"></span>
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
                    <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown"
                       aria-expanded="false">
                        <span class="fa fa-bell"></span>
                        <span class="badge">2</span>
                    </a>
                    <ul id="notifications" class="dropdown-menu dropdown-usermenu pull-right">
                        @foreach($testArr as $arr)
                            <li>
                                <a href="#">Notifikasi MR {{ $arr->data['code'] }}</a>
                            </li>
                        @endforeach
                    </ul>
                </li>
            </ul>
        </nav>
    </div>
</div>

