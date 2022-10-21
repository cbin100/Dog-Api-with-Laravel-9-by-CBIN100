# Dog-api-with-laravel-9
### Consume Dog Api in Laravel 9

Hi,
My name is Watchiba.

Please find here how I manage to consume Dog Api in Laravel 9.
The Api is provided by https://dog.ceo/dog-api.
The codes of this App is fully commented. You just need to clone this repository then use your own config.
 

## Prerequisite
### Laravel 9 (PHP >= 8.1)
### Mysql 8.0
### Redis (predis)
### Postman (postman.com)

You can use your own Laravel version (7, 8, etc..). If so, just copy and paste the project libraries and files except vendor, node_modules, and files related to composer. But I recommend to use the current Laravel version. 
## Configuration
The App is fully structured. For e.g:
Controller are found in App/Controllers/Api
Migration are in : database/migrations/Api
Models are in: App/Models/Api
Same as view.

There are additional files such Helpers/General_helper.php, config/Api, lang/en/api.php etc... These files are also used in the App.
This App comes with Redis installed. But you opted to use your own Laravel version, please install Redis by using this command:
#### composer require predis/predis

After cloning the repo, please create your own DB, then to the Terminal:
#### php artisan migrate --path=/database/migrations/Api
Please do not forget to clear caches as Redis might not work if you have just install it (php artisan cache:clear and php artisan config:clear

## Part 1 – External Data API
This part of the App consists of creating models, migrations, controller, and handler to return data provided by the dogs-ceo API.
The API is able to:

### 1.1 Return all breeds: [Screenshor here](https://github.com/cbin100/Dog-Api-with-Laravel-9-by-CBIN100/blob/main/screenshots/Breeds%20from%20External.png)

    Route::GET('/breed', [App\Http\Controllers\Api\InternalBreedController::class, 'get_breeds_from_external'])->name('get_breeds_from_external');

### 1.2. Return a specific breed [Screenshort here](https://github.com/cbin100/Dog-Api-with-Laravel-9-by-CBIN100/commit/4b6a4d532d91a5e49d714745f07aa9508f4d15dd)

    Route::GET('/breed/{slug}', [App\Http\Controllers\Api\InternalBreedController::class, '_get_specific_breeds_from_external_god_api'])->name('_get_specific_breeds_from_external_god_api');
    
### 1.3 : Return a random breed (one) [Screenshort here](https://github.com/cbin100/Dog-Api-with-Laravel-9-by-CBIN100/blob/main/screenshots/1-3%20Return%20a%20random%20breed.png)

    Route::GET('/breed/list/random', [App\Http\Controllers\Api\InternalBreedController::class, '_get_random_breeds_from_external'])->name('_get_random_breeds_from_external');
    
### 1.3.1: Bonus: Get N random breeds (many) [Screenshort here](

    Route::GET('/breed/list/random/{number}', [App\Http\Controllers\Api\InternalBreedController::class, '_get_random_breeds_from_external'])->name('_get_random_breeds_from_external');
    
### 1.4: Return images by breed [Screenshort here](https://github.com/cbin100/Dog-Api-with-Laravel-9-by-CBIN100/blob/main/screenshots/1-4-%20Return%20images%20by%20breed.png)

    Route::GET('/breed/{slug}/images', [App\Http\Controllers\Api\InternalBreedController::class, 'get_specific_breeds_images_from_external_god_api'])->name('get_specific_breeds_images_from_external_god_api');

## Part 2 – Laravel Models, Relationships and Saving Data.

This part of the exercise is designed to save (or import) data from External API (Dog API) into local (internal Database) and work with caching using REDIS.

### 2.1: Save all breeds into a database table – This url will import all data from External API then save into Local DB

    Route::GET('/breed/import', [App\Http\Controllers\Api\InternalBreedController::class, 'import_all_breeds_from_external'])->name('import_all_breeds_from_external');
[Screenshort here](https://github.com/cbin100/Dog-Api-with-Laravel-9-by-CBIN100/blob/main/screenshots/2%20-1-%20Save%20or%20import%20all%20breeds%20into%20a%20database%20table.png)

### 2.2: Save all breeds into a REDIS cache

    Route::GET('/breed/redis/all', [App\Http\Controllers\Api\InternalBreedController::class, 'get_all_external_breeds_from_redis_cache'])->name('get_all_external_breeds_from_redis_cache');

[Screenshort here](https://github.com/cbin100/Dog-Api-with-Laravel-9-by-CBIN100/blob/main/screenshots/2-2-Save%20all%20breeds%20into%20a%20REDIS%20cache.png)

### 2.3 and 2.4: Handle data updates/changes/Delete from the external API
    Route::match(['PUT', 'DELETE'],'/breed/{id}', [App\Http\Controllers\Api\InternalBreedController::class, 'update_or_delete_breed_from_external'])->name('update_or_delete_breed_from_external');
[Screenshort here](
    
### 2.5: Create three polymorph tables called ‘userable’, ‘parkable’ and ‘breedable’ 
See the corresponding polymorphic functions in Models/Api (Breedable, Userable, Parkable)

### 2.5.a: Link Park to User
    Route::POST('/user/{id}/associate', [App\Http\Controllers\Api\LinkParkToUser::class, 'associate_park_to_user'])->name('associate_park_to_user');


### 2.6: Link Breed to User
    Route::POST('/user/breed/{id}/associate', [App\Http\Controllers\Api\LinkParkToUser::class, 'associate_breed_to_user'])->name('associate_breed_to_user');
    

### 2.7: Link Breed to Park
    Route::POST('/park/{id}/associate', [App\Http\Controllers\Api\LinkParkToUser::class, 'associate_breed_to_park'])->name('associate_breed_to_park');

### 2.8 : Create update your API handler
    Route::POST('/users', [App\Http\Controllers\Api\InternalBreedController::class, 'api_create_user'])->name('api_create_user');
   

     ## BONUS 1
     *- After importing data from the External Dog API, we can use these URLs to consume the local API
     
    Route::GET('/internal/breed', [App\Http\Controllers\Api\InternalBreedController::class, 'get_breeds_from_internal'])->name('get_breeds_from_internal');
    
   
    
    ** Get Cached breeds from internal DB
    Route::GET('/breed/local/all', [App\Http\Controllers\Api\InternalBreedController::class, 'get_all_internal_breeds_from_redis_cache'])->name('get_all_internal_breeds_from_redis_cache');

## BONUS 2
I also developed a UI Portal which can help consume the API with just view clicks
Please, use routes in the web.php file
Route::get('/portal', [\App\Http\Controllers\Portal\ExternalBreedController::class, 'index'])->name('portal_index');

[Screenshot 1](https://github.com/cbin100/Dog-Api-with-Laravel-9-by-CBIN100/blob/main/screenshots/Screenshot%202022-10-21%20023714.png)

[Screenshot 2](https://github.com/cbin100/Dog-Api-with-Laravel-9-by-CBIN100/blob/main/screenshots/Screenshot%202022-10-21%20023838.png)

[Screenshot 3](https://github.com/cbin100/Dog-Api-with-Laravel-9-by-CBIN100/blob/main/screenshots/Screenshot%202022-10-21%20023940.png)



### Part 3 – GraphQL 
This part implements the graphql queries to return all the base models created so far. The following queries have been solved:

query users {
name, email, location
}
query breeds {
image,
}
query parks {
name
}

To solve the queries above, I've created 2 files - Schema file and resolver files:
https://github.com/cbin100/dog-api-with-laravel-9/blob/main/graphql/resolvers.js as the resolver file and
https://github.com/cbin100/dog-api-with-laravel-9/blob/main/graphql/schema.graphql   as the Schema file


## Part 4: Dog API with React
Due to the time, I could not complete React part which should focus on UI with React

## Conclusion
I hope you learned a thing or two about Consuming Dog API with Laravel or at least understand how I developed my APP. Please let me know your thoughts on how I did or what I can do to improve. I am open to feedback especially on everything described above.

If you’re interested in playing with my APP, you can fork and clone the app.
Let's connect on Linkedin https://www.linkedin.com/in/watchiba-j-541965195/

If you're generous, please buy me a coffee on
https://paypal.me/pelogroup?country.x=GB&locale.x=en_GB

## Thank you!
