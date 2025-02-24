@extends('adminlte::page')

@section('title', 'Danh sách Biến thể Sản phẩm')

@section('content_header')
    <h1>Danh sách Biến thể Sản phẩm</h1>
@endsection

@section('content')
    <a href="{{ route('products_variants.create') }}" class="btn btn-primary mb-3">Thêm Biến thể</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Sản phẩm</th>
                <th>Hình ảnh</th>
                <th>Size</th>
                <th>Màu sắc</th>
                <th>Giá</th>
                <th>Số lượng</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @foreach($variants as $groupKey => $group)
            <tr>
                <td>{{ $group->first()->id }}</td>
                <td>{{ $group->first()->product->name }}</td>

                <td>
                    @if(isset($productImages[$group->first()->product_id]))
                        @foreach($productImages[$group->first()->product_id] as $image)
                            <img src="{{ asset('storage/' . $image->image_url) }}" width="50" height="50" style="margin-right: 5px;">
                        @endforeach
                    @else
                        <span class="text-muted">Không có ảnh</span>
                    @endif
                </td>

                <td>
                    @foreach($group->unique('size_id') as $variant)
                        <span class="badge badge-primary">{{ $variant->size->size_name }}</span>
                    @endforeach
                </td>

                <!-- Gộp màu sắc vào một dòng -->
                <td>
                    @foreach($group->unique('color_id') as $variant)
                        <div style="display: inline-block; width: 20px; height: 20px; background-color: {{ $variant->color->color_code }}; border: 1px solid #ccc; margin-right: 5px;"></div>
                    @endforeach
                </td>

                <td>{{ number_format($group->first()->price, 0, ',', '.') }} VND</td>

                <td>
                    @foreach($group as $variant)
                        <span class="badge badge-success">{{ $variant->stock_quantity }}</span>
                    @endforeach
                </td>

                <td>
                    <a href="{{ route('products_variants.edit', $group->first()->id) }}" class="btn btn-warning btn-sm">Sửa</a>

                    <!-- Nút Xóa mở modal chọn Size -->
                    <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteModal{{ $group->first()->id }}">
                        Xóa
                    </button>

                    <!-- Modal chọn Size để xóa -->
                    <div class="modal fade" id="deleteModal{{ $group->first()->id }}" tabindex="-1" role="dialog">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Xóa Biến thể Sản phẩm</h5>
                                    <button type="button" class="close" data-dismiss="modal">
                                        <span>&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <p>Chọn kích thước cần xóa:</p>
                                    <form action="{{ route('products_variants.destroy', $group->first()->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')

                                        <!-- Nút chọn tất cả -->
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="selectAll{{ $group->first()->id }}" onclick="toggleSelectAll('{{ $group->first()->id }}')">
                                            <label class="form-check-label font-weight-bold" for="selectAll{{ $group->first()->id }}">
                                                Chọn tất cả
                                            </label>
                                        </div>

                                        @foreach($group as $variant)
                                            <div class="form-check">
                                                <input class="form-check-input size-checkbox{{ $group->first()->id }}" type="checkbox" name="size_ids[]" value="{{ $variant->size_id }}">
                                                <label class="form-check-label">{{ $variant->size->size_name }}</label>
                                            </div>
                                        @endforeach
                                        
                                        <button type="submit" class="btn btn-danger mt-3">Xóa</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </td>                
            </tr>
            @endforeach
        </tbody>
    </table>

    <script>
        function toggleSelectAll(groupId) {
            let selectAllCheckbox = document.getElementById("selectAll" + groupId);
            let checkboxes = document.querySelectorAll(".size-checkbox" + groupId);

            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
            });
        }
    </script>
@endsection
