@extends('adminlte::page')

@section('title', 'Thêm sản phẩm')

@section('content_header')
    <h1>Thêm sản phẩm</h1>
@endsection

@section('content')
    <form action="{{ route('products.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="name">Tên sản phẩm</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="price">Giá</label>
            <input type="number" name="price" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="category_id">Danh mục</label>
            <select name="category_id" class="form-control">
                <option value="">Chọn danh mục</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-success">Lưu sản phẩm</button>
        <a href="{{ route('products.index') }}" class="btn btn-secondary">Hủy</a>
    </form>
@endsection
