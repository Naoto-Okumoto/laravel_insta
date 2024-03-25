<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;

class PostsController extends Controller
{
    private $post;

    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    public function index()
    {
        $all_posts = $this->post->withTrashed()->latest()->paginate(5);
        // paginateでページ割り付けできる！
        // ->withTrashed()でソフトデリートしたポストも表示できる。

        return view('admin.posts.index')
                ->with('all_posts', $all_posts);
    }

    public function hide($id)
    {
        $this->post->destroy($id);
        // 書き方はいつもの、テーブルからレコード消すときと同じだが、このPostsControllerが扱うPostモデルに、SoftDeletesモデルを２箇所useで宣言していることによって、すでに挙動はsoft deleteになってる！　ちなみに、もしいつものようなpermanently delete（永久削除）したかったら、forceDelete()という別のメソッドを使う！

        return redirect()->back();
    }

    public function unhide($id)
    {
        $this->post->onlyTrashed()->findOrFail($id)->restore();
        // ->onlyTrashed()がないと、ソフトデリートされたポストから探せないから必要！
        // ->restore()でソフトデリート状態を解除する。

        return redirect()->back();
    }
}
