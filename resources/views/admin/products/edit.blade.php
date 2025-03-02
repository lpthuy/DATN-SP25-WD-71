@extends('adminlte::page')

@section('title', 'Chỉnh sửa sản phẩm')

@section('content_header')
    <h1>Chỉnh sửa sản phẩm</h1>
@endsection

@section('content')
    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">Tên sản phẩm</label>
            <input type="text" name="name" class="form-control" value="{{ $product->name }}" required>
        </div>

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
            <label for="images">Hình ảnh sản phẩm</label>
            <input type="file" name="images[]" class="form-control" multiple>
            <div class="mt-2">
                @foreach(explode(',', $product->image) as $img)
                    @if($img)
                        <img src="{{ asset('storage/' . $img) }}" alt="Hình ảnh sản phẩm" width="100">
                    @endif
                @endforeach
            </div>
        </div>

        {{-- Giá --}}
        <div class="form-group">
            <label for="price">Giá gốc</label>
            <input type="number" name="price" class="form-control" min="0" value="{{ $product->price }}" required>
        </div>

        <div class="form-group">
            <label for="discount_price">Giá sau giảm</label>
            <input type="number" name="discount_price" class="form-control" min="0" value="{{ $product->discount_price }}">
        </div>

        {{-- Biến thể sản phẩm --}}
        <div class="form-group">
            <label for="variants">Chọn Kích thước, Màu sắc và Số lượng</label>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Chọn</th>
                        <th>Size</th>
                        <th>Màu sắc</th>
                        <th>Số lượng</th>
                    </tr>
                </thead>
                <tbody id="variantTable">
                    @foreach($sizes as $size)
                        @foreach($colors as $color)
                            @php
                                $variant = $product->variants->where('size_id', $size->id)->where('color_id', $color->id)->first();
                            @endphp
                            <tr class="variant-row">
                                <td>
                                    <input type="checkbox" name="variants[{{ $size->id }}][{{ $color->id }}][selected]" 
                                           value="1" class="variant-checkbox" 
                                           onchange="toggleInputs(this)" {{ $variant ? 'checked' : '' }}>
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
                                           class="form-control variant-quantity" min="1" 
                                           value="{{ $variant ? $variant->stock_quantity : '' }}" 
                                           {{ $variant ? '' : 'disabled' }}>
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>

        <script>
            function toggleInputs(checkbox) {
                let row = checkbox.closest("tr");
                let quantityInput = row.querySelector(".variant-quantity");
        
                if (checkbox.checked) {
                    quantityInput.removeAttribute("disabled");
                } else {
                    quantityInput.setAttribute("disabled", "true");
                    quantityInput.value = "";
                }
            }
        </script>  

        <button type="submit" class="btn btn-success">Cập nhật sản phẩm</button>
        <a href="{{ route('products.index') }}" class="btn btn-secondary">Hủy</a>
    </form>
@endsection
