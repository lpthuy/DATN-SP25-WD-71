@extends('adminlte::page')

@section('title', 'Chi tiết đơn hàng #' . $order->order_code)

@section('content')
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Roboto', sans-serif;
    }

    .container {
        max-width: 1200px;
        margin: 20px auto;
        padding: 20px;
    }

    h2 {
        font-size: 2rem;
        font-weight: 600;
        color: #333;
        margin-bottom: 20px;
        text-align: center;
    }

    /* Nút quay lại */
    .btn-back {
        background: #6c757d;
        color: white;
        padding: 10px 20px;
        border-radius: 5px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: background 0.3s ease;
    }

    .btn-back:hover {
        background: #5a6268;
    }

    /* Card thông tin */
    .card {
        border: none;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
    }

    .card-header {
        background: #f8f9fa;
        border-bottom: 1px solid #ddd;
        padding: 15px;
        font-size: 1.2rem;
        font-weight: 600;
        color: #333;
        border-radius: 10px 10px 0 0;
    }

    .card-body {
        padding: 20px;
        background: #fff;
        border-radius: 0 0 10px 10px;
    }

    .card-body p {
        margin: 10px 0;
        font-size: 1rem;
        color: #555;
    }

    .card-body p strong {
        color: #333;
    }

    /* Thông tin đơn hàng */
    .order-info {
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
        margin-bottom: 20px;
    }

    .order-info p {
        font-size: 1rem;
        color: #555;
    }

    .order-info p strong {
        color: #333;
    }

    /* Form cập nhật trạng thái */
    .status-form {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 20px;
    }

    .status-form label {
        font-weight: 600;
        color: #333;
    }

    .status-form select {
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 1rem;
        width: 200px;
    }

    .status-form button {
        background: #007bff;
        color: white;
        padding: 8px 20px;
        border: none;
        border-radius: 5px;
        font-size: 1rem;
        cursor: pointer;
        transition: background 0.3s ease;
    }

    .status-form button:hover {
        background: #0056b3;
    }

    /* Bảng sản phẩm */
    .table {
        width: 100%;
        border-collapse: collapse;
        background: #fff;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .table thead {
        background: #007bff;
        color: white;
    }

    .table th,
    .table td {
        padding: 15px;
        text-align: center;
        vertical-align: middle;
    }

    .table tbody tr {
        border-bottom: 1px solid #eee;
        transition: background 0.3s ease;
    }

    .table tbody tr:hover {
        background: #f9f9f9;
    }

    /* Nút xuất PDF */
    .btn-export-pdf {
        background: #dc3545;
        color: white;
        padding: 8px 15px;
        border-radius: 5px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: background 0.3s ease;
    }

    .btn-export-pdf:hover {
        background: #c82333;
    }

    /* Responsive */
    @media (max-width: 768px) {
        h2 {
            font-size: 1.5rem;
        }

        .order-info {
            flex-direction: column;
        }

        .status-form {
            flex-direction: column;
            align-items: flex-start;
        }

        .status-form select,
        .status-form button {
            width: 100%;
        }

        .table th,
        .table td {
            font-size: 0.9rem;
            padding: 10px;
        }
    }
</style>

<div class="container">
    <h2>Chi tiết đơn hàng #{{ $order->order_code }}</h2>

    <!-- Nút quay lại -->
    <a href="{{ route('orders.index') }}" class="btn-back mb-3">
        <i class="fas fa-arrow-left"></i> Quay lại danh sách
    </a>

    <!-- Thông tin người đặt hàng -->
    <div class="card">
        <div class="card-header">Thông tin người đặt hàng</div>
        <div class="card-body">
            <p><strong>Họ tên:</strong> {{ $order->user->name ?? 'Không có' }}</p>
            <p><strong>Email:</strong> {{ $order->user->email ?? 'Không có' }}</p>
            <p><strong>Số điện thoại:</strong> {{ $order->user->phone ?? 'Không có' }}</p>
            <p><strong>Địa chỉ:</strong> {{ $order->user->address ?? 'Không có' }}</p>
        </div>
    </div>

    <!-- Thông tin đơn hàng -->
    <div class="order-info">
        <p><strong>Phương thức thanh toán:</strong> {{ $order->payment_method }}</p>
        <p><strong>Trạng thái:</strong> {{ $order->status }}</p>
    </div>

    <!-- Form cập nhật trạng thái -->
    <form action="{{ route('orders.updateStatus', $order->id) }}" method="POST" class="status-form">
        @csrf
        <label for="status">Cập nhật trạng thái:</label>
        <select name="status" class="form-control">
            <option value="Đang xử lý"
                {{ $order->status == 'Đang xử lý' ? 'selected' : ($order->status != 'Đang xử lý' ? 'disabled' : '') }}>
                Đang xử lý
            </option>
            <option value="đang xác nhận"
                {{ $order->status == 'đang xác nhận' ? 'selected' : (in_array($order->status, ['đang giao hàng', 'đã giao thành công', 'đã hủy']) ? 'disabled' : '') }}>
                Đang xác nhận
            </option>
            <option value="đang giao hàng"
                {{ $order->status == 'đang giao hàng' ? 'selected' : (in_array($order->status, ['đã giao thành công', 'đã hủy']) ? 'disabled' : '') }}>
                Đang giao hàng
            </option>
            <option value="đã giao thành công"
                {{ $order->status == 'đã giao thành công' ? 'selected' : ($order->status == 'đã hủy' ? 'disabled' : '') }}>
                Đã giao thành công
            </option>
            <option value="đã hủy"
                {{ $order->status == 'đã hủy' ? 'selected' : '' }}>
                Đã hủy
            </option>
        </select>
        <button type="submit" class="btn btn-primary">Cập nhật</button>
    </form>

    <!-- Sản phẩm trong đơn hàng -->
    <h4>Sản phẩm trong đơn hàng:</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Tên</th>
                <th>Màu</th>
                <th>Size</th>
                <th>Số lượng</th>
                <th>Giá</th>
                <th>Xuất file</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
            <tr>
                <td>{{ $item->product_name }}</td>
                <td>{{ $item->color }}</td>
                <td>{{ $item->size }}</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ number_format($item->price, 0, ',', '.') }} VNĐ</td>
                <td>
                    <a href="{{ route('orders.exportPDF', $order->id) }}" class="btn-export-pdf">
                        <i class="fas fa-file-pdf"></i> Xuất PDF
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection