@extends('layouts.app')

@section('title', 'Folllowing')

@section('content')
    @include('users.profile.header')

    <div style="margin-top: 100px;">
        {{-- もしこのユーザーが誰かを１人でもフォロワーしていたら --}}
        @if ($user->following->isNotEmpty())
            <div class="row justify-content-center">
                <div class="col-4">
                    <h3 class="text-muted text-center">Following</h3>

                    {{-- フォローしているユーザー（アバターと名前あり）を表示 --}}
                    @foreach ($user->following as $following)
                        <div class="row align-items-center mt-3">
                            {{-- フォローしているユーザーのアバターを表示（なければアイコン） --}}
                            <div class="col-auto">
                                <a href="{{ route('profile.show', $following->following->id)}}">
                                    @if ($following->following->avatar)
                                        <img src="{{ $following->following->avatar }}" alt="{{ $following->following->name }}" class="rounded-circle avatar-sm">
                                    @else
                                        <i class="fa-solid fa-circle-user text-secondary icon-sm"></i>
                                    @endif
                                </a>
                            </div>
                            {{-- フォローしているユーザーの名前を表示 --}}
                            <div class="col ps-0 text-truncate">
                                <a href="{{ route('profile.show', $following->following->id) }}" class="text-decoration-none text-dark fw-bold">{{ $following->following->name }}</a>
                            </div>
                            {{-- 条件によって、following / follow を表示 --}}
                            {{-- ただし、followを表示する条件（@else）は存在しないので、常にfollowing表示！ --}}
                            {{-- でも、先生によると両方の@ifともセキュリティ上、残すべきとのこと！ --}}
                            <div class="col-auto text-end">
                                @if ($following->following->id != Auth::user()->id)
                                    @if ($following->following->isFollowed())
                                        <form action="{{ route('follow.destroy', $following->following->id) }}" method="post">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="border-0 bg-transparent p-0 text-secondary">Following</button>
                                        </form>
                                    @else
                                        <form action="{{ route('follow.store', $following->following->id) }}" method="post">
                                            @csrf
                                            <button type="submit" class="border-0 bg-transparent p-0 text-primary btn-sm">Follow</button>
                                        </form>
                                    @endif
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        {{-- もしこのユーザーが誰１人もまだフォローしていなければ --}}
        @else
            <h3 class="text-muted text-center">No Following Yet.</h3>
        @endif
    </div>

@endsection