<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    private $comment;

    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    public function store(Request $request, $post_id)
    {
        $request->validate(
        [
            'comment_body' . $post_id => 'required|max:150'
        ], 
        [
            'comment_body' . $post_id . '.required' => 'You cannot submit an empty comment.',
            'comment_body' . $post_id . '.max'      => 'The comment must not have more than 150 characters.'
        ]);
        // 上のように書くことで、バリデーションにひっかかったときの警告文を変えることができる！（しかも、それぞれのバリデーションに応じて変えられる！）

        //input()関数使って、情報を取得！
        $this->comment->body    = $request->input('comment_body' . $post_id);
        //What is the comment
        $this->comment->user_id = Auth::user()->id;
        //Who created the comment
        $this->comment->post_id = $post_id;
        //What post was commented
        $this->comment->save();

        return redirect()->back();
    }

    #Destroy
    public function destroy($id)
    {
        // $comment  = $this->comment->findOrFail($id);
        // $comment->delete();
        
        $this->comment->destroy($id);
        //この書き方の方がシンプルでよい。

        return redirect()->back();
    }
}
