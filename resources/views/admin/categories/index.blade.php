@extends('layouts.app')

@section('title', 'Admin: Categories')

@section('content')
<!-- Form 新規のカテゴリを追加するフォーム　-->
<form action="{{ route('admin.categories.store')}}" method="post">
    @csrf

    <div class="row gx-2 mb-4">
        <div class="col-4">
            <input type="text" name="name" class="form-control" placeholder="Add a category..." value="{{ old('name')}}" autofocus>
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-plus"></i> Add
            </button>
        </div>
        {{-- Error --}}
        @error('name')
        <p class="text-danger small">{{ $message }}</p>
        @enderror
    </div>
</form>
    

{{-- 登録済みのカテゴリを一覧表示するテーブル --}}
<div class="row">
    <div class="col-7">
        <table class="table table-hover align-middle bg-white border text-secondary table-sm text-center">
            <thead class="table-warning text-secondary">
                <tr>
                    <th>#</th>
                    <th>NAME</th>
                    <th>COUNT</th>
                    <th>LAST UPDATED</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($all_categories as $category)
                    <tr>
                        {{-- カテゴリのID --}}
                        <td>{{ $category->id }}</td>
                        {{-- カテゴリ名--}}
                        <td class="text-dark">{{ $category->name }}</td>
                        {{-- そのカテゴリが何個のポストに属しているか --}}
                        <td>{{ $category->categoryPost->count() }}</td>
                        {{-- 最終更新日 --}}
                        <td>{{ $category->updated_at }}</td>
                        {{-- 編集モーダルへと削除モーダルへのボタン--}}
                        <td>
                            {{-- Edit Button モーダル開く--}}
                            <button class="btn btn-outline-warning btn-sm me-2" data-bs-toggle="modal" data-bs-target="#edit-category-{{ $category->id }}" title="Edit">
                                <i class="fa-solid fa-pen"></i>
                            </button>
                            {{-- Delete Button モーダル開く--}}
                            <button class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#delete-category-{{ $category->id }}" title="Delete">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </td>
                    </tr>
                    {{-- モーダルのインクルード　Include modal here --}}
                    @include('admin.categories.modal.action')
                @empty  {{-- カテゴリがまだ１つもなければ... --}}
                    <tr>
                        <td colspan="5" class="lead text-muted text-center">No categories found.</td>
                    </tr> 
                @endforelse
                {{-- Uncategorized である投稿の数を表示 --}}
                <tr>
                    <td></td>
                    <td class="text-dark">
                        Uncategorized
                        <p class="xsmall text-secondary my-0">Hidden posts are not included</p>
                    </td>
                    <td>{{ $uncategorized_count }}</td>
                    <td></td>
                    <td></td>
                </tr>
            </tbody>
        </table>

        {{-- ページ割り付け　Pagination --}}
        <div class="d-flex justify-content-center">
            {{ $all_categories->links() }}
        </div>

    </div>
</div>
    



@endsection