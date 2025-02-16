@extends('adminlte::page')

@section('content')
    <h1>Chỉnh sửa danh mục</h1>

    <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">Tên danh mục</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $category->name }}" required>
        </div>

        <div class="form-group">
            <label for="sku">SKU</label>
            <input type="text" class="form-control" id="sku" name="sku" value="{{ $category->sku }}">
        </div>

        <div class="form-group">
            <label for="parent_category_id">Danh mục cha</label>
            <select class="form-control" id="parent_category_id" name="parent_category_id">
                <option value="">Không có</option>
                @foreach(\App\Models\Category::where('id', '!=', $category->id)->get() as $parent)
                    <option value="{{ $parent->id }}" {{ $category->parent_category_id == $parent->id ? 'selected' : '' }}>
                        {{ $parent->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="image">Hình ảnh</label>
            <input type="file" class="form-control" id="image" name="image">
            @if($category->image_url)
                <p><img src="{{ asset('storage/' . $category->image_url) }}" width="100"></p>
            @endif
        </div>

        <button type="submit" class="btn btn-success">Cập nhật</button>
    </form>
@endsection