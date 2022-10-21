<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Api\Breedable;
class Breed extends Model
{
    use HasFactory;
    protected $table = 'breed';
    protected $fillable = ['name', 'parent', 'slug'];

    /**Polymorphic relationship on breedables table
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function breeds()
    {
        return $this->morphMany(Breedable::class, 'breedable');
    }

    /**
     * recursive relationship
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function get_sub_breed()
    {
        return $this->hasMany(Breed::class, 'parent', 'id');
    }





    public function contents(): HasMany
    {
        return $this->hasMany(Fact::class, 'breed_id')->orderBy('name');
    }

    public function allContents()
    {
        return $this->contents()->with('contentables');
    }















    public function childrenAccounts()
    {
        //return $this->hasMany(Breed::class, 'parent', 'id');
        return $this->hasMany(Breed::class, 'parent');
    }

    public function allChildrenAccounts()
    {
        return $this->childrenAccounts()->with('allChildrenAccounts');
    }






}
