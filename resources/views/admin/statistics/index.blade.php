@extends('adminlte::page')

@section('content')
<div class="container">
    <h2 class="mb-4">Thống kê đơn hàng</h2>

    <!-- Form lọc thống kê -->
    <form method="GET" action="{{ url('admin/statistics') }}">
        <div class="row mb-4">
            <div class="col-md-3">
                <label for="status">Trạng thái</label>
                <select name="status" id="status" class="form-control">
                    <option value="">Tất cả</option>
                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                    <option value="đã hủy" {{ request('status') == 'đã hủy' ? 'selected' : '' }}>Đã hủy</option>
                    <option value="đang xác nhận" {{ request('status') == 'đang xác nhận' ? 'selected' : '' }}>Đang xác nhận</option>
                    <option value="đã giao thành công" {{ request('status') == 'đã giao thành công' ? 'selected' : '' }}>Đã giao thành công</option>
                </select>
            </div>
            <!-- <div class="col-md-3">
                <label for="date_range">Chọn khoảng thời gian</label>
                <input type="text" name="date_range" class="form-control" id="date_range" value="{{ request('date_range') }}" placeholder="Chọn ngày (dd/mm/yyyy)">
            </div> -->
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary mt-4 w-100">Lọc</button>
            </div>
        </div>
    </form>

    <div class="row">
        <!-- Tổng số đơn hàng theo ngày, tháng, năm -->
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-header bg-info text-white">
                    <h5 class="card-title">Tổng số đơn hàng hôm nay</h5>
                </div>
                <div class="card-body">
                    <h4 class="text-center">{{ $totalOrdersToday }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-header bg-success text-white">
                    <h5 class="card-title">Tổng số đơn hàng trong tháng</h5>
                </div>
                <div class="card-body">
                    <h4 class="text-center">{{ $totalOrdersThisMonth }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-header bg-warning text-white">
                    <h5 class="card-title">Tổng số đơn hàng trong năm</h5>
                </div>
                <div class="card-body">
                    <h4 class="text-center">{{ $totalOrdersThisYear }}</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Thống kê theo trạng thái -->
    <div class="card mb-3">
        <div class="card-header bg-dark text-white">
            <h5 class="card-title">Thống kê theo trạng thái</h5>
        </div>
        <div class="card-body">
            @foreach($statusStats as $status => $count)
            <div class="row mb-2">
                <div class="col-md-6">
                    <h6 class="mb-0">{{ ucfirst($status) }}:</h6>
                </div>
                <div class="col-md-6 text-end">
                    <span class="badge bg-secondary">{{ $count }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Top 3 sản phẩm bán chạy nhất -->
    <div class="card mb-3">
        <div class="card-header bg-info text-white">
            <h5 class="card-title">Top 3 sản phẩm bán chạy nhất</h5>
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tên sản phẩm</th>
                        <th>Số lượng đã bán</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topProducts as $index => $product)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $product->product_name }}</td>
                        <td>{{ $product->total_sold }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<!-- Thêm script cho bộ lọc (nếu cần dùng date picker, ví dụ như jQuery UI) -->
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<script>
    $(document).ready(function() {
        $('#date_range').datepicker({
            dateFormat: 'dd/mm/yy'
        });
    });
</script>
@endsection