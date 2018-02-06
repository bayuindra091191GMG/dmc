<?php

use DaveJamesMiller\Breadcrumbs\Generator;

Breadcrumbs::register('admin.users', function (Generator $breadcrumbs) {
    $breadcrumbs->push(__('views.admin.dashboard.title'), route('admin.dashboard'));
    $breadcrumbs->push(__('views.admin.users.index.title'));
});

Breadcrumbs::register('admin.users.show', function (Generator $breadcrumbs, \App\Models\Auth\User\User $user) {
    $breadcrumbs->push(__('views.admin.dashboard.title'), route('admin.dashboard'));
    $breadcrumbs->push(__('views.admin.users.index.title'), route('admin.users'));
    $breadcrumbs->push(__('views.admin.users.show.title', ['name' => $user->name]));
});

Breadcrumbs::register('admin.users.edit', function (Generator $breadcrumbs, \App\Models\Auth\User\User $user) {
    $breadcrumbs->push(__('views.admin.dashboard.title'), route('admin.dashboard'));
    $breadcrumbs->push(__('views.admin.users.index.title'), route('admin.users'));
    $breadcrumbs->push(__('views.admin.users.edit.title', ['name' => $user->name]));
});

// Sites
Breadcrumbs::register('admin.sites', function (Generator $breadcrumbs) {
    $breadcrumbs->push(__('views.admin.dashboard.title'), route('admin.dashboard'));
    $breadcrumbs->push('Daftar Site');
});

Breadcrumbs::register('admin.sites.create', function (Generator $breadcrumbs) {
    $breadcrumbs->push(__('views.admin.dashboard.title'), route('admin.dashboard'));
    $breadcrumbs->push('Daftar Site', route('admin.sites'));
    $breadcrumbs->push('Tambah Site');
});

Breadcrumbs::register('admin.sites.edit', function (Generator $breadcrumbs, \App\Models\Site $site) {
    $breadcrumbs->push(__('views.admin.dashboard.title'), route('admin.dashboard'));
    $breadcrumbs->push('Daftar Site', route('admin.sites'));
    $breadcrumbs->push('Ubah Site', route('admin.sites.edit', ['site' => $site]));
});

// Suppliers
Breadcrumbs::register('admin.suppliers', function (Generator $breadcrumbs) {
    $breadcrumbs->push(__('views.admin.dashboard.title'), route('admin.dashboard'));
    $breadcrumbs->push('Daftar Vendor');
});

Breadcrumbs::register('admin.suppliers.create', function (Generator $breadcrumbs) {
    $breadcrumbs->push(__('views.admin.dashboard.title'), route('admin.dashboard'));
    $breadcrumbs->push('Daftar Vendor', route('admin.suppliers'));
    $breadcrumbs->push('Tambah Vendor');
});

Breadcrumbs::register('admin.suppliers.edit', function (Generator $breadcrumbs, \App\Models\Supplier $supplier) {
    $breadcrumbs->push(__('views.admin.dashboard.title'), route('admin.dashboard'));
    $breadcrumbs->push('Daftar Vendor', route('admin.suppliers'));
    $breadcrumbs->push('Ubah Vendor', route('admin.suppliers.edit', ['supplier' => $supplier]));
});

// Employees
Breadcrumbs::register('admin.employees', function (Generator $breadcrumbs) {
    $breadcrumbs->push(__('views.admin.dashboard.title'), route('admin.dashboard'));
    $breadcrumbs->push('Daftar Karyawan');
});

Breadcrumbs::register('admin.employees.create', function (Generator $breadcrumbs) {
    $breadcrumbs->push(__('views.admin.dashboard.title'), route('admin.dashboard'));
    $breadcrumbs->push('Daftar Karyawan', route('admin.employees'));
    $breadcrumbs->push('Tambah Karyawan');
});

Breadcrumbs::register('admin.employees.edit', function (Generator $breadcrumbs, \App\Models\Employee $employee) {
    $breadcrumbs->push(__('views.admin.dashboard.title'), route('admin.dashboard'));
    $breadcrumbs->push('Daftar Karyawan', route('admin.employees'));
    $breadcrumbs->push('Ubah Karyawan', route('admin.employees.edit', ['employee' => $employee]));
});

// Items
Breadcrumbs::register('admin.items', function (Generator $breadcrumbs) {
    $breadcrumbs->push(__('views.admin.dashboard.title'), route('admin.dashboard'));
    $breadcrumbs->push('Daftar Barang');
});

Breadcrumbs::register('admin.items.create', function (Generator $breadcrumbs) {
    $breadcrumbs->push(__('views.admin.dashboard.title'), route('admin.dashboard'));
    $breadcrumbs->push('Daftar Barang', route('admin.items'));
    $breadcrumbs->push('Tambah Barang');
});

Breadcrumbs::register('admin.items.edit', function (Generator $breadcrumbs, \App\Models\Item $item) {
    $breadcrumbs->push(__('views.admin.dashboard.title'), route('admin.dashboard'));
    $breadcrumbs->push('Daftar Barang', route('admin.items'));
    $breadcrumbs->push('Ubah Barang', route('admin.items.edit', ['item' => $item]));
});

// Statuses
Breadcrumbs::register('admin.statuses', function (Generator $breadcrumbs) {
    $breadcrumbs->push(__('views.admin.dashboard.title'), route('admin.dashboard'));
    $breadcrumbs->push('Daftar Status');
});

Breadcrumbs::register('admin.statuses.create', function (Generator $breadcrumbs) {
    $breadcrumbs->push(__('views.admin.dashboard.title'), route('admin.dashboard'));
    $breadcrumbs->push('Daftar Status', route('admin.statuses'));
    $breadcrumbs->push('Tambah Status');
});

Breadcrumbs::register('admin.statuses.edit', function (Generator $breadcrumbs, \App\Models\Status $status) {
    $breadcrumbs->push(__('views.admin.dashboard.title'), route('admin.dashboard'));
    $breadcrumbs->push('Daftar Status', route('admin.statuses'));
    $breadcrumbs->push('Ubah Status', route('admin.statuses.edit', ['status' => $status]));
});

// Roles
Breadcrumbs::register('admin.roles', function (Generator $breadcrumbs) {
    $breadcrumbs->push(__('views.admin.dashboard.title'), route('admin.dashboard'));
    $breadcrumbs->push('Daftar Role');
});

Breadcrumbs::register('admin.roles.create', function (Generator $breadcrumbs) {
    $breadcrumbs->push(__('views.admin.dashboard.title'), route('admin.dashboard'));
    $breadcrumbs->push('Daftar Role', route('admin.roles'));
    $breadcrumbs->push('Tambah Role');
});

Breadcrumbs::register('admin.roles.edit', function (Generator $breadcrumbs, \App\Models\Auth\Role\Role $role) {
    $breadcrumbs->push(__('views.admin.dashboard.title'), route('admin.dashboard'));
    $breadcrumbs->push('Daftar Role', route('admin.roles'));
    $breadcrumbs->push('Ubah Role', route('admin.roles.edit', ['role' => $role]));
});

// Approval Rule
Breadcrumbs::register('admin.approval_rules', function (Generator $breadcrumbs) {
    $breadcrumbs->push(__('views.admin.dashboard.title'), route('admin.dashboard'));
    $breadcrumbs->push('Daftar Pengaturan Approval');
});

Breadcrumbs::register('admin.approval_rules.create', function (Generator $breadcrumbs) {
    $breadcrumbs->push(__('views.admin.dashboard.title'), route('admin.dashboard'));
    $breadcrumbs->push('Daftar Pengaturan Approval', route('admin.approval_rules'));
    $breadcrumbs->push('Tambah Pengaturan Approval');
});

Breadcrumbs::register('admin.approval_rules.edit', function (Generator $breadcrumbs, \App\Models\ApprovalRule $approvalRule) {
    $breadcrumbs->push(__('views.admin.dashboard.title'), route('admin.dashboard'));
    $breadcrumbs->push('Daftar Pengaturan Approval', route('admin.approval_rules'));
    $breadcrumbs->push('Ubah Pengaturan Approval', route('admin.approval_rules.edit', ['approval_rule' => $approvalRule]));
});

// Permission Menu
Breadcrumbs::register('admin.permission_menus', function (Generator $breadcrumbs) {
    $breadcrumbs->push(__('views.admin.dashboard.title'), route('admin.dashboard'));
    $breadcrumbs->push('Daftar Otorisasi Menu');
});

Breadcrumbs::register('admin.permission_menus.create', function (Generator $breadcrumbs) {
    $breadcrumbs->push(__('views.admin.dashboard.title'), route('admin.dashboard'));
    $breadcrumbs->push('Daftar Otorisasi Menu', route('admin.permission_menus'));
    $breadcrumbs->push('Tambah Otorisasi Menu');
});

Breadcrumbs::register('admin.permission_menus.edit', function (Generator $breadcrumbs, \App\Models\PermissionMenu $permissionMenu) {
    $breadcrumbs->push(__('views.admin.dashboard.title'), route('admin.dashboard'));
    $breadcrumbs->push('Daftar Otorisasi Menu', route('admin.permission_menus'));
    $breadcrumbs->push('Ubah Otorisasi Menu', route('admin.permission_menus.edit', ['permission_menu' => $permissionMenu]));
});

// Permission Documents
Breadcrumbs::register('admin.permission_documents', function (Generator $breadcrumbs) {
    $breadcrumbs->push(__('views.admin.dashboard.title'), route('admin.dashboard'));
    $breadcrumbs->push('Daftar Otorisasi Dokumen');
});

Breadcrumbs::register('admin.permission_documents.create', function (Generator $breadcrumbs) {
    $breadcrumbs->push(__('views.admin.dashboard.title'), route('admin.dashboard'));
    $breadcrumbs->push('Daftar Otorisasi Dokumen', route('admin.permission_documents'));
    $breadcrumbs->push('Tambah Otorisasi Dokumen');
});

Breadcrumbs::register('admin.permission_documents.edit', function (Generator $breadcrumbs, \App\Models\PermissionDocument $permissionDocument) {
    $breadcrumbs->push(__('views.admin.dashboard.title'), route('admin.dashboard'));
    $breadcrumbs->push('Daftar Otorisasi Dokumen', route('admin.permission_documents'));
    $breadcrumbs->push('Ubah Otorisasi Dokumen', route('admin.permission_documents.edit', ['permission_document' => $permissionDocument]));
});

// Groups
Breadcrumbs::register('admin.groups', function (Generator $breadcrumbs) {
    $breadcrumbs->push(__('views.admin.dashboard.title'), route('admin.dashboard'));
    $breadcrumbs->push('Daftar Group');
});

Breadcrumbs::register('admin.groups.create', function (Generator $breadcrumbs) {
    $breadcrumbs->push(__('views.admin.dashboard.title'), route('admin.dashboard'));
    $breadcrumbs->push('Daftar Group', route('admin.groups'));
    $breadcrumbs->push('Tambah Group');
});

Breadcrumbs::register('admin.groups.edit', function (Generator $breadcrumbs, \App\Models\Group $group) {
    $breadcrumbs->push(__('views.admin.dashboard.title'), route('admin.dashboard'));
    $breadcrumbs->push('Daftar Group', route('admin.groups'));
    $breadcrumbs->push('Ubah Group', route('admin.groups.edit', ['group' => $group]));
});

// Machineries
Breadcrumbs::register('admin.machineries', function (Generator $breadcrumbs) {
    $breadcrumbs->push(__('views.admin.dashboard.title'), route('admin.dashboard'));
    $breadcrumbs->push('Daftar Alat Berat');
});

Breadcrumbs::register('admin.machineries.create', function (Generator $breadcrumbs) {
    $breadcrumbs->push(__('views.admin.dashboard.title'), route('admin.dashboard'));
    $breadcrumbs->push('Daftar Kategori Alat Berat', route('admin.machineries'));
    $breadcrumbs->push('Tambah Alat Berat');
});

Breadcrumbs::register('admin.machineries.edit', function (Generator $breadcrumbs, \App\Models\Machinery $machinery) {
    $breadcrumbs->push(__('views.admin.dashboard.title'), route('admin.dashboard'));
    $breadcrumbs->push('Daftar Kategori Alat Berat', route('admin.machineries'));
    $breadcrumbs->push('Ubah Alat Berat', route('admin.machineries.edit', ['machinery' => $machinery]));
});

// Machinery Types
Breadcrumbs::register('admin.machinery_types', function (Generator $breadcrumbs) {
    $breadcrumbs->push(__('views.admin.dashboard.title'), route('admin.dashboard'));
    $breadcrumbs->push('Daftar Kategori Alat Berat');
});

Breadcrumbs::register('admin.machinery_types.create', function (Generator $breadcrumbs) {
    $breadcrumbs->push(__('views.admin.dashboard.title'), route('admin.dashboard'));
    $breadcrumbs->push('Daftar Kategori Alat Berat', route('admin.machinery_types'));
    $breadcrumbs->push('Tambah Kategori Alat Berat');
});

Breadcrumbs::register('admin.machinery_types.edit', function (Generator $breadcrumbs, \App\Models\MachineryCategory $machineryCategory) {
    $breadcrumbs->push(__('views.admin.dashboard.title'), route('admin.dashboard'));
    $breadcrumbs->push('Daftar Kategori Alat Berat', route('admin.machinery_types'));
    $breadcrumbs->push('Ubah Kategori Alat Berat', route('admin.machinery_types.edit', ['machinery_type' => $machineryCategory]));
});

// Machinery Brands
Breadcrumbs::register('admin.machinery_brands', function (Generator $breadcrumbs) {
    $breadcrumbs->push(__('views.admin.dashboard.title'), route('admin.dashboard'));
    $breadcrumbs->push('Daftar Kategori Alat Berat');
});

Breadcrumbs::register('admin.machinery_brands.create', function (Generator $breadcrumbs) {
    $breadcrumbs->push(__('views.admin.dashboard.title'), route('admin.dashboard'));
    $breadcrumbs->push('Daftar Kategori Alat Berat', route('admin.machinery_brands'));
    $breadcrumbs->push('Tambah Kategori Alat Berat');
});

Breadcrumbs::register('admin.machinery_brands.edit', function (Generator $breadcrumbs, \App\Models\MachineryCategory $machineryCategory) {
    $breadcrumbs->push(__('views.admin.dashboard.title'), route('admin.dashboard'));
    $breadcrumbs->push('Daftar Kategori Alat Berat', route('admin.machinery_brands'));
    $breadcrumbs->push('Ubah Kategori Alat Berat', route('admin.machinery_brands.edit', ['machinery_brands' => $machineryCategory]));
});

// Machinery Categories
Breadcrumbs::register('admin.machinery_categories', function (Generator $breadcrumbs) {
    $breadcrumbs->push(__('views.admin.dashboard.title'), route('admin.dashboard'));
    $breadcrumbs->push('Daftar Kategori Alat Berat');
});

Breadcrumbs::register('admin.machinery_categories.create', function (Generator $breadcrumbs) {
    $breadcrumbs->push(__('views.admin.dashboard.title'), route('admin.dashboard'));
    $breadcrumbs->push('Daftar Kategori Alat Berat', route('admin.machinery_categories'));
    $breadcrumbs->push('Tambah Kategori Alat Berat');
});

Breadcrumbs::register('admin.machinery_categories.edit', function (Generator $breadcrumbs, \App\Models\MachineryCategory $machineryCategory) {
    $breadcrumbs->push(__('views.admin.dashboard.title'), route('admin.dashboard'));
    $breadcrumbs->push('Daftar Kategori Alat Berat', route('admin.machinery_categories'));
    $breadcrumbs->push('Ubah Kategori Alat Berat', route('admin.machinery_categories.edit', ['machinery_category' => $machineryCategory]));
});

// Departments
Breadcrumbs::register('admin.departments', function (Generator $breadcrumbs) {
    $breadcrumbs->push(__('views.admin.dashboard.title'), route('admin.dashboard'));
    $breadcrumbs->push('Daftar Departemen');
});

Breadcrumbs::register('admin.departments.create', function (Generator $breadcrumbs) {
    $breadcrumbs->push(__('views.admin.dashboard.title'), route('admin.dashboard'));
    $breadcrumbs->push('Daftar Departemen', route('admin.departments'));
    $breadcrumbs->push('Tambah Departemen');
});

Breadcrumbs::register('admin.departments.edit', function (Generator $breadcrumbs, \App\Models\Department $department) {
    $breadcrumbs->push(__('views.admin.dashboard.title'), route('admin.dashboard'));
    $breadcrumbs->push('Daftar Departemen', route('admin.departments'));
    $breadcrumbs->push('Ubah Departemen', route('admin.departments.edit', ['department' => $department]));
});

// Documents
Breadcrumbs::register('admin.documents', function (Generator $breadcrumbs) {
    $breadcrumbs->push(__('views.admin.dashboard.title'), route('admin.dashboard'));
    $breadcrumbs->push('Daftar Dokumen');
});

Breadcrumbs::register('admin.documents.create', function (Generator $breadcrumbs) {
    $breadcrumbs->push(__('views.admin.dashboard.title'), route('admin.dashboard'));
    $breadcrumbs->push('Daftar Dokumen', route('admin.documents'));
    $breadcrumbs->push('Tambah Dokumen');
});

Breadcrumbs::register('admin.documents.edit', function (Generator $breadcrumbs, \App\Models\Document $document) {
    $breadcrumbs->push(__('views.admin.dashboard.title'), route('admin.dashboard'));
    $breadcrumbs->push('Daftar Dokumen', route('admin.documents'));
    $breadcrumbs->push('Ubah Dokumen', route('admin.documents.edit', ['document' => $document]));
});

// Payment Methods
Breadcrumbs::register('admin.payment_methods', function (Generator $breadcrumbs) {
    $breadcrumbs->push(__('views.admin.dashboard.title'), route('admin.dashboard'));
    $breadcrumbs->push('Daftar Metode Pembayaran');
});

Breadcrumbs::register('admin.payment_methods.create', function (Generator $breadcrumbs) {
    $breadcrumbs->push(__('views.admin.dashboard.title'), route('admin.dashboard'));
    $breadcrumbs->push('Daftar Metode Pembayaran', route('admin.payment_methods'));
    $breadcrumbs->push('Tambah Metode Pembayaran');
});

Breadcrumbs::register('admin.payment_methods.edit', function (Generator $breadcrumbs, \App\Models\PaymentMethod $paymentMethod) {
    $breadcrumbs->push(__('views.admin.dashboard.title'), route('admin.dashboard'));
    $breadcrumbs->push('Daftar Metode Pembayaran', route('admin.payment_methods'));
    $breadcrumbs->push('Ubah Metode Pembayaran', route('admin.payment_methods.edit', ['payment_method' => $paymentMethod]));
});

// UOMS
Breadcrumbs::register('admin.uoms', function (Generator $breadcrumbs) {
    $breadcrumbs->push(__('views.admin.dashboard.title'), route('admin.dashboard'));
    $breadcrumbs->push('Daftar Satuan Unit');
});

Breadcrumbs::register('admin.uoms.create', function (Generator $breadcrumbs) {
    $breadcrumbs->push(__('views.admin.dashboard.title'), route('admin.dashboard'));
    $breadcrumbs->push('Daftar Satuan Unit', route('admin.uoms'));
    $breadcrumbs->push('Tambah Satuan Unit');
});

Breadcrumbs::register('admin.uoms.edit', function (Generator $breadcrumbs, \App\Models\Uom $uom) {
    $breadcrumbs->push(__('views.admin.dashboard.title'), route('admin.dashboard'));
    $breadcrumbs->push('Daftar Satuan Unit', route('admin.uoms'));
    $breadcrumbs->push('Ubah Satuan Unit', route('admin.uoms.edit', ['uom' => $uom]));
});

// Warehouses
Breadcrumbs::register('admin.warehouses', function (Generator $breadcrumbs) {
    $breadcrumbs->push(__('views.admin.dashboard.title'), route('admin.dashboard'));
    $breadcrumbs->push('Daftar Gudang');
});

Breadcrumbs::register('admin.warehouses.create', function (Generator $breadcrumbs) {
    $breadcrumbs->push(__('views.admin.dashboard.title'), route('admin.dashboard'));
    $breadcrumbs->push('Daftar Gudang', route('admin.warehouses'));
    $breadcrumbs->push('Tambah Gudang');
});

Breadcrumbs::register('admin.warehouses.edit', function (Generator $breadcrumbs, \App\Models\Warehouse $warehouse) {
    $breadcrumbs->push(__('views.admin.dashboard.title'), route('admin.dashboard'));
    $breadcrumbs->push('Daftar Gudang', route('admin.warehouses'));
    $breadcrumbs->push('Ubah Gudang', route('admin.warehouses.edit', ['warehouse' => $warehouse]));
});

// Menus
Breadcrumbs::register('admin.menus', function (Generator $breadcrumbs) {
    $breadcrumbs->push(__('views.admin.dashboard.title'), route('admin.dashboard'));
    $breadcrumbs->push('Daftar Menu');
});

Breadcrumbs::register('admin.menus.create', function (Generator $breadcrumbs) {
    $breadcrumbs->push(__('views.admin.dashboard.title'), route('admin.dashboard'));
    $breadcrumbs->push('Daftar Menu', route('admin.menus'));
    $breadcrumbs->push('Tambah Menu');
});

Breadcrumbs::register('admin.menus.edit', function (Generator $breadcrumbs, \App\Models\Menu $menu) {
    $breadcrumbs->push(__('views.admin.dashboard.title'), route('admin.dashboard'));
    $breadcrumbs->push('Daftar Menu', route('admin.menus'));
    $breadcrumbs->push('Ubah Menu', route('admin.menus.edit', ['menu' => $menu]));
});


