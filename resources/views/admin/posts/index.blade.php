@extends('layouts.app')

@section('title', 'Admin: Posts')

@section('content')
    <table class="table table-hover align-middle bg-white border text-secondary">
        <thead class="table-primary text-secondary">
            <tr>
                <th></th>
                <th></th>
                <th>CATEGORY</th>
                <th>OWNER</th>
                <th>CREATED AT</th>
                <th>STATUS</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($all_posts as $post)
                <tr>
                    {{-- ポストのID --}}
                    <td class="text-end">{{ $post->id }}</td>
                    {{-- 投稿画像(リンクで投稿詳細へ飛ぶ) --}}
                    <td>
                        <a href="{{ route('post.show', $post->id)}}">
                            <img src="{{ $post->image }}" alt="post id {{ $post->id }}" class="d-block mx-auto image-lg">
                        </a>
                    </td>
                    {{-- 所属のカテゴリ --}}
                    <td>
                        @forelse ($post->categoryPost as $category_post)
                        {{-- Postモデルに入れた、categoryPost()で移動 --}}
                            <div class="badge bg-secondary bg-opacity-50">
                                {{ $category_post->category->name }}
                                {{-- CategoryPostモデルに入れた、category()で移動 --}}
                            </div> 
                        @empty {{-- カテゴリ削除でuncategolizedなら黒バッジで表示！--}}
                            <div class="badge bg-dark text-wrap">Uncategolized</div>
                        @endforelse
                    </td>
                    {{-- 投稿者（リンクで投稿者のプロフへ飛べる） --}}
                    <td>
                        <a href="{{ route('profile.show', $post->user->id) }}" class="text-dark text-decoration-none">{{ $post->user->name }}</a>
                    </td>
                    {{-- 作成日 --}}
                    <td>{{ $post->created_at }}</td>
                    {{-- ステータス（状態） "Hidden" か "Visible"--}}
                    <td>
                        @if ($post->trashed())
                            <i class="fa-solid fa-circle-minus text-secondary"></i> &nbsp; Hidden
                        @else
                            <i class="fa-solid fa-circle text-primary"></i> &nbsp; Visible
                        @endif
                    </td>
                    {{-- ステータスを"unhide"か"hide"するボタンを表示するためのドロップダウン --}}
                    {{-- hideとは、ソフトデリート（レコードとしては残す）を指す！ --}}
                    <td>
                        {{-- @if (Auth::user()->id !== $post->user->id) --}}
                            <div class="dropdown">
                                <button class="btn btn-sm" data-bs-toggle="dropdown">
                                    <i class="fa-solid fa-ellipsis"></i>
                                </button>

                                <div class="dropdown-menu">
                                    @if ($post->trashed()) {{-- unhide --}}
                                        <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#unhide-post-{{ $post->id }}">
                                            <i class="fa-solid fa-eye"></i> Unhide Post {{ $post->id }}
                                        </button>
                                    @else  {{-- hide --}}
                                        <button class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#hide-post-{{ $post->id }}">
                                            <i class="fa-solid fa-eye-slash"></i> Hide Post {{ $post->id }}
                                        </button>
                                    @endif
                                </div>
                                {{-- モーダルをインクルード　Include the modal here --}}
                                @include('admin.posts.modal.status')
                            </div>
                        {{-- @endif --}}
                    </td>
                </tr>
            @empty  {{-- 投稿がまだ１つもなければ... --}}
                <tr>
                    <td colspan="7" class="lead text-muted text-center">No posts found</td>
                </tr> 
            @endforelse
        </tbody>
    </table>

    {{-- ページ割り付け　Pagination --}}
    <div class="d-flex justify-content-center">
        {{ $all_posts->links() }}
    </div>
@endsection