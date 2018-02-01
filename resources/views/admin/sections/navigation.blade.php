<div class="col-md-3 left_col">
    <div class="left_col scroll-view">
        <div class="navbar nav_title" style="border: 0;">
            <a href="{{ route('admin.dashboard') }}" class="site_title">
                <span>{{ config('app.name') }}</span>
            </a>
        </div>

        <div class="clearfix"></div>

        <!-- menu profile quick info -->
        <div class="profile clearfix">
            <div class="profile_pic">
                <img src="{{ auth()->user()->avatar }}" alt="..." class="img-circle profile_img">
            </div>
            <div class="profile_info">
                <h2>{{ auth()->user()->name }}</h2>
            </div>
        </div>
        <!-- /menu profile quick info -->

        <br/>

        <!-- sidebar menu -->
        <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
            <div class="menu_section">
                <h3>{{ __('views.backend.section.navigation.sub_header_0') }}</h3>
                <ul class="nav side-menu">
                    <li>
                        <a href="{{ route('admin.dashboard') }}">
                            <i class="fa fa-home" aria-hidden="true"></i>
                            {{ __('views.backend.section.navigation.menu_0_1') }}
                        </a>
                    </li>
                </ul>
            </div>
            <div class="menu_section">
                <h3>{{ __('views.backend.section.navigation.sub_header_1') }}</h3>
                <ul class="nav side-menu">
                    <li>
                        <a href="{{ route('admin.users') }}">
                            <i class="fa fa-users" aria-hidden="true"></i>
                            {{ __('views.backend.section.navigation.menu_1_1') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.permissions') }}">
                            <i class="fa fa-key" aria-hidden="true"></i>
                            {{ __('views.backend.section.navigation.menu_1_2') }}
                        </a>
                    </li>
                </ul>
            </div>
            <div class="menu_section">
                <h3>Semua Data</h3>

                <ul class="nav side-menu">
                    <li>
                        <a>
                            <i class="fa fa-list"></i>
                            Master Data
                            <span class="fa fa-chevron-down"></span>
                        </a>
                        <ul class="nav child_menu">
                            <li><a>Menu<span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li class="sub_menu">
                                        <a href="{{ route('admin.menus') }}">
                                            Daftar Menu
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('admin.menus.create') }}">
                                            Tambah Menu
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li><a>Golongan<span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li class="sub_menu">
                                        <a href="{{ route('admin.groups') }}">
                                            Daftar Golongan
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('admin.groups.create') }}">
                                            Tambah Golongan
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li><a>Jenis Alat Berat<span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li class="sub_menu">
                                        <a href="{{ route('admin.machinery_types') }}">
                                            Daftar Jenis Alat Berat
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('admin.machinery_types.create') }}">
                                            Tambah Jenis Alat Berat
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li><a>Departemen<span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li class="sub_menu">
                                        <a href="{{ route('admin.departments') }}">
                                            Daftar Departemen
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('admin.departments.create') }}">
                                            Tambah Departemen
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li><a>Gudang<span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li class="sub_menu">
                                        <a href="{{ route('admin.warehouses') }}">
                                            Daftar Gudang
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('admin.warehouses.create') }}">
                                            Tambah Gudang
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li><a>Jenis Dokumen<span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li class="sub_menu">
                                        <a href="{{ route('admin.documents') }}">
                                            Daftar Jenis Dokumen
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('admin.documents.create') }}">
                                            Tambah Jenis Dokumen
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li><a>Satuan Unit<span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li class="sub_menu">
                                        <a href="{{ route('admin.uoms') }}">
                                            Daftar Satuan Unit
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('admin.uoms.create') }}">
                                            Tambah Satuan Unit
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li><a>Metode Pembayaran<span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li class="sub_menu">
                                        <a href="{{ route('admin.payment_methods') }}">
                                            Daftar Metode Pembayaran
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('admin.payment_methods.create') }}">
                                            Tambah Metode Pembayaran
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li><a>Role<span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li class="sub_menu">
                                        <a href="{{ route('admin.roles') }}">
                                            Daftar Role
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('admin.roles.create') }}">
                                            Tambah Role
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li><a>Pengaturan Approval<span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li class="sub_menu">
                                        <a href="{{ route('admin.approval_rules') }}">
                                            Daftar Pengaturan Approval
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('admin.approval_rules.create') }}">
                                            Tambah Pengaturan Approval
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li><a>Site<span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li class="sub_menu">
                                        <a href="{{ route('admin.sites') }}">
                                            Daftar Sites
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('admin.sites.create') }}">
                                            Tambah Site
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li><a>Otorisasi Dokumen<span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li class="sub_menu">
                                        <a href="{{ route('admin.permission_documents') }}">
                                            Daftar Otorisasi Dokumen
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('admin.permission_documents.create') }}">
                                            Tambah Otorisasi Dokumen
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li><a>Otorisasi Menu<span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li class="sub_menu">
                                        <a href="{{ route('admin.permission_menus') }}">
                                            Daftar Otorisasi Menu
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('admin.permission_menus.create') }}">
                                            Tambah Otorisasi Menu
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li><a>Jenis Alat Berat<span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li class="sub_menu">
                                        <a href="{{ route('admin.machinery_types') }}">
                                            Daftar Jenis Alat Berat
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('admin.machinery_types.create') }}">
                                            Tambah Jenis Alat Berat
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a>
                            <i class="fa fa-list"></i>
                            Data
                            <span class="fa fa-chevron-down"></span>
                        </a>
                        <ul class="nav child_menu">
                            <li><a>Barang<span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li class="sub_menu">
                                        <a href="{{ route('admin.items') }}">
                                            Daftar Barang
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('admin.items.create') }}">
                                            Tambah Barang
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li><a>Karyawan<span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li class="sub_menu">
                                        <a href="{{ route('admin.employees') }}">
                                            Daftar Karyawan
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('admin.employees.create') }}">
                                            Tambah Karyawan
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li><a>Alat Berat<span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li class="sub_menu">
                                        <a href="{{ route('admin.machineries') }}">
                                            Alat Berat
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('admin.machineries.create') }}">
                                            Alat Berat
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
            <div class="menu_section">
                <h3>{{ __('views.backend.section.navigation.sub_header_2') }}</h3>

                <ul class="nav side-menu">
                    <li>
                        <a>
                            <i class="fa fa-list"></i>
                            {{ __('views.backend.section.navigation.menu_2_1') }}
                            <span class="fa fa-chevron-down"></span>
                        </a>
                        <ul class="nav child_menu">
                            <li>
                                <a href="{{ route('log-viewer::dashboard') }}">
                                    {{ __('views.backend.section.navigation.menu_2_2') }}
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('log-viewer::logs.list') }}">
                                    {{ __('views.backend.section.navigation.menu_2_3') }}
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
            <div class="menu_section">
                <h3>{{ __('views.backend.section.navigation.sub_header_3') }}</h3>
                <ul class="nav side-menu">
                  <li>
                      <a href="http://netlicensing.io/?utm_source=Laravel_Boilerplate&utm_medium=github&utm_campaign=laravel_boilerplate&utm_content=credits" target="_blank" title="Online Software License Management"><i class="fa fa-lock" aria-hidden="true"></i>NetLicensing</a>
                  </li>
                  <li>
                      <a href="https://photolancer.zone/?utm_source=Laravel_Boilerplate&utm_medium=github&utm_campaign=laravel_boilerplate&utm_content=credits" target="_blank" title="Individual digital content for your next campaign"><i class="fa fa-camera-retro" aria-hidden="true"></i>Photolancer Zone</a>
                  </li>
                </ul>
            </div>
        </div>
        <!-- /sidebar menu -->
    </div>
</div>
