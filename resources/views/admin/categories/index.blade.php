@extends('adminlte::page')

@section('content')
<h1>Danh sách Danh mục</h1>
<a href="{{ route('admin.categories.create') }}" class="btn btn-primary">Thêm mới</a>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<table class="table">
    <thead>
        <tr>
            <th>Tên danh mục</th>
            <th>SKU</th>
            <th>Danh mục cha</th>
            <th>Trạng thái</th>
            <th>Hình ảnh</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        @foreach($categories as $category)
        <tr>
            <td>{{ $category->name }}</td>
            <td>{{ $category->sku }}</td>
            <td>{{ $category->parentCategory->name ?? 'Không có' }}</td>
            <td>{{ $category->is_active ? 'Hoạt động' : 'Ẩn' }}</td>
            <td>
                @if($category->image_url)
                <img src="{{ asset('storage/' . $category->image_url) }}" width="50" height="50" />
                @endif
            </td>
            <td>
            <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-warning">Sửa</a>
            <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Xóa</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection