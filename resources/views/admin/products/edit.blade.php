@extends('adminlte::page')

@section('title', 'Chỉnh sửa sản phẩm')

@section('content_header')
    <h1>Chỉnh sửa sản phẩm</h1>
@endsection

@section('content')
    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Tên sản phẩm --}}
        <div class="form-group">
            <label for="name">Tên sản phẩm</label>
            <input type="text" name="name" class="form-control" value="{{ $product->name }}" required>
        </div>

        {{-- Danh mục --}}
        <div class="form-group">
            <label for="category_id">Danh mục</label>
            <select name="category_id" class="form-control">
                <option value="">Chọn danh mục</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Hình ảnh sản phẩm --}}
        <div class="form-group">
            <label>Hình ảnh sản phẩm</label>
            <div class="mb-2">
                @foreach(explode(',', $product->image) as $img)
                    <img src="{{ asset('storage/' . $img) }}" width="100" class="mr-2">
                @endforeach
            </div>
            <input type="file" name="images[]" class="form-control" multiple>
        </div>

        {{-- Cập nhật biến thể --}}
        <div class="form-group">
            <label for="variants">Chỉnh sửa Kích thước, Màu sắc, Số lượng và Giá</label>
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
                            @php
                                $variant = $product->variants->where('size_id', $size->id)->where('color_id', $color->id)->first();
                            @endphp
                            <tr>
                                <td>
                                    <input type="checkbox" name="variants[{{ $size->id }}][{{ $color->id }}][selected]" 
                                           value="1" class="variant-checkbox" onchange="toggleInputs(this)"
                                           {{ $variant ? 'checked' : '' }}>
                                </td>
                                <td>{{ $size->size_name }}</td>
                                <td>
                                    <div style="display: flex; align-items: center;">
                                        <div style="width: 20px; height: 20px; background-color: {{ $color->color_code }}; margin-right: 5px;"></div>
                                        {{ $color->color_name }}
                                    </div>
                                </td>
                                <td>
                                    <input type="number" name="variants[{{ $size->id }}][{{ $color->id }}][stock_quantity]" 
                                           class="form-control variant-quantity" min="1" placeholder="Nhập số lượng"
                                           value="{{ $variant->stock_quantity ?? '' }}" {{ $variant ? '' : 'disabled' }}>
                                </td>
                                <td>
                                    <input type="number" name="variants[{{ $size->id }}][{{ $color->id }}][price]"
                                           class="form-control variant-price" min="0" step="0.01" placeholder="Nhập giá"
                                           value="{{ $variant->price ?? '' }}" {{ $variant ? '' : 'disabled' }}>
                                </td>
                                <td>
                                    <input type="number" name="variants[{{ $size->id }}][{{ $color->id }}][discount_price]"
                                           class="form-control variant-discount-price" min="0" step="0.01" placeholder="Giá giảm"
                                           value="{{ $variant->discount_price ?? '' }}" {{ $variant ? '' : 'disabled' }}>
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
                let inputs = row.querySelectorAll('input[type="number"]');

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

        <button type="submit" class="btn btn-success">Cập nhật sản phẩm</button>
        <a href="{{ route('products.index') }}" class="btn btn-secondary">Hủy</a>
    </form>
@endsection
