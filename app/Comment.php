<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'post_id', 'comment',
    ];

    public function user(){
        // コメントは1人のユーザーに紐づく
        return $this->belongsTo(\App\User::class, 'user_id');
    }

    public function nices() {
        // 1つのコメントに複数のいいねがつく
        return $this->hasMany(\App\Comment::class, 'comment_id', 'id');
    }
}
