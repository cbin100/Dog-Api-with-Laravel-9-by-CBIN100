<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
//2.3: Create a User model
class User extends Model
{
    use HasFactory;
    protected $table = 'user';
    protected $fillable = ['name', 'email', 'password'];
}
