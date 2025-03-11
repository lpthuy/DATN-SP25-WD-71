<header id="header">
    <head>
        <!-- Thêm CSRF Token vào đây -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <body data-user-authenticated="{{ Auth::check() ? 'true' : 'false' }}" 
        data-user-role="{{ Auth::check() ? Auth::user()->role : '' }}">

    </head>
    <div class="topbar">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-6 contact-header">
                    <span>
                        Hotline:
                        <a class="fone" href="tel:19006750" title="1900 6750">1900 6750</a>
                    </span>
                    <span class="d-md-inline-block d-none">
                        Email:
                        <a href="mailto:support@sapo.vn" title="support@sapo.vn">support@sapo.vn</a>
                    </span>
                </div>
                <div class="col-md-6 col-12">
                    <div class="account-header">
                        @if(Auth::check()) 
                        <div class="dropdown user-dropdown">
                            <button class="avatar-btn" type="button">
                                <img src="{{ Auth::user()->avatar ?? asset('default-avatar.png') }}" class="user-avatar" alt="Avatar">
                                <span>{{ Auth::user()->name }}</span>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('profile') }}">Trang cá nhân</a></li>
                                @if(Auth::user()->role === 'admin')
                                    <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">Quay lại trang Admin</a></li>
                                @else
                                    <li><a class="dropdown-item" href="{{ route('order.index') }}">Đơn hàng của tôi</a></li>
                                @endif
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">Đăng xuất</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                        @else
                            <a class="btnx" href="{{ route('login') }}" title="Đăng nhập">Đăng nhập</a>
                            <a href="{{ route('register') }}" title="Đăng ký">Đăng ký</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="main-header">
        <div class="container">
            <div class="box-hearder">
                <div class="row align-items-center">
                    <div class="col-lg-3 col-md-2 col-2 header-upper-menu-mobile">
                        <div class="header-action-toggle" id="site-menu-handle">
                            <img width="64" height="64"
                                src="{{ asset('client/images/menu4d9c.png') }}"
                                alt="Lofi Style">
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-8 col-6 header-logo">

                        <a href="{{ route('home') }}" class="logo" title="Logo">
                            <img width="147" height="56"
                                src="{{ asset('client/images/logo4d9c.png') }}"
                                alt="Lofi Style">
                        </a>

                    </div>
                    <div class="col-lg-8 header-mid">
                        <div class="header-menu-des">
                            <div class="close-menu d-lg-none d-block">
                                <img width="25" height="25"
                                    src="{{ asset('client/images/close4d9c.png') }}"
                                    alt="Lofi Style">
                            </div>
                            <div id="main-nav-menu">
                                <ul class="menuList-main">
                                    <li class="nav-item active">
                                        <a class="nav-link" href="index.html" title="Trang chủ">Trang chủ</a>
                                    </li>
                                    <li class="nav-item ">
                                        <a class="nav-link" href="{{ route('about') }}" title="Giới thiệu">Giới
                                            thiệu</a>
                                    </li>
                                    <li class="nav-item has-submenu ">
                                        <a class="nav-link caret-down" href="collections/all.html"
                                            title="Sản phẩm">
                                            Sản phẩm
                                        </a>
                                        <i class="fa ic-caret-down"></i>
                                        <ul class="menuList-submain level-1">
                                            <li class="has-submenu">
                                                <a class="caret-down" href="{{ route('productbycategory') }}"
                                                    title="Sản phẩm áo">
                                                    Sản phẩm áo
                                                </a>
                                                <i class="fa ic-caret-down"></i>
                                                <ul class="menuList-submain level-2">
                                                    <li>
                                                        <a href="{{ route('productbycategory') }}" title="Áo phông">Áo phông</a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ route('productbycategory') }}" title="Áo sơ mi">Áo sơ mi</a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ route('productbycategory') }}" title="Áo kiểu">Áo kiểu</a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ route('productbycategory') }}" title="Áo len">Áo len</a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ route('productbycategory') }}" title="Áo khoác">Áo khoác</a>
                                                    </li>
                                                </ul>
                                            </li>
                                            <li class="has-submenu">
                                                <a class="caret-down" href="san-pham-quan.html"
                                                    title="Sản phẩm quần">
                                                    Sản phẩm quần
                                                </a>
                                                <i class="fa ic-caret-down"></i>
                                                <ul class="menuList-submain level-2">
                                                    <li>
                                                        <a href="quan-jeans.html" title="Quần jeans">Quần
                                                            jeans</a>
                                                    </li>
                                                    <li>
                                                        <a href="quan-kaki.html" title="Quần kaki">Quần kaki</a>
                                                    </li>
                                                    <li>
                                                        <a href="quan-short.html" title="Quần short">Quần
                                                            short</a>
                                                    </li>
                                                    <li>
                                                        <a href="quan-lung.html" title="Quần lửng">Quần lửng</a>
                                                    </li>
                                                </ul>
                                            </li>
                                            <li class="has-submenu">
                                                <a class="caret-down" href="san-pham-vay.html"
                                                    title="Sản phẩm váy">
                                                    Sản phẩm váy
                                                </a>
                                                <i class="fa ic-caret-down"></i>
                                                <ul class="menuList-submain level-2">
                                                    <li>
                                                        <a href="vay-bo.html" title="Váy bó">Váy bó</a>
                                                    </li>
                                                    <li>
                                                        <a href="vay-xuong.html" title="Váy xuông">Váy xuông</a>
                                                    </li>
                                                    <li>
                                                        <a href="vay-chu-a.html" title="Váy chữ A">Váy chữ A</a>
                                                    </li>
                                                    <li>
                                                        <a href="dam-om.html" title="Đầm ôm">Đầm ôm</a>
                                                    </li>
                                                </ul>
                                            </li>
                                            <li class="has-submenu">
                                                <a class="caret-down" href="tui-phu-kien.html" title="Túi xách">
                                                    Túi xách
                                                </a>
                                                <i class="fa ic-caret-down"></i>
                                                <ul class="menuList-submain level-2">
                                                    <li>
                                                        <a href="vi.html" title="Ví">Ví</a>
                                                    </li>
                                                </ul>
                                            </li>
                                            <li class="has-submenu">
                                                <a class="" href="giay-dep.html" title="Giày dép">
                                                    Giày dép
                                                </a>
                                            </li>
                                            <li class="has-submenu">
                                                <a class="" href="phu-kien-1.html" title="Phụ kiện">
                                                    Phụ kiện
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li class="nav-item ">
                                        <a class="nav-link" href="{{ route('post') }}" title="Tin tức">Tin tức</a>
                                    </li>
                                    <li class="nav-item ">
                                        <a class="nav-link" href="{{ route('contact') }}" title="Liên hệ">Liên hệ</a>
                                    </li>
                                </ul>
                            </div>




                            <div class="flash-scroll">
                                <a href="javascript:;" data-href=".section_flash_sale" class="scroll-down">
                                    <img width="12"
                                        src="{{ asset('client/images/menu_icon_34d9c.png') }}"
                                        alt="Lofi Style"> Flash sale
                                </a>
                            </div>


                        </div>
                    </div>
                    <div class="col-lg-2 col-md-2 col-4 header-right">
                        <div class="header-wrap-icon">

                            <div class="wrap-search header-action">
                                <div class="header-action-toggle" id="search-handle">
                                    <span class="box-icon">
                                        <img width="24" height="24"
                                            src="{{ asset('client/images/search4d9c.png') }}"
                                            alt="Lofi Style">
                                    </span>
                                </div>
                                <div class="header_dropdown site_search">
                                    <div class="site-search-container">
                                        <p class="title">Tìm kiếm</p>
                                        <div class="wrapper-search">
                                            <form action="{{ route(name: 'search') }}" method="get"
                                                role="search"
                                                class="searchform searchform-categoris ultimate-search">
                                                <div class="search-inner">
                                                    <input id="inputSearch" type="text" name="query" value
                                                        class="input-search form-control"
                                                        placeholder="Tìm kiếm sản phẩm..." autocomplete="off" />
                                                    <input type="hidden" name="type" value="product" />
                                                </div>
                                                <button type="submit" aria-label="Tìm kiếm" class="btn-search"
                                                    id="search-btn">
                                                    <img width="24" height="27"
                                                        src="{{ asset('client/images/search4d9c.png') }}"
                                                        alt="Lofi Style">
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="wrap-wishlist header-action">
                                <a href="{{ route('wishlist') }}" class="wishlist_header box-icon"
                                    title="Sản phẩm yêu thích">
                                    <svg xmlns:xlink="http://www.w3.org/1999/xlink"
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 18 17" fill="none">
                                        <path
                                            d="M1.34821 8.7354C0.330209 5.77575 1.60274 2.00897 4.40225 1.2018C5.92926 0.663681 7.96523 1.20177 8.98323 2.81612C10.0012 1.20177 12.0372 0.663681 13.5642 1.2018C16.6182 2.27803 17.6363 5.77575 16.6183 8.7354C15.3458 13.3094 10.2557 16 8.98323 16C7.71073 15.7309 2.87522 13.5784 1.34821 8.7354Z"
                                            stroke="#000000" stroke-width="1.5px" stroke-linecap="round"
                                            stroke-linejoin="round" fill="none"></path>
                                    </svg>
                                    <span class="headerWishlistCount count">0</span>
                                </a>
                            </div>
                            <div class="wrap-cart header-action block-cart">
                                <a href="{{ route('cart.show') }}" class="header-action-toggle header-cart" id="site-cart-handle">
                                    <span class="box-icon">
                                        <svg xmlns:xlink="http://www.w3.org/1999/xlink" xmlns="http://www.w3.org/2000/svg" class="svg-icon " width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <path d="M7 8V6C7 4.67392 7.52678 3.40215 8.46447 2.46447C9.40215 1.52678 10.6739 1 12 1C13.3261 1 14.5979 1.52678 15.5355 2.46447C16.4732 3.40215 17 4.67392 17 6V8H20C20.2652 8 20.5196 8.10536 20.7071 8.29289C20.8946 8.48043 21 8.73478 21 9V21C21 21.2652 20.8946 21.5196 20.7071 21.7071C20.5196 21.8946 20.2652 22 20 22H4C3.73478 22 3.48043 21.8946 3.29289 21.7071C3.10536 21.5196 3 21.2652 3 21V9C3 8.73478 3.10536 8.48043 3.29289 8.29289C3.48043 8.10536 3.73478 8 4 8H7ZM7 10H5V20H19V10H17V12H15V10H9V12H7V10ZM9 8H15V6C15 5.20435 14.6839 4.44129 14.1213 3.87868C13.5587 3.31607 12.7956 3 12 3C11.2044 3 10.4413 3.31607 9.87868 3.87868C9.31607 4.44129 9 5.20435 9 6V8Z" fill="#121212"/>
                                        </svg>
                                        <span class="count_item_pr count">{{ session('cart') ? count(session('cart')) : 0 }}</span>

                                    </span>
                                </a>
                                <div class="top-cart-content">
                                    <div class="CartHeaderContainer">
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="search-bar-mobile">
        <div class="container">
            <div class="search-box">
                <form class="searchform" action="https://lofi-style.mysapo.net/search" method="get"
                    role="search">
                    <div class="search-inner">
                        <input type="hidden" name="type" value="product" />
                        <input required id="inputSearch" name="query" autocomplete="off" class="input-search"
                            type="text" placeholder="Tìm kiếm sản phẩm...">
                    </div>
                    <button type="submit" class="btn-search" id="search-btn">
                        <img width="24" height="27"
                            src="{{ asset('client/images/search4d9c.png') }}"
                            alt="Lofi Style">
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>

<script>
    function updateCartCount() {
        fetch("{{ route('cart.count') }}")
        .then(response => response.json())
        .then(data => {
            document.querySelector('.count_item_pr.count').innerText = data.cart_count;
        })
        .catch(error => console.error('Lỗi cập nhật giỏ hàng:', error));
    }

    // Cập nhật ngay khi thêm vào giỏ hàng
    document.querySelectorAll('.btn-cart').forEach(button => {
        button.addEventListener('click', function () {
            setTimeout(updateCartCount, 1000); // Đợi 1s để cập nhật giỏ hàng
        });
    });

    // Cập nhật khi tải trang
    document.addEventListener("DOMContentLoaded", updateCartCount);
</script>
