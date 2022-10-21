<?php

namespace App\Models\Api;

use App\Models\Api\Userable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 2.4.2: Create three polymorph tables called ‘userable’, ‘parkable’ and ‘breedable’
 */
class Parkable extends Model
{
    use HasFactory;
    protected $table = 'parkable';
    protected $fillable = ['parkable_id', 'parkable_type'];

    public function parkable()
    {
        return $this->morphTo();
    }

    //2.5: Here we link Park to User
    public function park_to_user_association()
    {
        return $this->belongsTo(Userable::class, 'parkable_id');
    }

    public function morphic_user()
    {
        return $this->morphMany(Userable::class, 'userable');
    }

}
