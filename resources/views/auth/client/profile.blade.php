@extends('client.layouts.main')

@section('title', 'Tài khoản')

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
                    <strong itemprop="name">Tài khoản</strong>
                    <meta itemprop="position" content="2" />
                </li>

            </ul>
        </div>
    </section>
    <section class="signup page_customer_account">
        <div class="container">
            <div class="row">

                <div class="col-xs-12 col-sm-12 col-lg-3 col-left-ac">
                    <div class="block-account">
                        <h5 class="title-account">Trang tài khoản</h5>
                        <p>Xin chào, <span style="color:#ef4339;">Trần Việt Anh (PH39998)</span>&nbsp;!</p>
                        <ul>
                            <li>
                                <a disabled="disabled" class="title-info active" href="javascript:void(0);">Thông tin tài khoản</a>
                            </li>
                            <li>
                                <a class="title-info" href="{{ route('order') }}">Đơn hàng của bạn</a>
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
                <div class="col-xs-12 col-sm-12 col-lg-9 col-right-ac">
                    <h1 class="title-head margin-top-0">Thông tin tài khoản</h1>
                    <div class="form-signup name-account m992">
                        <p><strong>Họ tên:</strong>  Trần Việt Anh (PH39998)</p>
                        <p> <strong>Email:</strong> tranvanh2k4@gmail.com</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
