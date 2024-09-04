<?php

use didikala\models\Favorite;
use didikala\models\Order;
use didikala\models\User;

require_once "../app/bootstrap.php";

if (!User::isUserLogin())
    redirect('pages/login');

include '../app/views/inc/header.php';

$user = User::where('id', $_SESSION['user_id'])->first();
?>

    <!-- Start main-content -->
    <main class="main-content dt-sl mt-4 mb-3">
        <div class="container main-container">
            <div class="row">

                <?php include_once 'dashboard-sidebar.php' ?>

                <!-- Start Content -->
                <div class="col-xl-9 col-lg-8 col-md-8 col-sm-12">
                    <div class="row">
                        <div class="col-xl-6 col-lg-12">
                            <div class="px-3">
                                <div class="section-title text-sm-title title-wide mb-1 no-after-title-wide dt-sl mb-2">
                                    <h2>اطلاعات شخصی</h2>
                                </div>
                                <div class="profile-section dt-sl pt-3">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-12">
                                            <div class="label-info">
                                                <span>نام</span>
                                            </div>
                                            <div class="value-info">
                                                <span><?= $user->name ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-12">
                                            <div class="label-info">
                                                <span>نام کاربری</span>
                                            </div>
                                            <div class="value-info">
                                                <span><?= $user->username ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-sm-12">
                                            <div class="label-info">
                                                <span>ایمیل </span>
                                                <?php if ($user->email_verify) : ?>
                                                    <span class="text-success">(فعال شده)</span>
                                                <?php else : ?>
                                                    <form method="post" class="d-inline m-0 p-0" action="/dashboard/verify_email/">
                                                        <button name="sendEmail" value="<?= $user->email ?>" class="p-0 text-info border-0 bg-white">(فعال سازی)</button>
                                                    </form>
                                                <?php endif; ?>
                                            </div>
                                            <div class="value-info">
                                                <span><?= $user->email ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-sm-12">
                                            <div class="label-info">
                                                <span>شماره تلفن همراه</span>
                                            </div>
                                            <?php if (User::havePhone($user->id)) : ?>
                                                <div class="value-info">
                                                    <span><?= $user->phone ?></span>
                                                </div>
                                            <?php else : ?>
                                                <div class="value-info">
                                                    <span>ثبت نشده</span>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="profile-section-link">
                                        <a href="/dashboard/info" class="border-bottom-dt">
                                            <i class="mdi mdi-account-edit-outline"></i>
                                            ویرایش اطلاعات شخصی
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-12">
                            <div class="px-3">
                                <div class="section-title text-sm-title title-wide mb-1 no-after-title-wide dt-sl mb-2">
                                    <h2>لیست آخرین علاقه‌مندی‌ها</h2>
                                </div>
                                <div class="profile-section dt-sl">
                                    <ul class="list-favorites">
                                        <?php $favorites = Favorite::getFavorites($user->id);
                                        if ($favorites->count() > 0) :
                                            foreach ($favorites as $product) : ?>
                                                <li>
                                                    <a href="/product/detail/<?= $product->id ?>">
                                                        <img src="/public/<?= $product->thumbnail ?>" alt="">
                                                        <span><?= mb_substr($product->title, 0, 50) . '...' ?></span>
                                                    </a>
                                                </li>
                                            <?php endforeach; else: ?>
                                            <div class="alert alert-danger">لیست علاقه مندی خالی است.</div>
                                        <?php endif; ?>
                                    </ul>
                                    <div class="profile-section-link">
                                        <a href="/dashboard/favorites" class="border-bottom-dt">
                                            <i class="mdi mdi-square-edit-outline"></i>
                                            مشاهده و ویرایش لیست مورد علاقه
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="section-title text-sm-title title-wide mb-1 no-after-title-wide dt-sl mb-2 px-res-1">
                                <h2>آخرین سفارش‌ها</h2>
                            </div>
                            <div class="dt-sl">
                                <div class="table-responsive">
                                    <?php $orders = Order::getOrders($user->id);
                                    if ($orders->count() > 0) :?>
                                        <table class="table table-order">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>شماره سفارش</th>
                                                <th>تاریخ ثبت سفارش</th>
                                                <th>مبلغ قابل پرداخت</th>
                                                <th>مبلغ کل</th>
                                                <th>عملیات پرداخت</th>
                                                <th>جزییات</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php foreach ($orders as $index => $order) : ?>
                                                <tr>
                                                    <td><?= $index + 1 ?></td>
                                                    <td><?= $order->id ?></td>
                                                    <td><?= convert_date($order->created_at) ?></td>
                                                    <td><?= Order::getTotal($order->id) ?> تومان</td>
                                                    <td><?= Order::getSubTotal($order->id) ?> تومان</td>
                                                    <td><?= $order->status == 'completed' ? 'تکمیل شده' : 'لغو شده' ?></td>
                                                    <td class="details-link">
                                                        <form method="post" action="/dashboard/orders">
                                                            <button name="id" value="<?= $order->id ?>" class="btn"
                                                                    type="submit"><i class="mdi mdi-chevron-left"></i>
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                            <tr>
                                                <td class="link-to-orders" colspan="7">
                                                    <a href="/dashboard/orders">مشاهده لیست سفارش ها</a>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    <?php else : ?>
                                        <div class="alert alert-danger">سفارشی ثبت نشده است.</div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Content -->

            </div>
        </div>
    </main>
    <!-- End main-content -->

<?php include '../app/views/inc/footer.php'; ?>