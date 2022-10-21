<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
/**
 * 2.4.3: Create three polymorph tables called ‘userable’, ‘parkable’ and ‘breedable’
 */
class Breedable extends Model
{
    use HasFactory;
    protected $table = 'breedable';
    protected $fillable = ['name', 'parent', 'slug','breedable_id', 'breedable_type'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function breedable()
    {
        return $this->morphTo();
    }

    /** This is used to get sub-breed
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function get_parent_breeds()
    {
        return $this->belongsTo(Breed::class, 'breedable_id');
    }

    public function morphic_user()
    {
        return $this->morphMany(Userable::class, 'userable');
    }

    //2.6: Here we link Breed to User
    public function breed_to_user_association()
    {
        return $this->belongsTo(Userable::class, 'breedable_id');
    }


    //2.7: Here we link Breed to Park
    public function breed_to_park_association()
    {
        return $this->belongsTo(Parkable::class, 'breedable_id');
    }

}
