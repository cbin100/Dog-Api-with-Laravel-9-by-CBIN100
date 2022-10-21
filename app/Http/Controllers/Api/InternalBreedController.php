<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Api\Breed;
use App\Models\Api\Breedable;
use App\Models\Api\Image;
use App\Models\Api\Park;
use App\Models\Api\User;
use App\Models\Api\Userable;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as Base;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;
use function PHPUnit\Framework\isEmpty;

class InternalBreedController extends Base
{
    /** check if breed breeds record exist. If exist, then no need to create or insert into breeds table
     * @return bool
     */
    public function check_if_breed_record_exist(){
        $breed_records = Breed::all();
        if($breed_records->isEmpty())
        {
            return false;
        }else{
            return $breed_records;
        }
    }

    public function delete_dependant_breed_data()
    {
        // Emptying all other dependant tables
        Park::whereNotNull('id')->delete();
        //Breedable::truncate();
        Breedable::whereNotNull('id')->delete();
        //Fact::whereNotNull('id')->delete();
        Image::whereNotNull('id')->delete();
        User::whereNotNull('id')->delete();
        return true;
    }

    /** Method: Get Http response to fetching dog data
     * @return array
     */
    public function get_all_breeds_from_external()
    {
        try {
            $response = Http::get(env('EXTERNAL_API_ENDPOINT').'breeds/list/all/');
            $data = [
                'content' => json_decode(response($response)->content(), true), // Or $response->body(), that will retrieve the content of the API request
                'endpoint' => env('EXTERNAL_API_ENDPOINT').'breeds/list/all/'
            ];
            return $data;
        }catch (\Exception $exception){
            return Null;
        }
    }

    /** Get Specific breed from external Dog API
     * @param $slug that is the name of breed
     * To execute this, use the @route('_get_specific_breeds_from_external_god_api')
     * @return \Illuminate\Http\JsonResponse
     */
    public function _get_specific_breeds_from_external_god_api($slug = '')
    {
        try {
            $response = Http::get(env('EXTERNAL_API_ENDPOINT'). 'breed/' .$slug .'/list');
            $data = [
                'content' => response($response)->content(), // Or $response->body(), that will retrieve the content of the API request
                'endpoint' => env('EXTERNAL_API_ENDPOINT'). 'breed/' .$slug .'/list'
            ];
            $data['specific_breeds'] = json_decode($data['content'], true); // convert JSON data into array
            return response()->json($data['specific_breeds'], 200);
        }catch (\Exception $exception){
            return $this->sendApiError(['System Error' => $exception->getMessage()], 404);
        }
    }

    /** Get random breeds from Dog API
     * @param $number
     * To execute this, use @route('_get_random_breeds_from_external')
     * @return @return \Illuminate\Http\JsonResponse
     */

    public function _get_random_breeds_from_external($number = Null)
    {
        try {
            $router = app()->make('router');
            if(isset($number)){
                $response = Http::get(env('EXTERNAL_API_ENDPOINT').'breeds/list/random/'. $number);
            }else{
                $response = Http::get(env('EXTERNAL_API_ENDPOINT').'breeds/list/random/');
            }
            $data = [
                'content' => json_decode(response($response)->content(), true), // Or $response->body(), that will retrieve the content of the API request
                'endpoint' => env('EXTERNAL_API_ENDPOINT').'breeds/list/random/5',
                'general_helper' => new \General() // This class is just used as helper for view purposes. Because the _get_five_random_breeds_from_external() is used in the Portal
            ];
            return response()->json($data['content'], 200);
        }catch (\Exception $exception){
            return $this->sendApiError(['System Error' => $exception->getMessage()], 404);
        }
    }

    /** Get images of Specific breeds from external Dog API
     * @param $slug that is the name of breed
     * To execute this, use the @route('get_specific_breeds_images_from_external_god_api')
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_specific_breeds_images_from_external_god_api($slug = '')
    {
        try {
            $response = Http::get(env('EXTERNAL_API_ENDPOINT'). 'breed/' .$slug .'/images');
            $data = [
                'content' => response($response)->content(), // Or $response->body(), that will retrieve the content of the API request
            ];
            $data['specific_breeds_images'] = json_decode($data['content'], true); // convert JSON data into array
            return response()->json($data['specific_breeds_images'], 200);
        }catch (\Exception $exception){
            return $this->sendApiError(['System Error' => $exception->getMessage()], 404);
        }
    }


    /** Send HTTP Response for importing data from Dog API.
     * To execute this, use the @route('import_all_breeds_from_external')
     * @return \Illuminate\Http\JsonResponse
     */
    public function import_all_breeds_from_external()
    {
        $data['import_breeds'] = $this->transaction_import_breeds_from_dog_api();
        if($data['import_breeds']){
            return $this->sendApiResponse('Dog data have been imported successfully', 200);
        }else{
            return $this->sendApiError(['System Error' => 'No Dog data have been imported'], 512);
        }
    }

    /**
     * //Import and save Breeds and sub-breeds
     * @return \Illuminate\Http\RedirectResponse|void
     */
    public function transaction_import_breeds_from_dog_api()
    {
        try {
            DB::transaction(function () { // SQL Transaction as we're going to deal with multiple SQL statements.
                $records = $this->check_if_breed_record_exist();
                if($records == false) {
                    $this->delete_dependant_breed_data();// deleting dependencies, including users, parks, images, etc...
                    $data = $this->get_all_breeds_from_external(); // HTTP GET method to get data from Dod API
                    if($data !== NULL)
                    {
                        foreach ($data['content']['message'] as $keys => $values) // looping with focus on message key
                        {
                            if (is_array($values)) { // check if values is another array. if Yes then SUB-BREED
                                $data = [
                                    'name' => $keys,
                                    'slug' => Str::slug($keys, '-')
                                ];
                                $insertion = Breed::create($data); // inserting parent breeds...
                                $id = $insertion->id; // get last inserted breed id to be attributed to sub-breed
                                foreach ($values as $key => $value) { // sub-breed
                                    $data = [
                                        'name' => $value,
                                        'parent' => $id, // parent breed
                                        'slug' => Str::slug($value, '-')
                                    ];
                                    Breed::create($data); // inserting sub-breeds...
                                }
                            }else{
                                $data = [
                                    'name' => $values,
                                    'slug' => Str::slug($values, '-')
                                ];
                                $insertion = Breed::create($data); // inserting parent breeds...
                            }
                        }
                    }
                }
                $records = $this->check_if_breed_record_exist();
                    foreach ($records as $record){ // get images of every breeds and sub-breeds
                        // HTTP Request Get images url from all breeds (including sub-breeds)and insert into images table...
                        $response = Http::get(env('EXTERNAL_API_ENDPOINT').'breed/' .$record->name . '/images');
                        $data = [
                            'content' => json_decode(response($response)->content(), true), // converting JSON to Array Response
                        ];
                        foreach ($data['content'] as $keys => $values) // looping Array response. Here keys are message and success
                        {
                            if (is_array($values)) { // check if values is another array. Here, from Array response, only message key will be used
                                foreach ($values as $key => $value) { //
                                    $data = [
                                        'breed_id' => $record->id, // dependant breed Id...
                                        'url' => $value, // Image url
                                    ];
                                    Image::create($data); // inserting image url...
                                }
                            }
                        }
                        /*// Insert breeds and into breedable table
                        * This is in case we want to add breed data into the polymorphic table breedable so that it get the sense.
                         * Just remove the comments tags then it will work
                        $breedable = [
                            'name' => $record->name,
                            'parent' => $record->parent,
                            'slug' => $record->slug,
                            'breedable_id' => $record->id,
                            'breedable_type' => Breed::class,
                        ];
                        Breedable::create($breedable);*/
                    }

            }, 120); // 120 is the number of seconds (or 2 minutes), attempt
            return TRUE;
        } catch (\Exception $exception){
            return back()->with('error', $exception->getMessage()); // This is in case this function is called through the portal, so will throw an error in session then echo out in the view.
        }
    }


    public function get_all_breeds_from_internal_db()
    {
        $data = [];
        $breeds = [];
        $sub_breeds = [];
        $data['breeds'] = Breed::whereNull('parent')->with('get_sub_breed')->orderBy('name')->get();
        if(count($data['breeds']))
        {
            foreach ($data['breeds'] as $breed)
            {
                if(count($breed['get_sub_breed'])){
                    foreach ($breed['get_sub_breed'] as $value){
                        $sub_breeds[] = $value->name;
                    }
                }else{
                    $sub_breeds =[];
                }
                $breeds[] = [
                    $breed->name => $sub_breeds
                ];
            }
            return $breeds;
        }else{
            return NULL;
        }
    }

    /** Get Dog data from internal DB then send response to route('get_breeds_from_internal')
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function get_breeds_from_internal()
    {
        try {
            $data = [];
            $data['breeds'] = $this->get_all_breeds_from_internal_db();
            if($data['breeds'] == NULL)
            {
                $error = ['Error' => 'No data found. Please import data from Dog API via '];
                return $this->sendApiError($error, 404);
            } else{
                return $this->sendApiResponse($data['breeds'], 200);
            }
        }catch (\Exception $exception){
            return back()->with('error', $exception->getMessage());
        }
    }

    /** Get Dog API data then send JSON response to route('get_breeds_from_external')
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function get_breeds_from_external()
    {
        try {
            //$data = [];
            $data = $this->get_all_breeds_from_external();
            if($data == NULL)
            {
                $error = ['Error' => 'No data found. Please import data from Dog API via '];
                return $this->sendApiError($error, 404);
            } else{
                return response()->json($data['content'], 200);
            }
        }catch (\Exception $exception){
            return back()->with('error', $exception->getMessage());
        }
    }

    public function insert_all_external_breeds_into_redis_cache()
    {
        try {
            Redis::del('breeds_all');
            $data = $this->get_all_breeds_from_external();
            $content = json_encode($data['content'], true);
            Redis::set('breeds_all', $content);
            return $content;
        }catch (\Exception $exception){
            return $this->sendApiError(['System Error' => $exception->getMessage()], 404);
        }
    }

    /** 2.2: Save all breeds into a REDIS cache
     * ****************************************
     * Get Cache data of the External Dog API then send GET response
     * To execute this, use the @route('import_all_breeds_from_external')
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_all_external_breeds_from_redis_cache()
    {
        //dd('OK');
        try {
            $cachedBreeds = Redis::get('breeds_all');
            if(isset($cachedBreeds)) {
                $cachedBreeds = json_decode($cachedBreeds, FALSE);
                return response()->json($cachedBreeds, 200);
                //dd($cachedBreeds);
            }else{
                $content = $this->insert_all_external_breeds_into_redis_cache();
                $data['breeds'] = json_decode($content, false);
                return response()->json($data['breeds'], 200);
            }
        }catch (\Exception $exception){
            return $this->sendApiError(['System Error' => $exception->getMessage()], 404);
        }
    }


    public function insert_all_internal_breeds_into_redis_cache()
    {
        try {
            $data = $this->get_all_breeds_from_internal_db();
            if($data == NULL)
            {
                $error = ['Error' => 'No data found. Please import data from Dog API via '];
                return $this->sendApiError($error, 404);
            }else{
                Redis::del('all_internal_api_breeds_caches');
                //$content = json_encode($data['content'], true);
                $content = json_encode($data, true);
                Redis::set('all_internal_api_breeds_caches', $content);
                return $content;
            }
        }catch (\Exception $exception){
            return $this->sendApiError(['System Error' => $exception->getMessage()], 404);
        }
    }

    /**
     * Get Cache data of the Internal Dog API then send GET response
     * To execute this, use the @route('get_all_internal_breeds_from_redis_cache')
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_all_internal_breeds_from_redis_cache()
    {
        try {
            $all_internal_api_breeds_caches = Redis::get('all_internal_api_breeds_caches');
            if(isset($all_internal_api_breeds_caches)) {
                $data['all_internal_api_breeds_caches'] = json_decode($all_internal_api_breeds_caches, FALSE);
                return $this->sendApiResponse($data['all_internal_api_breeds_caches'], 200);
            }else{
                $content = $this->insert_all_internal_breeds_into_redis_cache();
                $data['all_internal_api_breeds_caches'] = json_decode($content, false);
                return $this->sendApiResponse($data['all_internal_api_breeds_caches'], 200);
            }
        }catch (\Exception $exception){
            return $this->sendApiError(['System Error' => $exception->getMessage()], 404);
        }
    }

    public function _create_user(Request $request)
    {
        $article = Userable::create([
            'name' => $request->name,
            'email' => $request->email,
            //'location' => $request->location
        ]);
    }

    public function api_create_user(Request $request)
    {
        try {
            $theUrl = url('api_create_user');
            $response= Http::post($theUrl, [
                'name'=>$request->name,
                'email'=>$request->email
            ]);
            $user = Userable::create([
                'name' => $request->name,
                'email' => $request->email,
                'location' => $request->location
            ]);
            return $this->sendApiResponse($user);

        }catch (\Exception $exception){
            return $this->sendApiError(['System Error' => $exception->getMessage()], 404);
        }
    }

    /** Here we can update or delete a breed from the external
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update_or_delete_breed_from_external(Request $request, $id)
    {
        try {
            $theUrl = url('api_create_user');
            if($request->isMethod('PUT')){ // detect if method is PUT
                $response= Http::PUT($theUrl, [
                    'name'=>$request->name,
                ]);
                $breed = Breed::where('id', $id)->update(['name' => $request->name]);
            }elseif ($request->isMethod('PUT')){ // detect if method is DELETE
                $response= Http::DELETE($theUrl, [
                    'name'=>$request->name,
                ]);
                $breed = Breed::findOrFail($id);
                $breed->delete();
            }
            $response = [
                'Method' => $request->method(),
                'data' => $breed->toarray()
            ];
            return $this->sendApiResponse($response);

        }catch (\Exception $exception){
            return $this->sendApiError(['System Error' => $exception->getMessage()], 404);
        }
    }

    /** Here we can update a breed from the external
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete_breed_from_external(Request $request)
    {
        try {
            $theUrl = url('api_create_user');
            $response= Http::PUT($theUrl, [
                'name'=>$request->name,
            ]);
            $breed = Breed::create([
                'name' => $request->name,
            ]);
            return $this->sendApiResponse($breed);

        }catch (\Exception $exception){
            return $this->sendApiError(['System Error' => $exception->getMessage()], 404);
        }
    }
}
