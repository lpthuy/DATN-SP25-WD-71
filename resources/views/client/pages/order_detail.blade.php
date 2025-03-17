@extends('client.layouts.main')

@section('title', 'Chi tiết đơn hàng')

@section('content')
<div class="container">
    <h1>Chi tiết đơn hàng</h1>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @elseif(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="order-summary">
        <p><strong>Mã đơn hàng:</strong> {{ $order->order_code }}</p>
        <p><strong>Trạng thái thanh toán:</strong> {{ ucfirst($order->payment_status) }}</p>
        <p><strong>Tổng tiền:</strong> {{ number_format($order->price, 0, ',', '.') }}₫</p>
        <p><strong>Phương thức thanh toán:</strong> {{ strtoupper($order->payment_method) }}</p>
        <p><strong>Địa chỉ giao hàng:</strong> {{ $order->shipping_address ?? 'Chưa cập nhật' }}</p>
        <p><strong>Ngày tạo đơn:</strong> {{ $order->created_at->format('d/m/Y H:i:s') }}</p>
        <p><strong>Ngày cập nhật:</strong> {{ $order->updated_at->format('d/m/Y H:i:s') }}</p>
    </div>

    @if($order->orderDetails && $order->orderDetails->count() > 0)
    <h3>Chi tiết sản phẩm</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Sản phẩm</th>
                <th>Màu sắc</th>
                <th>Size</th>
                <th>Số lượng</th>
                <th>Giá</th>
                <th>Thành tiền</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->orderDetails as $detail)
            <tr>
                <td>{{ $detail->product_name }}</td>
                <td>{{ $detail->color }}</td>
                <td>{{ $detail->size }}</td>
                <td>{{ $detail->quantity }}</td>
                <td>{{ number_format($detail->price, 0, ',', '.') }}₫</td>
                <td>{{ number_format($detail->price * $detail->quantity, 0, ',', '.') }}₫</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <p>Không có chi tiết sản phẩm nào.</p>
    @endif
</div>
@endsection