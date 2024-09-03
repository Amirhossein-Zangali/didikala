<?php

use didikala\models\Category;
use didikala\models\Favorite;
use didikala\models\Order;
use didikala\models\OrderItem;
use didikala\models\Product;
use didikala\models\User;

require_once "../app/bootstrap.php";

if (User::isUserLogin()) {
    if (!User::isUser()) {
        if (User::isUserAdmin() || User::isUserWriter())
            redirect('panel/');
        else
            redirect('');
    }
} else {
    redirect('pages/login');
}

$order = false;
if (isset($data['order']))
    $order = $data['order'];


include '../app/views/inc/header.php';

$user = User::where('id', $_SESSION['user_id'])->first();
?>

    <!-- Start main-content -->
    <main class="main-content dt-sl mt-4 mb-3">
        <div class="container main-container">
            <div class="row">

                <?php include_once 'dashboard-sidebar.php' ?>
                <?php if (!$order): ?>
                    <!-- Start Content -->
                    <div class="col-xl-9 col-lg-8 col-md-8 col-sm-12">
                        <div class="row">
                            <div class="col-12">
                                <div class="section-title text-sm-title title-wide mb-1 no-after-title-wide dt-sl mb-2 px-res-1">
                                    <h2>سفارش‌ها</h2>
                                </div>
                                <div class="profile-section dt-sl">
                                    <?php $orders = Order::getOrders($user->id, 0);
                                    if ($orders->count() > 0) :?>
                                    <div class="table-responsive">
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
                                                                        type="submit"><i
                                                                            class="mdi mdi-chevron-left"></i>
                                                                </button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                    </div>
                                    <?php else : ?>
                                        <div class="alert alert-danger">سفارشی ثبت نشده است.</div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Content -->
                <?php else : ?>
                    <!-- Start Content -->
                    <div class="col-xl-9 col-lg-8 col-md-8 col-sm-12">
                        <div class="row">
                            <div class="col-12">
                                <div class="profile-navbar">
                                    <a href="/dashboard/orders/" class="profile-navbar-btn-back">بازگشت</a>
                                    <h4>سفارش <span class="font-en"><?= $order->reference_id ?></span><span>ثبت شده در تاریخ <?= convert_date($order->created_at) ?></span>
                                    </h4>
                                </div>
                            </div>
                            <div class="col-12 mb-4">
                                <div class="dt-sl dt-sn">
                                    <div class="row table-draught px-3">
                                        <div class="col-md-6 col-sm-12">
                                            <span class="title">نام تحویل گیرنده:</span>
                                            <span class="value"><?= $user->name ?></span>
                                        </div>
                                        <div class="col-md-6 col-sm-12">
                                            <span class="title">شماره تماس تحویل گیرنده:</span>
                                            <span class="value"><?= $user->phone ?? 'ثبت نشده' ?></span>
                                        </div>
                                        <div class="col-md-6 col-sm-12">
                                            <span class="title">کد سفارش:</span>
                                            <span class="value"><?= $order->id ?></span>
                                        </div>
                                        <div class="col-md-6 col-sm-12">
                                            <span class="title">درگاه پرداخت:</span>
                                            <span class="value"><?= $order->gateway ?></span>
                                        </div>
                                        <div class="col-md-6 col-sm-12">
                                            <span class="title">هزینه ارسال:</span>
                                            <span class="value">رایگان</span>
                                        </div>
                                        <div class="col-md-6 col-sm-12">
                                            <span class="title">زمان تکمیل:</span>
                                            <span class="value"><?= $order->completed_at ? convert_date($order->completed_at, 'd-F-Y در ساعت H:i:s') : '<span class="text-danger">لفو شده</span>' ?></span>
                                        </div>
                                        <div class="col-12 text-center pb-0">
                                            <span class="title">مبلغ این سفارش:</span>
                                            <span class="value"><?= Order::getTotal($order->id) ?> تومان</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Content -->
                    <!-- Start Product-Slider -->
                    <section class="col-xl-12 col-lg-8 col-md-8 col-sm-12 slider-section dt-sl mt-5 mb-5">
                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="section-title text-sm-title title-wide no-after-title-wide">
                                    <h2>محصولات سفارش</h2>
                                </div>
                            </div>
                            <div class="table-responsive checkout-content dt-sl">

                                <?php flash('message'); ?>
                                <table class="table table-cart">
                                    <tbody>
                                    <?php $items = Order::getItems($order->id);
                                    foreach ($items as $item) :
                                        $product = Product::where('id', $item->product_id)->first(); ?>
                                        <tr class="checkout-item">
                                            <td>
                                                <img width="200" src="/public/<?= $product->thumbnail ?>"
                                                     class="img-fluid" alt="">
                                            </td>
                                            <td class="text-right">
                                                <a href="/product/detail/<?= $product->id ?>">
                                                    <h3 class="text-dark h6"><?= $product->title ?></h3>
                                                </a>
                                            </td>
                                            <td>تعداد
                                                <br><?= $item->quantity ?>
                                            </td>
                                            <td>
                                                <?php if (Product::hasDiscount($product->id)) : ?>
                                                    <del class="text-danger"><?= Product::getPrice($product->id) ?></del>
                                                    <span class="text-danger price">(<?= $product->getProfitPercent($product->id) ?>%)</span>
                                                    <br>
                                                <?php endif; ?>
                                                <strong><?= Product::getSalePrice($product->id) ?>
                                                    تومان</strong>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>

                            </div>

                        </div>
                    </section>
                    <!-- End Product-Slider -->
                <?php endif; ?>

            </div>
        </div>
    </main>
    <!-- End main-content -->

<?php include '../app/views/inc/footer.php'; ?>