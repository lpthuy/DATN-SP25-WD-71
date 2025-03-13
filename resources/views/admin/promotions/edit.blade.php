@extends('adminlte::page')

@section('content')
<h1>Chỉnh sửa Khuyến mãi</h1>

@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ route('promotions.update', $promotion->promotion_id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="form-group">
        <label>Mã khuyến mãi</label>
        <input type="text" name="code" class="form-control" value="{{ $promotion->code }}" required>
    </div>
    <div class="form-group">
        <label>Loại giảm giá</label>
        <select name="discount_type" class="form-control" required>
            <option value="percentage" {{ $promotion->discount_type == 'percentage' ? 'selected' : '' }}>Phần trăm</option>
            <option value="fixed" {{ $promotion->discount_type == 'fixed' ? 'selected' : '' }}>Cố định</option>
        </select>
    </div>
    <div class="form-group">
        <label>Giá trị giảm</label>
        <input type="number" name="discount_value" class="form-control" value="{{ $promotion->discount_value }}" required>
    </div>
    <div class="form-group">
        <label>Giới hạn sử dụng</label>
        <input type="number" name="usage_limit" class="form-control" value="{{ $promotion->usage_limit }}">
    </div>
    <div class="form-group">
        <label>Ngày bắt đầu</label>
        <input type="date" name="start_date" class="form-control" value="{{ $promotion->start_date->format('Y-m-d') }}" required>
    </div>
    <div class="form-group">
        <label>Ngày kết thúc</label>
        <input type="date" name="end_date" class="form-control" value="{{ $promotion->end_date ? $promotion->end_date->format('Y-m-d') : '' }}">
    </div>
    <div class="form-group">
        <label>Trạng thái</label>
        <select name="is_active" class="form-control">
            <option value="1" {{ $promotion->is_active ? 'selected' : '' }}>Hoạt động</option>
            <option value="0" {{ !$promotion->is_active ? 'selected' : '' }}>Ẩn</option>
        </select>
    </div>
    <button type="submit" class="btn btn-success">Cập nhật</button>
    <a href="{{ route('promotions.index') }}" class="btn btn-secondary">Hủy</a>
</form>
@endsection