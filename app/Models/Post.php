<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes;
// soft deleteするために、↑入れた！
// でもこっちはただのpreparation(準備)

class Post extends Model
{
    use HasFactory, SoftDeletes;
    // こっちのuseが実際に使うモデルの宣言！

    // 投稿者の名前を取得する。
    #A post belongs to a USER
    #To get the owner of the post
    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
        // withTrashed()を入れると、nullにならずに済む。
    }

    // ピボットテーブルへ移動する。
    #To get the categories under a post
    public function categoryPost()
    {
        return $this->hasMany(CategoryPost::class);
    }

    // 投稿に対するコメントを取得する。
    #To get all the comments of a post
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // 投稿に対するLIKEを取得する。（表示はカウントした数のみ）
    #To get all the likes of a post
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    // 投稿がすでにAuth UserにLIKEされているかチェックする関数。
    #Returns TRUE if the Auth user already liked the post
    public function isLiked()
    {
        return $this->likes()->where('user_id', Auth::user()->id)->exists();
        // すでにLIKEボタンを押してるかチェックする。
        // （$postから）likesテーブルに移動し、user_idにこのログインしているユーザーのidが存在しているか。存在していたらTRUE。
        // このファンクションをもとに、(homeの)body.bladeでif条件分岐内で（$post->isLiked()）として、そのポスト（のid）でlikesテーブルからuser_id探して、というように使う！
    }

}
