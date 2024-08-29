<?php
require_once "../app/bootstrap.php";

use \didishop\models\Category;
?>
<!DOCTYPE html>
<html lang="fa">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="theme-color" content="#f44336">
    <meta name="msapplication-navbutton-color" content="#f44336">
    <meta name="apple-mobile-web-app-status-bar-style" content="#f44336">
    <title><?= SITENAME ?></title>
    <!-- Bootstrap -->
    <link rel="stylesheet" href="./assets/css/vendor/bootstrap.min.css">
    <!-- Plugins -->
    <link rel="stylesheet" href="./assets/css/vendor/owl.carousel.min.css">
    <link rel="stylesheet" href="./assets/css/vendor/jquery.horizontalmenu.css">
    <!-- Font Icon -->
    <link rel="stylesheet" href="./assets/css/vendor/materialdesignicons.min.css">
    <!-- Main CSS File -->
    <link rel="stylesheet" href="./assets/css/main.css">
    <link rel="stylesheet" href="./assets/css/red-color.css">
</head>

<body>

<div class="wrapper">

    <!-- Start header -->
    <header class="main-header js-fixed-topbar dt-sl">
        <!-- Start topbar -->
        <div class="container main-container">
            <div class="topbar dt-sl">
                <div class="row">
                    <div class="col-lg-2 col-md-3 col-6">
                        <div class="logo-area float-right">
                            <a href="/">
                                <img src="./assets/img/logo.png" alt="logo">
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-5 hidden-sm">
                        <div class="search-area dt-sl">
                            <form action="/search" method="post" class="search">
                                <input name="search" type="text" placeholder="نام کالا مورد نظر خود را جستجو کنید…">
                                <button type="submit"><img src="./assets/img/theme/search.png" alt=""></button>
                            </form>
                        </div>
                    </div>
                    <div class="col-md-4 col-6 topbar-left">
                        <ul class="nav float-left">
                            <li class="nav-item account dropdown">
                                <a class="nav-link" href="panel/" data-toggle="dropdown" aria-haspopup="true"
                                   aria-expanded="false">
                                    <span class="label-dropdown">حساب کاربری</span>
                                    <i class="mdi mdi-account-circle-outline"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-sm dropdown-menu-left">
                                    <a class="dropdown-item" href="panel/">
                                        <i class="mdi mdi-account-card-details-outline"></i>پروفایل
                                    </a>
                                    <div class="dropdown-divider" role="presentation"></div>
                                    <a class="dropdown-item" href="panel/logout">
                                        <i class="mdi mdi-logout-variant"></i>خروج
                                    </a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- End topbar -->

        <!-- Start bottom-header -->
        <div class="bottom-header dt-sl mb-sm-bottom-header">
            <div class="container main-container">
                <!-- Start Main-Menu -->
                <nav class="main-menu dt-sl">
                    <ul class="list float-right hidden-sm">
                        <?php
                        $categories = (new Category())->getCategories();
                        foreach ($categories as $category):
                            ?>
                            <li class="list-item list-item-has-children <?= Category::hasSubCategories($category->id) ? 'list-item-has-children-icon' : '' ?> menu-col-1">
                                <a class="nav-link" href="category/<?= $category->id ?>"><?= $category->title ?></a>
                                <?php if (Category::hasSubCategories($category->id)): ?>
                                    <ul class="sub-menu nav">
                                        <?php
                                        $subCategories = (new Category())->getSubCategories($category->id);
                                        foreach ($subCategories as $subCategory):
                                            ?>
                                            <li class="list-item">
                                                <a class="nav-link"
                                                   href="category/<?= $subCategory->id ?>"><?= $subCategory->title ?></a>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <ul class="nav float-left">
                        <li class="nav-item">
                            <a class="nav-link" href="cart/">
                                <span class="label-dropdown">سبد خرید</span>
                                <i class="mdi mdi-cart-outline"></i>
                                <span class="count">0</span>
                            </a>
                        </li>
                    </ul>
                    <button class="btn-menu">
                        <div class="align align__justify">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </button>
                    <div class="side-menu">
                        <div class="logo-nav-res dt-sl text-center">
                            <a href="/">
                                <img src="assets/img/logo.png" alt="logo">
                            </a>
                        </div>
                        <div class="search-box-side-menu dt-sl text-center mt-2 mb-3">
                            <form action="">
                                <input type="text" name="s" placeholder="جستجو کنید...">
                                <i class="mdi mdi-magnify"></i>
                            </form>
                        </div>
                        <ul class="navbar-nav dt-sl">
                            <?php
                            $categories = (new Category())->getCategories();
                            foreach ($categories as $category):
                                ?>
                                <li class="<?= Category::hasSubCategories($category->id) ? 'sub-menu' : '' ?>">
                                    <a href="category/<?= $category->id ?>"><?= $category->title ?></a>
                                    <?php if (Category::hasSubCategories($category->id)): ?>
                                        <ul class="sub-menu">
                                            <?php
                                            $subCategories = (new Category())->getSubCategories($category->id);
                                            foreach ($subCategories as $subCategory):
                                                ?>
                                                <li>
                                                    <a href="category/<?= $subCategory->id ?>"><?= $subCategory->title ?></a>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <div class="overlay-side-menu">
                    </div>
                </nav>
                <!-- End Main-Menu -->
            </div>
        </div>
        <!-- End bottom-header -->
    </header>
    <!-- End header -->