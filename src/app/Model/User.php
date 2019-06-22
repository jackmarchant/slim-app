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
    protected $fillable = ['email', 'password', 'username'];

    /**
     * Get the posts for the user
     */
    public function posts()
    {
        return $this->hasMany('App\Model\Post');
    }
}