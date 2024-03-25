{{-- モーダル！（ユーザーを unhide/hide するページ） --}}
{{-- admin/posts/index.bladeに、@iuncludeされている！ --}}

@if($post->trashed())  {{-- 現在のステータスは Hidden --}}
    {{-- アンハイド　Unhide --}}
    <div class="modal fade" id="unhide-post-{{ $post->id }}">
        <div class="modal-dialog">
            <div class="modal-content border-primary">
                {{-- Modal Header --}}
                <div class="modal-header border-primary">
                    <h5 class="modal-title text-primary">
                        <i class="fa-solid fa-eye"></i> Unhide Post
                    </h5>
                </div>
                {{-- Modal Body --}}
                <div class="modal-body">
                    <p>Are you sure you want to unhide this post?</p>
                     <div class="mt-3">
                        <img src="{{ $post->image}}" alt="post id {{ $post->id }}" class="image-lg">
                        <p class="mt-1 text-muted">{{ $post->description }}</p>
                     </div>
                </div>
                {{-- Modal Footer --}}
                <div class="modal-footer border-0">
                    <form action="{{ route('admin.posts.unhide', $post->id )}}" method="post">
                        @csrf
                        @method('PATCH')

                        <button type="button" class="btn btn-outline-primary btn-sm" data-bs-dismiss="modal">Cancel</button>
                        <button class="btn btn-primary btn-sm">Unhide</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@else  {{-- 現在のステータスは Visible --}}
    {{-- ハイド Hide --}}
    <div class="modal fade" id="hide-post-{{ $post->id }}">
        <div class="modal-dialog">
            <div class="modal-content border-danger">
                {{-- Modal Header --}}
                <div class="modal-header border-danger">
                    <h5 class="modal-title text-danger">
                        <i class="fa-solid fa-eye-slash"></i> Hide Post
                    </h5>
                </div>
                {{-- Modal Body --}}
                <div class="modal-body">
                    <p>Are you sure you want to hide this post?</p>
                     <div class="mt-3">
                        <img src="{{ $post->image}}" alt="post id {{ $post->id }}" class="image-lg">
                        <p class="mt-1 text-muted">{{ $post->description }}</p>
                     </div>
                </div>
                {{-- Modal Footer --}}
                <div class="modal-footer border-0">
                    <form action="{{ route('admin.posts.hide', $post->id )}}" method="post">
                        @csrf
                        @method('DELETE')

                        <button type="button" class="btn btn-outline-danger btn-sm" data-bs-dismiss="modal">Cancel</button>
                        <button class="btn btn-danger btn-sm">Hide</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endif