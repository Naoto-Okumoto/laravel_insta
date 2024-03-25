<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function show($id)
    // Auth::userに限らず、他の全員も含むプロフィール画面へ飛ぶメソッド
    {
        $user = $this->user->findOrFail($id);
        return view('users.profile.show')
                ->with('user', $user);
    }

    public function edit()
    {
        $user = $this->user->findOrFail(Auth::user()->id);
        // 今回はログインしているユーザーの編集ページなので、Auth::で探してくる。

        return view('users.profile.edit')
                ->with('user', $user);
    }

    public function update(Request $request)
    {
        #1. Validate the request　バリデーション
        $request->validate([
            'name' => 'required|min:1|max:50',
            'email'  => 'required|email|max:50|unique:users,email,' . Auth::user()->id,
            //マイグレでemailはuniqueとしているので、上の表記が必要。
            'avatar' => 'mimes:jpeg,jpg,png,gif|max:1048',
            // avatarはrequired不要。入れてしまうと、編集のとき、必ずimageをupdateしなくてはいけなくなるから。
            'introduction' => 'max:100'
        ]);
        
        #2. Update the profile
        $user        = $this->user->findOrFail(Auth::user()->id);
        $user->name  = $request->name;
        $user->email = $request->email;
        $user->introduction = $request->introduction;

        // 画像は更新しない可能性もあるので、ifに入れる。
        if ($request->avatar) {
            $user->avatar = 'data:image/' . $request->avatar->extension() . ';base64,' . base64_encode(file_get_contents($request->avatar));
        }

        $user->save();
        
        #3. Redirect to Show Profile page (to confirm the update)
        return redirect()->route('profile.show', Auth::user()->id);
    }

    public function followers($id)
    // Auth::userに限らず、他の全員も含んで、フォロワー表示の画面へ飛ぶメソッド
    {
        $user = $this->user->findOrFail($id);

         return view('users.profile.followers')
                ->with('user', $user);
    }

    public function following($id)
    // Auth::userに限らず、他の全員も含んで、フォローしているユーザーを表示する画面へ飛ぶメソッド
    {
        $user = $this->user->findOrFail($id);

         return view('users.profile.following')
                ->with('user', $user);
    }
}
