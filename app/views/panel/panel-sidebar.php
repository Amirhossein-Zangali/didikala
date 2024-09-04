<?php
use didikala\models\User;
?>
<!-- Start Sidebar -->
<div class="col-xl-3 col-lg-4 col-md-4 col-sm-12 sticky-sidebar">
    <div class="profile-sidebar dt-sl">
        <div class="dt-sl dt-sn mb-3">
            <div class="profile-sidebar-header dt-sl">
                <div class="profile-avatar float-right">
                    <img src="/public/assets/img/profile.png" width="200" alt="">
                </div>
                <div class="profile-header-content mr-3 mt-2 float-right">
                    <span class="d-block profile-username"><?= $user->name ?> (<?= User::getRoll($user->id, 1) ?>)</span>
                    <?php if (User::havePhone($user->id)) : ?>
                        <span class="d-block profile-phone"><?= $user->phone ?></span>
                    <?php endif; ?>
                </div>
                <div class="profile-link mt-2 dt-sl">
                    <div class="row">
                        <div class="col-6 text-center">
                            <a href="/panel/info">
                                <i class="mdi mdi-lock-reset"></i>
                                <span class="d-block">تغییر رمز</span>
                            </a>
                        </div>
                        <div class="col-6 text-center">
                            <a href="/pages/logout/">
                                <i class="mdi mdi-logout-variant"></i>
                                <span class="d-block">خروج از حساب</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="dt-sl dt-sn mb-3">
            <div class="profile-menu-section dt-sl">
                <div class="label-profile-menu mt-2 mb-2">
                    <span>حساب کاربری شما</span>
                </div>
                <div class="profile-menu">
                    <ul>
                        <li>
                            <a href="/panel/" <?= $_GET['url'] == 'public/panel/' ? 'class="active"' : '' ?>>
                                <i class="mdi mdi-account-circle-outline"></i>
                                پروفایل
                            </a>
                        </li>
                        <?php if (User::canManageOrder()) : ?>
                        <li>
                            <a href="/panel/orders" <?= str_contains($_GET['url'], 'orders') ? 'class="active"' : '' ?>>
                                <i class="mdi mdi-basket"></i>
                                سفارش ها
                            </a>
                        </li>
                        <?php endif; ?>
                        <?php if (User::canManageUser()) : ?>
                            <li>
                                <a href="/panel/users" <?= str_contains($_GET['url'], 'users') ? 'class="active"' : '' ?>>
                                    <i class="mdi mdi-account"></i>
                                    کاربر ها
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if (User::canManageProduct()) : ?>
                            <li>
                                <a href="/panel/products" <?= str_contains($_GET['url'], 'products') ? 'class="active"' : '' ?>>
                                    <i class="mdi mdi-package-variant-closed"></i>
                                    محصول ها
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if (User::canManageComment()) : ?>
                        <li>
                            <a href="/panel/comments" <?= str_contains($_GET['url'], 'comments') ? 'class="active"' : '' ?>>
                                <i class="mdi mdi-comment-check"></i>
                                نظر ها
                            </a>
                        </li>
                        <?php endif; ?>
                        <?php if (User::canManageQuestion()) : ?>
                            <li>
                                <a href="/panel/questions" <?= str_contains($_GET['url'], 'questions') ? 'class="active"' : '' ?>>
                                    <i class="mdi mdi-account-question"></i>
                                    پرسش ها
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if (User::canManageCategory()) : ?>
                            <li>
                                <a href="/panel/categories" <?= str_contains($_GET['url'], 'categories') ? 'class="active"' : '' ?>>
                                    <i class="mdi mdi-format-list-bulleted"></i>
                                    دسته بندی ها
                                </a>
                            </li>
                        <?php endif; ?>

                        <li>
                            <a href="/panel/info" <?= str_contains($_GET['url'], 'info') ? 'class="active"' : '' ?>>
                                <i class="mdi mdi-account-edit-outline"></i>
                                اطلاعات شخصی
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Sidebar -->