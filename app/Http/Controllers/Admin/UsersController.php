<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UsersController extends Controller
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function index()
    {
        $all_users = $this->user->withTrashed()->latest()->paginate(5);
        // paginateでページ割り付けできる！
        // ->withTrashed()でソフトデリートしたユーザーも表示できる。

        return view('admin.users.index')
                ->with('all_users', $all_users);
    }

    public function deactivate($id)
    {
        $this->user->destroy($id);
        // 書き方はいつもの、テーブルからレコード消すときと同じだが、このUsersControllerが扱うUserモデルに、SoftDeletesモデルを２箇所useで宣言していることによって、すでに挙動はsoft deleteになってる！　ちなみに、もしいつものようなpermanently delete（永久削除）したかったら、forceDelete()という別のメソッドを使う！

        return redirect()->back();
    }

    public function activate($id)
    {
        $this->user->onlyTrashed()->findOrFail($id)->restore();
        // ->onlyTrashed()がないと、ソフトデリートされたユーザーから探せないから必要！
        // ->restore()でソフトデリート状態を解除する。

        return redirect()->back();
    }
}
