@extends('adminlte::page')

@section('content')
<h1>Danh sách Khuyến mãi</h1>
<a href="{{ route('promotions.create') }}" class="btn btn-primary">Thêm mới</a>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<table class="table">
    <thead>
        <tr>
            <th>Mã khuyến mãi</th>
            <th>Loại giảm giá</th>
            <th>Giá trị giảm</th>
            <th>Giới hạn sử dụng</th>
            <th>Ngày bắt đầu</th>
            <th>Ngày kết thúc</th>
            <th>Trạng thái</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        @foreach($promotions as $promotion)
        <tr>
            <td>{{ $promotion->code }}</td>
            <td>{{ $promotion->discount_type == 'percentage' ? 'Phần trăm' : 'Cố định' }}</td>
            <td>{{ $promotion->discount_value }}</td>
            <td>{{ $promotion->usage_limit ?? 'Không giới hạn' }}</td>
            <td>{{ $promotion->start_date->format('d-m-Y') }}</td>
            <td>{{ $promotion->end_date ? $promotion->end_date->format('d-m-Y') : 'Không có' }}</td>
            <td>{{ $promotion->is_active ? 'Hoạt động' : 'Ẩn' }}</td>
            <td>
                <a href="{{ route('promotions.edit', $promotion->promotion_id) }}" class="btn btn-warning">Sửa</a>
                <form action="{{ route('promotions.destroy', $promotion->promotion_id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Bạn có chắc muốn xóa?')">Xóa</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection