@extends('client.layouts.main')

@section('title', 'Xác nhận đơn hàng')

@section('content')
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Roboto', sans-serif;
        }

        body {
            background: #f5f5f5;
            color: #333;
        }

        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
        }

        .checkout-wrapper {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        .checkout-left, .checkout-right {
            flex: 1;
            min-width: 300px;
        }

        .section-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 15px;
            color: #333;
        }

        /* Thông tin người mua */
        .buyer-info {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .buyer-info input {
            width: 100%;
            padding: 10px;
            margin: 5px 0 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
        }

        /* Phần sản phẩm và thanh toán */
        .order-details {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .product-item {
            display: flex;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }

        .product-item img {
            width: 50px;
            height: 50px;
            margin-right: 15px;
        }

        .product-info {
            flex: 1;
        }

        .product-info p {
            margin: 0;
            font-size: 0.9rem;
        }

        .product-price {
            font-weight: 600;
            color: #333;
        }

        /* Mã giảm giá */
        .coupon-section {
            margin: 20px 0;
        }

        .coupon-section input {
            width: 70%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
        }

        .coupon-section button {
            width: 28%;
            padding: 10px;
            background: #dc3545; /* Đổi sang màu đỏ */
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .coupon-section button:hover {
            background: #c82333; /* Màu đỏ đậm hơn khi hover */
        }

        /* Phương thức thanh toán */
        .payment-methods {
            margin: 20px 0;
        }

        .payment-option {
            display: flex;
            align-items: center;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 10px;
            cursor: pointer;
            transition: border 0.3s ease;
        }

        .payment-option:hover {
            border-color: #dc3545; /* Đổi viền sang màu đỏ khi hover */
        }

        .payment-option input {
            margin-right: 10px;
        }

        .payment-option img {
            width: 30px;
            margin-left: 10px;
        }

        /* Tổng tiền */
        .order-summary {
            margin: 20px 0;
        }

        .order-summary p {
            display: flex;
            justify-content: space-between;
            font-size: 1rem;
            margin: 5px 0;
        }

        .order-summary .total {
            font-weight: 600;
            font-size: 1.2rem;
            color: #dc3545; /* Đổi tổng tiền sang màu đỏ */
        }

        /* Nút đặt hàng */
        .action-buttons {
            display: flex;
            justify-content: space-between;
            gap: 10px;
        }

        .action-buttons a, .action-buttons button {
            flex: 1;
            padding: 12px;
            text-align: center;
            border-radius: 5px;
            font-size: 1rem;
            text-decoration: none;
            transition: background 0.3s ease;
        }

        .action-buttons a {
            background: #f5f5f5;
            color: #dc3545; /* Đổi màu chữ nút "Quay về giỏ hàng" sang đỏ */
            border: 1px solid #ddd;
        }

        .action-buttons a:hover {
            background: #e9ecef;
        }

        .action-buttons button {
            background: #dc3545; /* Đổi nút "Đặt hàng" sang màu đỏ */
            color: white;
            border: none;
            cursor: pointer;
        }

        .action-buttons button:hover {
            background: #c82333; /* Màu đỏ đậm hơn khi hover */
        }

        /* Responsive */
        @media (max-width: 768px) {
            .checkout-wrapper {
                flex-direction: column;
            }

            .coupon-section input, .coupon-section button {
                width: 100%;
                margin: 5px 0;
            }

            .action-buttons {
                flex-direction: column;
            }
        }
    </style>

    <div class="container">
        <div class="checkout-wrapper">
            <!-- Bên trái: Thông tin người mua -->
            <div class="checkout-left">
                <div class="section-title">Thông tin nhận hàng</div>
                <div class="buyer-info">
                    <input type="email" value="{{ $user->email ?? 'Chưa có' }}" placeholder="Email" readonly>
                    <input type="text" value="{{ $user->name ?? 'Chưa đăng nhập' }}" placeholder="Họ và tên" readonly>
                    <input type="text" value="{{ $user->phone ?? 'Chưa có' }}" placeholder="Số điện thoại" readonly>
                    <input type="text" value="{{ $user->address ?? 'Chưa có' }}" placeholder="Địa chỉ" readonly>
                    <!-- Đã bỏ 3 cột Tỉnh thành, Quận huyện, Phường xã -->
                </div>
            </div>

            <!-- Bên phải: Sản phẩm và thanh toán -->
            <div class="checkout-right">
                <div class="section-title">Vận chuyển</div>
                <div class="order-details">
                    <p style="background: #e7f3ff; padding: 10px; border-radius: 5px;">
                        Đang bắt đầu thông tin giao hàng
                    </p>

                    <!-- Sản phẩm -->
                    @foreach ($checkoutItems as $item)
                        <div class="product-item">
                            <img src="https://via.placeholder.com/50" alt="Product Image">
                            <div class="product-info">
                                <p>{{ $item['name'] }}</p>
                                <p>{{ $item['color'] }} / {{ $item['size'] }}</p>
                            </div>
                            <div class="product-price">
                                {{ number_format($item['total_price'], 0, ',', '.') }}₫
                            </div>
                        </div>
                    @endforeach

                    <!-- Mã giảm giá -->
                    <div class="coupon-section">
                        <input type="text" id="coupon-code" placeholder="Nhập mã giảm giá">
                        <button onclick="applyCoupon()">Áp dụng</button>
                    </div>
                    <div id="coupon-message" class="text-danger mt-1"></div>

                    <!-- Phương thức thanh toán -->
                    <div class="section-title">Thanh toán</div>
                    <div class="payment-methods">
                        <label class="payment-option">
                            <input type="radio" name="payment_method" id="vnpay" value="vnpay" checked>
                            Thanh toán qua VNPAY-QR
                            <img src="https://via.placeholder.com/30" alt="VNPay">
                        </label>
                        <label class="payment-option">
                            <input type="radio" name="payment_method" id="cod" value="cod">
                            Thanh toán khi nhận hàng (COD)
                            <img src="https://via.placeholder.com/30" alt="COD">
                        </label>
                    </div>

                    <!-- Tổng tiền -->
                    <div class="order-summary">
                        <p>Tạm tính <span>{{ number_format($total, 0, ',', '.') }}₫</span></p>
                        <p>Phí vận chuyển <span>-</span></p>
                        <p class="total">Tổng cộng <span id="total-price">{{ number_format($total, 0, ',', '.') }}₫</span></p>
                    </div>

                    <!-- Nút đặt hàng -->
                    <div class="action-buttons">
                        <a href="#">Quay về giỏ hàng</a>
                        <button id="buy-now-btn">Đặt hàng</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CSRF token -->
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

        function applyCoupon() {
            const code = document.getElementById('coupon-code').value.trim();
            const messageEl = document.getElementById('coupon-message');
            let total = {{ $total }};

            fetch('{{ route('apply.coupon') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ code: code, total: total })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const newTotal = total - data.discount;
                    fetch('{{ route('save.promo.code') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ code: code })
                    });

                    document.getElementById('total-price').innerText = new Intl.NumberFormat('vi-VN').format(newTotal) + '₫';
                    messageEl.innerHTML = `<span class="text-success">${data.message} - Giảm ${data.discount.toLocaleString()}₫</span>`;
                } else {
                    messageEl.innerText = data.message;
                }
            })
            .catch(error => {
                console.error('Lỗi:', error);
                messageEl.innerText = 'Lỗi khi áp dụng mã!';
            });
        }
    </script>
@endsection