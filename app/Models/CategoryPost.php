<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryPost extends Model
{
    use HasFactory;

    // 下の３行を追加した
    // 今回、テーブルが単数系なので、ちゃんとLaravelが探し出せるようにする。
    protected $table = 'category_post';
    protected $fillable = ['category_id', 'post_id'];  // [1, 2, 5]
    public $timestamps = false;
    // この記述がないと、テーブルからタイムスタンプを探してしまう。

    // カテゴリーテーブルに移動し、カテゴリを取得する。
    #To get the name of the category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}

/*
    [1. 1]
    [1. 2]
    [1, 5]
*/