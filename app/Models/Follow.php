<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
    use HasFactory;

    public $timestamps = false;

    #To get the info of a follower
    // プロフィール画面からその人のfollowerを表示するときに（のみ）使う。
    // Usersテーブルに移動して、名前を取得したいから。
    public function follower()
    {
        return $this->belongsTo(User::class, 'follower_id')->withTrashed();
        // withTrashed()を入れると、nullにならずに済む。
    }

    #To get the info of the user being followed
    // プロフィール画面からその人がフォローしている人を表示するときに（のみ）使う。
    // Usersテーブルに移動して、名前を取得したいから。
    public function following()
    {
        return $this->belongsTo(User::class, 'following_id')->withTrashed();
        // withTrashed()を入れると、nullにならずに済む。
    }
}
