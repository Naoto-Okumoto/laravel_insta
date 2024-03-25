{{-- モーダル！（カテゴリを edit/delete するページ） --}}
{{-- admin/categories/index.bladeに、@iuncludeされている！ --}}


    {{-- 編集　Edit --}}
    <div class="modal fade" id="edit-category-{{ $category->id }}">
        <div class="modal-dialog">
            <div class="modal-content border-warning">
                <form action="{{ route('admin.categories.update', $category->id )}}" method="post">
                    @csrf
                    @method('PATCH')

                    {{-- Modal Header --}}
                    <div class="modal-header border-warning">
                        <h5 class="modal-title">
                            <i class="fa-solid fa-pen-to-square"></i> Edit Category
                        </h5>
                    </div>
                    {{-- Modal Body --}}
                    <div class="modal-body">
                        <div class="mt-3">
                            <input type="text" name="name" value="{{ $category->name }}" class="form-control">
                        </div>
                    </div>
                    {{-- Modal Footer --}}
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-outline-warning btn-sm" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-warning btn-sm">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- 削除　Delete --}}
    <div class="modal fade" id="delete-category-{{ $category->id }}">
        <div class="modal-dialog">
            <div class="modal-content border-danger">
                {{-- Modal Header --}}
                <div class="modal-header border-danger">
                    <h5 class="modal-title">
                        <i class="fa-solid fa-trash-can"></i> Delete category
                    </h5>
                </div>
                {{-- Modal Body --}}
                <div class="modal-body">
                    <p>Are you sure you want to delete <span class="fw-bold">{{ $category->name }}</span> category?</p>
                    <p class="fw-light">This action will affect all the posts under this category. Posts without a category will fall under Uncategorized.</p>
                </div>
                {{-- Modal Footer --}}
                <div class="modal-footer border-0">
                    <form action="{{ route('admin.categories.destroy', $category->id )}}" method="post">
                        @csrf
                        @method('DELETE')

                        <button type="button" class="btn btn-outline-danger btn-sm" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
