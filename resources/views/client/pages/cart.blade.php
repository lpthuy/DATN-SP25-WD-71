@extends('client.layouts.main')

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
                            <!-- Cột sản phẩm chiếm toàn bộ chiều ngang -->
                            <div class="col-12">
                                <table class="table table-bordered cart-table">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" id="select-all"></th>
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
                                        @php
                                        use App\Models\Product;
                                        use App\Models\ProductVariant;

                                        $cartItems = session('cart', []);
                                        $validCartItems = [];

                                        foreach ($cartItems as $key => $item) {
                                        $productExists = Product::find($item['product_id']);
                                        $variantExists = ProductVariant::find($item['variant_id']);
                                        if ($productExists && $variantExists) {
                                        $validCartItems[$key] = $item;
                                        }
                                        }
                                        @endphp

                                        @if(count($validCartItems) > 0)
                                        @foreach($validCartItems as $cartKey => $item)
                                        <tr id="cart-item-{{ $cartKey }}">
                                            <td><input type="checkbox" class="cart-checkbox" data-id="{{ $cartKey }}"></td>
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

                        <!-- Thanh toán hiển thị ở dưới -->
                        <div class="fixed-total-container">
                            <div class="total-price-container">
                                Tổng tiền: <span id="total-price">0₫</span>
                            </div>
                            <form id="checkout-form" action="{{ route('checkout.show') }}" method="GET">
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

<!-- Scripts -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const checkboxes = document.querySelectorAll(".cart-checkbox");
        const checkoutForm = document.getElementById("checkout-form");
        const selectedProductsInput = document.getElementById("selected-products");
        const totalPriceElement = document.getElementById("total-price");
        const selectAllCheckbox = document.getElementById("select-all");

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
            localStorage.setItem("checkedItems", JSON.stringify(checkedItems));
            totalPriceElement.innerText = new Intl.NumberFormat('vi-VN').format(total) + "₫";
        }

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

        // Chọn tất cả
        selectAllCheckbox.addEventListener("change", function() {
            checkboxes.forEach(cb => cb.checked = this.checked);
            updateTotalPrice();
        });

        // Checkbox từng sản phẩm
        checkboxes.forEach(cb => {
            cb.addEventListener("change", function() {
                updateTotalPrice();
                if (!this.checked) selectAllCheckbox.checked = false;
            });
        });

        // Submit form thanh toán
        checkoutForm.addEventListener("submit", function(e) {
            let selected = [];
            checkboxes.forEach(cb => {
                if (cb.checked) {
                    let id = cb.getAttribute("data-id");
                    let quantity = document.querySelector(`.quantity-input[data-id='${id}']`).value;
                    selected.push({
                        cartKey: id,
                        quantity
                    });
                }
            });

            if (selected.length === 0) {
                alert("Vui lòng chọn ít nhất một sản phẩm để thanh toán!");
                e.preventDefault();
                return;
            }

            selectedProductsInput.value = JSON.stringify(selected);
        });

        // Xóa sản phẩm
        document.querySelectorAll(".remove-cart-item").forEach(btn => {
            btn.addEventListener("click", function() {
                const cartKey = this.getAttribute("data-id");
                fetch("/gio-hang/xoa", {
                        method: "POST",
                        headers: {
                            "X-Requested-With": "XMLHttpRequest",
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            cartKey
                        })
                    }).then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            document.querySelector(`#cart-item-${cartKey}`).remove();
                            updateTotalPrice();
                        }
                    });
            });
        });

        // Cập nhật số lượng
        document.querySelectorAll(".btn-quantity").forEach(btn => {
            btn.addEventListener("click", function() {
                const cartKey = this.getAttribute("data-id");
                const input = document.querySelector(`.quantity-input[data-id='${cartKey}']`);
                let newQty = parseInt(input.value);

                if (this.classList.contains("btn-increase")) newQty++;
                if (this.classList.contains("btn-decrease") && newQty > 1) newQty--;

                input.value = newQty;

                fetch("/gio-hang/cap-nhat", {
                        method: "POST",
                        headers: {
                            "X-Requested-With": "XMLHttpRequest",
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            cartKey,
                            quantity: newQty
                        })
                    }).then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            document.querySelector(`#cart-item-${cartKey} .cart-total`).innerText = data.item_total + "₫";
                            updateTotalPrice();
                        }
                    });
            });
        });

        // Kiểm tra lại giỏ hàng mỗi 5 giây
        setInterval(() => {
            fetch('{{ route("cart.recheck") }}', {
                    method: 'POST',
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                        "Content-Type": "application/json",
                    },
                }).then(res => res.json())
                .then(data => {
                    if (data.success) {
                        const validKeys = Object.keys(data.cart);
                        document.querySelectorAll('[id^="cart-item-"]').forEach(row => {
                            const cartKey = row.id.replace('cart-item-', '');
                            if (!validKeys.includes(cartKey)) {
                                row.remove();
                            }
                        });
                        let total = 0;
                        for (const item of Object.values(data.cart)) {
                            total += item.price * item.quantity;
                        }
                        totalPriceElement.innerText = new Intl.NumberFormat('vi-VN').format(total) + "₫";
                    }
                });
        }, 5000);

        restoreCheckedItems();
    });
</script>

<style>
    /* Tổng thể container thanh toán */
    .fixed-total-container {
        position: sticky;
        bottom: 0;
        background-color: #fff;
        padding: 15px;
        text-align: center;
        box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
        width: 100%;
        z-index: 1000;
    }

    .total-price-container {
        font-size: 18px;
        font-weight: bold;
        color: #333;
    }

    .btn-checkout {
        background-color: #e40046;
        color: white;
        padding: 12px 20px;
        border: none;
        font-size: 16px;
        width: 100%;
        margin-top: 10px;
        border-radius: 8px;
        transition: background 0.3s ease;
    }

    .btn-checkout:hover {
        background-color: #c3003b;
    }

    /* Bảng sản phẩm */
    .cart-table {
        width: 100%;
        border-collapse: collapse;
        border-radius: 10px;
        overflow: hidden;
        background: #fff;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    }

    .cart-table th,
    .cart-table td {
        text-align: center;
        vertical-align: middle;
        padding: 12px;
        border-bottom: 1px solid #f0f0f0;
    }

    .cart-table th {
        background-color: #f7f7f7;
        font-weight: 600;
    }

    .cart-image {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 6px;
    }

    .quantity-container {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .btn-quantity {
        width: 32px;
        height: 32px;
        border: none;
        background-color: #eee;
        cursor: pointer;
        font-size: 16px;
        border-radius: 5px;
        transition: background 0.2s;
    }

    .btn-quantity:hover {
        background-color: #ddd;
    }

    .quantity-input {
        width: 50px;
        text-align: center;
        border: 1px solid #ccc;
        margin: 0 5px;
        height: 32px;
        border-radius: 5px;
    }

    /* Nút XÓA */
    .remove-cart-item {
        background-color: #ff6b6b;
        color: white;
        border: none;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 14px;
        transition: background 0.3s ease;
    }

    .remove-cart-item:hover {
        background-color: #e55;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .cart-table thead {
            display: none;
        }

        .cart-table tr {
            display: block;
            margin-bottom: 15px;
            border: 1px solid #eee;
            border-radius: 10px;
            padding: 10px;
        }

        .cart-table td {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
        }

        .cart-table td::before {
            content: attr(data-label);
            font-weight: bold;
            flex: 1;
            color: #333;
        }

        .quantity-container {
            justify-content: flex-end;
        }

        .fixed-total-container {
            padding: 10px;
        }
    }
</style>

@endsection