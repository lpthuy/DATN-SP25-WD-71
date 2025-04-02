@extends('adminlte::page')

@section('content')
<h2>Chi tiết đơn hàng #{{ $order->order_code }}</h2>

{{-- Nút quay lại --}}
<a href="{{ route('orders.index') }}" class="btn btn-secondary mb-3">
    <i class="fas fa-arrow-left"></i> Quay lại danh sách
</a>

{{-- Thông tin người đặt hàng --}}
<div class="card mb-4">
    <div class="card-header">Thông tin người đặt hàng</div>
    <div class="card-body">
        <p><strong>Họ tên:</strong> {{ $order->user->name ?? 'Không có' }}</p>
        <p><strong>Email:</strong> {{ $order->user->email ?? 'Không có' }}</p>
        <p><strong>Số điện thoại:</strong> {{ $order->user->phone ?? 'Không có' }}</p>
        <p><strong>Địa chỉ:</strong> {{ $order->user->address ?? 'Không có' }}</p>
    </div>
</div>

{{-- Thông tin đơn --}}
<p><strong>Phương thức thanh toán:</strong> {{ $order->payment_method }}</p>
<p><strong>Trạng thái:</strong> {{ $order->status }}</p>

<form action="{{ route('orders.updateStatus', $order->id) }}" method="POST" class="mb-4">
    @csrf
    <label for="status">Cập nhật trạng thái:</label>
    <input type="text" name="status" value="{{ $order->status }}" class="form-control mb-2" />
    <button type="submit" class="btn btn-primary">Cập nhật</button>
</form>

<h4>Sản phẩm trong đơn hàng:</h4>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Tên</th>
            <th>Màu</th>
            <th>Size</th>
            <th>Số lượng</th>
            <th>Giá</th>
            <th>xuất file</th>
        </tr>
    </thead>
    <tbody>
        @foreach($items as $item)
        <tr>
            <td>{{ $item->product_name }}</td>
            <td>{{ $item->color }}</td>
            <td>{{ $item->size }}</td>
            <td>{{ $item->quantity }}</td>
            <td>{{ number_format($item->price) }}VNĐ</td>
            <td>
                <a href="{{ route('orders.exportPDF', $order->id) }}" class="btn btn-danger mb-3">
                    <i class="fas fa-file-pdf"></i> Xuất PDF
                </a>
                
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
