<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    #One to many (inverse)
    #To get the info of the owner of the comment
    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
        // withTrashed()を入れると、nullにならずに済む。

    }

    

}
