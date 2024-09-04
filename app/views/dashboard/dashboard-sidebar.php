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
                    <span class="d-block profile-username"><?= $user->name ?></span>
                    <?php if (User::havePhone($user->id)) : ?>
                        <span class="d-block profile-phone"><?= $user->phone ?></span>
                    <?php endif; ?>
                </div>
                <?php if (!User::isUser()): ?>
                    <a class="btn btn-primary float-left mt-2" href="/panel/">رفتن به پنل</a>
                <?php endif; ?>
                <div class="profile-link mt-2 dt-sl">
                    <div class="row">
                        <div class="col-6 text-center">
                            <a href="/dashboard/info">
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
                            <a href="/dashboard/" <?= $_GET['url'] == 'public/dashboard/' ? 'class="active"' : '' ?>>
                                <i class="mdi mdi-account-circle-outline"></i>
                                پروفایل
                            </a>
                        </li>
                        <li>
                            <a href="/dashboard/orders" <?= str_contains($_GET['url'], 'orders') ? 'class="active"' : '' ?>>
                                <i class="mdi mdi-basket"></i>
                                سفارش ها
                            </a>
                        </li>
                        <li>
                            <a href="/dashboard/favorites" <?= str_contains($_GET['url'], 'favorites') ? 'class="active"' : '' ?>>
                                <i class="mdi mdi-heart-outline"></i>
                                لیست علاقه مندی ها
                            </a>
                        </li>
                        <li>
                            <a href="/dashboard/comments" <?= str_contains($_GET['url'], 'comments') ? 'class="active"' : '' ?>>
                                <i class="mdi mdi-glasses"></i>
                                پرسش و نظرات
                            </a>
                        </li>
                        <li>
                            <a href="/dashboard/addresses" <?= str_contains($_GET['url'], 'addresses') ? 'class="active"' : '' ?>>
                                <i class="mdi mdi-sign-direction"></i>
                                آدرس
                            </a>
                        </li>
                        <li>
                            <a href="/dashboard/info" <?= str_contains($_GET['url'], 'info') ? 'class="active"' : '' ?>>
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