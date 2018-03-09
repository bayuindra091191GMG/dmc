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
    //Route::get('users/{user}', 'UserController@show')->name('users.show');
    Route::get('users/tambah', 'UserController@create')->name('users.create');
    Route::post('users/simpan', 'UserController@store')->name('users.store');
    Route::get('users/{user}/ubah', 'UserController@edit')->name('users.edit');
    Route::put('users/ubah/{user}', 'UserController@update')->name('users.update');
    Route::delete('users/hapus/{user}', 'UserController@destroy')->name('users.destroy');
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
    Route::post('suppliers/hapus', 'SupplierController@destroy')->name('suppliers.destroy');

    //Employees
    Route::get('employees', 'EmployeeController@index')->name('employees');
    Route::get('employees/tambah', 'EmployeeController@create')->name('employees.create');
    Route::post('employees/simpan', 'EmployeeController@store')->name('employees.store');
    Route::get('employees/{employee}/ubah/', 'EmployeeController@edit')->name('employees.edit');
    Route::put('employees/ubah/{employee}', 'EmployeeController@update')->name('employees.update');

    //Items
    Route::get('items', 'ItemController@index')->name('items');
    Route::get('items/detil/{item}', 'ItemController@show')->name('items.show');
    Route::get('items/tambah', 'ItemController@create')->name('items.create');
    Route::post('items/simpan', 'ItemController@store')->name('items.store');
    Route::get('items/{item}/ubah', 'ItemController@edit')->name('items.edit');
    Route::put('items/ubah/{item}', 'ItemController@update')->name('items.update');
    Route::post('items/hapus', 'ItemController@destroy')->name('items.destroy');

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
    Route::post('groups/hapus', 'GroupController@destroy')->name('groups.destroy');

    //Machinery Types
    Route::get('machinery_types', 'MachineryTypeController@index')->name('machinery_types');
    Route::get('machinery_types/{machinery_type}/ubah', 'MachineryTypeController@edit')->name('machinery_types.edit');
    Route::put('machinery_types/ubah/{machinery_type}', 'MachineryTypeController@update')->name('machinery_types.update');
    Route::get('machinery_types/tambah', 'MachineryTypeController@create')->name('machinery_types.create');
    Route::post('machinery_types/simpan', 'MachineryTypeController@store')->name('machinery_types.store');

    //Machinery Categories
    Route::get('machinery_categories', 'MachineryCategoryController@index')->name('machinery_categories');
    Route::get('machinery_categories/{machinery_category}/ubah', 'MachineryCategoryController@edit')->name('machinery_categories.edit');
    Route::put('machinery_categories/ubah/{machinery_category}', 'MachineryCategoryController@update')->name('machinery_categories.update');
    Route::get('machinery_categories/tambah', 'MachineryCategoryController@create')->name('machinery_categories.create');
    Route::post('machinery_categories/simpan', 'MachineryCategoryController@store')->name('machinery_categories.store');

    //Machinery Brands
    Route::get('machinery_brands', 'MachineryBrandController@index')->name('machinery_brands');
    Route::get('machinery_brands/{machinery_brand}/ubah', 'MachineryBrandController@edit')->name('machinery_brands.edit');
    Route::put('machinery_brands/ubah/{machinery_brand}', 'MachineryBrandController@update')->name('machinery_brands.update');
    Route::get('machinery_brands/tambah', 'MachineryBrandController@create')->name('machinery_brands.create');
    Route::post('machinery_brands/simpan', 'MachineryBrandController@store')->name('machinery_brands.store');

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
    Route::post('departments/hapus', 'DepartmentController@destroy')->name('departments.destroy');

    //Documents
    Route::get('documents', 'DocumentController@index')->name('documents');
    Route::get('documents/{document}/ubah', 'DocumentController@edit')->name('documents.edit');
    Route::put('documents/ubah/{document}', 'DocumentController@update')->name('documents.update');
    Route::get('documents/tambah', 'DocumentController@create')->name('documents.create');
    Route::post('documents/simpan', 'DocumentController@store')->name('documents.store');
    Route::post('documents/hapus', 'DocumentController@destroy')->name('documents.destroy');

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

    //Purchase Request Headers
    Route::get('purchase_requests', 'Purchasing\PurchaseRequestHeaderController@index')->name('purchase_requests');
    Route::get('purchase_requests/detil/{purchase_request}', 'Purchasing\PurchaseRequestHeaderController@show')->name('purchase_requests.show');
    Route::get('purchase_requests/{purchase_request}/ubah', 'Purchasing\PurchaseRequestHeaderController@edit')->name('purchase_requests.edit');
    Route::put('purchase_requests/ubah/{purchase_request}', 'Purchasing\PurchaseRequestHeaderController@update')->name('purchase_requests.update');
    Route::get('purchase_requests/tambah', 'Purchasing\PurchaseRequestHeaderController@create')->name('purchase_requests.create');
    Route::post('purchase_requests/simpan', 'Purchasing\PurchaseRequestHeaderController@store')->name('purchase_requests.store');

    //Purchase Request Details
    Route::get('purchase_request_details', 'Purchasing\PurchaseRequestDetailController@index')->name('purchase_request_details');
    Route::post('purchase_request_details/simpan', 'Purchasing\PurchaseRequestDetailController@store')->name('purchase_request_details.store');
    Route::put('purchase_request_details/ubah', 'Purchasing\PurchaseRequestDetailController@update')->name('purchase_request_details.update');
    Route::post('purchase_request_details/hapus', 'Purchasing\PurchaseRequestDetailController@delete')->name('purchase_request_details.delete');

    //Purchase Order Headers
    Route::get('purchase_orders', 'Purchasing\PurchaseOrderHeaderController@index')->name('purchase_orders');
    Route::get('purchase_orders/detil/{purchase_order}', 'Purchasing\PurchaseOrderHeaderController@show')->name('purchase_orders.show');
    Route::get('purchase_orders/{purchase_order}/ubah', 'Purchasing\PurchaseOrderHeaderController@edit')->name('purchase_orders.edit');
    Route::put('purchase_orders/ubah/{purchase_order}', 'Purchasing\PurchaseOrderHeaderController@update')->name('purchase_orders.update');
    Route::get('purchase_orders/tambah', 'Purchasing\PurchaseOrderHeaderController@create')->name('purchase_orders.create');
    Route::post('purchase_orders/simpan', 'Purchasing\PurchaseOrderHeaderController@store')->name('purchase_orders.store');

    //Purchase Order Details
    Route::post('purchase_order_details/simpan', 'Purchasing\PurchaseOrderDetailController@store')->name('purchase_order_details.store');
    Route::put('purchase_order_details/ubah', 'Purchasing\PurchaseOrderDetailController@update')->name('purchase_order_details.update');
    Route::post('purchase_order_details/hapus', 'Purchasing\PurchaseOrderDetailController@delete')->name('purchase_order_details.delete');

    //Quotation Headers
    Route::get('quotations', 'Purchasing\QuotationHeaderController@index')->name('quotations');
    Route::get('quotations/detil/{quotation}', 'Purchasing\QuotationHeaderController@show')->name('quotations.show');
    Route::get('quotations/{quotation}/ubah', 'Purchasing\QuotationHeaderController@edit')->name('quotations.edit');
    Route::put('quotations/ubah/{quotation}', 'Purchasing\QuotationHeaderController@update')->name('quotations.update');
    Route::get('quotations/tambah', 'Purchasing\QuotationHeaderController@create')->name('quotations.create');
    Route::post('quotations/simpan', 'Purchasing\QuotationHeaderController@store')->name('quotations.store');

    //Quotation Details
    Route::post('quotation_details/simpan', 'Purchasing\QuotationDetailController@store')->name('quotation_details.store');
    Route::put('quotation_details/ubah', 'Purchasing\QuotationDetailController@update')->name('quotation_details.update');
    Route::post('quotation_details/hapus', 'Purchasing\QuotationDetailController@delete')->name('quotation_details.delete');

    //Delivery Order Headers
    Route::get('delivery_orders', 'Inventory\DeliveryOrderHeaderController@index')->name('delivery_orders');
    Route::get('delivery_orders/detil/{delivery_order}', 'Inventory\DeliveryOrderHeaderController@show')->name('delivery_orders.show');
    Route::get('delivery_orders/{delivery_order}/ubah', 'Inventory\DeliveryOrderHeaderController@edit')->name('delivery_orders.edit');
    Route::put('delivery_orders/ubah/{delivery_order}', 'Inventory\DeliveryOrderHeaderController@update')->name('delivery_orders.update');
    Route::get('delivery_orders/tambah', 'Inventory\DeliveryOrderHeaderController@create')->name('delivery_orders.create');
    Route::post('delivery_orders/simpan', 'Inventory\DeliveryOrderHeaderController@store')->name('delivery_orders.store');

    //Delivery Order Details
    Route::post('delivery_order_details/simpan', 'Inventory\DeliveryOrderDetailController@store')->name('delivery_order_details.store');
    Route::put('delivery_order_details/ubah', 'Inventory\DeliveryOrderDetailController@update')->name('delivery_order_details.update');
    Route::post('delivery_order_details/hapus', 'Inventory\DeliveryOrderDetailController@delete')->name('delivery_order_details.delete');

    //Issued Docket Headers
    Route::get('issued_dockets', 'Inventory\DocketController@index')->name('issued_dockets');
    Route::get('issued_dockets/detil/{issued_docket}', 'Inventory\DocketController@show')->name('issued_dockets.show');
    Route::get('issued_dockets/{issued_docket}/ubah', 'Inventory\DocketController@edit')->name('issued_dockets.edit');
    Route::put('issued_dockets/ubah/{issued_docket}', 'Inventory\DocketController@update')->name('issued_dockets.update');
    Route::get('issued_dockets/tambah', 'Inventory\DocketController@create')->name('issued_dockets.create');
    Route::post('issued_dockets/simpan', 'Inventory\DocketController@store')->name('issued_dockets.store');
    Route::get('issued_dockets/print/{issued_docket}', 'Inventory\DocketController@printDocument')->name('issued_dockets.print');
    Route::get('issued_dockets/download/{issued_docket}', 'Inventory\DocketController@download')->name('issued_dockets.download');
    Route::get('issued_dockets/report', 'Inventory\DocketController@report')->name('issued_dockets.report');
    Route::post('issued_dockets/download_report', 'Inventory\DocketController@downloadReport')->name('issued_dockets.download-report');

    //Issued Docket Details
    Route::get('issued_docket_details', 'Inventory\DocketDetailController@index')->name('issued_docket_details');
    Route::post('issued_docket_details/simpan', 'Inventory\DocketDetailController@store')->name('issued_docket_details.store');
    Route::put('issued_docket_details/ubah', 'Inventory\DocketDetailController@update')->name('issued_docket_details.update');
    Route::post('issued_docket_details/hapus', 'Inventory\DocketDetailController@delete')->name('issued_docket_details.delete');

    //Item Receipts Headers
    Route::get('item_receipts', 'Inventory\ItemReceiptController@index')->name('item_receipts');
    Route::get('item_receipts/detil/{item_receipt}', 'Inventory\ItemReceiptController@show')->name('item_receipts.show');
    Route::get('item_receipts/{item_receipt}/ubah', 'Inventory\ItemReceiptController@edit')->name('item_receipts.edit');
    Route::put('item_receipts/ubah/{item_receipt}', 'Inventory\ItemReceiptController@update')->name('item_receipts.update');
    Route::get('item_receipts/tambah', 'Inventory\ItemReceiptController@create')->name('item_receipts.create');
    Route::post('item_receipts/simpan', 'Inventory\ItemReceiptController@store')->name('item_receipts.store');
    Route::get('item_receipts/print/{item_receipt}', 'Inventory\ItemReceiptController@printDocument')->name('item_receipts.print');

    //Item Receipts Details
    Route::get('item_receipt_details', 'Inventory\ItemReceiptDetailController@index')->name('item_receipt_details');
    Route::post('item_receipt_details/simpan', 'Inventory\ItemReceiptDetailController@store')->name('item_receipt_details.store');
    Route::put('item_receipt_details/ubah', 'Inventory\ItemReceiptDetailController@update')->name('item_receipt_details.update');
    Route::post('item_receipt_details/hapus', 'Inventory\ItemReceiptDetailController@delete')->name('item_receipt_details.delete');

    //Stock Adjustment
    Route::get('stock_adjustments', 'Inventory\StockAdjustmentController@index')->name('stock_adjustments');
    Route::get('stock_adjustments/tambah', 'Inventory\StockAdjustmentController@create')->name('stock_adjustments.create');
    Route::post('stock_adjustments/simpan', 'Inventory\StockAdjustmentController@store')->name('stock_adjustments.store');

    //Stock In
    Route::get('stock_ins', 'Inventory\StockInController@index')->name('stock_ins');
    Route::get('stock_ins/tambah', 'Inventory\StockInController@create')->name('stock_ins.create');
    Route::post('stock_ins/simpan', 'Inventory\StockInController@store')->name('stock_ins.store');

    //Stock Item Mutation
    Route::get('item_mutations', 'Inventory\ItemMutationController@index')->name('item_mutations');
    Route::get('item_mutations/tambah', 'Inventory\ItemMutationController@create')->name('item_mutations.create');
    Route::post('item_mutations/simpan', 'Inventory\ItemMutationController@store')->name('item_mutations.store');

    //Interchanges
    Route::get('interchanges', 'Inventory\InterchangeController@index')->name('interchanges');
    Route::get('interchanges/tambah', 'Inventory\InterchangeController@create')->name('interchanges.create');
    Route::post('interchanges/simpan', 'Inventory\InterchangeController@store')->name('interchanges.store');
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

Route::get('/select-employees', 'Admin\EmployeeController@getEmployees')->name('select.employees');
Route::get('/select-items', 'Admin\ItemController@getItems')->name('select.items');
Route::get('/select-extended_items', 'Admin\ItemController@getExtendedItems')->name('select.extended_items');
Route::get('/select-warehouses', 'Admin\ItemController@getWarehouses')->name('select.warehouses');
Route::get('/select-groups', 'Admin\GroupController@getGroups')->name('select.groups');
Route::get('/select-machineries', 'Admin\MachineryController@getMachineries')->name('select.machineries');
Route::get('/select-machinery_types', 'Admin\MachineryTypeController@getMachineryTypes')->name('select.machinery_types');
Route::get('/select-suppliers', 'Admin\SupplierController@getSuppliers')->name('select.suppliers');
Route::get('/select-purchase_requests', 'Admin\Purchasing\PurchaseRequestHeaderController@getPurchaseRequests')->name('select.purchase_requests');
Route::get('/select-purchase_orders', 'Admin\Purchasing\PurchaseOrderHeaderController@getPurchaseOrders')->name('select.purchase_orders');
Route::get('/select-delivery_orders', 'Admin\Inventory\DeliveryOrderController@getDeliveryOrders')->name('select.delivery_orders');

/**
 * Datatables
 */

// MASTER DATA
Route::get('/datatables-users', 'Admin\UserController@getIndex')->name('datatables.users');
Route::get('/datatables-employees', 'Admin\EmployeeController@getIndex')->name('datatables.employees');
Route::get('/datatables-items', 'Admin\ItemController@getIndex')->name('datatables.items');
Route::get('/datatables-groups', 'Admin\GroupController@anyData')->name('datatables.groups');
Route::get('/datatables-machineries', 'Admin\MachineryController@getIndex')->name('datatables.machineries');
Route::get('/datatables-machinery_types', 'Admin\MachineryTypeController@getIndex')->name('datatables.machinery_types');
Route::get('/datatables-machinery_categories', 'Admin\MachineryCategoryController@getIndex')->name('datatables.machinery_categories');
Route::get('/datatables-machinery_brands', 'Admin\MachineryBrandController@getIndex')->name('datatables.machinery_brands');
Route::get('/datatables-departments', 'Admin\DepartmentController@getIndex')->name('datatables.departments');
Route::get('/datatables-documents', 'Admin\DocumentController@anyData')->name('datatables.documents');
Route::get('/datatables-payment_methods', 'Admin\PaymentMethodController@anyData')->name('datatables.payment_methods');
Route::get('/datatables-uoms', 'Admin\UOMController@anyData')->name('datatables.uoms');
Route::get('/datatables-warehouses', 'Admin\WarehouseController@anyData')->name('datatables.warehouses');
Route::get('/datatables-sites', 'Admin\SiteController@getIndex')->name('datatables.sites');
Route::get('/datatables-suppliers', 'Admin\SupplierController@getIndex')->name('datatables.suppliers');
Route::get('/datatables-menus', 'Admin\MenuController@anyData')->name('datatables.menus');

// PURCHASING
Route::get('/datatables-purchase_requests', 'Admin\Purchasing\PurchaseRequestHeaderController@getIndex')->name('datatables.purchase_requests');
Route::get('/datatables-purchase_orders', 'Admin\Purchasing\PurchaseOrderHeaderController@getIndex')->name('datatables.purchase_orders');
Route::get('/datatables-quotations', 'Admin\Purchasing\QuotationHeaderController@getIndex')->name('datatables.quotations');

// INVENTORY
Route::get('/datatables-delivery_orders', 'Admin\Inventory\DeliveryOrderHeaderController@getIndex')->name('datatables.delivery_orders');
Route::get('/datatables-issued_dockets', 'Admin\Inventory\DocketController@getIndex')->name('datatables.issued_dockets');
Route::get('/datatables-item_receipts', 'Admin\Inventory\ItemReceiptController@getIndex')->name('datatables.item_receipts');
Route::get('/datatables-stock_adjustments', 'Admin\Inventory\StockAdjustmentController@getIndex')->name('datatables.stock_adjustments');
Route::get('/datatables-stock_ins', 'Admin\Inventory\StockInController@getIndex')->name('datatables.stock_ins');
Route::get('/datatables-item_mutations', 'Admin\Inventory\ItemMutationController@getIndex')->name('datatables.item_mutations');
Route::get('/datatables-interchanges', 'Admin\Inventory\InterchangeController@getIndex')->name('datatables.interchanges');

// AUTHORIZATION
Route::get('/datatables-permission-documents', 'Admin\PermissionDocumentController@getIndex')->name('datatables.permission_documents');
Route::get('/datatables-permission-menus', 'Admin\PermissionMenuController@getIndex')->name('datatables.permission_menus');
Route::get('/datatables-approval-rules', 'Admin\ApprovalRuleController@getIndex')->name('datatables.approval_rules');
Route::get('/datatables-roles', 'Admin\RoleController@getIndex')->name('datatables.roles');

// DOCUMENTS
Route::get('/documents/purchase-request', function (){
   return view('documents.Victor23mega PR Example');
});