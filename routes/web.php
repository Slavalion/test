<?php

use App\Http\Controllers\ActionCartController;
use App\Http\Controllers\ActionSearchController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\LivecargoController;
use App\Http\Controllers\Admin\PickUpZoneController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\BalanceController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\DemoController;
use App\Http\Controllers\LikerController;
use App\Http\Controllers\PickUpController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicDeiveriesController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\ReviewComplaintController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ReviewReactionController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\WalletsController;
use App\Http\Controllers\WalletTransactionController;
use App\Http\Controllers\TariffController;
use App\Http\Middleware\MaintenanceModeMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

Route::redirect('/', '/login');

Route::middleware('auth')->group(function () {
    Route::redirect('/dashboard', '/purchase/list');

    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');

        Route::post('/notifications', [ProfileController::class, 'setNotifications'])->name('profile.notifications.update');

        Route::post('/tg-payment', [ProfileController::class, 'updateTgPaymentData'])->name('profile.tg-payment.update');
        Route::post('/generate-api-key', [ProfileController::class, 'generateApiKey'])->name('profile.generate-api-key');
        Route::post('/generate-ref-code', [ProfileController::class, 'generateRefCode'])->name('profile.generate-ref-code');
    });

    Route::post('search', [SearchController::class, 'send'])->middleware(MaintenanceModeMiddleware::class)->name('search.do');
    Route::post('pickpoints', [SearchController::class, 'pickpoints'])->name('pickpoints');

    Route::get('liker', LikerController::class)->name('liker');
    Route::post('liker', [LikerController::class, 'do'])->middleware(MaintenanceModeMiddleware::class)->name('liker.do');

    Route::prefix('purchase')->group(function () {
        Route::post('/', [PurchaseController::class, 'add'])->middleware(MaintenanceModeMiddleware::class)->name('purchase.add');
        Route::get('list', [PurchaseController::class, 'list'])->name('purchase.list');
        Route::post('destroy', [PurchaseController::class, 'destroy'])->name('purchases.destroy');

        Route::post('generate', [SearchController::class, 'generate'])->middleware(MaintenanceModeMiddleware::class)->name('purchase.generate');
        Route::post('import', [SearchController::class, 'importExcel'])->middleware(MaintenanceModeMiddleware::class)->name('purchase.import');

        Route::get('download', [PurchaseController::class, 'download'])->name('purchases.download');
    });

    Route::get('question', [QuestionController::class, 'list'])->name('question');
    Route::post('question', [QuestionController::class, 'add'])->middleware(MaintenanceModeMiddleware::class)->name('question.add');

    Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews');
    Route::post('/reviews', [ReviewController::class, 'create'])->middleware(MaintenanceModeMiddleware::class)->name('reviews.create');

    Route::prefix('review-complaints')->group(function () {
        Route::get('/', [ReviewComplaintController::class, 'index'])->name('review-complaints');
        Route::post('/search', [ReviewComplaintController::class, 'search'])->middleware(MaintenanceModeMiddleware::class)->name('review-complaints.search');
        Route::post('/store', [ReviewComplaintController::class, 'store'])->middleware(MaintenanceModeMiddleware::class)->name('review-complaints.store');
    });

    Route::prefix('review-reactions')->group(function () {
        Route::get('/', [ReviewReactionController::class, 'index'])->name('review-reactions');
        Route::post('/search', [ReviewReactionController::class, 'search'])->middleware(MaintenanceModeMiddleware::class)->name('review-reactions.search');
        Route::post('/store', [ReviewReactionController::class, 'store'])->middleware(MaintenanceModeMiddleware::class)->name('review-reactions.store');
    });

    Route::prefix('wallets')->group(function () {
        Route::get('/', [WalletsController::class, 'index'])->name('wallets');
        Route::get('/{wallet}', [WalletsController::class, 'show'])->name('wallets.show');
        Route::post('connect', [WalletsController::class, 'connect'])->middleware(MaintenanceModeMiddleware::class)->name('wallets.connect');
        Route::post('update-balance', [WalletsController::class, 'updateBalance'])->middleware(MaintenanceModeMiddleware::class)->name('wallets.update-balance');
        Route::post('confirm', [WalletsController::class, 'confirm'])->middleware(MaintenanceModeMiddleware::class)->name('wallets.confirm');
        Route::post('delete', [WalletsController::class, 'delete'])->middleware(MaintenanceModeMiddleware::class)->name('wallets.delete');
    });

    Route::get('actions/add-to-cart', [ActionCartController::class, 'index'])->name('actions.add-to-cart');
    Route::post('actions/add-to-cart/store', [ActionCartController::class, 'store'])->middleware(MaintenanceModeMiddleware::class)->name('actions.add-to-cart.store');

    Route::get('actions/search', [ActionSearchController::class, 'index'])->name('actions.search');
    Route::post('actions/search/store', [ActionSearchController::class, 'store'])->middleware(MaintenanceModeMiddleware::class)->name('actions.search.store');

    Route::prefix('transactions')->group(function () {
        Route::get('/', [WalletTransactionController::class, 'index'])->name('transactions');
        Route::get('download', [WalletTransactionController::class, 'download'])->name('transactions.download');
    });

    Route::post('balance', [BalanceController::class, 'fill'])->name('balance.fill');

    Route::get('deliveries', [DeliveryController::class, 'index'])->name('delivery.index');
    Route::get('deliveries/download/xls', [DeliveryController::class, 'download'])->name('delivery.download');
    Route::post('deliveries/update-data', [DeliveryController::class, 'updateData'])->middleware(MaintenanceModeMiddleware::class)->name('deliveries.update-data');

    Route::get('pick-ups', [PickUpController::class, 'index'])->name('pick-ups.index');
    Route::post('pick-ups/download/xls', [PickUpController::class, 'import'])->name('pick-ups.import');

    Route::get('faq', [App\Http\Controllers\FaqController::class, 'index'])->name('faq.index');

    Route::prefix('admin')->middleware('can:admin-access')->group(function () {
        Route::get('dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard');

        Route::get('statistic', [App\Http\Controllers\Admin\StatisticController::class, 'index'])->name('admin.statistic');

        Route::get('message-sender', [App\Http\Controllers\Admin\MessageSenderController::class, 'index'])->name('admin.message-sender');
        Route::post('message-sender/send', [App\Http\Controllers\Admin\MessageSenderController::class, 'send'])->name('admin.message-sender.send');

        Route::get('settings', [SettingController::class, 'index'])->name('admin.settings');
        Route::post('settings/set', [SettingController::class, 'set'])->name('admin.settings.set');
        Route::post('settings/set-prices', [SettingController::class, 'setPrices'])->name('admin.settings.set-prices');

        Route::get('users', [UsersController::class, 'index'])->name('admin.users');
        Route::get('users/{user}', [UsersController::class, 'show'])->name('admin.users.show');
        Route::post('users/{user}/top-up-balance', [UsersController::class, 'topUpBalance'])->name('admin.users.top-up-balance');
        Route::post('users/login-as/{user}', [UsersController::class, 'loginAs'])->name('admin.users.loginas');
        Route::post('users/set-preferences', [UsersController::class, 'setPreferences'])->name('admin.users.set-preferences');
        Route::post('users/set-prices', [UsersController::class, 'setPrices'])->name('admin.users.set-prices');
        // Route::get('users/switch-logout', [UsersController::class, 'switchLogout'])->name('users.switch-logout');

        Route::prefix('faqs')->group(function () {
            Route::get('/', [FaqController::class, 'index'])->name('admin.faqs.index');
            Route::get('/create', [FaqController::class, 'create'])->name('admin.faqs.create');
            Route::post('/create', [FaqController::class, 'store'])->name('admin.faqs.store');
            Route::get('/{faq}', [FaqController::class, 'edit'])->name('admin.faqs.edit');
            Route::post('/{faq}', [FaqController::class, 'update'])->name('admin.faqs.update');
        });

        Route::get('livecargo', [LivecargoController::class, 'index'])->name('admin.livecargo');

        Route::get('pickup-zones', [PickUpZoneController::class, 'index'])->name('admin.pickup-zones');
        Route::get('pickup-zones/{zone}', [PickUpZoneController::class, 'addresses'])->name('admin.pickup-zones.addresses');

        Route::get('products', [ProductController::class, 'index'])->name('admin.products');
        Route::post('products/set-dimensions', [ProductController::class, 'setDimensions'])->name('admin.products.set-dimensions');

        Route::get('dev', [App\Http\Controllers\Admin\DevController::class, 'index'])->name('admin.dev');
        Route::get('dev/get-by-task-id', [App\Http\Controllers\Admin\DevController::class, 'getByTaskID']);
    });

    Route::middleware('can:courier-access')->group(function () {
        Route::get('generated/deliveries', [PublicDeiveriesController::class, 'zoneList']);
        Route::get('generated/deliveries/{zone}-{date}', [PublicDeiveriesController::class, 'addressList']);
        Route::get('generated/deliveries/{zone}-{date}/{address}', [PublicDeiveriesController::class, 'index']);
        Route::post('generated/set-status', [PublicDeiveriesController::class, 'setStatus'])->name('courier.deliveries.set-status');
    });
});

// Demo section
Route::get('demo-auth', [DemoController::class, 'auth']);

// tariff section
Route::get('tariffs', [TariffController::class, 'index'])->name('tariffs.index');

require __DIR__.'/auth.php';
