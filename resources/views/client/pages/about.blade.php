@extends('client.layouts.main')

@section('title', 'Giới thiệu')

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
                    <strong itemprop="name">Giới thiệu</strong>
                    <meta itemprop="position" content="2" />
                </li>

            </ul>
        </div>
    </section>
    <section class="pages">
        <div class="container">
            <div class="row">
                <div class="col-lg-9 col-12 order-lg-9">
                    <div class="page-wrapper">
                        <h1 class="title-page">Giới thiệu</h1>
                        <div class="content-page rte">


                            <p><b>LOFI STYLE&nbsp;</b>- Thương hiệu thời trang của người trẻ hiện đại! Ra đời
                                vào năm 2016, LOFISTYLE&nbsp;luôn nỗ lực với sứ mệnh tạo nên xu hướng thời trang
                                mang đến sự tin tưởng&nbsp;và năng lượng tích cực cho khách hàng.&nbsp;<b>LOFI
                                    STYLE&nbsp;</b>mang hơi thở của thời trang&nbsp;<strong><em>HIỆN ĐẠI, TRẺ
                                        TRUNG, PHÓNG KHOÁNG</em></strong>&nbsp;với những mẫu thiết kế bắt nhịp
                                xu hướng thịnh hành và có tính ứng dụng cao trong mọi hoàn cảnh.</p>
                            <p>&nbsp;</p>
                            <p><em><strong>HÃY ĐẾN&nbsp;</strong><b>LOFI STYLE</b><strong>&nbsp;ĐỂ TRẢI NGHIỆM
                                        SỰ KHÁC BIỆT!</strong></em></p>

                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-12 order-lg-3">
                    <div class="sidebar">
                        <div class="group-menu">
                            <div class="page_menu_title title_block">
                                <h2>Danh mục trang</h2>
                            </div>
                            <div class="layered-category">
                                <ul class="menuList-links">
                                    <li class="nav-item ">
                                        <a class="nav-link" href="index.html" title="Trang chủ">Trang chủ</a>
                                    </li>
                                    <li class="nav-item active">
                                        <a class="nav-link" href="gioi-thieu.html" title="Giới thiệu">Giới
                                            thiệu</a>
                                    </li>
                                    <li class="has-submenu level0 ">
                                        <a class="nav-link" href="collections/all.html" title="Sản phẩm">
                                            Sản phẩm
                                            <span class="icon-plus-submenu plus-nClick1"></span>
                                        </a>
                                        <ul class="submenu-links" style="display: none;">
                                            <li class="has-submenu level1">
                                                <a href="san-pham-ao.html" title="Sản phẩm áo">
                                                    Sản phẩm áo
                                                    <span class="icon-plus-submenu plus-nClick2"></span>
                                                </a>
                                                <ul class="submenu-links" style="display: none;">
                                                    <li>
                                                        <a href="ao-phong.html" title="Áo phông">Áo phông</a>
                                                    </li>
                                                    <li>
                                                        <a href="ao-so-mi.html" title="Áo sơ mi">Áo sơ mi</a>
                                                    </li>
                                                    <li>
                                                        <a href="ao-kieu.html" title="Áo kiểu">Áo kiểu</a>
                                                    </li>
                                                    <li>
                                                        <a href="ao-len.html" title="Áo len">Áo len</a>
                                                    </li>
                                                    <li>
                                                        <a href="ao-khoac.html" title="Áo khoác">Áo khoác</a>
                                                    </li>
                                                </ul>
                                            </li>
                                            <li class="has-submenu level1">
                                                <a href="san-pham-quan.html" title="Sản phẩm quần">
                                                    Sản phẩm quần
                                                    <span class="icon-plus-submenu plus-nClick2"></span>
                                                </a>
                                                <ul class="submenu-links" style="display: none;">
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
                                            <li class="has-submenu level1">
                                                <a href="san-pham-vay.html" title="Sản phẩm váy">
                                                    Sản phẩm váy
                                                    <span class="icon-plus-submenu plus-nClick2"></span>
                                                </a>
                                                <ul class="submenu-links" style="display: none;">
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
                                            <li class="has-submenu level1">
                                                <a href="tui-phu-kien.html" title="Túi xách">
                                                    Túi xách
                                                    <span class="icon-plus-submenu plus-nClick2"></span>
                                                </a>
                                                <ul class="submenu-links" style="display: none;">
                                                    <li>
                                                        <a href="vi.html" title="Ví">Ví</a>
                                                    </li>
                                                </ul>
                                            </li>
                                            <li class="has-submenu level1">
                                                <a href="giay-dep.html" title="Giày dép">
                                                    Giày dép
                                                </a>
                                            </li>
                                            <li class="has-submenu level1">
                                                <a href="phu-kien-1.html" title="Phụ kiện">
                                                    Phụ kiện
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li class="nav-item ">
                                        <a class="nav-link" href="tin-tuc.html" title="Tin tức">Tin tức</a>
                                    </li>
                                    <li class="nav-item ">
                                        <a class="nav-link" href="lien-he.html" title="Liên hệ">Liên hệ</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
