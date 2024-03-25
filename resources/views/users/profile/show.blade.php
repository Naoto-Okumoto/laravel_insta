@extends('layouts.app')

@section('title', $user->name)

@section('content')
    {{-- プロフィールのヘッダーをインクルード --}}
    @include('users.profile.header')

    {{-- そのユーザーの既存の投稿をグリッドイメージですべて表示する --}}
    {{-- Show all the posts of the user here --}}
    <div style="margin-top: 10px;">
        @if($user->posts->isNotEmpty())
            <div class="row">
                @foreach ($user->posts as $post)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <a href="{{ route('post.show', $post->id)}}">
                            <img src="{{ $post->image }}" alt="post id {{ $post->id }}" class="grid-img">
                        </a>
                    </div>
                @endforeach
            </div>
        @else
            <h3 class="text-muted text-center">No Posts Yet.</h3>
        @endif
    </div>
@endsection