@extends('adminlte::page')

@section('title', 'Thống kê đơn hàng')

@section('content_header')
<h1>Thống kê đơn hàng - Năm {{ $year }}</h1>
@stop

@section('content')
@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<!-- Form lọc -->
<div class="card">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.order.statistics') }}" class="form-inline mb-4">
            <div class="form-group mr-3">
                <label for="filter" class="mr-2">Lọc theo:</label>
                <select name="filter" id="filter" class="form-control">
                    <option value="daily" {{ $filter === 'daily' ? 'selected' : '' }}>Theo ngày</option>
                    <option value="monthly" {{ $filter === 'monthly' ? 'selected' : '' }}>Theo tháng</option>
                    <option value="yearly" {{ $filter === 'yearly' ? 'selected' : '' }}>Theo năm</option>
                </select>
            </div>
            <div class="form-group mr-3">
                <label for="year" class="mr-2">Năm:</label>
                <input type="number" name="year" id="year" value="{{ $year }}" class="form-control" placeholder="Năm">
            </div>
            <button type="submit" class="btn btn-primary">Lọc</button>
        </form>

        <!-- Bảng thống kê -->
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Thời gian</th>
                        <th>Số lượng đơn</th>
                        <th>Tổng doanh thu</th>
                        <th>Tổng phí vận chuyển</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($stats as $stat)
                    <tr>
                        <td>{{ $stat->period }}</td>
                        <td>{{ $stat->order_count }}</td>
                        <td>{{ number_format($stat->total_revenue, 2) }} VND</td>
                        <td>{{ number_format($stat->total_shipping_cost, 2) }} VND</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@stop

@section('css')
<!-- Thêm CSS tùy chỉnh nếu cần -->
@stop

@section('js')
<!-- Thêm JS tùy chỉnh nếu cần -->
@stop