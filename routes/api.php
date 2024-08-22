<?php

use App\Http\Controllers\AuthController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [AuthController::class,'logout']);
});


Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return response()->json(['message' => 'Email verified successfully.'], 200);
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/resend', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return response()->json(['message' => 'Verification link sent.'], 200);
})->middleware('auth')->name('verification.resend');

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});
