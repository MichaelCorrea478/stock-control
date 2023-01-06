<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WalletController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::middleware(['auth'])->group(function() {
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    Route::prefix('users')->group(function() {
        Route::get('/information', [HomeController::class, 'getUserInformation'])->name('users.information');
    });

    Route::prefix('stocks')->group(function() {
        Route::get('/index', [StockController::class, 'index'])->name('stocks.index');
        Route::get('/list', [StockController::class, 'list'])->name('stocks.list');
        Route::get('/current_prices', [StockController::class, 'getCurrentPrices'])->name('stocks.current_prices');
        Route::post('/buy', [StockController::class, 'buyStock'])->name('stocks.buy');
        Route::post('/sell', [StockController::class, 'sellStock'])->name('stocks.sell');
    });

    Route::prefix('wallets')->group(function() {
        Route::get('/get', [WalletController::class, 'getUserWallet'])->name('wallets.get');
        Route::post('/deposit', [WalletController::class, 'makeDeposit'])->name('wallets.deposit');
        Route::post('/withdraw', [WalletController::class, 'makeWithdraw'])->name('wallets.withdraw');
    });
});

