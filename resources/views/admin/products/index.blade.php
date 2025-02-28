@extends('adminlte::page')

@section('title', 'Danh sách sản phẩm')

@section('content_header')
    <h1>Danh sách sản phẩm</h1>
@endsection

@section('content')
    <a href="{{ route('products.create') }}" class="btn btn-primary mb-3">Thêm sản phẩm</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên sản phẩm</th>
                <th>Giá</th>
                <th>Danh mục</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
            <tr>
                <td>{{ $product->id }}</td>
                <td>{{ $product->name }}</td>
                <td>{{ number_format($product->price, 0, ',', '.') }} VND</td>
                <td>{{ $product->category->name ?? 'Không có danh mục' }}</td>
                <td>
                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning btn-sm">Sửa</a>
                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">Xóa</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $products->links() }}
@endsection
