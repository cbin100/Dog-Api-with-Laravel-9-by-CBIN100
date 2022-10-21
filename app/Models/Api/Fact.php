<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Api\Breed;
class Fact extends Model
{
    use HasFactory;
    protected $table = 'facts';
    protected $fillable = ['user_id', 'breed_id', 'parent_breed', 'image_id', 'park_id'];
    //protected $attributes = ['user_id' => 0, 'breed_id' => 0, 'image_id' => 0, 'park_id' => 0];


    public function contentable(): MorphTo
    {
        return $this->morphTo();
    }

    public function contentables()
    {
        return $this->contentable()->with('allContents');
    }




    //Fact model
    public function factable(){
        return $this->morphTo(__FUNCTION__, 'parent_breed', 'breed_id');
    }

    public function factables()
    {
        return $this->factable()->with('allContents');
    }
}
