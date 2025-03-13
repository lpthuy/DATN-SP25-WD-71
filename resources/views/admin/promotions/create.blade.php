@extends('adminlte::page')

@section('content')
<h1>Thêm Khuyến mãi</h1>

@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ route('promotions.store') }}" method="POST">
    @csrf
    <div class="form-group">
        <label>Mã khuyến mãi</label>
        <input type="text" name="code" class="form-control" required>
    </div>
    <div class="form-group">
        <label>Loại giảm giá</label>
        <select name="discount_type" class="form-control" required>
            <option value="percentage">Phần trăm</option>
            <option value="fixed">Cố định</option>
        </select>
    </div>
    <div class="form-group">
        <label>Giá trị giảm</label>
        <input type="number" name="discount_value" class="form-control" required>
    </div>
    <div class="form-group">
        <label>Giới hạn sử dụng</label>
        <input type="number" name="usage_limit" class="form-control">
    </div>
    <div class="form-group">
        <label>Ngày bắt đầu</label>
        <input type="date" name="start_date" class="form-control" required>
    </div>
    <div class="form-group">
        <label>Ngày kết thúc</label>
        <input type="date" name="end_date" class="form-control">
    </div>
    <div class="form-group">
        <label>Trạng thái</label>
        <select name="is_active" class="form-control">
            <option value="1">Hoạt động</option>
            <option value="0">Ẩn</option>
        </select>
    </div>
    <button type="submit" class="btn btn-success">Lưu</button>
    <a href="{{ route('promotions.index') }}" class="btn btn-secondary">Hủy</a>
</form>
@endsection