@extends('layouts.app')

@section('title', 'Show Post')

@section('content')
    <style>
        .col-4{
            overflow-y: scroll;
        }

        .card-body{
            position: absolute;
            top: 65px;
        }
    </style>
    <div class="row border shadow">
        {{-- 左側 2/3 投稿された画像を表示 --}}
        <div class="col p-0 border-end">
            <img src="{{ $post->image }}" alt="post id {{ $post->id }}" class="w-100">
        </div>

        {{-- 右側 1/3 --}}
        <div class="col-4 px-0 bg-white">
            <div class="card border-0">
                {{-- カードヘッダー --}}
                {{-- 以下、title.bladeとほぼ同じ --}}
                <div class="card-header bg-white py-3">
                    <div class="row align-items-center">
                        {{-- アバター（あれば） --}}
                        <div class="col-auto">
                            <a href="{{ route('profile.show', $post->user->id) }}">
                                @if ($post->user->avatar)
                                    <img src="{{ $post->user->avatar }}" alt="{{ $post->user->name }}" class="rounded-circle avatar-sm">
                                @else
                                    <i class="fa-solid fa-circle-user text-secondary icon-sm"></i>
                                @endif
                            </a>
                        </div>
                        {{-- 投稿者の名前 --}}
                        <div class="col ps-0">
                            <a href="{{ route('profile.show', $post->user->id) }}" class="text-decoration-none text-dark">{{ $post->user->name }}</a>
                        </div>

                        {{-- カードヘッダーの右端 --}}
                        <div class="col-auto">
                            {{-- もしあなたがその投稿のオーナーだったら... --}}
                            {{-- if you are the owner of the post, you can edit or delete the post --}}
                            @if (Auth::user()->id === $post->user->id)
                                <div class="dropdown">
                                    <button class="btn btn-sm shadow-none" data-bs-toggle="dropdown">
                                        <i class="fa-solid fa-ellipsis"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        {{-- 投稿の編集ページに向かうボタン --}}
                                        <a href="{{ route('post.edit', $post->id)}}" class="dropdown-item">
                                            <i class="fa-regular fa-pen-to-square"></i> Edit
                                        </a>
                                        {{-- 削除のモーダルを開くボタン --}}
                                        <button class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#delete-post-{{ $post->id }}">
                                            <i class="fa-regular fa-trash-can"></i> Delete
                                        </button>
                                    </div>
                                    {{-- Include modal here --}}
                                    {{-- モーダルのインクルード --}}
                                    @include('users.posts.contents.modals.delete')
                                </div>
                            @else
                                {{-- もしログイン者が投稿者じゃなければ、フォローかアンフォローのボタンを表示する --}}
                                {{-- If you are not the owner of the post, show a follow/unfollow button. --}}
                                @if ($post->user->isFollowed())
                                {{-- もしこの投稿のユーザーがすでに自分（Auth user）にフォローされていたら --}}
                                    {{-- フォローをはずす（アンフォロー）のアクションへ --}}
                                    <form action="{{ route('follow.destroy', $post->user->id) }}" method="post" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-secondary btn-sm fw-bold">Following</button>
                                    </form>  
                                @else
                                {{-- まだ自分(Auth user)がこの投稿者（$post->user->id）をフォローしていなければ --}}
                                    {{-- フォローするアクションへ --}}
                                    <form action="{{ route('follow.store', $post->user->id )}}" method="post">
                                        @csrf
                                        <button type="submit" class="btn btn-primary btn-sm fw-bold">Follow</button>
                                    </form>  
                                @endif 
                            @endif
                        </div>
                    </div>
                </div>
                
                {{-- カードボディ --}}
                {{-- 以下、body.bladeとほぼ同じ --}}
                <div class="card-body w-100">
                        {{-- heart button + no. of likes + categories --}}
                        <div class="row align-items-center">
                            {{--ハートボタン（LIKE用） --}}
                            <div class="col-auto">
                                <form action="{{ route('like.store', $post->id )}}" method="post">
                                    @csrf
                                    <button type="submit" class="btn btn-sm shadow-none p-0"><i class="fa-regular fa-heart"></i></button>
                                </form>
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

                    {{-- ownwer + description --}}
                    {{-- 投稿者の名前 --}}
                    <a href="{{ route('profile.show', $post->user->id) }}" class="text-decoration-none text-dark fw-bold">{{ $post->user->name }}</a>
                    &nbsp;
                    {{--投稿内容（description） --}}
                    <p class="d-inline fw-light">{{ $post->description }}</p>
                    {{-- 投稿日 --}}
                    <p class="text-uppercase text-muted xsmall">{{ date('M d, Y', strtotime($post->created_at)) }}</p>

                    {{-- comments コメント　--}}
                    {{-- comments.bladeと似ているけど、変更点もあるので、@includeせずに直接記入 --}}
                    {{-- コメントを追加するフォーム --}}
                    <div class="mt-4">
                        <form action="{{ route('comment.store', $post->id)}}" method="post">
                            @csrf
                            <div class="input-group">
                                <textarea name="comment_body{{ $post->id }}" rows="1" class="form-control formcontrol-sm" placeholder="Add a comment...">{{ old('comment_body' . $post->id )}}</textarea>
                                <button type="submit" class="btn btn-outline-secondary btn-sm">Post</button>
                            </div>
                            {{-- Error --}}
                            @error('comment_body' . $post->id)
                                <div class="text-danger small">{{ $message}}</div>
                            @enderror
                        </form>

                        {{-- 既存のコメントの一覧表示 --}}
                        {{-- Show all the comments here --}}
                        @if ($post->comments->isNotEmpty())
                            <ul class="list-group mt-2">
                                @foreach ($post->comments as $comment)
                                    <li class="list-group-item border-0 p-0 mb-2">
                                        <a href="{{ route('profile.show', $comment->user->id) }}" class="text-decoration-none text-dark fq-bold">{{ $comment->user->name}}</a>
                                        &nbsp;
                                        <p class="d-inline fw-light">{{ $comment->body }}</p>

                                        <form action="{{ route('comment.destroy', $comment->id)}}" method="post">
                                            @csrf
                                            @method('DELETE')
                                            <span class="text-uppercase text-muted xsmall">{{ date('M d, Y', strtotime($comment->created_at))}}</span>

                                            {{-- コメントの削除 --}}
                                            {{-- if the auth user is the owner of the comment, show a delete button --}}
                                            @if (Auth::user()->id === $comment->user->id)
                                                &middot;
                                                <button class="border-0 bg-transparent text-danger p-0 xsmall" type="submit">Delete</button>
                                            @endif
                                        </form>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection