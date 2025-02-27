@extends('client.layouts.main')

@section('title', 'Đơn hàng')

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
                            Xin chào, <span style="color:#f02757;">Trần Việt Anh (PH39998)</span>&nbsp;!
                        </p>
                        <ul>
                            <li>
                                <a disabled="disabled" class="title-info" href="/account">Thông tin tài khoản</a>
                            </li>
                            <li>
                                <a disabled="disabled" class="title-info active" href="javascript:void(0);">Đơn hàng của
                                    bạn</a>
                            </li>
                            <li>
                                <a class="title-info" href="/account/changepassword">Đổi mật khẩu</a>
                            </li>
                            <li>
                                <a class="title-info" href="/account/addresses">Sổ địa chỉ (0)</a>
                            </li>
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
                                                <th>Đơn hàng</th>
                                                <th>Ngày</th>
                                                <th>Địa chỉ</th>
                                                <th>Giá trị đơn hàng</th>
                                                <th>TT thanh toán</th>
                                                <th>TT vận chuyển</th>
                                            </tr>
                                        </thead>

                                        <tbody>

                                            <tr>
                                                <td colspan="6">
                                                    <p>Không có đơn hàng nào.</p>
                                                </td>
                                            </tr>

                                        </tbody>


                                    </table>

                                </div>

                                <div
                                    class="paginate-pages pull-right page-account text-right col-xs-12 col-sm-12 col-md-12 col-lg-12">

                                </div>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection

@section('js')
@endsection
