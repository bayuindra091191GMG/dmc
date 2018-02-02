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

Breadcrumbs::register('admin.machinery_categories', function (Generator $breadcrumbs) {
    $breadcrumbs->push(__('views.admin.dashboard.title'), route('admin.dashboard'));
    $breadcrumbs->push('Daftar Kategori Alat Berat');
});

Breadcrumbs::register('admin.machinery_categories.create', function (Generator $breadcrumbs) {
    $breadcrumbs->push(__('views.admin.dashboard.title'), route('admin.dashboard'));
    $breadcrumbs->push('Daftar Kategori Alat Berat', route('admin.machinery_categories'));
    $breadcrumbs->push('Tambah Kategori Alat Berat');
});

Breadcrumbs::register('admin.machinery_categories', function (Generator $breadcrumbs) {
    $breadcrumbs->push(__('views.admin.dashboard.title'), route('admin.dashboard'));
    $breadcrumbs->push('Ubah Kategori Alat Berat');
});


