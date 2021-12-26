<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'category_id', 'title', 'content'
    ];

    public function category(){
        // 投稿は1つのカテゴリーに属する
        return $this->belongsTo(\App\Category::class, 'category_id');
    }

    public function user(){
        // 投稿は1つのユーザーが紐づく
        return $this->belongsTo(\App\User::class, 'user_id');
    }

    public function comments(){
        // 投稿には複数のコメントが紐づく
        return $this->hasMany(\App\Comment::class, 'post_id', 'id');
    }

    public function bookmarks(){
        // post_userの中間テーブル
        return $this->belongsToMany('App\User')->withTimestamps();
    }
}
