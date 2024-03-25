<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
   private $post;
   private $category;
   
   public function __construct(Post $post, Category $category){
        $this->post     = $post;
        $this->category = $category;
   }

   public function create()
   {
    $all_categories = $this->category->all();
    return view('users.posts.create')
            ->with('all_categories', $all_categories);
   }

   public function store(Request $request)
    {
        #1. Validate all form data
        $request->validate([
            'category'     => 'required|array|between:1,3',
            // カテゴリは複数選択可（３つまで）でarray(配列)で引き渡される。
            'description'  => 'required|min:1|max:1000',
            'image'        => 'required|mimes:jpeg,jpg,png,gif|max:1048'
            // mime = multipurpose internet mail extentions  (マイム)
        ]);

        #2. Save the post
        $this->post->user_id = Auth::user()->id;
        //owner of the post = id of the logged in user
        //このuser_idカラムの値は入力で引き渡されるものではないから、別表記。
        //これのために、use宣言が必要！追記。
        // ここの下のイメージの表記は慎重に書く。この書き方はテンプレ。
        $this->post->image = 'data:image/' . $request->image->extension() . ';base64,' . base64_encode(file_get_contents($request->image));
        $this->post->description = $request->description;
        $this->post->save();
        //ここで一旦、postに関する更新は終了。つぎは、category_postの更新に移る。だから、save()はここでよい！

        #3. Save the categories to the category_post table
        //カテゴリは複数選択なので、配列でまわってきている。
        //それをひとつずつ取り出して、category_idのカラムの方の値にひとつずつ加えていく。
        //CategoryPostモデルで$fillableにしてるから、この形式で挿入可能。
        foreach($request->category as $category_id){
            $category_post[] = ['category_id' => $category_id];
        }

        $this->post->categoryPost()->createMany($category_post);
        //categoryPost()メソッドでcategory_postテーブルに移動して、createMany()メソッドの引数に配列を渡して、挿入。
        //post_idは固定で、category_idのカラムに１つずつ違うものが入っていく。

        #4. Go back to homepage
        return redirect()->route('index');
    }

    public function show($id)
    {
        $post = $this->post->findOrFail($id);

        return view('users.posts.show')
                ->with('post', $post);
    }

    public function edit($id)
    {
        $post = $this->post->findOrFail($id);

        // セキュリティ Secutiry
        // そもそも投稿者のオーナーじゃないとeditボタンが表示されないが、悪い人が直接URLをいじる可能性もあるから！
        // If the Auth user is not the owner of the post, redirect to homepage
        if($post->user->id != Auth::user()->id){
            // != not the same, == compare
            return redirect()->route('index');
        }

        $all_categories = $this->category->all();
        // カテゴリテーブルのすべてを取得する。
        // 静的メソッドなら、Category::all();
        // これはedit画面で、選択できるすべてのカテゴリを表示するため。（create.bladeと同じ）

        
        // こちらは、ポストごとの、選択されたカテゴリーを取得する。
        // すでに選択済みのカテゴリをデフォルトで表示するため。
        #Get all the category IDs of this post. Save in an array
        $selected_categories = [];  //Initilize(初期化)
        foreach($post->categoryPost as $category_post) {
            $selected_categories[] = $category_post->category_id;
        }

        return view('users.posts.edit')
                ->with('post', $post)
                ->with('all_categories', $all_categories)
                ->with('selected_categories', $selected_categories);
    }

    public function update(Request $request, $id)
    {
        #1. Validate the request　バリデーション
        $request->validate([
            'category'     => 'required|array|between:1,3',
            'description'  => 'required|min:1|max:1000',
            'image'        => 'mimes:jpeg,jpg,png,gif|max:1048'
            //imageは更新しないかもしれないので、requiredは指定しない！
        ]);
        
        #2. Update the post
        $post        = $this->post->findOrFail($id);
        //idで探してきたpost（postsテーブル内のとある行）に対して、中身を上書きしていく。ここがstore（$this->postに挿入していく）と違う

        // 画像は更新しない可能性もあるので、ifに入れる。
        if ($request->image) {
            $post->image = 'data:image/' . $request->image->extension() . ';base64,' . base64_encode(file_get_contents($request->image));
        }
        $post->description = $request->description;

        $post->save();
        //ここで一旦、postに関する更新は終了。つぎは、category_postの更新に移る。だから、save()はここでよい！

        //カテゴリーは、まず以前に選択済みのカテゴリーを消してから再登録！
        #3. Delete all the records from category_post related to this post
        $post->categoryPost()->delete();

        #4. save the categories to the category_post table
        //カテゴリは複数選択なので、配列でまわってきている。
        //それをひとつずつ取り出して、category_idのカラムの方の値にひとつずつ新たに挿入していく。
        //ここのプロセスはstorre()と全く同じ。
        //CategoryPostモデルで$fillableにしてるから、この形式で挿入可能。
        foreach($request->category as $category_id){
            $category_post[] = ['category_id' => $category_id];
        }

        $post->categoryPost()->createMany($category_post);
        //categoryPost()メソッドでcategory_postテーブルに移動して、createMany()メソッドの引数に配列を渡して、挿入。
        //post_idは固定で、category_idのカラムに１つずつ違うものが入っていく。

        #5. Redirect to Show Post page (to confirm the update)
        return redirect()->route('post.show', $id);
    }

    public function destroy($id)
    {
        $post  = $this->post->findOrFail($id);
        
        // このコントローラでuseしてるPostモデルでsoftDeletesモデルを使う宣言をしてしまっているので、こちらはノーマルのデリートではなく、forceDelete()メソッドに変える必要あり！
        $post->forceDelete();
        // 今回はdeleteを使う。deleteは引数を持たず、一個しか削除できない。
        // destroyは複数の引数を持って、複数を一気に削除できる。

        return redirect()->route('index');
    }

}
