@extends('client.layouts.main')

@section('title', 'Chi tiết đơn hàng')

@section('content')
    <section class="bread-crumb">
        <div class="container">
            <ul class="breadcrumb">
                <li class="home">
                    <a href="{{ route('home') }}" title="Trang chủ">Trang chủ</a>
                </li>
                <li>
                    <a href="{{ route('order') }}" title="Đơn hàng của bạn">Đơn hàng của bạn</a>
                </li>
                <li>
                    <strong>Chi tiết đơn hàng</strong>
                </li>
            </ul>
        </div>
    </section>

    <section class="page_customer_account">
        <div class="container">
            <h2>Chi tiết đơn hàng: #{{ $order->order_code }}</h2>
            <p><strong>Ngày đặt hàng:</strong> {{ date('d/m/Y', strtotime($order->created_at)) }}</p>
            <p><strong>Sản phẩm:</strong> {{ $order->product_name }}</p>
            <p><strong>Màu sắc:</strong> {{ $order->colorName->color_name ?? 'Không xác định' }}</p>
            <p><strong>Kích thước:</strong> {{ $order->sizeName->size_name ?? 'Không xác định' }}</p>
            <p><strong>Số lượng:</strong> {{ $order->quantity }}</p>
            <p><strong>Giá:</strong> {{ number_format($order->price, 2, ',', '.') }} VNĐ</p>
            <p><strong>Phương thức thanh toán:</strong> 
                @if($order->payment_method == 'cod')
                    <span class="badge badge-warning">COD</span>
                @else
                    <span class="badge badge-success">Chuyển khoản</span>
                @endif
            </p>
            <a href="{{ route('order') }}" class="btn btn-secondary">Quay lại danh sách đơn hàng</a>
        </div>
    </section>
@endsection
