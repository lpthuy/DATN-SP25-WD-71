@extends('client.layouts.main')

@section('title', 'Đăng ký')

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
                    <strong itemprop="name">Đăng ký</strong>
                    <meta itemprop="position" content="2" />
                </li>

            </ul>
        </div>
    </section>
    <section class="section">
        <div class="container ">
            <div class="wrap_background_aside  page_login">
                <div class="wrap_background_aside">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-12 col-xl-4 offset-xl-4 offset-lg-3 offset-md-3 offset-xl-3">
                            <div class="row">
                                <div class="page-login pagecustome clearfix">
                                    <div class="wpx">
                                        <h1 class="title_heads a-center"><span>Đăng ký</span></h1>
                                        <span class="block a-center dkm margin-top-10">Đã có tài khoản, đăng nhập <a href="{{ route('login') }}" class="btn-link-style btn-register">tại đây</a></span>
                                        <div id="login" class="section">
                                            <form method="post" action="/account/register" id="customer_register" accept-charset="UTF-8"><input name="FormType" type="hidden" value="customer_register"><input name="utf8" type="hidden" value="true"><input type="hidden" id="Token-34586600b26f481e885942da7177412c" name="Token" value="03AFcWeA6lb0t6brVn4Wug-n3Ubbzctb_fgQKFIxXetj6dtPO7UodQyV7vbKirfLwR-fwU96lCizxHYzzFLb9XXhvEvMt4fIhxynAiL466bRT-yc81B8Ia2xWveYXkTCV2sWOXNwJSSOpgN-3Hk0sLeJTbGPvxqN6-QtKw4N1mw7qQi6tANByYs-7_uX8CCU9AM_SrhIpWhVmnxvIxqsyTVYP3POGDJPX0CZ68ZsZKNUmhu4OKPhfavOAJIuy2dePLC3yW2ym05XMblKYM21VVbmbVGFyGia-HGAOQWqYS7Y89iEFb0ipzDEfVjywdSThqMCe6AKaSg6jKmeMAkaT5T_eiA71puFVd7vHdOAdZYeqDfzvDjjSyyIo9Crzxa9bV6sZAZoyieCXhVoHVNIdc8OZOARwAyAdQHDI0fZbVQ2xjePSPG7wllbOiQFHQoW_NZY5-gT3p3EGUMUK1V-fQrBQVkq0yEDVX1_v5jJbRdsMzJnDHm1uFkkaIwmLOlBGCxGjmBygW3_ML_rr4a-bJTu4WwMarMMsDShkCA4zHBr5AlpgBoLaaB1q1k9cNDXXTNTzXzYDrOYow3Lwai6eFgjSA9LWV0ZlcYlEMKoxYz8JYrSM4tAhl-j_gm6XQfNK4a0rqrwVs_SxyOrKZic0dUlYZi9L63joIpn0OeeikG4H-YameBJ1UcR25k37JX1FTSws6CFxB2z1DKDQviv9P2Zrmleh_bKBOLLaspckBXDgg5A7he8oxX8XT7sA71x8_AerbX3DhSpI4lYRW5jLr1wmEuxUm3HWvPP3Q05bszzMGDoUWHuCYsUUrrS-MmAX6TtvA02rf3hI8uBLbM2M3JuETvU0qKc98y1T7CN2k5IQcTuF483WkQJsHN_GycrKI9uidhGeWwoXFyVhYjv7nYQZCh3tuBapskKgqZcSDzxTf4yBy5d7UNPWVQT1gHkYo3eFiavkTVPUVMtHjuAmWzEKP8UWOZbVlsdqSaJ_-JZVQqSCzgyCAJ6T6KjsxKiuKy25x4Semy9pK"><script src="https://www.google.com/recaptcha/api.js?render=6Ldtu4IUAAAAAMQzG1gCw3wFlx_GytlZyLrXcsuK"></script><script>grecaptcha.ready(function() {grecaptcha.execute("6Ldtu4IUAAAAAMQzG1gCw3wFlx_GytlZyLrXcsuK", {action: "customer_register"}).then(function(token) {document.getElementById("Token-34586600b26f481e885942da7177412c").value = token});});</script>
                                            <div class="form-signup " style="color:red;">

                                            </div>
                                            <div class="form-signup clearfix">
                                                <div class="row">
                                                    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                                                        <fieldset class="form-group">
                                                            <input type="text" class="form-control form-control-lg" value="" name="lastName" id="lastName" placeholder="Họ" required="" fdprocessedid="0n8grg">
                                                        </fieldset>
                                                    </div>
                                                    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                                                        <fieldset class="form-group">
                                                            <input type="text" class="form-control form-control-lg" value="" name="firstName" id="firstName" placeholder="Tên" required="" fdprocessedid="a9sxlb">
                                                        </fieldset>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                                                        <fieldset class="form-group">
                                                            <input type="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,63}$" class="form-control form-control-lg" value="" name="email" id="email" placeholder="Email" required="" fdprocessedid="3ldbb9">
                                                        </fieldset>
                                                    </div>
                                                    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                                                        <fieldset class="form-group">
                                                            <input placeholder="Số điện thoại" type="text" pattern="\d+" class="form-control form-control-comment form-control-lg" name="Phone" required="" fdprocessedid="hpvz0a">
                                                        </fieldset>
                                                    </div>
                                                    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                                                        <fieldset class="form-group">
                                                            <input type="password" class="form-control form-control-lg" value="" name="password" id="password" placeholder="Mật khẩu" required="" fdprocessedid="bg06fo">
                                                        </fieldset>
                                                    </div>
                                                </div>
                                                <div class="section">
                                                    <button type="submit" value="Đăng ký" class="btn  btn-style btn_50" fdprocessedid="tdhv2">Đăng ký</button>
                                                </div>
                                            </div>
                                            </form>
                                            <div class="block social-login--facebooks margin-top-15">
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
            </div>
        </div>
    </section>
@endsection
