@extends('client.layouts.main')

@section('title', 'Thanh toán')

@section('content')
<div class="container">
    <h1>Thanh toán</h1>

    @php
    $cart = session('cart', []);
    $totalPrice = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart));

    // Lấy tên sản phẩm từ từng mục trong giỏ hàng
    $productNames = [];
    foreach ($cart as $item) {
    // Kiểm tra key 'product_name'
    if (isset($item['product_name'])) {
    $productNames[] = $item['product_name'];
    } else {
    // Debug: Nếu không có, gán giá trị để xem
    $productNames[] = 'undefined';
    }
    }
    // Nếu có 1 sản phẩm, hiển thị tên đó, nếu nhiều thì nối lại với dấu phẩy
    $productName = count($productNames) === 1
    ? $productNames[0]
    : (count($productNames) > 1 ? implode(', ', $productNames) : '');
    @endphp

    <h4>Tổng tiền: {{ number_format($totalPrice, 0, ',', '.') }}₫</h4>

    <form action="{{ route('order.store') }}" method="POST">
        @csrf

        {{-- Truyền thông tin giỏ hàng vào form --}}
        @foreach($cart as $key => $item)
        <input type="hidden" name="items[{{ $key }}][product_id]" value="{{ $item['product_id'] }}">
        <input type="hidden" name="items[{{ $key }}][product_name]" value="{{ $item['product_name'] ?? '' }}">
        <input type="hidden" name="items[{{ $key }}][price]" value="{{ $item['price'] }}">
        <input type="hidden" name="items[{{ $key }}][quantity]" value="{{ $item['quantity'] }}">
        @endforeach

        <div class="form-group">
            <label for="product_name">Tên sản phẩm</label>
            <input type="text" name="product_name" id="product_name" class="form-control"
                value="{{ $productName }}" readonly>
        </div>

        <!-- Các trường khác -->
        <div class="form-group">
            <label for="order_id">Mã hóa đơn</label>
            <input type="text" class="form-control" id="order_id" name="order_id" value="{{ date('YmdHis') }}" readonly>
        </div>

        <div class="form-group">
            <label for="amount">Số tiền</label>
            <input type="number" class="form-control" id="amount" name="amount" value="{{ $totalPrice }}" readonly>
        </div>

        <div class="form-group">
            <label for="order_desc">Nội dung thanh toán</label>
            <textarea class="form-control" id="order_desc" name="order_desc" rows="2">
Thanh toán đơn hàng {{ date('YmdHis') }}
            </textarea>
        </div>

        <div class="form-group">
            <label for="bank_code">Ngân hàng</label>
            <select name="bank_code" id="bank_code" class="form-control">
                <option value="">Không chọn</option>
                <option value="NCB">Ngân hàng NCB</option>
                <option value="AGRIBANK">Ngân hàng Agribank</option>
                <option value="SCB">Ngân hàng SCB</option>
            </select>
        </div>

        <div class="form-group">
            <label for="language">Ngôn ngữ</label>
            <select name="language" id="language" class="form-control">
                <option value="vn" selected>Tiếng Việt</option>
                <option value="en">English</option>
            </select>
        </div>

        <div class="form-group">
            <label for="txtexpire">Thời hạn thanh toán</label>
            <input type="text" class="form-control" id="txtexpire" name="txtexpire" value="{{ now()->addMinutes(15)->format('YmdHis') }}" readonly>
        </div>

        <div class="form-group">
            <label for="shipping_address">Địa chỉ giao hàng</label>
            <input type="text" class="form-control" id="shipping_address" name="shipping_address" required>
        </div>

        <div class="form-group mt-3">
            <label>Phương thức thanh toán</label>
            <div>
                <label>
                    <input type="radio" name="payment_method" value="cod" checked>
                    Thanh toán khi nhận hàng (COD)
                </label>
                <label>
                    <input type="radio" name="payment_method" value="vnpay">
                    Thanh toán qua VNPay
                </label>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Đặt hàng</button>
    </form>
</div>
@endsection