<?php

    use Illuminate\Http\Request;
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


Route::get('/', [\App\Http\Controllers\Portal\ExternalBreedController::class, 'index'])->name('portal_index');


//Portal
Route::get('/portal', [\App\Http\Controllers\Portal\ExternalBreedController::class, 'index'])->name('portal_index');
Route::post('/portal/breed/all', [\App\Http\Controllers\Portal\ExternalBreedController::class, 'get_breeds'])->name('portal_get_breeds');
//Route::post('/portal/breed/{slug}', [\App\Http\Controllers\Portal\ExternalBreedController::class, 'get_specific_breed'])->name('portal_get_specific_breed');
Route::get('/portal/breed/{slug}', [\App\Http\Controllers\Api\InternalBreedController::class, '_get_specific_breeds_from_external_god_api'])->name('portal_get_specific_breed');


Route::GET('/portal/breed/redis/all', [App\Http\Controllers\Api\InternalBreedController::class, 'get_all_breeds_from_redis_cache']);
Route::GET('/portal/breed/redis/local', [App\Http\Controllers\Api\InternalBreedController::class, 'get_all_internal_breeds_from_redis_cache']);
Route::GET('/portal/breed/redis/local/insert', [App\Http\Controllers\Api\InternalBreedController::class, 'insert_all_internal_breeds_into_redis_cache']);





