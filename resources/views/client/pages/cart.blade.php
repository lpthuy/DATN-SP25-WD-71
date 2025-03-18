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
                                <!-- Cột thông tin sản phẩm -->
                                <div class="col-md-8">
                                    <table class="table table-bordered cart-table">
                                        <thead>
                                            <tr>
                                                <th><input type="checkbox" id="select-all"></th> <!-- Ô chọn tất cả -->
                                                <th>Hình ảnh</th>
                                                <th>Tên sản phẩm</th>
                                                <th>Màu sắc</th>
                                                <th>Size</th>
                                                <th>Đơn giá</th>
                                                <th>Số lượng</th>
                                                <th>Thành tiền</th>
                                                <th>Hành động</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(session()->has('cart') && count(session('cart')) > 0)
                                                @foreach(session('cart') as $cartKey => $item)
                                                    <tr id="cart-item-{{ $cartKey }}">
                                                        <td>
                                                            <input type="checkbox" class="cart-checkbox" data-id="{{ $cartKey }}">
                                                        </td>
                                                        <td>
                                                            @php
                                                                $image = isset($item['image']) ? explode(',', $item['image'])[0] : 'default.png';
                                                            @endphp
                                                            <img src="{{ asset('storage/' . $image) }}" alt="{{ $item['name'] }}" class="cart-image">
                                                        </td>
                                                        <td>{{ $item['name'] }}</td>
                                                        <td>{{ $item['color'] }}</td>
                                                        <td>{{ $item['size'] }}</td>
                                                        <td>{{ number_format($item['price'], 0, ',', '.') }}₫</td>
                                                        <td>
                                                            <div class="quantity-container">
                                                                <button class="btn-quantity btn-decrease" data-id="{{ $cartKey }}">-</button>
                                                                <input type="number" value="{{ $item['quantity'] }}" min="1" class="quantity-input" data-id="{{ $cartKey }}">
                                                                <button class="btn-quantity btn-increase" data-id="{{ $cartKey }}">+</button>
                                                            </div>
                                                        </td>
                                                        <td class="cart-total">{{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}₫</td>
                                                        <td>
                                                            <button class="btn btn-danger remove-cart-item" data-id="{{ $cartKey }}">Xóa</button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="9" class="text-center">Giỏ hàng của bạn hiện tại chưa có sản phẩm nào.</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                        
                                    </table>
                                </div>
                            </div>
                            
                            <!-- Thanh toán hiển thị ở dưới giống Shopee -->
                            <div class="fixed-total-container">
                                <div class="total-price-container">
                                    Tổng tiền: <span id="total-price">0₫</span>
                                </div>
                                <form id="checkout-form" action="{{ route('checkout') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="selected_products" id="selected-products">
                                    <button type="submit" class="btn btn-checkout">Thanh toán</button>
                                </form>
                                
                            </div>
                            
                            
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
    let checkboxes = document.querySelectorAll(".cart-checkbox");
    let checkoutForm = document.getElementById("checkout-form");
    let selectedProductsInput = document.getElementById("selected-products");

    checkoutForm.addEventListener("submit", function (event) {
        let selectedProducts = [];
        checkboxes.forEach(checkbox => {
            if (checkbox.checked) {
                let cartKey = checkbox.getAttribute("data-id");
                let quantity = document.querySelector(`.quantity-input[data-id='${cartKey}']`).value;
                selectedProducts.push({ cartKey, quantity });
            }
        });

        if (selectedProducts.length === 0) {
            alert("Vui lòng chọn ít nhất một sản phẩm để thanh toán!");
            event.preventDefault(); // Ngăn chặn submit nếu không có sản phẩm nào
            return;
        }

        selectedProductsInput.value = JSON.stringify(selectedProducts);
    });
});

    </script>

    <!-- JavaScript xử lý xóa sản phẩm bằng AJAX -->
    <script>
       document.addEventListener("DOMContentLoaded", function () {
    let checkboxes = document.querySelectorAll(".cart-checkbox");
    let selectAllCheckbox = document.getElementById("select-all");
    let totalPriceElement = document.getElementById("total-price");

    // Khôi phục trạng thái checkbox từ LocalStorage
    function restoreCheckedItems() {
        let checkedItems = JSON.parse(localStorage.getItem("checkedItems")) || {};
        checkboxes.forEach(checkbox => {
            let cartKey = checkbox.getAttribute("data-id");
            if (checkedItems[cartKey]) {
                checkbox.checked = true;
            }
        });
        updateTotalPrice();
    }

    // Cập nhật tổng tiền khi tick checkbox
    function updateTotalPrice() {
        let total = 0;
        let checkedItems = {};
        checkboxes.forEach(checkbox => {
            let cartKey = checkbox.getAttribute("data-id");
            if (checkbox.checked) {
                let itemTotal = parseFloat(document.querySelector(`#cart-item-${cartKey} .cart-total`).innerText.replace(/\D/g, ''));
                total += itemTotal;
                checkedItems[cartKey] = true;
            } else {
                checkedItems[cartKey] = false;
            }
        });
        localStorage.setItem("checkedItems", JSON.stringify(checkedItems)); // Lưu vào LocalStorage
        totalPriceElement.innerText = new Intl.NumberFormat('vi-VN').format(total) + "₫";
    }

    // Xử lý chọn tất cả sản phẩm
    selectAllCheckbox.addEventListener("change", function () {
        let isChecked = this.checked;
        checkboxes.forEach(checkbox => {
            checkbox.checked = isChecked;
        });
        updateTotalPrice();
    });

    // Xử lý tick checkbox của từng sản phẩm
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener("change", function () {
            updateTotalPrice();
            if (!this.checked) {
                selectAllCheckbox.checked = false; // Bỏ chọn "Chọn tất cả" nếu bỏ tick một sản phẩm
            }
        });
    });

    // Xử lý xóa sản phẩm
    document.querySelectorAll(".remove-cart-item").forEach(button => {
        button.addEventListener("click", function () {
            let cartKey = this.getAttribute("data-id");
            fetch("/gio-hang/xoa", {
                method: "POST",
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                },
                body: JSON.stringify({ cartKey: cartKey })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.querySelector(`#cart-item-${cartKey}`).remove();
                    updateTotalPrice();
                }
            })
            .catch(error => console.error("Lỗi:", error));
        });
    });

    // Xử lý thay đổi số lượng sản phẩm
    document.querySelectorAll(".btn-quantity").forEach(button => {
        button.addEventListener("click", function () {
            let cartKey = this.getAttribute("data-id");
            let quantityInput = document.querySelector(`.quantity-input[data-id='${cartKey}']`);
            let newQuantity = parseInt(quantityInput.value);

            if (this.classList.contains("btn-increase")) {
                newQuantity++;
            } else if (this.classList.contains("btn-decrease") && newQuantity > 1) {
                newQuantity--;
            }

            quantityInput.value = newQuantity;

            fetch("/gio-hang/cap-nhat", {
                method: "POST",
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                },
                body: JSON.stringify({ cartKey: cartKey, quantity: newQuantity })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.querySelector(`#cart-item-${cartKey} .cart-total`).innerText = data.item_total + "₫";
                    updateTotalPrice();
                }
            })
            .catch(error => console.error("Lỗi:", error));
        });
    });

    // Gọi hàm khôi phục trạng thái tick khi tải lại trang
    restoreCheckedItems();
});

    </script>
@endsection

    <style>
        .fixed-total-container {
    position: sticky;  /* Giữ cố định trong viewport */
    bottom: 0;
    background-color: white; /* Giữ nền trắng */
    padding: 15px;
    text-align: center;
    box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1); /* Đổ bóng nhẹ */
    width: 100%;
    z-index: 1000; /* Đảm bảo không bị che phủ */
}

.total-price-container {
    font-size: 18px;
    font-weight: bold;
    color: #000;
}

.btn-checkout {
    background-color: #e40046;
    color: white;
    padding: 10px 20px;
    border: none;
    font-size: 16px;
    width: 100%;
}

        .cart-table th, .cart-table td {
            text-align: center;
            vertical-align: middle;
        }
        .cart-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
        }
        .quantity-container {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .btn-quantity {
            width: 30px;
            height: 30px;
            border: none;
            background-color: #ddd;
            cursor: pointer;
        }
        .quantity-input {
            width: 40px;
            text-align: center;
            border: 1px solid #ccc;
            margin: 0 5px;
        }
        .order-summary {
            border: 1px solid #ddd;
            padding: 15px;
            text-align: center;
        }
        .order-total {
            font-size: 18px;
            font-weight: bold;
            margin: 15px 0;
        }
        .btn-checkout {
            background-color: #e40046;
            color: white;
            padding: 10px;
            border: none;
            width: 100%;
        }
    </style>
