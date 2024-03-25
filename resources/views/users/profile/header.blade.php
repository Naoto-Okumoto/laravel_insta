{{--profile/show.bladeに、@iuncludeされている！ --}}
{{--profile/followers.bladeにも、@iuncludeされている！ --}}
{{--profile/following.bladeにも、@iuncludeされている！ --}}
{{-- プロフィール画面のヘッダー --}}

<div class="row">
    {{-- 左側 1/3　Left Side --}}
    <div class="col-4">
        {{-- アバター（あれば） --}}
        @if ($user->avatar) 
            <img src="{{ $user->avatar }}" alt="{{ $user->name }}" class="img-thumbnail rounded-circle d-block mx-auto avatar-lg">
        @else
            <i class="fa-solid fa-circle-user text-secondary d-block text-center icon-lg"></i>   
        @endif
    </div>
    {{-- 右側　2/3  Right Side --}}
    <div class="col-8">
        <div class="row mb-3">
            <div class="col-auto">
                <h2 class="display-6 mb-0">{{ $user->name}}</h2>
            </div>
            <div class="col-auto p-2">
                {{-- もしユーザー自身なら編集ボタンを、そうでなければFollowing/Followを表示する --}}
                @if (Auth::user()->id === $user->id)
                    <a href="{{ route('profile.edit') }}" class="btn btn-outline-secondary btn-sm fw-bold">Edit Profile</a>
                @else
                    @if ($user->isFollowed())
                    {{-- もしこのユーザーがすでに自分（Auth user）にフォローされていたら --}}
                        {{-- フォローをはずす（アンフォロー）のアクションへ --}}
                        <form action="{{ route('follow.destroy', $user->id) }}" method="post" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-outline-secondary btn-sm fw-bold">Following</button>
                        </form>  
                    @else
                    {{-- まだ自分(Auth user)がこの$userをフォローしていなければ --}}
                        {{-- フォローするアクションへ --}}
                        <form action="{{ route('follow.store', $user->id )}}" method="post">
                            @csrf
                            <button class="btn btn-primary btn-sm fw-bold">Follow</button>
                        </form>  
                    @endif 
                @endif
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-auto">
                {{-- そのユーザーの投稿の数 --}}
                <a href="{{ route('profile.show', $user->id) }}" class="text-decoration-none text-dark">
                    <strong>{{ $user->posts->count() }}</strong>
                    {{ $user->posts->count() == 1 ? " post" : " posts"}} 
                </a>
            </div>
            <div class="col-auto">
                {{-- そのユーザーのフォロワー数、とフォロワーを表示するページへのリンク --}}
                <a href="{{ route('profile.followers', $user->id) }}" class="text-decoration-none text-dark">
                    <strong>{{ $user->followers->count() }}</strong>
                    {{ $user->followers->count() == 1 ? " follower" : " followers"}}
                </a>
            </div>
            <div class="col-auto">
                {{-- そのユーザーがフォローしている数,とフォローしているユーザーを表示するページへのリンク --}}
                <a href="{{ route('profile.following', $user->id) }}" class="text-decoration-none text-dark">
                    <strong>{{ $user->following->count() }}</strong> following
                </a>
            </div>
        </div>
        {{-- そのユーザーのイントロダクションを表示 --}}
        <p class="fw-bold">{{ $user->introduction }}</p>
    </div>
</div>