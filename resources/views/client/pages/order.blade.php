@extends('client.layouts.main')

@section('title', 'Đơn hàng')

@section('content')
<section class="bread-crumb">
    <div class="container">
        <ul class="breadcrumb" itemscope itemtype="https://schema.org/BreadcrumbList">
            <li class="home" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                <a itemprop="item" href="{{ route('home') }}" title="Trang chủ">
                    <span itemprop="name">Trang chủ</span>
                    <meta itemprop="position" content="1" />
                </a>
            </li>
            <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                <strong itemprop="name">Đơn hàng</strong>
                <meta itemprop="position" content="2" />
            </li>
        </ul>
    </div>
</section>

<section class="signup page_customer_account">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-12 col-left-ac">
                <div class="block-account">
                    <h5 class="title-account">Trang tài khoản</h5>
                    <p>
                        Xin chào, <span style="color:#f02757;">{{ Auth::user()->name }}</span>!
                    </p>
                    <p><strong>Số điện thoại:</strong> {{ Auth::user()->phone }}</p>
                    <p><strong>Địa chỉ:</strong> {{ Auth::user()->address }}</p>
                    <ul>
                        <li><a class="title-info" href="{{ route('profile') }}">Thông tin tài khoản</a></li>
                        <li><a class="title-info active" href="javascript:void(0);">Đơn hàng của bạn</a></li>
                        <li><a class="title-info" href="{{ route('changePassword') }}">Đổi mật khẩu</a></li>
                    </ul>
                </div>
            </div>

            <div class="col-lg-9 col-12 col-right-ac">
                <h1 class="title-head margin-top-0">Đơn hàng của bạn</h1>

                <div class="my-account">
                    <div class="dashboard">
                        <div class="recent-orders">
                            <div class="table-responsive-block tab-all" style="overflow-x:auto;">
                                <table class="table table-cart table-order" id="my-orders-table">
                                    <thead class="thead-default">
                                        <tr>
                                            <th>Mã đơn hàng</th>
                                            <th>Ngày</th>
                                            <th>Thanh toán</th>
                                            <th>Xem chi tiết</th>
                                            <th>Trạng thái</th> <!-- thêm mới -->
                                            <th>Hành động</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @if($orders->count() > 0)
                                        @foreach ($orders as $order)
                                        <tr>
                                            <td>#{{ $order->order_code }}</td>
                                            <td>{{ date('d/m/Y', strtotime($order->created_at)) }}</td>
                                            <td>
                                                @php
                                                $method = strtolower($order->payment_method);
                                                @endphp

                                                @if($method === 'cod')
                                                <span class="badge badge-warning">COD</span>
                                                @elseif($method === 'vnpay')
                                                <span class="badge badge-success">Chuyển khoản</span>
                                                @else
                                                <span class="badge badge-secondary">{{ $order->payment_method }}</span>
                                                @endif

                                            </td>

                                            <td>
                                                <a href="{{ route('order.detail', ['id' => $order->id]) }}" >
                                                    Xem chi tiết
                                                </a>
                                            </td>
                                            <td>
                                                <span class="badge order-status-badge" id="order-status-{{ $order->id }}">
                                                    @if ($order->status === 'processing')
                                                    Đang xử lý
                                                    @elseif ($order->status === 'shipping')
                                                    Đang giao hàng
                                                    @elseif ($order->status === 'completed')
                                                    Đã giao hàng
                                                    @elseif ($order->status === 'cancelled')
                                                    Đã hủy
                                                    @else
                                                    {{ ucfirst($order->status) }}
                                                    @endif
                                                </span>
                                            </td>



                                            <td id="order-action-{{ $order->id }}">
                                                @php
                                                $status = strtolower($order->status);
                                                @endphp

                                                @if($status === 'processing' || $status === 'đang xử lý')
                                                <button class="btn btn-sm btn-danger" onclick="showCancelModal({{ $order->id }})">
                                                    <i class="fas fa-times-circle"></i> Huỷ đơn
                                                </button>

                                                @elseif($status === 'completed' || $status === 'đã giao thành công')
                                                <form action="{{ route('order.received', $order->id) }}" method="POST" style="display:inline-block;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success">
                                                        <i class="fas fa-check-circle"></i> Đã nhận hàng
                                                    </button>
                                                </form>

                                                @elseif($status === 'cancelled' || $status === 'đã huỷ')
                                                <span class="badge badge-danger">Đã huỷ</span>

                                                @else
                                                <span class="text-muted">Không có hành động</span>
                                                @endif
                                            </td>


                                        </tr>
                                        @endforeach
                                        @else
                                        <tr>


                                            <td colspan="7">

                                                <p class="text-center">Bạn chưa có đơn hàng nào.</p>
                                            </td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>

                            <div class="paginate-pages pull-right page-account text-right col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                {{ $orders->links() }}

                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</section>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll(".order-status-badge").forEach(function(badge) {
            const orderId = badge.id.replace("order-status-", "");
            const actionCell = document.getElementById("order-action-" + orderId);

            function renderActionByStatus(statusText) {
                const status = statusText.trim().toLowerCase();

                if (status.includes("đang xử lý")) {
                    actionCell.innerHTML = `

                            <button class="btn btn-sm btn-danger" onclick="showCancelModal(${orderId})">
                                <i class="fas fa-times-circle"></i> Huỷ đơn
                            </button>
                        `;

                } else if (status.includes("giao thành công") || status.includes("đã giao") || status.includes("hoàn tất")) {
                    actionCell.innerHTML = `
                            <form action="/orders/${orderId}/received" method="POST" style="display:inline-block;">

                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <button type="submit" class="btn btn-sm btn-success">
                                    <i class="fas fa-check-circle"></i> Đã nhận hàng
                                </button>
                            </form>
    
                            <button class="btn btn-sm btn-outline-danger" style="margin-left: 5px;" onclick="showReturnModal(${orderId})">
                                <i class="fas fa-undo-alt"></i> Hoàn hàng
                            </button>
                        `;
                } else if (status.includes("hủy") || status.includes("đã huỷ")) {
                    actionCell.innerHTML = `<span class="badge badge-danger">Đã huỷ</span>`;
                } else {
                    actionCell.innerHTML = `<span class="text-muted">Không có hành động</span>`;
                }

            }

            // Gọi lần đầu
            renderActionByStatus(badge.innerText);

            // Kiểm tra định kỳ mỗi 2 giây nếu có thay đổi nội dung trạng thái
            setInterval(() => {
                renderActionByStatus(badge.innerText);
            }, 2000);
        });
    });
</script>





    <!-- Modal hoàn hàng -->
<div id="returnModal" class="cancel-modal" style="display: none;">
    <div class="cancel-modal-content">
        <h5 class="cancel-title">📦 Lý do hoàn hàng</h5>
        <form id="returnForm" enctype="multipart/form-data" method="POST" action="{{ route('order.return', ['id' => 0]) }}">
            @csrf
            <input type="hidden" name="order_id" id="returnOrderId">

            <div class="form-group">
                <label for="return_reason">Lý do:</label>
                <textarea class="form-control" name="return_reason" id="return_reason" rows="3" required></textarea>
            </div>

            <div class="form-group mt-3">
                <label for="return_media">Ảnh/Video hàng lỗi:</label>
                <input type="file" class="form-control" name="return_media" accept="image/*,video/*" required>
            </div>

            <div class="cancel-actions">
                <button type="submit" class="btn btn-danger">Gửi hoàn hàng</button>
                <button type="button" onclick="closeReturnModal()" class="btn btn-secondary">Đóng</button>
            </div>
        </form>
    </div>
</div>

<script>

    document.addEventListener("DOMContentLoaded", function() {
        const badges = document.querySelectorAll('.order-status-badge');

        setInterval(() => {
            badges.forEach(badge => {
                const id = badge.id.replace('order-status-', '');

                fetch(`/api/order-status/${id}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.status) {
                            let text = '';
                            let classList = 'badge order-status-badge ';

                            switch (data.status) {
                                case 'processing':
                                    text = 'Đang xử lý';
                                    classList += 'badge-info';
                                    break;
                                case 'shipping':
                                    text = 'Đang giao hàng';
                                    classList += 'badge-primary';
                                    break;
                                case 'completed':
                                    text = 'Đã giao hàng';
                                    classList += 'badge-success';
                                    break;
                                case 'cancelled':
                                    text = 'Đã hủy';
                                    classList += 'badge-danger';
                                    break;
                                default:
                                    text = data.status;
                                    classList += 'badge-secondary';
                            }


</script>
    

    <!-- Modal chọn lý do hủy -->
    <div id="cancelModal" class="cancel-modal" style="display: none;">
        <div class="cancel-modal-content">
            <h5 class="cancel-title">📝 Lý do hủy đơn hàng</h5>
            <form id="cancelForm">
                <div class="cancel-reason">
                    <label><input type="radio" name="reason" value="Tôi không còn nhu cầu"> Tôi không còn nhu cầu</label>
                    <label><input type="radio" name="reason" value="Tôi đặt nhầm"> Tôi đặt nhầm</label>
                    <label><input type="radio" name="reason" value="Thời gian giao hàng quá lâu"> Thời gian giao hàng quá
                        lâu</label>
                    <label><input type="radio" name="reason" value="Lý do khác" id="other-reason-radio"> Lý do khác</label>
                    <textarea id="customReason" placeholder="Nhập lý do khác..." rows="3" style="display: none;"></textarea>
                </div>

                <input type="hidden" id="cancelOrderId">


<script>
    function showCancelModal(orderId) {
        document.getElementById('cancelOrderId').value = orderId;
        document.getElementById('cancelModal').style.display = 'block';
    }

    function closeCancelModal() {
        document.getElementById('cancelModal').style.display = 'none';
        document.querySelectorAll('input[name="reason"]').forEach(el => el.checked = false);
        document.getElementById('customReason').value = '';
        document.getElementById('customReason').style.display = 'none';
    }

    document.querySelectorAll('input[name="reason"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const custom = document.getElementById('customReason');
            if (this.value === 'Lý do khác') {
                custom.style.display = 'block';
            } else {
                custom.style.display = 'none';
            }
        });
    });

    function submitCancelReason() {
        const orderId = document.getElementById('cancelOrderId').value;
        const selected = document.querySelector('input[name="reason"]:checked');
        let reason = '';

        if (!selected) {
            alert('Vui lòng chọn lý do hủy đơn!');
            return;
        }

        if (selected.value === 'Lý do khác') {
            reason = document.getElementById('customReason').value.trim();
            if (!reason) {
                alert('Vui lòng nhập lý do cụ thể!');

                return;
            }

            if (selected.value === 'Lý do khác') {
                reason = document.getElementById('customReason').value.trim();
                if (!reason) {
                    alert('Vui lòng nhập lý do cụ thể!');
                    return;
                }
            } else {
                reason = selected.value;
            }

            fetch("{{ route('order.cancel') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    order_id: orderId,
                    cancel_reason: reason
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        alert(data.message);
                        location.reload();
                    } else {
                        alert(data.message || 'Huỷ thất bại!');
                    }
                })
                .catch(error => {
                    alert('Có lỗi xảy ra khi gửi yêu cầu.');
                    console.error(error);
                });

        }


        fetch("{{ route('order.cancel') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    order_id: orderId,
                    cancel_reason: reason
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alert(data.message);
                    location.reload();
                } else {
                    alert(data.message || 'Huỷ thất bại!');
                }
            })
            .catch(error => {
                alert('Có lỗi xảy ra khi gửi yêu cầu.');
                console.error(error);
            });

    }
</script>





<style>
    .btn-cancel-order {
        background-color: #ffe6e6;
        color: #d9534f;
        border: 1px solid #d9534f;
        padding: 5px 12px;
        font-size: 14px;
        border-radius: 4px;
        transition: 0.3s ease;
        font-weight: bold;
    }

    .btn-cancel-order i {
        margin-right: 5px;
    }

    .btn-cancel-order:hover {
        background-color: #d9534f;
        color: white;
        box-shadow: 0 0 5px rgba(217, 83, 79, 0.5);
    }
</style>

<style>
    /* Reset và biến chung */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Roboto', sans-serif;
    }

    body {
        background-color: #f8f9fa;
        color: #333;
        line-height: 1.6;
        padding-top: 20px;
    }

    /* Breadcrumbs */
    .breadcrumb {
        background: #fff;
        padding: 15px;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .breadcrumb a {
        color: #007bff;
        text-decoration: none;
    }

    .breadcrumb a:hover {
        text-decoration: underline;
    }

    /* Sidebar tài khoản */
    .block-account {
        background: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
    }

    .title-account {
        color: #f02757;
        margin-bottom: 15px;
        font-size: 18px;
        font-weight: 600;
    }

    .block-account p {
        margin-bottom: 10px;
        color: #666;
    }

    .block-account ul {
        list-style: none;
    }

    .block-account ul li {
        margin-bottom: 10px;
    }

    .title-info {
        color: #007bff;
        text-decoration: none;
        font-weight: 500;
    }

    .title-info.active {
        color: #f02757;
        font-weight: 600;
    }

    .title-info:hover {
        text-decoration: underline;
    }

    /* Phần chính (Right) */
    .title-head {
        color: #333;
        font-size: 24px;
        margin-bottom: 20px;
        font-weight: 600;
    }

    /* Bảng đơn hàng */
    .table-order {
        width: 100%;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .table-order th {
        background-color: #007bff;
        color: white;
        padding: 15px;
        text-align: center;
    }

    .table-order td {
        padding: 15px;
        text-align: center;
        border-bottom: 1px solid #eee;
    }

    .table-order tr:hover {
        background-color: #f8f9fa;
        cursor: pointer;
    }

    .badge {
        padding: 8px 12px;
        border-radius: 4px;
        font-weight: 500;
    }

    .badge-warning {
        background: #ffc107;
        color: #000;
    }

    .badge-success {
        background: #28a745;
        color: #fff;
    }

    .badge-danger {
        background: #dc3545;
        color: #fff;
    }

    .badge-secondary {
        background: #6c757d;
        color: #fff;
    }

    /* Nút hành động */
    .btn {
        padding: 8px 15px;
        border-radius: 4px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-primary {
        background: #007bff;
        color: #fff;
        border: none;
    }

    .btn-primary:hover {
        background: #0056b3;
    }

    .btn-danger {
        background: #dc3545;
        color: #fff;
        border: none;
    }

    .btn-danger:hover {
        background: #c82333;
    }

    .btn-success {
        background: #28a745;
        color: #fff;
        border: none;
    }

    .btn-success:hover {
        background: #218838;
    }

    /* Modal hủy đơn */
    .cancel-modal {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 1000;
    }

    .cancel-modal-content {
        background: #fff;
        padding: 20px;
        border-radius: 10px;
        width: 90%;
        max-width: 500px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        animation: slideIn 0.3s ease;
    }

    .cancel-title {
        color: #f02757;
        margin-bottom: 15px;
        font-size: 20px;
        font-weight: 600;
    }

    .cancel-reason label {
        display: block;
        margin-bottom: 10px;
        color: #333;
        cursor: pointer;
    }

    .cancel-reason input[type="radio"] {
        margin-right: 8px;
    }

    .cancel-reason textarea {
        width: 100%;
        padding: 10px;
        margin-top: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        resize: vertical;
    }

    .cancel-actions {
        margin-top: 20px;
        text-align: right;
    }

    .cancel-actions .btn {
        margin-left: 10px;
    }

    /* Animation */
    @keyframes slideIn {
        from {
            transform: translateY(-50px);
            opacity: 0;
        }

        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    /* Responsive */
    @media (max-width: 768px) {

        .col-left-ac,
        .col-right-ac {

            width: 100%;
        }


        .table-order th,
        .table-order td {
            padding: 10px;
            font-size: 14px;
        }

        .title-head {
            font-size: 20px;
        }
    }
</style>



@endsection