
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Views\UserController;
use App\Http\Controllers\Views\FamilyCardController;
use App\Http\Controllers\Views\TransactionController;
use App\Http\Controllers\Views\TarifController;

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
    Route::resource('transaction', TransactionController::class);
    Route::resource('tarif', TarifController::class);

    Route::group(['prefix' => 'tarif'], function () {
        Route::post('/tarif_warga', [TarifController::class, 'storeLand']);
    });
    // Route::post('tarif.addnewtarif', [TarifController::class, 'addTarif']);
});


