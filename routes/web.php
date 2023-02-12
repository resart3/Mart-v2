
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Views\UserController;
use App\Http\Controllers\Views\FamilyCardController;
use App\Http\Controllers\Views\FamilyMemberController;
use App\Http\Controllers\Views\TransactionController;
use App\Http\Controllers\Views\TarifController;
use App\Http\Controllers\Views\LandController;
use App\Http\Controllers\Views\ReportController;
use App\Http\Controllers\Views\CalonPemilihController;
use App\Http\Controllers\Views\StatisticController;
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
    Route::get('data_filter/{rt}', [FamilyCardController::class, 'filter_rt']);
    Route::resource('calonPemilih', CalonPemilihController::class);
    Route::get('calonPemilih/filter/{rt}', [CalonPemilihController::class, 'filter']);
    Route::resource('statistic', StatisticController::class);
    Route::get('transaction/{nomor}/{tahun}', [TransactionController::class, 'show_transaction'])->name('detail_transaction');
    Route::get('transaction/{nomor}/{tahun}/{bulan}', [TransactionController::class, 'show_receipt_image'])->name('get_receipt_image');
    Route::delete('transaction/{nomor}/{tahun}/{bulan}', [TransactionController::class, 'delete_transaction'])->name('delete_transaction');
    Route::resource('transaction', TransactionController::class);
    Route::resource('tarif', TarifController::class);
    Route::resource('land', LandController::class);
    Route::resource('detail', FamilyMemberController::class);
    // Route::resource('report', ReportController::class);
    Route::group(['prefix' => 'report'], function () {
        Route::get('/report_jumlah', [ReportController::class, 'index_reportJumlah']);
        Route::get('/report_tunggakan', [ReportController::class, 'index_reportTunggakan']);
        Route::get('/detail_reportJumlah/{rt_rw}/{bulan}/{tahun}', [ReportController::class, 'detail_jumlah'])->name('detail_jumlah');
        Route::get('/detail_reportTunggakan/{rt_rw}/{bulan}/{tahun}', [ReportController::class, 'detail_tunggakan'])->name('detail_tunggakan');
        Route::get('/detailRt_iuran/{rt_rw}', [ReportController::class, 'detailRt_jumlah'])->name('detailRt_jumlah');
        Route::get('/detailRt_tunggakan/{rt_rw}', [ReportController::class, 'detailRt_tunggakan'])->name('detailRt_tunggakan');
        Route::get('/filterRt_jumlah/{tahun}/{bulan}', [ReportController::class, 'ajaxJumlahRt']);
        Route::get('/filterRt_tunggakan/{tahun}/{bulan}', [ReportController::class, 'ajaxTunggakanRt']);
        Route::get('/filer_jumlah/{tahun}/{bulan}', [ReportController::class, 'ajaxJumlah']);
        Route::get('/filer_tunggakan/{tahun}/{bulan}', [ReportController::class, 'ajaxTunggakan']);
        Route::get('/print_jumlah/{tahun}/{bulan}', [ReportController::class, 'printJumlah']);
        Route::get('/print_tunggakan/{tahun}/{bulan}', [ReportController::class, 'printTunggakan']);
        Route::get('/print_DetailJumlah/{rt_rw}/{tahun}/{bulan}', [ReportController::class, 'printDetailJumlah']);
        Route::get('/print_DetailTunggakan/{rt_rw}/{tahun}/{bulan}', [ReportController::class, 'printDetailTunggakan']);
    });

    Route::group(['prefix' => 'land'], function () {        
        Route::post('/nama_warga', [LandController::class, 'ajaxGetName']);
        Route::post('/category_amount', [LandController::class, 'ajaxGetAmount']);
        // Route::get('/edit-tarif/{id}', [TarifController::class], 'edit');
    });

    Route::group(['prefix' => 'user'], function() {
        Route::get('/edit-user/{id}', [UserController::class], 'edit');
    });
});