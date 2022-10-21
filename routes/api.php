<?php

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

//Dod API:
    //1.1: Return all breeds from External
    Route::GET('/breed', [App\Http\Controllers\Api\InternalBreedController::class, 'get_breeds_from_external'])->name('get_breeds_from_external');
    //1.2. Return a specific breed
    Route::GET('/breed/{slug}', [App\Http\Controllers\Api\InternalBreedController::class, '_get_specific_breeds_from_external_god_api'])->name('_get_specific_breeds_from_external_god_api');
    //1.3 : Return a random breed (one)
    Route::GET('/breed/list/random', [App\Http\Controllers\Api\InternalBreedController::class, '_get_random_breeds_from_external'])->name('_get_random_breeds_from_external');
    //1.3.1: Bonus: Get N random breeds (many)
    Route::GET('/breed/list/random/{number}', [App\Http\Controllers\Api\InternalBreedController::class, '_get_random_breeds_from_external'])->name('_get_random_breeds_from_external');
    //1.4: Return images by breed
    Route::GET('/breed/{slug}/images', [App\Http\Controllers\Api\InternalBreedController::class, 'get_specific_breeds_images_from_external_god_api'])->name('get_specific_breeds_images_from_external_god_api');

    //2.1: Save all breeds into a database table –
    Route::GET('/breed/import', [App\Http\Controllers\Api\InternalBreedController::class, 'import_all_breeds_from_external'])->name('import_all_breeds_from_external');
    //2.2: Save all breeds into a REDIS cache
    Route::GET('/breed/redis/all', [App\Http\Controllers\Api\InternalBreedController::class, 'get_all_external_breeds_from_redis_cache'])->name('get_all_external_breeds_from_redis_cache');

    // 2.3 and 2.4: Handle data updates/changes/Delete from the external API
    Route::match(['PUT', 'DELETE'],'/breed/{id}', [App\Http\Controllers\Api\InternalBreedController::class, 'update_or_delete_breed_from_external'])->name('update_or_delete_breed_from_external');

    //
    /**
     * BONUS
     * After importing data from the External Dog API, we can use these URLs
     */
    Route::GET('/internal/breed', [App\Http\Controllers\Api\InternalBreedController::class, 'get_breeds_from_internal'])->name('get_breeds_from_internal');
    // Get Cached breeds from internal DB
    Route::GET('/breed/local/all', [App\Http\Controllers\Api\InternalBreedController::class, 'get_all_internal_breeds_from_redis_cache'])->name('get_all_internal_breeds_from_redis_cache');

    //2.8 : Create update your API handler
    Route::POST('/users', [App\Http\Controllers\Api\InternalBreedController::class, 'api_create_user'])->name('api_create_user');
    //2.5.a: Link Park to User
    Route::POST('/user/{id}/associate', [App\Http\Controllers\Api\LinkParkToUser::class, 'associate_park_to_user'])->name('associate_park_to_user');
    // 2.6: Link Breed to User
    Route::POST('/user/breed/{id}/associate', [App\Http\Controllers\Api\LinkParkToUser::class, 'associate_breed_to_user'])->name('associate_breed_to_user');
    // 2.7: Link Breed to Park
    Route::POST('/park/{id}/associate', [App\Http\Controllers\Api\LinkParkToUser::class, 'associate_breed_to_park'])->name('associate_breed_to_park');


