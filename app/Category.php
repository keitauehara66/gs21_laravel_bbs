<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'category_name', 'email', 'password',
    ];

    public function posts(){
        // 1つのカテゴリーには複数の投稿が紐づく
        return $this->hasMany(\App\Post::class, 'category_id', 'id');
    }

}
