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
                                                <th>Sản phẩm</th>
                                                <th>Số lượng</th>
                                                <th>Giá</th>
                                                <th>Thanh toán</th>

                                                <th>Xem chi tiết</th>  <!-- ✅ Cột mới -->

                                            </tr>
                                        </thead>
                                    
                                        <tbody>
                                            @if($orders->count() > 0)
                                                @foreach ($orders as $order)
                                                    <tr>
                                                        <td>#{{ $order->order_code }}</td>
                                                        <td>{{ date('d/m/Y', strtotime($order->created_at)) }}</td>
                                                        <td>{{ $order->product_name }}</td>
                                                        <td>{{ $order->quantity }}</td>

                                                        <td>{{ number_format($order->price, 2, ',', '.') }} VNĐ</td>

                                                        <td>
                                                            @if($order->payment_method == 'cod')
                                                                <span class="badge badge-warning">COD</span>
                                                            @else
                                                                <span class="badge badge-success">Chuyển khoản</span>
                                                            @endif
                                                        </td>

                                                        <td>
                                                            <a href="{{ route('order.detail', ['id' => $order->id]) }}" class="btn btn-sm btn-primary">
                                                                Xem chi tiết
                                                            </a>
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
@endsection
