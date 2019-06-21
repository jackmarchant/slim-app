<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class User extends Model {
    
    /** @var string */
    protected $table = 'users';

    /** 
     * The attributes that are mass assignable
     * 
     * @var array
     */
    protected $fillable = ['email', 'password'];
}