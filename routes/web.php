<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


/**
 * Auth routes
 */
Route::group(['namespace' => 'Auth'], function () {

    // Authentication Routes...
    Route::get('login', 'LoginController@showLoginForm')->name('login');
    Route::post('login', 'LoginController@login');
    Route::get('logout', 'LoginController@logout')->name('logout');

    // Registration Routes...
    if (config('auth.users.registration')) {
        Route::get('register', 'RegisterController@showRegistrationForm')->name('register');
        Route::post('register', 'RegisterController@register');
    }

    // Password Reset Routes...
    Route::get('password/reset', 'ForgotPasswordController@showLinkRequestForm')->name('password.request');
    Route::post('password/email', 'ForgotPasswordController@sendResetLinkEmail')->name('password.email');
    Route::get('password/reset/{token}', 'ResetPasswordController@showResetForm')->name('password.reset');
    Route::post('password/reset', 'ResetPasswordController@reset');

    // Confirmation Routes...
    if (config('auth.users.confirm_email')) {
        Route::get('confirm/{user_by_code}', 'ConfirmController@confirm')->name('confirm');
        Route::get('confirm/resend/{user_by_email}', 'ConfirmController@sendEmail')->name('confirm.send');
    }

    // Social Authentication Routes...
    Route::get('social/redirect/{provider}', 'SocialLoginController@redirect')->name('social.redirect');
    Route::get('social/login/{provider}', 'SocialLoginController@login')->name('social.login');
});

/**
 * Backend routes
 */
Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => 'admin'], function () {

    // Dashboard
    Route::get('/', 'DashboardController@index')->name('dashboard');

    //Users
    Route::get('users', 'UserController@index')->name('users');
    Route::get('users/{user}', 'UserController@show')->name('users.show');
    Route::get('users/{user}/ubah', 'UserController@edit')->name('users.edit');
    Route::put('users/{user}', 'UserController@update')->name('users.update');
    Route::delete('users/{user}', 'UserController@destroy')->name('users.destroy');
    Route::get('permissions', 'PermissionController@index')->name('permissions');
    Route::get('permissions/{user}/repeat', 'PermissionController@repeat')->name('permissions.repeat');
    Route::get('dashboard/log-chart', 'DashboardController@getLogChartData')->name('dashboard.log.chart');
    Route::get('dashboard/registration-chart', 'DashboardController@getRegistrationChartData')->name('dashboard.registration.chart');

    //Sites
    Route::get('sites', 'SiteController@index')->name('sites');
    Route::get('sites/tambah', 'SiteController@create')->name('sites.create');
    Route::post('sites/simpan', 'SiteController@store')->name('sites.store');
    Route::get('sites/{site}/ubah/', 'SiteController@edit')->name('sites.edit');
    Route::put('sites/ubah/{site}', 'SiteController@update')->name('sites.update');

    //Suppliers
    Route::get('suppliers', 'SupplierController@index')->name('suppliers');
    Route::get('suppliers/tambah', 'SupplierController@create')->name('suppliers.create');
    Route::post('suppliers/simpan', 'SupplierController@store')->name('suppliers.store');
    Route::get('suppliers/{supplier}/ubah/', 'SupplierController@edit')->name('suppliers.edit');
    Route::put('suppliers/ubah/{supplier}', 'SupplierController@update')->name('suppliers.update');

    //Employees
    Route::get('employees', 'EmployeeController@index')->name('employees');
    Route::get('employees/tambah', 'EmployeeController@create')->name('employees.create');
    Route::post('employees/simpan', 'EmployeeController@store')->name('employees.store');
    Route::get('employees/{employee}/ubah/', 'EmployeeController@edit')->name('employees.edit');
    Route::put('employees/ubah/{employee}', 'EmployeeController@update')->name('employees.update');

    //Items
    Route::get('items', 'ItemController@index')->name('items');
    Route::get('items/tambah', 'ItemController@create')->name('items.create');
    Route::post('items/simpan', 'ItemController@store')->name('items.store');
    Route::get('items/{status}/ubah', 'Itemontroller@create')->name('items.edit');
    Route::put('items/ubah/{status}', 'ItemController@update')->name('items.update');

    //Statuses
    Route::get('statuses', 'StatusController@index')->name('statuses');
    Route::get('statuses/tambah', 'StatusController@create')->name('statuses.create');
    Route::post('statuses/simpan', 'StatusController@store')->name('statuses.store');
    Route::get('statuses/{status}/ubah', 'StatusController@create')->name('statuses.edit');
    Route::put('statuses/ubah/{status}', 'StatusController@update')->name('statuses.update');

    //Roles
    Route::get('roles', 'RoleController@index')->name('roles');
    Route::get('roles/{role}/ubah', 'RoleController@edit')->name('roles.edit');
    Route::put('roles/ubah/{role}', 'RoleController@update')->name('roles.update');
    Route::get('roles/tambah', 'RoleController@create')->name('roles.create');
    Route::post('roles/simpan', 'RoleController@store')->name('roles.store');

    //Approval Rule
    Route::get('approval_rules', 'ApprovalRuleController@index')->name('approval_rules');
    Route::get('approval_rules/{approval_rule}/ubah', 'ApprovalRuleController@edit')->name('approval_rules.edit');
    Route::put('approval_rules/ubah/{approval_rule}', 'ApprovalRuleController@update')->name('approval_rules.update');
    Route::get('approval_rules/tambah', 'ApprovalRuleController@create')->name('approval_rules.create');
    Route::post('approval_rules/simpan', 'ApprovalRuleController@store')->name('approval_rules.store');

    //Permission Menu
    Route::get('permission_menus', 'PermissionMenuController@index')->name('permission_menus');
    Route::get('permission_menus/{permission_menu}/ubah', 'PermissionMenuController@edit')->name('permission_menus.edit');
    Route::put('permission_menus/ubah/{permission_menus}', 'PermissionMenuController@update')->name('permission_menus.update');
    Route::get('permission_menus/tambah', 'PermissionMenuController@create')->name('permission_menus.create');
    Route::post('permission_menus/simpan', 'PermissionMenuController@store')->name('permission_menus.store');

    //Permission Document
    Route::get('permission_documents', 'PermissionDocumentController@index')->name('permission_documents');
    Route::get('permission_documents/{permission_document}/ubah', 'PermissionDocumentController@edit')->name('permission_documents.edit');
    Route::put('permission_documents/ubah/{permission_document}', 'PermissionDocumentController@update')->name('permission_documents.update');
    Route::get('permission_documents/tambah', 'PermissionDocumentController@create')->name('permission_documents.create');
    Route::post('permission_documents/simpan', 'PermissionDocumentController@store')->name('permission_documents.store');

    //Groups
    Route::get('groups', 'GroupController@index')->name('groups');
    Route::get('groups/{group}/ubah', 'GroupController@edit')->name('groups.edit');
    Route::put('groups/ubah/{group}', 'GroupController@update')->name('groups.update');
    Route::get('groups/tambah', 'GroupController@create')->name('groups.create');
    Route::post('groups/simpan', 'GroupController@store')->name('groups.store');

    //Machinery Types
    Route::get('machinery_types', 'MachineryTypeController@index')->name('machinery_types');
    Route::get('machinery_types/{machinery_type}/ubah', 'MachineryTypeController@edit')->name('machinery_types.edit');
    Route::put('machinery_types/ubah/{machinery_type}', 'MachineryTypeController@update')->name('machinery_types.update');
    Route::get('machinery_types/tambah', 'MachineryTypeController@create')->name('machinery_types.create');
    Route::post('machinery_types/simpan', 'MachineryTypeController@store')->name('machinery_types.store');

    //Machineries
    Route::get('machineries', 'MachineryController@index')->name('machineries');
    Route::get('machineries/{machinery}/ubah', 'MachineryController@edit')->name('machineries.edit');
    Route::put('machineries/ubah/{machinery}', 'MachineryController@update')->name('machineries.update');
    Route::get('machineries/tambah', 'MachineryController@create')->name('machineries.create');
    Route::post('machineries/simpan', 'MachineryController@store')->name('machineries.store');

    //Departments
    Route::get('departments', 'DepartmentController@index')->name('departments');
    Route::get('departments/{department}/ubah', 'DepartmentController@edit')->name('departments.edit');
    Route::put('departments/ubah/{department}', 'DepartmentController@update')->name('departments.update');
    Route::get('departments/tambah', 'DepartmentController@create')->name('departments.create');
    Route::post('departments/simpan', 'DepartmentController@store')->name('departments.store');

    //Documents
    Route::get('documents', 'DocumentController@index')->name('documents');
    Route::get('documents/{document}/ubah', 'DocumentController@edit')->name('documents.edit');
    Route::put('documents/ubah/{document}', 'DocumentController@update')->name('documents.update');
    Route::get('documents/tambah', 'DocumentController@create')->name('documents.create');
    Route::post('documents/simpan', 'DocumentController@store')->name('documents.store');

    //Payment Methods
    Route::get('payment_methods', 'PaymentMethodController@index')->name('payment_methods');
    Route::get('payment_methods/{payment_method}/ubah', 'PaymentMethodController@edit')->name('payment_methods.edit');
    Route::put('payment_methods/ubah/{payment_method}', 'PaymentMethodController@update')->name('payment_methods.update');
    Route::get('payment_methods/tambah', 'PaymentMethodController@create')->name('payment_methods.create');
    Route::post('payment_methods/simpan', 'PaymentMethodController@store')->name('payment_methods.store');

    //UOMs
    Route::get('uoms', 'UOMController@index')->name('uoms');
    Route::get('uoms/{uom}/ubah', 'UOMController@edit')->name('uoms.edit');
    Route::put('uoms/ubah/{uom}', 'UOMController@update')->name('uoms.update');
    Route::get('uoms/tambah', 'UOMController@create')->name('uoms.create');
    Route::post('uoms/simpan', 'UOMController@store')->name('uoms.store');

    //Warehouses
    Route::get('warehouses', 'WarehouseController@index')->name('warehouses');
    Route::get('warehouses/{warehouse}/ubah', 'WarehouseController@edit')->name('warehouses.edit');
    Route::put('warehouses/ubah/{warehouse}', 'WarehouseController@update')->name('warehouses.update');
    Route::get('warehouses/tambah', 'WarehouseController@create')->name('warehouses.create');
    Route::post('warehouses/simpan', 'WarehouseController@store')->name('warehouses.store');

    //Menus
    Route::get('menus', 'MenuController@index')->name('menus');
    Route::get('menus/{menu}/ubah', 'MenuController@edit')->name('menus.edit');
    Route::put('menus/ubah/{menu}', 'MenuController@update')->name('menus.update');
    Route::get('menus/tambah', 'MenuController@create')->name('menus.create');
    Route::post('menus/simpan', 'MenuController@store')->name('menus.store');
});


Route::get('/', 'Auth\LoginController@showLoginForm');

/**
 * Membership
 */
Route::group(['as' => 'protection.'], function () {
    Route::get('membership', 'MembershipController@index')->name('membership')->middleware('protection:' . config('protection.membership.product_module_number') . ',protection.membership.failed');
    Route::get('membership/access-denied', 'MembershipController@failed')->name('membership.failed');
    Route::get('membership/clear-cache/', 'MembershipController@clearValidationCache')->name('membership.clear_validation_cache');
});

/**
 * Select2
 */

Route::get('/select-warehouses', 'Admin\ItemController@getWarehouse')->name('select.warehouses');
Route::get('/select-groups', 'Admin\GroupController@getGroups')->name('select.groups');
Route::get('/select-machinery_types', 'Admin\MachineryTypeController@getMachineryTypes')->name('select.machinery_types');

/**
 * Datatables
 */

// MASTER DATA
Route::get('/datatables-users', 'Admin\UserController@anyData')->name('datatables.users');
Route::get('/datatables-employees', 'Admin\EmployeeController@getIndex')->name('datatables.employees');
Route::get('/datatables-items', 'Admin\ItemController@getIndex')->name('datatables.items');
Route::get('/datatables-groups', 'Admin\GroupController@anyData')->name('datatables.groups');
Route::get('/datatables-machinery_types', 'Admin\MachineryTypeController@anyData')->name('datatables.machinery_types');
Route::get('/datatables-machineries', 'Admin\MachineryController@getIndex')->name('datatables.machineries');
Route::get('/datatables-departments', 'Admin\DepartmentController@anyData')->name('datatables.departments');
Route::get('/datatables-documents', 'Admin\DocumentController@anyData')->name('datatables.documents');
Route::get('/datatables-payment_methods', 'Admin\PaymentMethodController@anyData')->name('datatables.payment_methods');
Route::get('/datatables-uoms', 'Admin\UOMController@anyData')->name('datatables.uoms');
Route::get('/datatables-warehouses', 'Admin\WarehouseController@anyData')->name('datatables.warehouses');
Route::get('/datatables-roles', 'Admin\RoleController@getIndex')->name('datatables.roles');
Route::get('/datatables-permission-documents', 'Admin\PermissionDocumentController@getIndex')->name('datatables.permission_documents');
Route::get('/datatables-permission-menus', 'Admin\PermissionMenuController@getIndex')->name('datatables.permission_menus');
Route::get('/datatables-approval-rules', 'Admin\ApprovalRuleController@getIndex')->name('datatables.approval_rules');
Route::get('/datatables-sites', 'Admin\SiteController@getIndex')->name('datatables.sites');
Route::get('/datatables-suppliers', 'Admin\SupplierController@getIndex')->name('datatables.suppliers');

// PURCHASING

// AUTHORIZATION

// STOCK