<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\authController;
use App\Http\Controllers\consultationController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(authController::Class)->group(function(){
    // momentos: get,post,put,delete
        Route::post('/register','register');
        Route::post('/login','login');    
        Route::middleware('auth:sanctum')->delete('/logout','logout');
        Route::get('/showById/{id}','showById');
        Route::put('/updateRandomPassword', 'updateRandomPassword');
        Route::put('/updateManualPassword','updateManualPassword');
    });

    Route::controller(consultationController::Class)->group(function(){
        // momentos: get,post,put,delete
       // Route::get('/getStore','getStore');
        Route::get('/{tienda}/getStore', 'getStore');
       // Route::get('/lapiedad/getStore','getStore');
            //Route::get('/getStore','getStore');
            Route::get('/getDepartaments','getDepartaments');
            Route::get('/getCategoriesforDepartament','getCategoriesforDepartament');
            Route::get('/getArticlesforDepartament','getArticlesforDepartament');
            Route::get('/getArticlesforCategory','getArticlesforCategory');
        });

        Route::controller(consultationController::class)->group(function () {
            //Route::get('/{tienda}/getStore', 'getStore');
            //Route::get('/zamora/getStore', 'getStore');
        });
    
        