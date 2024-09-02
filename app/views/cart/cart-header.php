<!DOCTYPE html>
<html lang="fa">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="theme-color" content="#f7858d">
    <meta name="msapplication-navbutton-color" content="#f7858d">
    <meta name="apple-mobile-web-app-status-bar-style" content="#f7858d">
    <title><?= SITENAME ?></title>
    <!-- Bootstrap -->
    <link rel="stylesheet" href="/public/assets/css/vendor/bootstrap.min.css">
    <!-- Plugins -->
    <link rel="stylesheet" href="/public/assets/css/vendor/owl.carousel.min.css">
    <link rel="stylesheet" href="/public/assets/css/vendor/jquery.horizontalmenu.css">
    <link rel="stylesheet" href="/public/assets/css/vendor/nice-select.css">
    <link rel="stylesheet" href="/public/assets/css/vendor/nouislider.min.css">
    <!-- Font Icon -->
    <link rel="stylesheet" href="/public/assets/css/vendor/materialdesignicons.min.css">
    <!-- Main CSS File -->
    <link rel="stylesheet" href="/public/assets/css/main.css">
    <link rel="stylesheet" href="/public/assets/css/colors/default.css" id="colorswitch">
</head>

<body>

<div class="wrapper shopping-page">
    <!-- Start header-shopping -->
    <header class="header-shopping dt-sl">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center pt-2">
                    <div class="header-shopping-logo dt-sl">
                        <a href="/">
                            <img src="/public/assets/img/logo.png" alt="">
                        </a>
                    </div>
                </div>
                <div class="col-12 text-center">
                    <?php $url = $_SERVER['REDIRECT_URL']; ?>
                    <ul class="checkout-steps">
                        <li>
                            <a class="active">
                                <span>اطلاعات ارسال</span>
                            </a>
                        </li>
                        <li class="<?= strstr($url, 'pay') || strstr($url, 'complete') ? 'active' : '' ?>">
                            <a class="<?= strstr($url, 'pay') || strstr($url, 'complete') ? 'active' : '' ?>">
                                <span>پرداخت</span>
                            </a>
                        </li>
                        <li class="<?= strstr($url, 'complete') ? 'active' : '' ?>">
                            <a class="<?= strstr($url, 'complete') ? 'active' : '' ?>">
                                <span>اتمام خرید و ارسال</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </header>
    <!-- End header-shopping -->