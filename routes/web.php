
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Views\UserController;
use App\Http\Controllers\Views\FamilyCardController;
use App\Http\Controllers\Views\FamilyMemberController;
use App\Http\Controllers\Views\TransactionController;
use App\Http\Controllers\Views\TarifController;
use App\Http\Controllers\Views\LandController;

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

Route::redirect('/','user/login');

Route::group(['prefix' => 'user'], function () {
    Route::get('/login', [UserController::class, 'view_login']);
    Route::post('/login', [UserController::class, 'login']);
    Route::get('/logout', [UserController::class, 'logout']);
});

Route::group(['prefix' => 'dashboard', 'middleware' => 'superuser'], function () {
    Route::get('/', function () { $title = 'Dashboard'; return view('index', compact('title')); });
    Route::resource('user', UserController::class);
    Route::resource('data', FamilyCardController::class);
    Route::get('transaction/{nomor}/{tahun}', [TransactionController::class, 'show_transaction'])->name('detail_transaction');
    Route::get('transaction/{nomor}/{tahun}/{bulan}', [TransactionController::class, 'show_receipt_image'])->name('get_receipt_image');
    Route::delete('transaction/{nomor}/{tahun}/{bulan}', [TransactionController::class, 'delete_transaction'])->name('delete_transaction');
    Route::resource('transaction', TransactionController::class);
    Route::resource('tarif', TarifController::class);
    Route::resource('land', LandController::class);
    Route::resource('detail', FamilyMemberController::class);

    Route::group(['prefix' => 'land'], function () {        
        Route::post('/nama_warga', [LandController::class, 'ajaxGetName']);
        Route::post('/category_amount', [LandController::class, 'ajaxGetAmount']);
        // Route::get('/edit-tarif/{id}', [TarifController::class], 'edit');
    });

    Route::group(['prefix' => 'user'], function() {
        Route::get('/edit-user/{id}', [UserController::class], 'edit');
    });
});


