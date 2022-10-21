<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Api\Breedable;
use App\Models\Api\Parkable;
use App\Models\Api\Userable;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as Base;
class LinkParkToUser extends Base
{
    public function associate_park_to_user(Request $request, $id)
    {
        try {
            $user = Userable::find($id);
            if(is_null($user)){
                $user = Userable::firstOrCreate([
                    'name' => $request->name,
                    'email' => $request->email,
                    'location' => $request->location,
                    'userable_type' => Parkable::class
                ]);
            }
            $park = Parkable::firstOrCreate([
                'name' => $request->park_name,
                //etc....
            ]);
            //Here we link Parkable to userable. Method defined in App\Models\Api\Parkable::class
            $park->park_to_user_association()->associate($user);
            $park->save();

            //Here in case we want to link user to the park. Method defined in App\Models\Api\Userable::class
            //$user->user_to_park_association()->associate($park);
            //$user->save();
            return $this->sendApiResponse($park->toarray());
        }catch (\Exception $exception){
            return $this->sendApiError($exception->getMessage(), 404);
        }
    }

    public function associate_breed_to_user(Request $request, $id)
    {
        try {
            $user = Userable::find($id);
            if(is_null($user)){
                $user = Userable::firstOrCreate([
                    'name' => $request->name,
                    'email' => $request->email,
                    'location' => $request->location,
                    'userable_type' => Parkable::class
                ]);
            }

            $breed = Breedable::firstOrCreate([
                'name' => $request->breed_name,
                //etc....
            ]);
            //Here we link Parkable to userable. Method defined in App\Models\Api\Breedable::class
            $breed->breed_to_user_association()->associate($user);
            $breed->save();

            //Here in case we want to link user to the park. Method defined in App\Models\Api\Userable::class
            //$user->user_to_breed_association()->associate($breed);
            //$user->save();
            return $this->sendApiResponse($breed->toarray());
        }catch (\Exception $exception){
            return $this->sendApiError($exception->getMessage(), 404);
        }
    }

    public function associate_breed_to_park(Request $request, $id)
    {
        try {
            $park = Parkable::find($id);
            if(is_null($park)){
                $user = Userable::firstOrCreate([
                    'name' => $request->park_name,
                    'parkable_type' => Breedable::class
                ]);
            }

            $breed = Breedable::firstOrCreate([
                'name' => $request->breed_name,
                //etc....
            ]);
            //Here we link Parkable to userable. Method defined in App\Models\Api\Breedable::class
            $breed->breed_to_park_association()->associate($park);
            $breed->save();

            //Here in case we want to link user to the park. Method defined in App\Models\Api\Userable::class
            //$user->user_to_breed_association()->associate($breed);
            //$user->save();

            return $this->sendApiResponse($breed->toarray());
        }catch (\Exception $exception){
            return $this->sendApiError($exception->getMessage(), 404);
        }
    }
}
