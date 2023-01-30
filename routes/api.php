<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NewPasswordController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\TipoPolizaController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
  
    Route::group(['middleware' => 'auth:sanctum'], function() {
      Route::post('logout', [AuthController::class, 'logout']);
      Route::get('profile', [AuthController::class, 'user']);
      Route::post('email/verification-notification', [EmailVerificationController::class, 'sendVerificationEmail']);
      Route::get('verify-email/{id}/{hash}', [EmailVerificationController::class, 'verify'])->name('verification.verify');
      
      Route::group(['middleware' => 'verified'], function() {
        
        Route::get('/user', function (Request $request) {
            return $request->user();
        });        
      });
      
    });
    Route::post('forgot-password', [NewPasswordController::class, 'forgotPassword']);
    Route::post('reset-password', [NewPasswordController::class, 'reset']);
    
});
  
Route::group(['middleware' => ['auth:sanctum','verified']], function() {
  // Tipo poliza
  // Route::resource('tipo-poliza',TipoPolizaController::class,['except'=>['edit','create'] ])->middleware("permission: tipo-poliza:manage");
  Route::resource('tipo-poliza',TipoPolizaController::class,['except'=>['edit','create'] ]);

  // Route::resource('roles', RolesController::class);
  // Route::resource('permissions', PermissionsController::class);
});
