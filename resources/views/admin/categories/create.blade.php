@extends('adminlte::page')

@section('content')
<h1>Thêm Danh mục</h1>

<form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">


    @csrf
    <div class="form-group">
        <label for="name">Tên danh mục</label>
        <input type="text" class="form-control" id="name" name="name" required>
    </div>
    <div class="form-group">
        <label for="sku">SKU</label>
        <input type="text" class="form-control" id="sku" name="sku" required>
    </div>
    <div class="form-group">
        <label for="parent_category_id">Danh mục cha</label>
        <select class="form-control" id="parent_category_id" name="parent_category_id">
            <option value="">Không có</option>
            @foreach($parentCategories as $parent)
            <option value="{{ $parent->id }}">{{ $parent->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label for="image">Hình ảnh</label>
        <input type="file" class="form-control" id="image" name="image">
    </div>
    <button type="submit" class="btn btn-primary">Lưu</button>
</form>
@endsection