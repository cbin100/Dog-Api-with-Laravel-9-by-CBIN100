<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Api\Breedable;
class Image extends Model
{
    use HasFactory;
    protected $table = 'images';
    protected $fillable = ['breed_id', 'url'];
    public function breeds()
    {
        return $this->morphMany(Breedable::class, 'breedable');
    }
}
