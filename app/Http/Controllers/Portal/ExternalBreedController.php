<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Api\Breed;
use App\Models\Api\Breedable;
use App\Models\Api\Image;
use App\Models\Api\Park;
use App\Models\Api\User;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Api\InternalBreedController as InternalBreedController;
class ExternalBreedController extends Controller
{
    public string $endpoint;
    public $import_breed;
    public function __construct(){
        $this->import_breed = new InternalBreedController;

    }

    public function index()
    {
        return view('layouts.Api_web_portal.breed_content');
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
            return back()->with('error', $exception->getMessage());
        }
    }
    public function get_specific_breeds_from_external(Request $request)
    {
        $validated = $request->validate([
            'txt_specific_breed_from_external' => 'required|min:2',
        ]);
        $response = Http::get(env('EXTERNAL_API_ENDPOINT'). $validated['txt_specific_breed_from_external']);

        $data = [
            'content' => response($response)->content(), // Or $response->body(), that will retrieve the content of the API request
        ];
        return $data;
    }

    public function get_specific_breeds_from_internal()
    {
        $response = Http::get(env('EXTERNAL_API_ENDPOINT').'breeds/list/all/');
        $data = [
            'content' => response($response)->content(), // Or $response->body(), that will retrieve the content of the API request
        ];
        return $data;
    }

    public function get_all_breeds_from_internal()
    {
        $response = Http::get(env('EXTERNAL_API_ENDPOINT').'breeds/list/all/');
        $data = [
            'content' => response($response)->content(), // Or $response->body(), that will retrieve the content of the API request
        ];
        return $data;
    }


    /**
     * //Import and save Breeds and sub-breeds
     * @return \Illuminate\Http\RedirectResponse|void
     */
    public function import_all_breeds_from_external()
    {
        $data['import_breed'] = $this->import_breed->transaction_import_breeds_from_dog_api();
        if($data['import_breed'] == TRUE)
        {
            return back()->with('success','Breeds imported and saved successfully!');
        }
    }



    public function get_breeds(Request $request)
    {
        if($request->has('btn_import_all_breeds_from_external')) // When Click to import and save Breeds
        {
            $this->import_all_breeds_from_external();
        }
        if ($request->has('btn_get_all_breed_from_external')){
            $data = $this->import_breed->get_all_breeds_from_external();
            $data['breeds_from_external'] = $data['content'];
            $data['general_helper'] = new \General();
            return view('layouts.Api_web_portal.breed_content',compact('data'));
        }
        if ($request->has('btn_get_specific_breed_from_external')){
            $validated = $request->validate([
                'txt_specific_breed_from_external' => 'required|min:2',
            ]);
            $response = Http::get(env('EXTERNAL_API_ENDPOINT'). 'breed/' .$validated['txt_specific_breed_from_external'] .'/list');

            $data = [
                'content' => response($response)->content(), // Or $response->body(), that will retrieve the content of the API request
                'endpoint' => env('EXTERNAL_API_ENDPOINT'). 'breed/' .$validated['txt_specific_breed_from_external'] .'/list'
            ];
            $data['specific_breeds'] = json_decode($data['content'], true); // convert JSON data into array

            $data['general_helper'] = new \General();
            return view('layouts.Api_web_portal.breed_content',compact('data'));
        }

        if($request->has('btn_get_5_random_breeds_from_external')) {
            $data = $this->get_five_random_breeds_from_external();
            $data['five_random_breeds'] = $data['content'];
            return view('layouts.Api_web_portal.breed_content',compact('data'));
        }
        if($request->input('btn_get_random_breeds_image_from_external')){
            $data = $this->get_random_breeds_image_from_external();
            $data['random_breeds_image_from_external'] = $data['content'];
            return view('layouts.Api_web_portal.breed_content',compact('data'));
        }

        return view('layouts.Api_web_portal.breed_content');
    }

    public function get_specific_breed(Request $request)
    {
        $validated = $request->validate([
            'txt_specific_breed_from_external' => 'required|min:2',
        ]);
        $response = Http::get(env('EXTERNAL_API_ENDPOINT'). $validated['txt_specific_breed_from_external']);
        $data = [
            'content' => response($response)->content(), // Or $response->body(), that will retrieve the content of the API request
        ];
        return $data;
    }

    /** Get 5 random breeds from Dog API
     * @return array
     */
    public function get_five_random_breeds_from_external()
    {
        $response = Http::get(env('EXTERNAL_API_ENDPOINT').'breeds/list/random/5');
        $data = [
            'content' => json_decode(response($response)->content(), true), // Or $response->body(), that will retrieve the content of the API request
            'endpoint' => env('EXTERNAL_API_ENDPOINT').'breeds/list/random/5',
            'general_helper' => new \General()
        ];
        return $data;
    }

    /**Get a random breed image from Dog API
     * @return array
     */
    public function get_random_breeds_image_from_external()
    {
        $response = Http::get(env('EXTERNAL_API_ENDPOINT').'breed/hound/images/random')['message'];
        $data = [
            'content' => response($response)->content(), // Or $response->body(), that will retrieve the content of the API request
            'endpoint' => env('EXTERNAL_API_ENDPOINT').'breed/hound/images/random',
            'general_helper' => new \General()
        ];
        $data['random_breeds_image_from_external'] = $data['content'];
        return $data;
    }
}
