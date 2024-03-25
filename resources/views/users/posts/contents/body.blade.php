{{-- Home.bladeに、@iuncludeされている！ --}}

{{-- Clickable image 表示する画像は、クリックできるようにして、showページへ飛ぶ--}}
<div class="container p-0">
    <a href="{{ route('post.show', $post->id)}}">
        <img src="{{ $post->image }}" alt="post id {{ $post->id }}" class="w-100">
    </a>
</div>
<div class="card-body">
    {{-- heart button + No. of likes + categories --}}
    <div class="row align-items-center">
        {{--ハートボタン（LIKE用） --}}
        <div class="col-auto">
            @if ($post->isLiked())
                {{-- もしこの投稿がAuth UserにすでにLIKEされていたら --}}
                <form action="{{ route('like.destroy', $post->id) }}" method="post">
                    @csrf
                    @method('DELETE')

                    <button type="submit" class="btn btn-sm shadow-none p-0"><i class="fa-solid fa-heart text-danger"></i></button>
                </form>
            @else
                {{-- まだこの投稿がAuth UserにLIKEされていなければ --}}
                <form action="{{ route('like.store', $post->id )}}" method="post">
                    @csrf
                    <button type="submit" class="btn btn-sm shadow-none p-0"><i class="fa-regular fa-heart"></i></button>
                </form>
            @endif
        </div>
        {{-- LIKEの数をカウントして表示 --}}
        <div class="col-auto px-0">
            <span>{{ $post->likes->count() }}</span>
        </div>
        {{-- その投稿のカテゴリーを、バッジの形式で表示 --}}
        <div class="col text-end">
            @forelse ($post->categoryPost as $category_post)
            {{-- Postモデルに入れた、categoryPost()で移動 --}}
                <div class="badge bg-secondary bg-opacity-50">
                    {{ $category_post->category->name }}
                    {{-- CategoryPostモデルに入れた、category()で移動 --}}
                </div> 
            @empty {{-- カテゴリ削除でuncategolizedなら黒バッジで表示！--}}
                <div class="badge bg-dark text-wrap">Uncategolized</div>
            @endforelse
        </div>
    </div>

    {{-- owner + description --}}
    {{-- 投稿者の名前 --}}
    <a href="{{ route('profile.show', $post->user->id) }}" class="text-decoration-none text-dark fw-bold">{{ $post->user->name }}</a>
    &nbsp;
    {{-- 投稿内容（description） --}}
    <p class="d-inline fw-light">{{ $post->description}}</p>
    {{-- 投稿日 --}}
    <p class="text-uppercase text-muted xsmall">{{ date('M d, Y', strtotime($post->created_at)) }}</p>

    {{-- コメント表示画面のインクルード　Include comments here--}}
    @include('users.posts.contents.comments')
</div>