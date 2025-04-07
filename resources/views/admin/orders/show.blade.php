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
    <select id="status-select" name="status" class="form-control mb-2">
        <option value="Đang xử lý" {{ $order->status == 'Đang xử lý' ? 'selected' : '' }}>Đang xử lý</option>
        <option value="đang xác nhận" {{ $order->status == 'đang xác nhận' ? 'selected' : '' }}>Đang xác nhận</option>
        <option value="đang giao hàng" {{ $order->status == 'đang giao hàng' ? 'selected' : '' }}>đang giao hàng</option>
        <option value="đã giao thành công" {{ $order->status == 'đã giao thành công' ? 'selected' : '' }}>Đã giao thành công</option>
        <option value="returning" {{ $order->status == 'returning' ? 'selected' : '' }}>Đã hoàn hàng</option>
        <option value="đã hủy" {{ $order->status == 'đã hủy' ? 'selected' : '' }}>Đã hủy</option>
    </select>
    <button type="submit" class="btn btn-primary">Cập nhật</button>
</form>

<script>
    const currentStatus = "{{ $order->status }}";

    const statusMap = {
    "processing": ["confirming"],
    "confirming": ["shipping"],
    "shipping": ["completed"],
    "completed": [],       // ✅ KHÔNG cho admin chọn "returned"
    "cancelled": [],
    "returning": []        // ✅ Cho hiển thị, không được chuyển sang
};


const statusLabels = {
    "processing": "Đang xử lý",
    "confirming": "Đang xác nhận",
    "shipping": "Đang giao hàng",
    "completed": "Đã giao thành công",
    "cancelled": "Đã hủy",
    "returning": "Đã hoàn hàng"
};



    const select = document.getElementById("status-select");
    select.innerHTML = "";

    // Thêm option hiện tại (disable)
    const currentOption = document.createElement("option");
    currentOption.value = currentStatus;
    currentOption.text = statusLabels[currentStatus] || currentStatus;
    currentOption.disabled = true;
    currentOption.selected = true;
    select.appendChild(currentOption);

    // Thêm trạng thái tiếp theo
    const nextStatuses = statusMap[currentStatus] || [];
    nextStatuses.forEach(status => {
        const opt = document.createElement("option");
        opt.value = status;
        opt.text = statusLabels[status] || status;
        select.appendChild(opt);
    });
</script>




<h4>Sản phẩm trong đơn hàng:</h4>
<td>
    <a href="{{ route('orders.exportPDF', $order->id) }}" class="btn btn-danger mb-3">
        <i class="fas fa-file-pdf"></i> Xuất PDF
    </a>
    
</td>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Tên</th>
            <th>Màu</th>
            <th>Size</th>
            <th>Số lượng</th>
            <th>Giá</th>
                <th>Thành tiền</th>
            
        </tr>
    </thead>
    <tbody>
        @foreach($items as $item)
        <tr>
            <td>{{ $item->product_name }}</td>
            <td>{{ $item->color }}</td>
            <td>{{ $item->size }}</td>
            <td>{{ $item->quantity }}</td>
            <td>{{ number_format($item->price) }} VNĐ</td>
                    <td>{{ number_format($item->price * $item->quantity) }} VNĐ</td>
            <th>
                
            </th>
        </tr>

        
        @endforeach
    </tbody>
</table>
@php
    $total = 0;
    foreach ($items as $item) {
        $total += $item->price * $item->quantity;
    }

    $promotion = null;
    $discountAmount = 0;
    $finalTotal = $total;

    if ($order->promotion_code) {
        $promotion = \App\Models\Promotion::where('code', $order->promotion_code)->first();
        if ($promotion) {
            $discountAmount = $promotion->discount_type === 'fixed'
                ? $promotion->discount_value
                : $total * ($promotion->discount_value / 100);
            $finalTotal = max(0, $total - $discountAmount);
        }
    }
@endphp

<div class="mt-4 border-top pt-3">
    <h4><strong>Thông tin thanh toán</strong></h4>
    <p><strong>Giá gốc:</strong> {{ number_format($total, 0, ',', '.') }} VNĐ</p>

    @if ($promotion)
        <p><strong>Mã giảm giá:</strong> {{ $order->promotion_code }} 
            ({{ $promotion->discount_type === 'percentage' ? $promotion->discount_value . '%' : number_format($promotion->discount_value, 0, ',', '.') . ' VNĐ' }})
        </p>
        <p><strong>Đã giảm:</strong> {{ number_format($discountAmount, 0, ',', '.') }} VNĐ</p>
    @endif

    <h4 style="color:#e3342f"><strong>Tổng thanh toán:</strong> {{ number_format($finalTotal, 0, ',', '.') }} VNĐ</h4>
</div>

@endsection
