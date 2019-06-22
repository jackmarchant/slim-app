<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Post extends Model {
    
    /** @var string */
    protected $table = 'posts';

    /** 
     * The attributes that are mass assignable
     * 
     * @var array
     */
    protected $fillable = ['content', 'user_id'];

    /**
     * Get the user that owns the post.
     */
    public function user()
    {
        return $this->belongsTo('App\Model\User');
    }
}