@extends('client.layouts.main')

@section('title', 'Xác nhận đơn hàng')

@section('content')
    <div class="container mt-4">
        <h2>Xác nhận đơn hàng</h2>
        <div class="card mb-4">
            <div class="card-header"><strong>Thông tin người mua</strong></div>
            <div class="card-body">
                <p><strong>Họ tên:</strong> {{ $user->name ?? 'Chưa đăng nhập' }}</p>
                <p><strong>Email:</strong> {{ $user->email ?? 'Chưa có' }}</p>
                <p><strong>Số điện thoại:</strong> {{ $user->phone ?? 'Chưa có' }}</p>
                <p><strong>Địa chỉ:</strong> {{ $user->address ?? 'Chưa có' }}</p>
            </div>
        </div>

        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>Tên sản phẩm</th>
                    <th>Màu</th>
                    <th>Size</th>
                    <th>Số lượng</th>
                    <th>Đơn giá</th>
                    <th>Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($checkoutItems as $item)
                    <tr>
                        <td class="product-name">{{ $item['name'] }}</td>
                        <td class="product-color">{{ $item['color'] }}</td>
                        <td class="product-size">{{ $item['size'] }}</td>
                        <td class="product-quantity">{{ $item['quantity'] }}</td>
                        <td class="product-price" data-price="{{ $item['price'] }}">
                            {{ number_format($item['price'], 0, ',', '.') }}₫</td>
                        <td class="product-total">{{ number_format($item['total_price'], 0, ',', '.') }}₫</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4">
            <h5 class="mb-3">🔰 Chọn phương thức thanh toán</h5>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="card h-100">
                        <label class="card-body d-flex align-items-center" for="cod">
                            <input class="form-check-input me-2" type="radio" name="payment_method" id="cod" value="cod"
                                checked>
                            <div>
                                <strong>Thanh toán khi nhận hàng (COD)</strong><br>
                                <small class="text-muted">Bạn sẽ thanh toán tiền mặt khi nhận sản phẩm.</small>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <div class="card h-100">
                        <label class="card-body d-flex align-items-center" for="vnpay">
                            <input class="form-check-input me-2" type="radio" name="payment_method" id="vnpay"
                                value="vnpay">
                            <div>
                                <strong>Chuyển khoản qua VNPay</strong><br>
                                <small class="text-muted">Thanh toán online an toàn, nhanh chóng qua VNPay.</small>
                            </div>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-right">
            <h4>Tổng cộng: {{ number_format($total, 0, ',', '.') }}₫</h4>
        </div>

        <button class="btn btn-success mt-3" id="buy-now-btn">Xác nhận và thanh toán</button>
    </div>

    {{-- CSRF token --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script>
        document.getElementById("buy-now-btn").addEventListener("click", function () {
            let paymentMethod = document.querySelector("input[name='payment_method']:checked")?.value;

            if (!paymentMethod) {
                alert("Vui lòng chọn phương thức thanh toán!");
                return;
            }

            let totalPrice = {{ $total }};
            let csrfToken = document.querySelector('meta[name="csrf-token"]').content;

            if (paymentMethod === "vnpay") {
                console.log("👉 Đang gửi yêu cầu thanh toán VNPay...");

                fetch("{{ route('vnpay.payment') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken
                    },
                    body: JSON.stringify({
                        product_id: 0,
                        product_name: "Thanh toán giỏ hàng",
                        color: null,
                        size: null,
                        quantity: 1,
                        price: totalPrice,
                        total_price: totalPrice,
                        bank_code: ""
                    })
                })
                    .then(res => res.json())
                    .then(data => {
                        console.log("✅ Phản hồi từ server:", data);
                        if (data.code === "00" && data.data) {
                            window.location.href = data.data;
                        } else {
                            alert("Không thể tạo thanh toán. Hãy thử lại!");
                        }
                    })
                    .catch(err => {
                        console.error("❌ Lỗi fetch:", err);
                        alert("Lỗi khi gửi yêu cầu đến VNPay!");
                    });
            } else {
                console.log("👉 Gửi yêu cầu thanh toán COD...");

                fetch("{{ route('order.cod') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken
                    },
                    body: JSON.stringify({})
                })
                    .then(res => res.json())
                    .then(data => {
                        console.log("✅ Phản hồi COD:", data);
                        if (data.status === "success") {
                            window.location.href = data.redirect;
                        } else {
                            alert(data.message || "Lỗi khi lưu đơn hàng COD.");
                        }
                    })
                    .catch(err => {
                        console.error("❌ Lỗi gửi COD:", err);
                        alert("Không thể gửi đơn hàng COD!");
                    });
            }

        });
    </script>


@endsection