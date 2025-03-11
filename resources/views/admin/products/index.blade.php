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
                <th>Danh mục</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
                <tr>
                    <td>{{ $product->id }}</td>
                    <td>
                        @if($product->image)
                            <img src="{{ asset('storage/' . explode(',', $product->image)[0]) }}" alt="{{ $product->name }}"
                                width="100">
                        @else
                            Không có ảnh
                        @endif
                    </td>

                    <td>{{ $product->name }}</td>
                    <td>{{ $product->category->name ?? 'Không có danh mục' }}</td>
                    <td>
                        {{-- Nút hiển thị modal biến thể --}}
                        <button class="btn btn-info btn-sm open-variants" data-product-id="{{ $product->id }}"
                            data-variants="{{ $product->variants->toJson() }}">
                            Biến thể
                        </button>
                        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning btn-sm">Sửa</a>
                        <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"
                                onclick="return confirm('Bạn có chắc chắn muốn xóa?')">Xóa</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $products->links() }}

    <!-- Modal hiển thị biến thể -->
    <div class="modal fade" id="variantModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Danh sách biến thể</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Size</th>
                                <th>Màu</th>
                                <th>Giá cũ</th>
                                <th>Giá mới</th>
                                <th>Số lượng</th>
                            </tr>
                        </thead>
                        <tbody id="variantTableBody">
                            <!-- Dữ liệu sẽ được load vào đây bằng JavaScript -->
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>
@endsection
{{ $products->links() }}
@section('js')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll('.open-variants').forEach(button => {
                button.addEventListener('click', function () {
                    let variants = JSON.parse(this.getAttribute('data-variants'));
                    let tableBody = document.getElementById("variantTableBody");
                    tableBody.innerHTML = "";

                    if (variants.length === 0) {
                        tableBody.innerHTML = "<tr><td colspan='5' class='text-center'>Không có biến thể nào</td></tr>";
                    } else {
                        variants.forEach(variant => {
                            let sizeName = variant.size && variant.size.size_name ? variant.size.size_name : 'Không có size';
                            let colorCode = variant.color && variant.color.color_code ? variant.color.color_code : '#000';

                            let oldPrice = variant.price
                                ? `<span style="text-decoration: line-through; color: gray;">${new Intl.NumberFormat('vi-VN').format(variant.price)} VND</span>`
                                : 'N/A';

                            let newPrice = variant.discount_price && variant.discount_price < variant.price
                                ? `<span style="color: red; font-weight: bold;">${new Intl.NumberFormat('vi-VN').format(variant.discount_price)} VND</span>`
                                : `<span style="color: green; font-weight: bold;">${new Intl.NumberFormat('vi-VN').format(variant.price)} VND</span>`;

                            let row = `
                            <tr>
                                <td>${sizeName}</td>
                                <td>
                                    <span style="background-color: ${colorCode}; display: inline-block; width: 20px; height: 20px; border-radius: 5px;"></span>
                                </td>
                                <td>${oldPrice}</td>
                                <td>${newPrice}</td>
                                <td>${variant.stock_quantity}</td>
                            </tr>
                        `;
                            tableBody.innerHTML += row;
                        });
                    }

                    $('#variantModal').modal('show');
                });
            });
        });
    </script>
@endsection