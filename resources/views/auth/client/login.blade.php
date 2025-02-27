@extends('client.layouts.main')

@section('title', 'Đăng nhập')

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
                    <strong itemprop="name">Đăng nhập</strong>
                    <meta itemprop="position" content="2" />
                </li>

            </ul>
        </div>
    </section>
    <section class="section">
        <div class="container">
            <div class="wrap_background_aside page_login">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xl-4 offset-0 offset-xl-4 offset-lg-3 offset-md-3 offset-xl-3 col-12">
                        <div class="row">
                            <div class="page-login pagecustome clearfix">
                                <div class="wpx">
                                    <h1 class="title_heads a-center"><span>Đăng nhập</span></h1>
                                    <span class="block a-center dkm margin-top-10">Nếu bạn chưa có tài khoản, <a href="{{ route('register') }}" class="btn-link-style btn-register">đăng ký tại đây</a></span>
                                    <div id="login" class="section">
                                        <form method="post" action="/account/login" id="customer_login" accept-charset="UTF-8"><input name="FormType" type="hidden" value="customer_login"><input name="utf8" type="hidden" value="true">
                                        <span class="form-signup" style="color:red;">

                                        </span>
                                        <div class="form-signup clearfix">
                                            <fieldset class="form-group">
                                                <input type="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,63}$" class="form-control form-control-lg" value="" name="email" id="customer_email" placeholder="Email" required="" fdprocessedid="ev206">
                                            </fieldset>
                                            <fieldset class="form-group">
                                                <input type="password" class="form-control form-control-lg" value="" name="password" id="customer_password" placeholder="Mật khẩu" required="" fdprocessedid="u9a4aa">
                                            </fieldset>
                                            <div class="pull-xs-left">
                                                <input class="btn btn-style btn_50" type="submit" value="Đăng nhập" fdprocessedid="123awm">
                                                <span class="block a-center quenmk">Quên mật khẩu</span>
                                            </div>
                                        </div>
                                        </form>
                                    </div>

                                    <div class="h_recover" style="display:none;">
                                        <div id="recover-password" class="form-signup page-login">
                                            <form method="post" action="/account/recover" id="recover_customer_password" accept-charset="UTF-8"><input name="FormType" type="hidden" value="recover_customer_password"><input name="utf8" type="hidden" value="true">
                                            <div class="form-signup" style="color: red;">

                                            </div>
                                            <div class="form-signup clearfix">
                                                <fieldset class="form-group">
                                                    <input type="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,63}$" class="form-control form-control-lg" value="" name="Email" id="recover-email" placeholder="Email" required="">
                                                </fieldset>
                                            </div>
                                            <div class="action_bottom">
                                                <input class="btn btn-style btn_50" style="margin-top: 0px;" type="submit" value="Lấy lại mật khẩu">

                                            </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="block social-login--facebooks">
                                        <p class="a-center">
                                            Hoặc đăng nhập bằng
                                        </p>
                                        <script>function loginFacebook(){var a={client_id:"947410958642584",redirect_uri:"https://store.mysapo.net/account/facebook_account_callback",state:JSON.stringify({redirect_url:window.location.href}),scope:"email",response_type:"code"},b="https://www.facebook.com/v3.2/dialog/oauth"+encodeURIParams(a,!0);window.location.href=b}function loginGoogle(){var a={client_id:"997675985899-pu3vhvc2rngfcuqgh5ddgt7mpibgrasr.apps.googleusercontent.com",redirect_uri:"https://store.mysapo.net/account/google_account_callback",scope:"email profile https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile",access_type:"online",state:JSON.stringify({redirect_url:window.location.href}),response_type:"code"},b="https://accounts.google.com/o/oauth2/v2/auth"+encodeURIParams(a,!0);window.location.href=b}function encodeURIParams(a,b){var c=[];for(var d in a)if(a.hasOwnProperty(d)){var e=a[d];null!=e&&c.push(encodeURIComponent(d)+"="+encodeURIComponent(e))}return 0==c.length?"":(b?"?":"")+c.join("&")}</script>
                                        <a href="javascript:void(0)" class="social-login--facebook" onclick="loginFacebook()"><img width="129px" height="37px" alt="facebook-login-button" src="//bizweb.dktcdn.net/assets/admin/images/login/fb-btn.svg"></a>
                                        <a href="javascript:void(0)" class="social-login--google" onclick="loginGoogle()"><img width="129px" height="37px" alt="google-login-button" src="//bizweb.dktcdn.net/assets/admin/images/login/gp-btn.svg"></a>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection
