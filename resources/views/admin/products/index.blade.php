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
                <th>Hình ảnh</th>
                <th>Tên sản phẩm</th>
                <th>Giá</th>
                <th>Danh mục</th>
                <th>Biến thể (Size - Màu - Số lượng)</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
            <tr>
                <td>{{ $product->id }}</td>
                <td>
                    @if($product->image)
                        @php
                            $images = explode(',', $product->image); // Tách ảnh thành mảng
                        @endphp
                        @foreach($images as $img)
                            <img src="{{ asset('storage/' . trim($img)) }}" alt="{{ $product->name }}" width="100" style="margin-right: 5px;">
                        @endforeach
                    @else
                        Không có ảnh
                    @endif
                </td>
                
                <td>{{ $product->name }}</td>
                <td>
                    @if($product->discount_price && $product->discount_price < $product->price)
                        <span style="text-decoration: line-through; color: red;">
                            {{ number_format($product->price, 0, ',', '.') }} VND
                        </span>
                        <br>
                        <span style="color: green; font-weight: bold;">
                            {{ number_format($product->discount_price, 0, ',', '.') }} VND
                        </span>
                    @else
                        {{ number_format($product->price, 0, ',', '.') }} VND
                    @endif
                </td>
                
                <td>{{ $product->category->name ?? 'Không có danh mục' }}</td>
                <td>
                    @foreach($product->variants as $variant)
                        <div style="margin-bottom: 5px;">
                            <span class="badge badge-secondary">{{ $variant->size->size_name }}</span>
                            <span style="background-color: {{ $variant->color->color_code }}; padding: 5px; border-radius: 5px; display: inline-block; width: 20px; height: 20px;"></span>
                            <span class="text-muted">SL: {{ $variant->stock_quantity }}</span>
                        </div>
                    @endforeach
                </td>
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
