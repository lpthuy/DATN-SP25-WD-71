@extends('adminlte::page')

@section('content')
<div class="container mt-4">
    <h2 class="text-center mb-4">Thống kê doanh thu</h2>

    <form method="GET" action="{{ route('admin.revenue.statistics') }}" class="row g-3 mb-4">
        <div class="col-md-4">
            <label for="filter_type" class="form-label">Lọc theo:</label>
            <select name="filter_type" id="filter_type" class="form-control">
                <option value="day" {{ request('filter_type') == 'day' ? 'selected' : '' }}>Ngày</option>
                <option value="month" {{ request('filter_type') == 'month' ? 'selected' : '' }}>Tháng</option>
                <option value="year" {{ request('filter_type') == 'year' ? 'selected' : '' }}>Năm</option>
            </select>
        </div>
        <div class="col-md-4">
            <label for="filter_value" class="form-label">Chọn ngày/tháng/năm:</label>
            <input type="text" name="filter_value" id="filter_value" class="form-control" value="{{ request('filter_value') }}">
        </div>
        <div class="col-md-4 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100">Lọc</button>
        </div>
    </form>

    <div class="card p-3 mb-4">
        <h4>Doanh thu: <span class="text-success">{{ number_format($totalRevenue, 0, ',', '.') }} VNĐ</span></h4>
    </div>

    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>Thời gian</th>
                <th>Doanh thu</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($revenues as $time => $revenue)
            <tr>
                <td>{{ $time }}</td>
                <td>{{ number_format($revenue, 0, ',', '.') }} VNĐ</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Thêm thư viện Flatpickr -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        flatpickr("#filter_value", {
            enableTime: true, // Cho phép chọn giờ
            dateFormat: "Y-m-d H:i", // Định dạng ngày giờ
            locale: "vn" // Hiển thị tiếng Việt
        });
    });
</script>

@endsection