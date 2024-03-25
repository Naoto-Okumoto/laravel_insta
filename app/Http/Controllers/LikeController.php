<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Like;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    private $like;

    public function __construct(Like $like)
    {
        $this->like = $like;
    }

    public function store($post_id)
    {
        $this->like->user_id = Auth::user()->id;
        $this->like->post_id = $post_id;
        $this->like->save();

        return redirect()->back();
    }

    #Destroy
    public function destroy($post_id)
    {
        $like = $this->like
                ->where('user_id', Auth::user()->id)
                ->where('post_id', $post_id)
                ->delete();
        // いつもみたいにfindOrFail()では探せない。findOrFail()では引数は１つだけで、主キー（PK）であるidにもとづいて１つだけを返す。今回は結果的に２のwhere()で１つにしぼられるが、それは主キーになんら基づいていない。現に、likesテーブルは主キー（id）を持たない。
        // ちなみにwhere()はPostモデルのisliked()関数で使ってる！
        
        return redirect()->back();
    }
}
