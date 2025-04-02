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
                                                            <a href="{{ route('order.detail', ['id' => $order->id]) }}" class="btn btn-sm btn-primary">
                                                                Xem chi tiết
                                                            </a>
                                                        </td>
                                                        <td>
                                                            @if ($order->status === 'processing')
                                                                <span class="badge badge-info">Đang xử lý</span>
                                                            @elseif ($order->status === 'shipping')
                                                                <span class="badge badge-primary">Đang giao hàng</span>
                                                            @elseif ($order->status === 'completed')
                                                                <span class="badge badge-success">Đã giao hàng</span>
                                                            @elseif ($order->status === 'cancelled')
                                                                <span class="badge badge-danger">Đã hủy</span>
                                                            @else
                                                                <span class="badge badge-secondary">{{ ucfirst($order->status) }}</span>
                                                            @endif
                                                        </td>
                                                        
                                                        
                                                        <td>
                                                            @if($order->status !== 'cancelled')
                                                            <button class="btn btn-cancel-order" onclick="showCancelModal({{ $order->id }})">
                                                                <i class="fas fa-times-circle"></i> Huỷ đơn
                                                            </button>
                                                            
                                                            @else
                                                                <span class="badge badge-danger">Đã huỷ</span>
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
    

<!-- Modal chọn lý do hủy -->
<div id="cancelModal" class="cancel-modal" style="display: none;">
    <div class="cancel-modal-content">
        <h5 class="cancel-title">📝 Lý do hủy đơn hàng</h5>
        <form id="cancelForm">
            <div class="cancel-reason">
                <label><input type="radio" name="reason" value="Tôi không còn nhu cầu"> Tôi không còn nhu cầu</label>
                <label><input type="radio" name="reason" value="Tôi đặt nhầm"> Tôi đặt nhầm</label>
                <label><input type="radio" name="reason" value="Thời gian giao hàng quá lâu"> Thời gian giao hàng quá lâu</label>
                <label><input type="radio" name="reason" value="Lý do khác" id="other-reason-radio"> Lý do khác</label>
                <textarea id="customReason" placeholder="Nhập lý do khác..." rows="3" style="display: none;"></textarea>
            </div>

            <input type="hidden" id="cancelOrderId">

            <div class="cancel-actions">
                <button type="button" onclick="submitCancelReason()" class="btn btn-danger">Xác nhận</button>
                <button type="button" onclick="closeCancelModal()" class="btn btn-secondary">Đóng</button>
            </div>
        </form>
    </div>
</div>




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
        radio.addEventListener('change', function () {
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
        .cancel-modal {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: rgba(0, 0, 0, 0.4); /* nền mờ tối */
            z-index: 9999;
        }
        
        .cancel-modal-content {
            background-color: #fff;
            padding: 24px 28px;
            border-radius: 12px;
            max-width: 420px;
            width: 100%;
            box-shadow: 0 8px 20px rgba(0,0,0,0.3);
            animation: slideIn .3s ease;
        }
        
        .cancel-title {
            margin-bottom: 16px;
            font-size: 18px;
            font-weight: 600;
        }
        
        .cancel-reason label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
        }
        
        .cancel-reason textarea {
            margin-top: 8px;
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            resize: vertical;
        }
        
        .cancel-actions {
            text-align: right;
            margin-top: 16px;
        }
        
        .cancel-actions .btn {
            padding: 8px 16px;
            border-radius: 6px;
            font-weight: bold;
            font-size: 14px;
            margin-left: 10px;
        }
        
        .cancel-actions .btn-danger {
            background-color: #e3342f;
            color: white;
            border: none;
        }
        
        .cancel-actions .btn-secondary {
            background-color: #f1f1f1;
            color: #333;
            border: 1px solid #ccc;
        }
        
        /* Hiệu ứng */
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
        </style>
        
        
@endsection
