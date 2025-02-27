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

    <form action="/cart" method="post" novalidate="" class="cart ajaxcart cartpage">
    <div class="row">
        <div class="col-md-8 col-xs-12 col-sm-8">
            <div class="cart-header-info">
                <div>Thông tin sản phẩm</div><div>Đơn giá</div><div>Số lượng</div><div>Thành tiền</div>
            </div>
            <div class="ajaxcart__inner ajaxcart__inner--has-fixed-footer cart_body items">
                <div class="ajaxcart__row">
                    <div class="ajaxcart__product cart_product" data-line="1">
                        <a href="/tui-xach-nu-deo-cheo-da-pu-di-du-tiec-du-lich" class="ajaxcart__product-image cart_image" title="Túi Xách Nữ Đeo Chéo Da Pu Đi Dự Tiệc Du Lịch"><img src="https://bizweb.dktcdn.net/thumb/compact/100/456/491/products/pdwkie-simg-d0daf0-800x1200-max.png" alt="Túi Xách Nữ Đeo Chéo Da Pu Đi Dự Tiệc Du Lịch"></a>
                        <div class="grid__item cart_info">
                            <div class="ajaxcart__product-name-wrapper cart_name">
                                <a href="/tui-xach-nu-deo-cheo-da-pu-di-du-tiec-du-lich" class="ajaxcart__product-name h4" title="Túi Xách Nữ Đeo Chéo Da Pu Đi Dự Tiệc Du Lịch">Túi Xách Nữ Đeo Chéo Da Pu Đi Dự Tiệc Du Lịch</a>
                                <span class="ajaxcart__product-meta variant-title">1</span>
                            </div>
                            <div class="grid">
                                <div class="grid__item one-half text-right cart_prices">
                                    <span class="cart-price">300.000₫</span>
                                    <span class="cart-price-compare"></span>
                                    <a class="cart__btn-remove remove-item-cart ajaxifyCart--remove" href="javascript:;" data-line="1">Xóa</a>
                                </div>
                            </div>
                            <div class="grid">
                                <div class="grid__item one-half cart_select">
                                    <div class="ajaxcart__qty input-group-btn">
                                        <button type="button" class="ajaxcart__qty-adjust ajaxcart__qty--minus items-count" data-id="" data-qty="0" data-line="1" aria-label="-" fdprocessedid="ptmygi">
                                            -
                                        </button>
                                        <input type="text" name="updates[]" class="ajaxcart__qty-num number-sidebar" maxlength="3" value="1" min="0" data-id="" data-line="1" aria-label="quantity" pattern="[0-9]*" fdprocessedid="9g64al">
                                        <button type="button" class="ajaxcart__qty-adjust ajaxcart__qty--plus items-count" data-id="" data-line="1" data-qty="2" aria-label="+" fdprocessedid="3pnhh">
                                            +
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="grid">
                                <div class="grid__item one-half text-right cart_prices">
                                    <span class="cart-price">300.000₫</span>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>
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
                                <div class="text-right cart__totle"><span class="total-price">300.000₫</span></div>
                            </div>
                        </div>
                        <div class="cart__btn-proceed-checkout-dt">
                            <button onclick="location.href='/checkout'" type="button" class="button btn btn-default cart__btn-proceed-checkout" id="btn-proceed-checkout" title="Thanh toán" fdprocessedid="xp4pz">Thanh toán</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </form>

    </div>

                    </div>
                </div>
                <div class="cart-mobile-page d-block d-xl-none">
                    <div class="CartMobileContainer"></div>
                </div>
            </div>
        </div>
    </section>
@endsection
