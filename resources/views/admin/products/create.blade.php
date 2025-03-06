@extends('adminlte::page')

@section('title', 'Thêm sản phẩm')

@section('content_header')
    <h1>Thêm sản phẩm</h1>
@endsection

@section('content')
    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Tên sản phẩm --}}
        <div class="form-group">
            <label for="name">Tên sản phẩm</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        {{-- Danh mục --}}
        <div class="form-group">
            <label for="category_id">Danh mục</label>
            <select name="category_id" class="form-control">
                <option value="">Chọn danh mục</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- Upload Hình ảnh --}}
        <div class="form-group">
            <label for="images">Hình ảnh sản phẩm</label>
            <input type="file" name="images[]" class="form-control" multiple>
        </div>

        {{-- Chọn Kích thước, Màu sắc, Số lượng, Giá, Giá giảm --}}
        <div class="form-group">
            <label for="variants">Chọn Kích thước, Màu sắc, Số lượng và Giá</label>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Chọn</th>
                        <th>Size</th>
                        <th>Màu sắc</th>
                        <th>Số lượng</th>
                        <th>Giá</th>
                        <th>Giá giảm</th>
                    </tr>
                </thead>
                <tbody id="variantTable">
                    @foreach($sizes as $size)
                        @foreach($colors as $color)
                            <tr class="variant-row">
                                <td>
                                    <input type="checkbox" name="variants[{{ $size->id }}][{{ $color->id }}][selected]" 
                                           value="1" class="variant-checkbox" onchange="toggleInputs(this)">
                                </td>
                                <td class="size-name">{{ $size->size_name }}</td>
                                <td class="color-name">
                                    <div style="display: flex; align-items: center;">
                                        <div style="width: 20px; height: 20px; background-color: {{ $color->color_code }}; margin-right: 5px;"></div>
                                        {{ $color->color_name }}
                                    </div>
                                </td>
                                <td>
                                    <input type="number" name="variants[{{ $size->id }}][{{ $color->id }}][stock_quantity]" 
                                           class="form-control variant-quantity" min="1" placeholder="Nhập số lượng" disabled>
                                </td>
                                <td>
                                    <input type="number" name="variants[{{ $size->id }}][{{ $color->id }}][price]"
                                           class="form-control variant-price" min="0" step="0.01" placeholder="Nhập giá" disabled>
                                </td>
                                <td>
                                    <input type="number" name="variants[{{ $size->id }}][{{ $color->id }}][discount_price]"
                                           class="form-control variant-discount-price" min="0" step="0.01" placeholder="Giá giảm" disabled>
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Script bật/tắt input khi chọn biến thể --}}
        <script>
            function toggleInputs(checkbox) {
                let row = checkbox.closest('tr');
                let inputs = row.querySelectorAll('input[type=\"number\"]');

                if (checkbox.checked) {
                    inputs.forEach(input => input.removeAttribute('disabled'));
                } else {
                    inputs.forEach(input => {
                        input.setAttribute('disabled', 'true');
                        input.value = '';
                    });
                }
            }
        </script>

        <button type="submit" class="btn btn-success">Lưu sản phẩm</button>
        <a href="{{ route('products.index') }}" class="btn btn-secondary">Hủy</a>
    </form>
@endsection
