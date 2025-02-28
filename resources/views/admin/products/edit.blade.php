@extends('adminlte::page')

@section('title', 'Chỉnh sửa sản phẩm')

@section('content_header')
    <h1>Chỉnh sửa sản phẩm: {{ $product->name }}</h1>
@endsection

@section('content')
    <form action="{{ route('products.update', $product->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">Tên sản phẩm</label>
            <input type="text" name="name" class="form-control" value="{{ $product->name }}" required>
        </div>

        <div class="form-group">
            <label for="price">Giá</label>
            <input type="number" name="price" class="form-control" value="{{ $product->price }}" required>
        </div>

        <div class="form-group">
            <label for="category_id">Danh mục</label>
            <select name="category_id" class="form-control">
                <option value="">Chọn danh mục</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" @if($category->id == $product->category_id) selected @endif>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-success">Cập nhật</button>
        <a href="{{ route('products.index') }}" class="btn btn-secondary">Hủy</a>
    </form>
@endsection
