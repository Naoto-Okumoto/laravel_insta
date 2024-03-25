@extends('layouts.app')

@section('title', 'Suggested Users')

@section('content')
    @if ($all_suggested_users)
<div class="row justify-content-center">
    <div class="col-4">
        <div class="row justify-content-center mb-3">
            <div class="col">
                <h3 class="text-muted text-center">All Suggested Users For You</h3>
            </div>
        </div>

        @foreach ($all_suggested_users as $user)
            <div class="row align-items-center mb-3">
                {{-- suggested_userのアバター表示（なければアイコン） --}}
                <div class="col-auto">
                    <a href="{{ route('profile.show', $user->id) }}">
                        @if ($user->avatar)
                            <img src="{{ $user->avatar }}" alt="{{ $user->name }}" class="rounded-circle avatar-sm">
                        @else
                            <i class="fa-solid fa-circle-user text-secondary icon-sm"></i>
                        @endif
                    </a>
                </div>
                {{-- suggested_userの名前を表示 --}}
                <div class="col ps-0 text-truncate">
                    <a href="{{ route('profile.show', $user->id) }}" class="text-decoration-none text-dark fw-bold">{{ $user->name }}
                    </a>
                </div>
                {{-- suggested_userをフォローするボタン --}}
                {{-- ここの表示されるユーザーはみんなまだフォローしていない人なので、followボタンだけでよい --}}
                <div class="col-auto">
                    <form action="{{ route('follow.store', $user->id) }}" method="post">
                        @csrf
                        <button type="submit" class="border-0 bg-transparent p-0 text-primary btn-sm">Follow</button>
                    </form>
                </div>
            </div>
        @endforeach
    @else
        <h3 class="text-muted text-center">No Suggested Users For You.</h3>
    @endif

    </div>
</div>

@endsection