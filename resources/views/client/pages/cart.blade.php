@extends('client.layouts.main')

@section('title', 'Giỏ hàng')

@section('content')
    <section class="bread-crumb">
        <div class="container">
            <ul class="breadcrumb" itemscope itemtype="https://schema.org/BreadcrumbList">
                <li class="home" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                    <a itemprop="item" href="index.html" title="Trang chủ">
                        <span itemprop="name">Trang chủ</span>
                        <meta itemprop="position" content="1" />
                    </a>
                </li>
                <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                    <strong itemprop="name">Giỏ hàng</strong>
                    <meta itemprop="position" content="2" />
                </li>
            </ul>
        </div>
    </section>

    <section class="main-cart-page main-container col1-layout">
        <div class="main container cartpcstyle">
            <div class="wrap_background_aside margin-bottom-40">
                <div class="header-cart">
                    <div class="heading-home">
                        <div class="site-animation">
                            <h1>Giỏ hàng của bạn</h1>
                        </div>
                    </div>
                </div>
                <div class="cart-page d-xl-block d-none">
                    <div class="drawer__inner">
                        <div class="CartPageContainer">
                            <div class="row">
                                <!-- Product Info Column -->
                                <div class="col-md-8 col-xs-12 col-sm-8">
                                    <div class="cart-header-info">
                                        <div>Thông tin sản phẩm</div>
                                        <div>Đơn giá</div>
                                        <div>Số lượng</div>
                                        <div>Thành tiền</div>
                                    </div>
                                    <div class="ajaxcart__inner ajaxcart__inner--has-fixed-footer cart_body items">
                                        @if(session()->has('cart') && count(session('cart')) > 0)
                                            @foreach(session('cart') as $index => $item)
                                                <div class="cart-row" id="cart-item-{{ $item['id'] }}">
                                                    <div class="cart-product">
                                                        @php
                                                            $images = explode(',', $item['image']);
                                                            $firstImage = isset($images[0]) ? trim($images[0]) : null;
                                                        @endphp
                                                        @if($firstImage)
                                                            <img src="{{ asset('storage/' . $firstImage) }}" alt="{{ $item['name'] }}" class="cart-image">
                                                        @else
                                                            <img src="{{ asset('images/no-image.png') }}" alt="Không có ảnh" class="cart-image">
                                                        @endif
                                                        <span>{{ $item['name'] }}</span>
                                                    </div>
                                                    <div class="cart-price">{{ number_format($item['price'], 0, ',', '.') }}₫</div>
                                                    <div class="cart-quantity">{{ $item['quantity'] }}</div>
                                                    <div class="cart-total">{{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}₫</div>

                                                    <!-- Nút xóa sản phẩm AJAX -->
                                                    <button class="btn btn-danger remove-cart-item" data-id="{{ $item['id'] }}">Xóa</button>
                                                </div>
                                            @endforeach
                                        @else
                                            <p>Giỏ hàng của bạn hiện tại chưa có sản phẩm nào.</p>
                                        @endif
                                    </div>
                                </div>

                                <!-- Order Summary Column -->
                                <div class="col-md-4 col-xs-12 col-sm-4">
                                    <div class="ajaxcart__footer ajaxcart__footer--fixed cart-footer">
                                        <div class="wamper_order_cart">
                                            <div class="order_block">
                                                <div class="order_title">
                                                    <h2>Thông tin đơn hàng</h2>
                                                </div>
                                                <div class="ajaxcart__subtotal">
                                                    <div class="cart__subtotal">
                                                        <div class="cart__col-6">Tổng tiền:</div>
                                                        <div class="text-right cart__totle">
                                                            <span id="total-price" class="total-price">
                                                                {{ number_format($totalPrice, 0, ',', '.') }}₫
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="cart__btn-proceed-checkout-dt">
                                                    <button onclick="location.href='/checkout'" type="button" class="button btn btn-default cart__btn-proceed-checkout" id="btn-proceed-checkout" title="Thanh toán">Thanh toán</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- JavaScript xử lý xóa sản phẩm bằng AJAX -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".remove-cart-item").forEach(button => {
        button.addEventListener("click", function () {
            let productId = this.getAttribute("data-id");
            let productRow = document.querySelector(`#cart-item-${productId}`);

            fetch("/remove-cart-item", {
                method: "POST",
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                },
                body: JSON.stringify({ product_id: productId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // 🔥 Xóa sản phẩm khỏi giao diện ngay lập tức
                    if (productRow) {
                        productRow.remove();
                    }

                    // 🔥 Cập nhật tổng tiền ngay lập tức
                    let totalPriceElement = document.querySelector("#total-price");
                    if (totalPriceElement) {
                        totalPriceElement.innerText = data.total_price + "₫";
                    }

                    // 🔥 Kiểm tra nếu giỏ hàng trống, hiển thị thông báo
                    let cartItems = document.querySelectorAll(".cart-row");
                    if (cartItems.length === 0) {
                        document.querySelector(".cart_body").innerHTML = "<p>Giỏ hàng của bạn hiện tại chưa có sản phẩm nào.</p>";
                    }
                } else {
                    alert("Lỗi khi xóa sản phẩm. Vui lòng thử lại!");
                }
            })
            .catch(error => console.error("Lỗi:", error));
        });
    });
});

    </script>
@endsection
