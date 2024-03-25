@extends('layouts.app')

@section('title', 'Edit Post')

@section('content')
    <form action="{{ route('post.update', $post->id)}}" method="post" enctype="multipart/form-data">
        @csrf
        @method('PATCH')

        {{-- Category --}}
        <div class="mb-3">
            <label for="category" class="form-label d-block fw-bold">Category <span class="text-muted fw-normal">(up tp 3)</span></label>

            @foreach ($all_categories as $category)
                <div class="form-check form-check-inline">
                    {{-- is_array()関数は、第1引数がvalue, 第2引数が配列（array）--}}
                    {{-- TRUEの場合には、inputにcheckedをもたせる。 --}}
                    @if (in_array($category->id, $selected_categories))
                        {{-- チェックボックスなので、nameのところ注意！ --}}
                        <input type="checkbox" name="category[]" id="{{ $category->name }}" value="{{ $category->id }}" class="form-check-input" checked>
                    @else
                        {{-- チェックボックスなので、nameのところ注意！ --}}
                        <input type="checkbox" name="category[]" id="{{ $category->name }}" value="{{ $category->id }}" class="form-check-input">
                    @endif
                    <label for="{{ $category->name }}" class="form-check-label">{{ $category->name }}</label>
                </div>
            @endforeach
            {{-- Error --}}
            @error('category')
                <p class="text-danger small">{{ $message }}</p>
            @enderror
        </div>

        {{-- Description --}}
        <div class="mb-3">
            <label for="description" class="form-label fw-bold">Description</label>
            <textarea name="description" id="description" rows="3" class="form-control" placeholder="What's on your mind">{{ old('description', $post->description)}}</textarea>
            {{-- Error --}}
            @error('description')
                <p class="text-danger small">{{ $message }}</p>
            @enderror
        </div>

        {{-- Image --}}
        <div class="mb-4">
            <div class="col-6">
                <label for="image" class="form-label fw-bold">Image</label>
                <img src="{{ $post->image }}" alt="post {{ $post->image }}" class="img-thumbnail w-100">
                <input type="file" name="image" id="image" class="form-control mt-1" aria-describedby="image-info">
                <div class="form-text" id="image-info">
                    The acceptable formats are jpeg, jpg, png, gif only. <br>
                    Max file size is 1048kb.
                </div>
                {{-- Error --}}
                @error('image')
                    <p class="text-danger small">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <button type="submit" class="btn btn-warning px-5">Save</button>
    </form>

@endsection