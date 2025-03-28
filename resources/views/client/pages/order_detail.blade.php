@extends('client.layouts.main')

@section('title', 'Chi tiết đơn hàng')

@section('content')
<div class="container mt-4">
    <h2>Chi tiết đơn hàng #{{ $order->order_code }}</h2>
    <p><strong>Phương thức thanh toán:</strong> {{ strtoupper($order->payment_method) }}</p>
    <p><strong>Trạng thái:</strong> {{ ucfirst($order->status) }}</p>
    <p><strong>Ngày đặt hàng:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>

    <table class="table table-bordered mt-4">
        <thead>
            <tr>
                <th>Tên sản phẩm</th>
                <th>Màu</th>
                <th>Size</th>
                <th>Số lượng</th>
                <th>Giá</th>
                <th>Thành tiền</th>
            </tr>
        </thead>
        <tbody>
            @php $total = 0; @endphp
            @foreach($items as $item)
                <tr>
                    <td>{{ $item->product_name }}</td>
                    <td>{{ $item->color }}</td>
                    <td>{{ $item->size }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ number_format($item->price, 0, ',', '.') }}₫</td>
                    <td>{{ number_format($item->price * $item->quantity, 0, ',', '.') }}₫</td>
                </tr>
                @php $total += $item->price * $item->quantity; @endphp
            @endforeach
        </tbody>
    </table>

    <div class="text-right">
        <h4><strong>Tổng cộng:</strong> {{ number_format($total, 0, ',', '.') }}₫</h4>
    </div>

    <a href="{{ route('order') }}" class="btn btn-secondary mt-3">⬅ Quay lại danh sách đơn hàng</a>
</div>
@endsection
