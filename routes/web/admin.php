<?php

use App\Http\Controllers\Dashboard\AdminAuthController;
use App\Http\Controllers\Dashboard\AdminController;
use App\Http\Controllers\Dashboard\AttributeController;
use App\Http\Controllers\Dashboard\BotUserController;
use App\Http\Controllers\Dashboard\CategoryController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\OrderController;
use App\Http\Controllers\Dashboard\ProductController;
use App\Http\Controllers\Dashboard\PromotionController;
use App\Http\Controllers\Dashboard\ReviewController;
use App\Http\Controllers\Dashboard\StockController;
use App\Http\Controllers\Dashboard\SupportChatController;
use App\Http\Controllers\Dashboard\SupportMessageController;
use App\Http\Controllers\Telegram\TelegramController;
use App\Http\Controllers\Telegram\LandingController;
use Illuminate\Support\Facades\Route;
use Telegram\Bot\Api;

Route::get('admin/login', [AdminAuthController::class, 'showLoginForm'])->name('dashboard.login');
Route::post('admin/login', [AdminAuthController::class, 'login']);

Route::post('dashboard/logout', [AdminAuthController::class, 'logout'])->name('logout')->middleware('auth:admin');

Route::prefix('dashboard')->middleware(['auth:admin', 'role:admin'])->group(function () {

    Route::get('', [DashboardController::class, 'index'])->name('dashboard');

    // categories
    Route::resource('categories', CategoryController::class);

    // attributes
    Route::resource('attributes', AttributeController::class);
    Route::post('attributes/{attribute}/values', [AttributeController::class, 'storeValue'])
        ->name('attributes.values.store');
    Route::delete('attributes/{attribute}/values/{value}', [AttributeController::class, 'destroyValue'])
        ->name('attributes.values.destroy');

    // admins
    Route::resource('admins', AdminController::class)->except('show');

    // products
    Route::resource('products', ProductController::class);
    Route::get('/export-products', [ProductController::class, 'export'])
        ->name('products.export');

    // stocks
    Route::get('stocks', [StockController::class, 'index'])->name('stocks.index');
    Route::get('stocks/edit/{stock}', [StockController::class, 'edit'])->name('stocks.edit');
    Route::put('stocks/edit/{stock}', [StockController::class, 'update'])->name('stocks.update');
    Route::get('stocks/show/{stock}', [StockController::class, 'show'])->name('stocks.show');

    // orders
    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('orders/{order}/invoice-pdf', [OrderController::class, 'invoicePdf'])->name('orders.invoice.pdf');
    Route::get('/export-orders', [OrderController::class, 'export'])
        ->name('orders.export');

    // bot-users
    Route::get('bot-users', [BotUserController::class, 'index'])->name('bot.users.index');

    // reviews
    Route::get('reviews', [ReviewController::class, 'index'])->name('reviews.index');

    // landing carousel
    Route::get('landing-carousel', [LandingController::class, 'carouselIndex'])
        ->name('landing.carousel.index');
    Route::post('landing-carousel', [LandingController::class, 'carouselStore'])
        ->name('landing.carousel.store');
    Route::put('landing-carousel/{carousel}', [LandingController::class, 'carouselUpdate'])
        ->name('landing.carousel.update');
    Route::delete('landing-carousel/{carousel}', [LandingController::class, 'carouselDestroy'])
        ->name('landing.carousel.destroy');

    // promotions
    Route::get('promotions', [PromotionController::class, 'index'])
        ->name('promotions.index');
    Route::post('promotions', [PromotionController::class, 'update'])
        ->name('promotions.update');

    Route::prefix('support')->name('support.')->group(function () {
        Route::get('/', [SupportChatController::class, 'index'])->name('index');
        Route::get('/{chat}', [SupportChatController::class, 'show'])->name('show');
        Route::post('/{chat}/send', [SupportMessageController::class, 'send'])->name('send');
        Route::post('/{chat}/close', [SupportMessageController::class, 'close'])->name('close');
    });

    // orders - update status
    Route::post('orders/{order}/status', [OrderController::class, 'updateStatus'])
        ->name('orders.update-status');

    // disactive bot
    Route::post('admin/bot-users/{user}/toggle-block', [BotUserController::class, 'toggleBlock'])
        ->name('admin.bot-users.toggle-block');
});

// Telegram
Route::prefix('telegram')->group(function () {
    Route::get('webhook', function () {
        $telegram = new Api(env('TELEGRAM_BOT_TOKEN'));
        $hook = $telegram->setWebhook(['url' => env('TELEGRAM_WEBHOOK_URL')]);
        dd($hook);
    });

    Route::post('webhook', [TelegramController::class, 'handleWebhook']);
});
