<?php

namespace App\Models\Api;

use App\Models\Api\Parkable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 2.4.1: Create three polymorph tables called ‘userable’, ‘parkable’ and ‘breedable’
 */
class Userable extends Model
{
    use HasFactory;
    protected $table = 'userable';
    protected $fillable = ['name', 'email', 'location', 'userable_id', 'userable_type'];

    public function userable()
    {
        return $this->morphTo();
    }

    /** 2.5.b: Here in case we want to link user to the park
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user_to_park_association()
    {
        return $this->belongsTo(Parkable::class, 'userable_id');
    }

    /** Here in case we want to link user to the park
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user_to_breed_association()
    {
        return $this->belongsTo(Breedable::class, 'userable_id');
    }
}
