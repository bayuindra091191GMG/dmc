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
                {{--<img src="{{ auth()->user()->avatar }}" alt="..." class="img-circle profile_img">--}}
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
                <ul class="nav side-menu">
                    @foreach($menuHeader as $header)
                        <li>
                            <a style="font-weight: bold;">
                                <i class="fa fa-list"></i>
                                    {{ $header->menu_header->name }}
                                <span class="fa fa-chevron-down"></span>
                            </a>
                            <ul class="nav child_menu">
                                @foreach($menus as $menu)
                                    @if($menu->menu->menu_header->id == $header->menu_header_id)
                                        @if($menu->menu->route != "-")
                                            <li><a href="{{ route($menu->menu->route) }}">{{ $menu->menu->name }}</a></li>
                                        @else
                                            <li>
                                                <a>{{ $menu->menu->name }}<span class="fa fa-chevron-down"></span></a>
                                                <ul class="nav child_menu">
                                                    @foreach($menu->menu->menu_subs as $sub)
                                                        <li class="sub_menu">
                                                            <a href="{{ route($sub->route) }}">
                                                                {{ $sub->name }}
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </li>
                                        @endif
                                    @endif
                                @endforeach
                            </ul>
                        </li>
                    @endforeach
                    {{--<li>--}}
                        {{--<a style="font-weight: bold;">--}}
                            {{--<i class="fa fa-list"></i>--}}
                            {{--Management--}}
                            {{--<span class="fa fa-chevron-down"></span>--}}
                        {{--</a>--}}
                        {{--<ul class="nav child_menu">--}}
                            {{--<li><a href="{{ route('admin.users') }}">User</a></li>--}}
                            {{--<li><a href="{{ route('admin.sites') }}">Site</a></li>--}}
                            {{--<li><a href="{{ route('admin.departments') }}">Departemen</a></li>--}}
                            {{--<li><a href="{{ route('admin.documents') }}">Dokumen</a></li>--}}
                            {{--<li><a href="{{ route('admin.payment_methods') }}">Metode Pembayaran</a></li>--}}
                            {{--<li><a href="{{ route('admin.menu_headers') }}">Header Menu</a></li>--}}
                            {{--<li><a href="{{ route('admin.menus') }}">Menu</a></li>--}}
                            {{--<li><a href="{{ route('admin.menu_subs') }}">Sub Menu</a></li>--}}
                        {{--</ul>--}}
                    {{--</li>--}}
                    {{--<li>--}}
                        {{--<a style="font-weight: bold;">--}}
                            {{--<i class="fa fa-list"></i>--}}
                            {{--Otorisasi--}}
                            {{--<span class="fa fa-chevron-down"></span>--}}
                        {{--</a>--}}
                        {{--<ul class="nav child_menu">--}}
                            {{--<li><a href="{{ route('admin.permission_documents') }}">Otorisasi Dokumen</a></li>--}}
                            {{--<li><a href="{{ route('admin.permission_menus') }}">Otorisasi Menu</a></li>--}}
                            {{--<li><a href="{{ route('admin.roles') }}">Level Akses</a></li>--}}
                            {{--<li><a href="{{ route('admin.approval_rules') }}">Approval Rules</a></li>--}}
                        {{--</ul>--}}
                    {{--</li>--}}
                    {{--<li>--}}
                        {{--<a style="font-weight: bold;">--}}
                            {{--<i class="fa fa-list"></i>--}}
                            {{--Logistik--}}
                            {{--<span class="fa fa-chevron-down"></span>--}}
                        {{--</a>--}}
                        {{--<ul class="nav child_menu">--}}
                            {{--<li>--}}
                                {{--<a>Master<span class="fa fa-chevron-down"></span></a>--}}
                                {{--<ul class="nav child_menu">--}}
                                    {{--<li class="sub_menu">--}}
                                        {{--<a href="{{ route('admin.warehouses') }}">--}}
                                            {{--Gudang--}}
                                        {{--</a>--}}
                                    {{--</li>--}}
                                    {{--<li>--}}
                                        {{--<a href="{{ route('admin.groups') }}">--}}
                                            {{--Kategori Inventory--}}
                                        {{--</a>--}}
                                    {{--</li>--}}
                                {{--</ul>--}}
                            {{--</li>--}}
                            {{--<li><a href="{{ route('admin.items') }}">Inventory</a></li>--}}
                            {{--<li>--}}
                                {{--<a>Material Request (MR)<span class="fa fa-chevron-down"></span></a>--}}
                                {{--<ul class="nav child_menu">--}}
                                    {{--<li class="sub_menu">--}}
                                        {{--<a href="{{ route('admin.material_requests.other') }}">--}}
                                            {{--MR Part/Non-Part--}}
                                        {{--</a>--}}
                                    {{--</li>--}}
                                    {{--<li>--}}
                                        {{--<a href="{{ route('admin.material_requests.fuel') }}">--}}
                                            {{--MR BBM--}}
                                        {{--</a>--}}
                                    {{--</li>--}}
                                    {{--<li>--}}
                                        {{--<a href="{{ route('admin.material_requests.oil') }}">--}}
                                            {{--MR Oli--}}
                                        {{--</a>--}}
                                    {{--</li>--}}
                                    {{--<li>--}}
                                        {{--<a href="{{ route('admin.material_requests.service') }}">--}}
                                            {{--MR Servis--}}
                                        {{--</a>--}}
                                    {{--</li>--}}
                                {{--</ul>--}}
                            {{--</li>--}}
                            {{--<li><a href="{{ route('admin.interchanges') }}">Interchanges</a></li>--}}
                            {{--<li><a href="{{ route('admin.issued_dockets') }}">Issued Docket (ID)</a></li>--}}
                            {{--<li><a href="{{ route('admin.delivery_orders') }}">Surat Jalan</a></li>--}}
                            {{--<li><a href="{{ route('admin.stock_adjustments') }}">Stock Adjustment (SA)</a></li>--}}
                            {{--<li><a href="{{ route('admin.stock_ins') }}">Stock In (SI)</a></li>--}}
                            {{--<li><a href="{{ route('admin.stock_cards') }}">Stock Card</a></li>--}}
                            {{--<li><a href="{{ route('admin.item_mutations') }}">Mutasi</a></li>--}}
                        {{--</ul>--}}
                    {{--</li>--}}
                    {{--<li>--}}
                        {{--<a style="font-weight: bold;">--}}
                            {{--<i class="fa fa-list"></i>--}}
                            {{--Purchasing--}}
                            {{--<span class="fa fa-chevron-down"></span>--}}
                        {{--</a>--}}
                        {{--<ul class="nav child_menu">--}}
                            {{--<li><a href="{{ route('admin.suppliers') }}">Vendor</a></li>--}}
                            {{--<li><a href="{{ route('admin.purchase_requests') }}">Purchase Request (PR)</a></li>--}}
                            {{--<li><a href="{{ route('admin.quotations') }}">Request For Quotation (RFQ)</a></li>--}}
                            {{--<li><a href="{{ route('admin.purchase_orders') }}">Purchase Order (PO)</a></li>--}}
                            {{--<li><a href="{{ route('admin.item_receipts') }}">Goods Receipt (GR)</a></li>--}}
                            {{--<li><a href="{{ route('admin.purchase_invoices') }}">Purchase Invoice (PI)</a></li>--}}
                            {{--<li>--}}
                                {{--<a>Request For Payment (RFP)<span class="fa fa-chevron-down"></span></a>--}}
                                {{--<ul class="nav child_menu">--}}
                                    {{--<li class="sub_menu">--}}
                                        {{--<a href="{{ route('admin.payment_requests') }}">--}}
                                            {{--Daftar RFP--}}
                                        {{--</a>--}}
                                    {{--</li>--}}
                                    {{--<li>--}}
                                        {{--<a href="{{ route('admin.payment_requests.choose_vendor') }}">--}}
                                            {{--Tambah RFP dari Invoice--}}
                                        {{--</a>--}}
                                    {{--</li>--}}
                                    {{--<li>--}}
                                        {{--<a href="{{ route('admin.payment_requests.choose_vendor_po') }}">--}}
                                            {{--Tambah RFP DP/CBD--}}
                                        {{--</a>--}}
                                    {{--</li>--}}
                                {{--</ul>--}}
                            {{--</li>--}}
                            {{--<li>--}}
                                {{--<a>Report<span class="fa fa-chevron-down"></span></a>--}}
                                {{--<ul class="nav child_menu">--}}
                                    {{--<li class="sub_menu">--}}
                                        {{--<a href="{{ route('admin.purchase_requests.report') }}">--}}
                                            {{--Purchase Request (PR)--}}
                                        {{--</a>--}}
                                    {{--</li>--}}
                                    {{--<li>--}}
                                        {{--<a href="{{ route('admin.purchase_orders.report') }}">--}}
                                            {{--Purchase Order (PO)--}}
                                        {{--</a>--}}
                                    {{--</li>--}}
                                    {{--<li>--}}
                                        {{--<a href="{{ route('admin.item_receipts.report') }}">--}}
                                            {{--Goods Receipt (GR)--}}
                                        {{--</a>--}}
                                    {{--</li>--}}
                                    {{--<li>--}}
                                        {{--<a href="{{ route('admin.purchase_invoices.report') }}">--}}
                                            {{--Purchase Invoice (PI)--}}
                                        {{--</a>--}}
                                    {{--</li>--}}
                                {{--</ul>--}}
                            {{--</li>--}}
                        {{--</ul>--}}
                    {{--</li>--}}
                    {{--<li>--}}
                        {{--<a style="font-weight: bold;">--}}
                            {{--<i class="fa fa-list"></i>--}}
                            {{--Equipment--}}
                            {{--<span class="fa fa-chevron-down"></span>--}}
                        {{--</a>--}}
                        {{--<ul class="nav child_menu">--}}
                            {{--<li><a>Master<span class="fa fa-chevron-down"></span></a>--}}
                                {{--<ul class="nav child_menu">--}}
                                    {{--<li class="sub_menu">--}}
                                        {{--<a href="{{ route('admin.machinery_categories') }}">--}}
                                            {{--Kategori Alat--}}
                                        {{--</a>--}}
                                    {{--</li>--}}
                                    {{--<li>--}}
                                        {{--<a href="{{ route('admin.machinery_brands') }}">--}}
                                            {{--Merek Alat--}}
                                        {{--</a>--}}
                                    {{--</li>--}}
                                {{--</ul>--}}
                            {{--</li>--}}
                            {{--<li><a href="{{ route('admin.machineries') }}">Alat Berat</a></li>--}}
                        {{--</ul>--}}
                    {{--</li>--}}
                    {{--<li>--}}
                        {{--<a style="font-weight: bold;">--}}
                            {{--<i class="fa fa-list"></i>--}}
                            {{--Utility--}}
                            {{--<span class="fa fa-chevron-down"></span>--}}
                        {{--</a>--}}
                        {{--<ul class="nav child_menu">--}}
                            {{--<li>--}}
                                {{--<a href="{{ route('admin.settings.preference') }}">--}}
                                    {{--Preferensi Perusahaan--}}
                                {{--</a>--}}
                            {{--</li>--}}
                        {{--</ul>--}}
                    {{--</li>--}}
                    {{--<li>--}}
                        {{--<a style="font-weight: bold;">--}}
                            {{--<i class="fa fa-list"></i>--}}
                            {{--Master Data--}}
                            {{--<span class="fa fa-chevron-down"></span>--}}
                        {{--</a>--}}
                        {{--<ul class="nav child_menu">--}}
                            {{--<li><a>Status<span class="fa fa-chevron-down"></span></a>--}}
                                {{--<ul class="nav child_menu">--}}
                                    {{--<li class="sub_menu">--}}
                                        {{--<a href="{{ route('admin.statuses') }}">--}}
                                            {{--Daftar Status--}}
                                        {{--</a>--}}
                                    {{--</li>--}}
                                    {{--<li>--}}
                                        {{--<a href="{{ route('admin.statuses.create') }}">--}}
                                            {{--Tambah Status--}}
                                        {{--</a>--}}
                                    {{--</li>--}}
                                {{--</ul>--}}
                            {{--</li>--}}
                            {{--<li><a>Menu<span class="fa fa-chevron-down"></span></a>--}}
                                {{--<ul class="nav child_menu">--}}
                                    {{--<li class="sub_menu">--}}
                                        {{--<a href="{{ route('admin.menus') }}">--}}
                                            {{--Daftar Menu--}}
                                        {{--</a>--}}
                                    {{--</li>--}}
                                    {{--<li>--}}
                                        {{--<a href="{{ route('admin.menus.create') }}">--}}
                                            {{--Tambah Menu--}}
                                        {{--</a>--}}
                                    {{--</li>--}}
                                {{--</ul>--}}
                            {{--</li>--}}
                            {{--<li><a>Kategori Inventory<span class="fa fa-chevron-down"></span></a>--}}
                                {{--<ul class="nav child_menu">--}}
                                    {{--<li class="sub_menu">--}}
                                        {{--<a href="{{ route('admin.groups') }}">--}}
                                            {{--Daftar Kategori--}}
                                        {{--</a>--}}
                                    {{--</li>--}}
                                    {{--<li>--}}
                                        {{--<a href="{{ route('admin.groups.create') }}">--}}
                                            {{--Tambah Kategori--}}
                                        {{--</a>--}}
                                    {{--</li>--}}
                                {{--</ul>--}}
                            {{--</li>--}}
                            {{--<li><a>Departemen<span class="fa fa-chevron-down"></span></a>--}}
                                {{--<ul class="nav child_menu">--}}
                                    {{--<li class="sub_menu">--}}
                                        {{--<a href="{{ route('admin.departments') }}">--}}
                                            {{--Daftar Departemen--}}
                                        {{--</a>--}}
                                    {{--</li>--}}
                                    {{--<li>--}}
                                        {{--<a href="{{ route('admin.departments.create') }}">--}}
                                            {{--Tambah Departemen--}}
                                        {{--</a>--}}
                                    {{--</li>--}}
                                {{--</ul>--}}
                            {{--</li>--}}
                            {{--<li><a>Gudang<span class="fa fa-chevron-down"></span></a>--}}
                                {{--<ul class="nav child_menu">--}}
                                    {{--<li class="sub_menu">--}}
                                        {{--<a href="{{ route('admin.warehouses') }}">--}}
                                            {{--Daftar Gudang--}}
                                        {{--</a>--}}
                                    {{--</li>--}}
                                    {{--<li>--}}
                                        {{--<a href="{{ route('admin.warehouses.create') }}">--}}
                                            {{--Tambah Gudang--}}
                                        {{--</a>--}}
                                    {{--</li>--}}
                                {{--</ul>--}}
                            {{--</li>--}}
                            {{--<li><a>Jenis Dokumen<span class="fa fa-chevron-down"></span></a>--}}
                                {{--<ul class="nav child_menu">--}}
                                    {{--<li class="sub_menu">--}}
                                        {{--<a href="{{ route('admin.documents') }}">--}}
                                            {{--Daftar Jenis Dokumen--}}
                                        {{--</a>--}}
                                    {{--</li>--}}
                                    {{--<li>--}}
                                        {{--<a href="{{ route('admin.documents.create') }}">--}}
                                            {{--Tambah Jenis Dokumen--}}
                                        {{--</a>--}}
                                    {{--</li>--}}
                                {{--</ul>--}}
                            {{--</li>--}}
                            {{--<li><a>Satuan Unit<span class="fa fa-chevron-down"></span></a>--}}
                                {{--<ul class="nav child_menu">--}}
                                    {{--<li class="sub_menu">--}}
                                        {{--<a href="{{ route('admin.uoms') }}">--}}
                                            {{--Daftar Satuan Unit--}}
                                        {{--</a>--}}
                                    {{--</li>--}}
                                    {{--<li>--}}
                                        {{--<a href="{{ route('admin.uoms.create') }}">--}}
                                            {{--Tambah Satuan Unit--}}
                                        {{--</a>--}}
                                    {{--</li>--}}
                                {{--</ul>--}}
                            {{--</li>--}}
                            {{--<li><a>Metode Pembayaran<span class="fa fa-chevron-down"></span></a>--}}
                                {{--<ul class="nav child_menu">--}}
                                    {{--<li class="sub_menu">--}}
                                        {{--<a href="{{ route('admin.payment_methods') }}">--}}
                                            {{--Daftar Metode Pembayaran--}}
                                        {{--</a>--}}
                                    {{--</li>--}}
                                    {{--<li>--}}
                                        {{--<a href="{{ route('admin.payment_methods.create') }}">--}}
                                            {{--Tambah Metode Pembayaran--}}
                                        {{--</a>--}}
                                    {{--</li>--}}
                                {{--</ul>--}}
                            {{--</li>--}}
                            {{--<li><a>Site<span class="fa fa-chevron-down"></span></a>--}}
                                {{--<ul class="nav child_menu">--}}
                                    {{--<li class="sub_menu">--}}
                                        {{--<a href="{{ route('admin.sites') }}">--}}
                                            {{--Daftar Sites--}}
                                        {{--</a>--}}
                                    {{--</li>--}}
                                    {{--<li>--}}
                                        {{--<a href="{{ route('admin.sites.create') }}">--}}
                                            {{--Tambah Site--}}
                                        {{--</a>--}}
                                    {{--</li>--}}
                                {{--</ul>--}}
                            {{--</li>--}}
                            {{--<li><a>Vendor<span class="fa fa-chevron-down"></span></a>--}}
                                {{--<ul class="nav child_menu">--}}
                                    {{--<li class="sub_menu">--}}
                                        {{--<a href="{{ route('admin.suppliers') }}">--}}
                                            {{--Daftar Vendor--}}
                                        {{--</a>--}}
                                    {{--</li>--}}
                                    {{--<li>--}}
                                        {{--<a href="{{ route('admin.suppliers.create') }}">--}}
                                            {{--Tambah Vendor--}}
                                        {{--</a>--}}
                                    {{--</li>--}}
                                {{--</ul>--}}
                            {{--</li>--}}

                            {{--<li><a>Tipe Alat Berat<span class="fa fa-chevron-down"></span></a>--}}
                                {{--<ul class="nav child_menu">--}}
                                    {{--<li class="sub_menu">--}}
                                        {{--<a href="{{ route('admin.machinery_types') }}">--}}
                                            {{--Daftar Tipe Alat Berat--}}
                                        {{--</a>--}}
                                    {{--</li>--}}
                                    {{--<li>--}}
                                        {{--<a href="{{ route('admin.machinery_types.create') }}">--}}
                                            {{--Tambah Tipe Alat Berat--}}
                                        {{--</a>--}}
                                    {{--</li>--}}
                                {{--</ul>--}}
                            {{--</li>--}}
                            {{--<li><a>Kategori Alat Berat<span class="fa fa-chevron-down"></span></a>--}}
                                {{--<ul class="nav child_menu">--}}
                                    {{--<li class="sub_menu">--}}
                                        {{--<a href="{{ route('admin.machinery_categories') }}">--}}
                                            {{--Daftar Kategori Alat Berat--}}
                                        {{--</a>--}}
                                    {{--</li>--}}
                                    {{--<li>--}}
                                        {{--<a href="{{ route('admin.machinery_categories.create') }}">--}}
                                            {{--Tambah Kategori Alat Berat--}}
                                        {{--</a>--}}
                                    {{--</li>--}}
                                {{--</ul>--}}
                            {{--</li>--}}
                            {{--<li><a>Merek Alat Berat<span class="fa fa-chevron-down"></span></a>--}}
                                {{--<ul class="nav child_menu">--}}
                                    {{--<li class="sub_menu">--}}
                                        {{--<a href="{{ route('admin.machinery_brands') }}">--}}
                                            {{--Daftar Merek Alat Berat--}}
                                        {{--</a>--}}
                                    {{--</li>--}}
                                    {{--<li>--}}
                                        {{--<a href="{{ route('admin.machinery_brands.create') }}">--}}
                                            {{--Tambah Merek Alat Berat--}}
                                        {{--</a>--}}
                                    {{--</li>--}}
                                {{--</ul>--}}
                            {{--</li>--}}
                        {{--</ul>--}}
                    {{--</li>--}}

                </ul>
            </div>
        </div>
        <!-- /sidebar menu -->
    </div>
</div>
