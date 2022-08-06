<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FamilyCardController;
use App\Http\Controllers\FamilyMemberController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\LandController;
use App\Http\Controllers\CategoryController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('public/assets/images/transaction/{id}/{filename}', function ($id, $filename){
    $path = public_path('assets/images/transaction/' . $id . '/' . $filename);    
    if (!File::exists($path)) {
        abort(404);
    }

    $response = Response::make(File::get($path));
    $response->header("Content-Type", File::mimeType($path));

    return $response;
});

Route::group(['prefix' => 'user'], function () {
    Route::post('login', [UserController::class, 'login']);
    Route::get('profile', [UserController::class, 'profile'])->middleware('user');
    Route::get('logout', [UserController::class, 'logout'])->middleware('user');
});

Route::group(['middleware' => 'user'], function () {
    Route::get('family/{id}', [FamilyCardController::class, 'family_by_nik']);
    Route::get('transaction', [TransactionController::class, 'index']);
    Route::get('transaction/{id}', [TransactionController::class, 'show']);
    Route::post('transaction_receipt', [TransactionController::class, 'add_receipt']);
    Route::resource('family_card', FamilyCardController::class);
    Route::resource('family_member', FamilyMemberController::class);
    Route::resource('land', LandController::class);    
    Route::resource('category', CategoryController::class);
    Route::get('getUpdated', [CategoryController::class, 'getUpdated']);
    
//    Route::get('images/transaction/{id}/{filename}', [TransactionController::class, 'transaction_receipt']);
});







// Route::group(['middleware' => 'user'], function () {
    
//     // Route::get('data/{id}', [FamilyMemberController::class]);
// //    Route::get('images/transaction/{id}/{filename}', [TransactionController::class, 'transaction_receipt']);
// });
