<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\Org\ReadingController;

Route::get('/logout', '\App\Http\Controllers\Auth\LoginController@logout')->name('login.logout');

Auth::routes();

Route::get('/', function () {
    return view('welcome');
})->name('welcome');
Route::get('/quienes-somos', function () {
    return view('abouts');
})->name('abouts');
Route::get('/faq', function () {
    return view('faq');
})->name('faq');
Route::get('/precios', function () {
    return view('pricing');
})->name('pricing');
Route::get('/contacto', function () {
    return view('contact');
})->name('contact');

Route::get('/pago-cuenta', function () {
    return view('accounts.rutificador');
})->name('accounts.rutificador');

Route::get('/deudas/{rut}', [App\Http\Controllers\AccountController::class, 'debt'])->name('accounts.debt');

Route::get('/terminos-y-condiciones', function () {
    return view('termsofservice');
})->name('termsofservice');
Route::get('/politicas-de-privacidad', function () {
    return view('privacypolicy');
})->name('privacypolicy');
Route::get('/cuenta/acceso', function () {
    return view('account_login');
})->name('account.login');
Route::get('/cuenta/registro', function () {
    return view('account_register');
})->name('account.register');


Route::post('/checkout', [App\Http\Controllers\CheckoutController::class, 'store'])->name('checkout-save');
Route::PATCH('/checkout/{order_code}',[App\Http\Controllers\CheckoutController::class,'update'])->name('checkout-update');
Route::PATCH('/checkout/{order_code}/editar-entrada',[App\Http\Controllers\CheckoutController::class,'recovery_update'])->name('checkout-update-recovery');
Route::get('/checkout/{order_code}', [App\Http\Controllers\CheckoutController::class, 'show'])->name('checkout-order-code');
Route::get('/resumen-orden/{order_code}', [App\Http\Controllers\OrderController::class,'show'])->name('orders-summary');

Route::get('/payment/{order_code}', [App\Http\Controllers\PaymentController::class, 'create_order_payment'])->name('Payment-order-code');
Route::get('/payment-response/{order_code}', [App\Http\Controllers\PaymentController::class, 'resume_order_payment'])->name('Payment-response');
Route::post('/payment-response/{order_code}', [App\Http\Controllers\PaymentController::class, 'resume_order_payment'])->name('Payment-response2');

//Route::get('/dte/folios/nuevos', [App\Http\Controllers\DteController::class, 'folio_create']);
//Route::get('/dte/{order_code}', [App\Http\Controllers\DteController::class, 'create'])->name('dte-order-code');

Route::post('/cuenta/validar', [App\Http\Controllers\AccountController::class, 'access'])->name('account.access');
Route::get('/dashboard', [App\Http\Controllers\AccountController::class, 'dashboard'])->name('account.dashboard');
Route::get('/cuenta/perfil', [App\Http\Controllers\AccountController::class, 'profile'])->name('account.profile');

Route::get('/orgs/nuevo', [App\Http\Controllers\OrgController::class, 'create'])->name('orgs.create');
Route::get('/orgs', [App\Http\Controllers\OrgController::class, 'index'])->name('orgs.index');

Route::get('/suscripciones/nuevo', [App\Http\Controllers\SubscriptionController::class, 'create'])->name('subscriptions.create');
Route::get('/suscripciones', [App\Http\Controllers\SubscriptionController::class, 'index'])->name('subscriptions.index');

Route::get('/paquetes/nuevo', [App\Http\Controllers\PackageController::class, 'create'])->name('packages.create');
Route::get('/paquetes', [App\Http\Controllers\PackageController::class, 'index'])->name('packages.index');

// Organizationes routes
Route::prefix('org')->name('orgs.')->group(function () {

    Route::post('{id}/up_cargo_fijo', [App\Http\Controllers\OrgController::class, 'fixed_charge_store'])->name('fixed_charge_store');

    Route::get('{id}/dashboard', [App\Http\Controllers\OrgController::class, 'dashboard'])->name('dashboard');

    Route::get('{id}/socios', [App\Http\Controllers\Org\MemberController::class, 'index'])->name('members.index');
    Route::get('{id}/socios/nuevo', [App\Http\Controllers\Org\MemberController::class, 'create'])->name('members.create');

    Route::post('{id}/socios', [App\Http\Controllers\Org\MemberController::class, 'store'])->name('members.store');

    Route::get('{id}/socios/{member}/editar', [App\Http\Controllers\Org\MemberController::class, 'edit'])->name('members.edit');
    Route::put('{id}/socios/{memberId}', [App\Http\Controllers\Org\MemberController::class, 'update'])->name('members.update');
    Route::put('{id}/member/{memberId}/services/{serviceId}/transfer', [App\Http\Controllers\Org\MemberController::class, 'transferService'])->name('members.services.transfer');
    // Mostrar el formulario de edición
    Route::get('{id}/member/{memberId}/services/{serviceId}/edit', [App\Http\Controllers\Org\MemberController::class, 'editService'])->name('members.services.edit');
    // Procesar el formulario (actualizar)
    Route::put('{id}/member/{memberId}/services/{serviceId}', [App\Http\Controllers\Org\MemberController::class, 'updateService'])->name('members.services.update');
    Route::put('{id}/member/{memberId}/services/{serviceId}/toggle', [App\Http\Controllers\Org\MemberController::class, 'toggleStatus'])->name('members.services.toggleStatus');
    //Export Excel
    Route::get('{id}/member/export', [App\Http\Controllers\Org\MemberController::class, 'export'])->name('members.export');


    Route::get('{id}/lecturas', [App\Http\Controllers\Org\ReadingController::class, 'index'])->name('readings.index');
    Route::get('{id}/lecturas/nuevo', [App\Http\Controllers\Org\ReadingController::class, 'create'])->name('readings.create');
    Route::get('{id}/lecturas/historial', [App\Http\Controllers\Org\ReadingController::class, 'history'])->name('readings.history');
    Route::post('{id}/lecturas/update', [App\Http\Controllers\Org\ReadingController::class, 'update'])->name('readings.update');// Ruta para guardar la lectura
    // Ruta para DTE (Boleta o Factura)
    Route::get('{id}/lecturas/{readingId}/dte', [App\Http\Controllers\Org\ReadingController::class, 'dte'])->name('readings.dte');
    // Agregar estas dos rutas específicas para boleta y factura
    Route::get('{id}/lecturas/{readingId}/dte/boleta', [App\Http\Controllers\Org\ReadingController::class, 'dte'])->name('readings.boleta');
    Route::get('{id}/lecturas/{readingId}/dte/factura', [App\Http\Controllers\Org\ReadingController::class, 'dte'])->name('readings.factura');
    //Export Excel
    Route::get('{id}/lecturas/export', [App\Http\Controllers\Org\ReadingController::class, 'export'])->name('readings.export');
    Route::post('{id}/readings/update-current', [App\Http\Controllers\Org\ReadingController::class, 'current_reading_update'])->name('readings.current_reading_update');
    Route::get('{id}/lecturas/history/export', [App\Http\Controllers\Org\ReadingController::class, 'exportHistory'])->name('readings.history.export');

    Route::get('{id}/folios', [App\Http\Controllers\Org\FolioController::class, 'index'])->name('folios.index');
    Route::get('{id}/folios/nuevo', [App\Http\Controllers\Org\FolioController::class, 'create'])->name('folios.create');
    Route::post('{id}/folios/store', [App\Http\Controllers\Org\FolioController::class, 'store'])->name('folios.store');

    Route::get('{id}/tramos', [App\Http\Controllers\Org\SectionController::class, 'index'])->name('sections.index');
    Route::get('{id}/tramos/nuevo', [App\Http\Controllers\Org\SectionController::class, 'create'])->name('sections.create');
    Route::get('{id}/tramos/{tramoId}/editar', [App\Http\Controllers\Org\SectionController::class, 'edit'])->name('sections.edit');
    Route::put('{id}/tramos/{tramoId}', [App\Http\Controllers\Org\SectionController::class, 'update'])->name('sections.update');
   Route::delete('{id}/tramos/{tramoId}', [App\Http\Controllers\Org\SectionController::class, 'destroy'])->name('sections.destroy');
Route::post('{id}/tramos/store-tier', [App\Http\Controllers\Org\SectionController::class, 'storeTier'])->name('sections.storeTier'); // para tramos (tier_config)
Route::post('{id}/tramos/store-fixed-cost', [App\Http\Controllers\Org\SectionController::class, 'storeFixedCost'])->name('sections.storeFixedCost'); // para fixed cost config

    //Export Excel
    Route::get('{id}/tramos/export', [App\Http\Controllers\Org\SectionController::class, 'export'])->name('sections.export');

    Route::get('{id}/servicios', [App\Http\Controllers\Org\ServiceController::class, 'index'])->name('services.index');
    Route::get('{id}/servicios/nuevo', [App\Http\Controllers\Org\ServiceController::class, 'create'])->name('services.create');
    Route::get('{id}/member/{memberId}/services/create', [App\Http\Controllers\Org\ServiceController::class, 'createForMember'])->name('services.createForMember');
    Route::post('{id}/member/{memberId}/services', [App\Http\Controllers\Org\ServiceController::class, 'storeForMember'])->name('services.store');
    //Export Excel
    Route::get('{id}/servicios/export', [App\Http\Controllers\Org\ServiceController::class, 'export'])->name('services.export');


    Route::get('{id}/orders', [App\Http\Controllers\Org\OrdersController::class, 'index'])->name('orders.index');
    Route::get('{id}/orders/{order_code}', [App\Http\Controllers\Org\OrdersController::class, 'show'])->name('orders.show');
    Route::post('{id}/orders', [App\Http\Controllers\Org\OrdersController::class, 'store'])->name('orders.store');


    Route::get('{id}/voucher/{order_code}', [App\Http\Controllers\Org\DteController::class, 'create'])->name('voucher.create');
    Route::get('{id}/dte/{reading_id}', [App\Http\Controllers\Org\DteController::class, 'dte_create'])->name('dte.create');
    Route::get('{id}/dte-timbre/{reading_id}', [App\Http\Controllers\Org\DteController::class, 'dte_bell'])->name('dte.bell');

    Route::get('{id}/historialDTE', [App\Http\Controllers\Org\DteController::class, 'historyDTE'])->name('historyDTE');
    Route::get('{id}/historialDTE/export', [App\Http\Controllers\Org\DteController::class, 'exportDTE'])->name('exportDTE');
    Route::get('{id}/print-dtes', [App\Http\Controllers\Org\DteController::class, 'printAllDTE'])->name('printAllDTE');
     Route::get('{id}/historialDTE/{reading_id}/dte', [App\Http\Controllers\Org\ReadingController::class, 'multiBoletaPrint'])->name('multiBoletaPrint');

    Route::get('{id}/sectores', [App\Http\Controllers\Org\LocationController::class, 'index'])->name('locations.index');
    Route::get('{id}/sectores/nuevo', [App\Http\Controllers\Org\LocationController::class, 'create'])->name('locations.create');

    Route::post('{id}/sectores', [App\Http\Controllers\Org\LocationController::class, 'store'])->name('locations.store');
    Route::get('{id}/sectores/{locationId}/editar', [App\Http\Controllers\Org\LocationController::class, 'edit'])->name('locations.edit');
    Route::put('{id}/sectores/{locationId}', [App\Http\Controllers\Org\LocationController::class, 'update'])->name('locations.update');
    //Route::delete('{id}/sectores/{locationId}', [App\Http\Controllers\Org\LocationController::class, 'destroy'])->name('locations.destroy');
    //Export Excel
    Route::get('{id}/sectores/export', [App\Http\Controllers\Org\LocationController::class, 'export'])->name('locations.export');

    Route::get('{id}/documentos', [App\Http\Controllers\Org\DocumentController::class, 'index'])->name('documents.index');
    Route::get('{id}/documentos/nuevo', [App\Http\Controllers\Org\DocumentController::class, 'create'])->name('documents.create');

    Route::get('{id}/inventarios', [App\Http\Controllers\Org\InventaryController::class, 'index'])->name('inventories.index');
    Route::get('{id}/inventarios/nuevo', [App\Http\Controllers\Org\InventaryController::class, 'create'])->name('inventories.create');
    Route::post('{id}/inventarios', [App\Http\Controllers\Org\InventaryController::class, 'store'])->name('inventories.store');
    Route::get('{id}/inventarios/{inventoryId}/editar', [App\Http\Controllers\Org\InventaryController::class, 'edit'])->name('inventories.edit');
    Route::put('{id}/inventarios/{inventoryId}', [App\Http\Controllers\Org\InventaryController::class, 'update'])->name('inventories.update');
    //Export Excel
    Route::get('{id}/inventarios/export', [App\Http\Controllers\Org\InventaryController::class, 'export'])->name('inventories.export');

    Route::get('{id}/inventarios/categories', [App\Http\Controllers\Org\InventoryCategoryController::class, 'index'])->name('categories.index');
    Route::get('{id}/inventarios/categories/create', [App\Http\Controllers\Org\InventoryCategoryController::class, 'create'])->name('categories.create');
    Route::post('{id}/inventarios/categories', [App\Http\Controllers\Org\InventoryCategoryController::class, 'store'])->name('categories.store');
    Route::get('{id}/inventarios/categories/{categoryId}/editar', [App\Http\Controllers\Org\InventoryCategoryController::class, 'edit'])->name('categories.edit');
    Route::put('{id}/inventarios/categories/{categoryId}', [App\Http\Controllers\Org\InventoryCategoryController::class, 'update'])->name('categories.update');
    Route::delete('{id}/inventarios/categories/{categoryId}', [App\Http\Controllers\Org\InventoryCategoryController::class, 'destroy'])->name('categories.destroy');
    //Export Excel
    Route::get('{id}/inventarios/categories/export', [App\Http\Controllers\Org\InventoryCategoryController::class, 'export'])->name('categories.export');

    Route::get('{id}/pagos', [App\Http\Controllers\Org\PaymentController::class, 'index'])->name('payments.index');
    Route::get('{id}/pagos/historial', [App\Http\Controllers\Org\PaymentController::class, 'history'])->name('payments.history');
    Route::get('{id}/pagos/servicios/{rut}', [App\Http\Controllers\Org\PaymentController::class, 'showServices'])->name('payments.services');

    Route::get('{id}/pagos/nuevo', [App\Http\Controllers\Org\PaymentController::class, 'create'])->name('payments.create');
    Route::post('{id}/pagos/nuevo', [App\Http\Controllers\Org\PaymentController::class, 'store'])->name('orgs.payments.store');
    //Export Excel
    Route::get('{id}/pagos/export', [App\Http\Controllers\Org\PaymentController::class, 'export'])->name('payments.export');
    Route::get('{id}/pagos/history/export', [App\Http\Controllers\Org\PaymentController::class, 'exportHistory'])->name('payments.history.export');

    Route::get('{id}/notificaciones', [App\Http\Controllers\Org\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('{id}/notificaciones', [App\Http\Controllers\Org\NotificationController::class, 'store'])->name('notifications.store');

    Route::get('{id}/historyfolio/export', [App\Http\Controllers\Org\FolioController::class, 'export'])->name('historyfolio.export');

});

// Billings routes
Route::prefix('facturaciones')->name('billings.')->group(function () {

    Route::get('folios', [App\Http\Controllers\Billing\FolioController::class, 'index'])->name('folios.index');
    Route::get('folios/nuevo', [App\Http\Controllers\Billing\FolioController::class, 'create'])->name('folios.create');
    Route::post('folios/store', [App\Http\Controllers\Billing\FolioController::class, 'store'])->name('folios.store');
});

// Payments routes

// Kron routes
Route::prefix('kron')->name('kron.')->group(function () {

    Route::get('readings_charge_store', [App\Http\Controllers\KronController::class, 'readings_charge_store']);
});

// Ajax routes
Route::prefix('ajax')->name('ajax.')->group(function () {

    Route::get('regiones', [App\Http\Controllers\AjaxController::class, 'state']);
    Route::get('{id}/comunas', [App\Http\Controllers\AjaxController::class, 'cities']);

    // Nueva ruta para comunas por región (sin provincia)
    Route::get('{stateId}/comunas-por-region', [App\Http\Controllers\AjaxController::class, 'citiesByState']);

     // Nueva ruta para verificar RUT
    Route::post('check-rut', [App\Http\Controllers\AjaxController::class, 'checkRut']);
});

Route::get('pos-integrado', function () {
    return view('pos-integrate');
});

