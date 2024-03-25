<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class HomeController extends Controller
{
    private $post;
    private $user;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Post $post, User $user)
    {
        // $this->middleware('auth'); ルートとredundantなので、こちらはコメントアウト
        $this->post = $post;
        $this->user = $user;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // $all_posts = $this->post->latest()->get();
        $home_posts = $this->getHomePosts();
        $suggested_users = $this->getSuggestedUsers();

        return view('users.home')
                ->with('home_posts', $home_posts)
                ->with('suggested_users', $suggested_users);
    }

    // 自身の投稿と、自身（Auth user）がフォローしている人の投稿だけホームに表示する
    // ただ、コードの書き方に注意。中心は$post。投稿からの視点で考える。
    #Get the posts of the users that the Auth user is following
    private function getHomePosts()
    {
        $all_posts = $this->post->latest()->get();
        $home_posts = []; // 初期化
        // この初期化は、下でforeachでまわして取得する$home_postsが空だったときに、nullではなくemptyで返すためのもの。
        // In case the $home_posts is empty, it wil not return null, but empty instead. （emptyとnullは違うもの！）

        foreach($all_posts as $post)
        {
            if($post->user->isFollowed() || $post->user->id === Auth::user()->id)
            // その投稿者($post->user->id)がAuth userにフォローされていたら（post視点で）
            {
                $home_posts[] = $post;
            }
        }

        return $home_posts;
    }

    // Auth Userがフォローしていないユーザーのみを取得する。
    #Get the users that the Auth user is not folowing.
    private function getSuggestedUsers()
    {
        // まずは、自分（Auth User）を除く、全ユーザーを取得する。
        $all_users = $this->user->all()->except(Auth::user()->id);
        $suggested_users = []; //初期化により、nullを防ぐ。

        foreach($all_users as $user) {
            // そのユーザーがフォローされてなければ、$suggested_usersに入れていく。
            if(!$user->isFollowed()) {
                $suggested_users[] = $user;
            }
        }

        // $suggested_usersの配列からmaxで３つだけ取得したい。
        return array_slice($suggested_users, 0, 3);
        // array_slice(x,y,z);
        // x - array name
        // y - offset/starting index
        // z - length/how many
    }

    public function search(Request $request)
    {
        $users = $this->user->where('name', 'like', '%'.$request->search.'%')->get();

        return view('users.search')
                ->with('users', $users)
                ->with('search', $request->search);
    }
}
