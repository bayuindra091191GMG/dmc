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
    $breadcrumbs->push('Daftar Kategori Alat Berat', route('admin.machinery_categories'));
    $breadcrumbs->push('Ubah Alat Berat', route('admin.machinery_categories.edit', ['machinery' => $machinery]));
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


