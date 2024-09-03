<?php

use \didikala\models\Product;
use \didikala\models\Order;
use \didikala\models\User;

require_once "../app/bootstrap.php";
include_once 'cart-header.php';
$order = $data['order'];
$items = $data['items'];

$user = User::where('id', $_SESSION["user_id"])->first();

if (isset($_GET['success'])) {
    if ($_GET['success'] == 1) {
        if ($_GET['status'] == 2) {
            if (is_numeric($_GET['trackId'])) {
                $complete_date = Order::getVerifyPay($_GET['trackId']);
            }
        }
    }
    $trackId = $_GET['trackId'];
}

if (isset($complete_date)) {
    $current_order = Order::where('id', $order->id)->first();
    $current_order->gateway = 'zibal';
    $current_order->reference_id = $trackId;
    $current_order->status = 'completed';
    $current_order->completed_at = $complete_date;
    if ($current_order->save()){
        foreach ($items as $item) {
            Product::updateStock($item->product_id, '-');
        }
        redirect('cart/complete');
    }
} else if ($order->status == 'pending') {
    $current_order = Order::where('id', $order->id)->first();
    $current_order->gateway = 'zibal';
    $current_order->reference_id = $trackId;
    $current_order->status = 'failed';
    if ($current_order->save())
        redirect('cart/complete');
}
?>

    <!-- Start main-content -->
    <main class="main-content dt-sl mt-4 mb-3">
        <div class="container main-container">

            <div class="row">
                <?php if ($order->status == 'completed') : ?>
                    <div class="cart-page-content col-12 px-0">
                        <div class="checkout-alert dt-sn mb-4">
                            <div class="circle-box-icon successful">
                                <i class="mdi mdi-check-bold"></i>
                            </div>
                            <div class="checkout-alert-title d-flex flex-column justify-content-center align-items-center">
                                <h4> سفارش <span class="checkout-alert-highlighted checkout-alert-highlighted-success"><?= $order->reference_id ?></span>
                                    با موفقیت در سیستم ثبت شد.
                                </h4>
                                <form method="post" action="/dashboard/orders/">
                                    <button name="id" value="<?= $order->id ?>" type="submit" class="btn btn-primary text-center">رفتن به سفارش</button>
                                </form>
                            </div>
                        </div>
                        <section class="checkout-details dt-sl dt-sn mt-4 pt-2 pb-3 pr-3 pl-3 mb-5 px-res-1">
                            <div class="checkout-details-title">
                                <h4>
                                    کد سفارش:
                                    <span>
                                        <?= $order->reference_id ?>
                                    </span>
                                </h4>
                                <div class="row">
                                    <div class="col-lg-9 col-md-8 col-sm-12">
                                        <div class="checkout-details-title">
                                            <p>
                                                سفارش شما با موفقیت در سیستم ثبت شد و هم اکنون
                                                <span class="text-highlight text-highlight-success">تکمیل شده</span>
                                                است.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-12 px-res-0">
                                        <div class="checkout-table">
                                            <div class="row">
                                                <div class="col-md-6 col-sm-12">
                                                    <p>
                                                        نام تحویل گیرنده:
                                                        <span>
                                                            <?= $user->name ?>
                                                        </span></p>
                                                </div>
                                                <div class="col-md-6 col-sm-12">
                                                    <p>
                                                        شماره تماس :
                                                        <span>
                                                            <?= $user->phone ?>
                                                        </span></p>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 col-sm-12">
                                                    <p>
                                                        مبلغ کل:
                                                        <span>
                                                            <?= Order::getTotal($order->id) ?> تومان
                                                        </span></p>
                                                </div>
                                                <div class="col-md-6 col-sm-12">
                                                    <p>
                                                        روش پرداخت:
                                                        <span>
                                                            پرداخت اینترنتی
                                                            <span class="green">
                                                                (موفق)
                                                            </span></span></p>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                    <p>آدرس : <?= $user->address ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                <?php elseif ($order->status == 'failed') : ?>
                    <div class="cart-page-content col-12 px-0">
                        <div class="checkout-alert dt-sn mb-4">
                            <div class="circle-box-icon failed">
                                <i class="mdi mdi-close"></i>
                            </div>
                            <div class="checkout-alert-title d-flex flex-column justify-content-center align-items-center">
                                <h4> سفارش <span
                                            class="checkout-alert-highlighted checkout-alert-highlighted-success"><?= $order->reference_id ?></span>
                                    پرداخت ناموفق بود.
                                </h4>
                                <a href="/dashboard/" class="btn btn-primary text-center">رفتن به حساب کاربری</a>
                            </div>
                        </div>
                        <section class="checkout-details dt-sl dt-sn mt-4 pt-2 pb-3 pr-3 pl-3 mb-5 px-res-1">
                            <div class="checkout-details-title">
                                <h4>
                                    کد سفارش:
                                    <span>
                                        <?= $order->reference_id ?>
                                    </span>
                                </h4>
                                <div class="row">
                                    <div class="col-lg-9 col-md-8 col-sm-12">
                                        <div class="checkout-details-title">
                                            <p>
                                                سفارش شما با موفقیت در سیستم ثبت شد و هم اکنون
                                                <span class="text-highlight text-highlight-error">لغو شده</span>  است.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-12 px-res-0">
                                        <div class="checkout-table">
                                            <div class="row">
                                                <div class="col-md-6 col-sm-12">
                                                    <p>
                                                        نام تحویل گیرنده:
                                                        <span>
                                                            <?= $user->name ?>
                                                        </span></p>
                                                </div>
                                                <div class="col-md-6 col-sm-12">
                                                    <p>
                                                        شماره تماس :
                                                        <span>
                                                            <?= $user->phone ?>
                                                        </span></p>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 col-sm-12">
                                                    <p>
                                                        مبلغ کل:
                                                        <span>
                                                            <?= Order::getTotal($order->id) ?> تومان
                                                        </span></p>
                                                </div>
                                                <div class="col-md-6 col-sm-12">
                                                    <p>
                                                        روش پرداخت:
                                                        <span>
                                                            پرداخت اینترنتی
                                                            <span class="red">
                                                                (ناموفق)
                                                            </span></span></p>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                    <p>آدرس : <?= $user->address ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </main>
    <!-- End main-content -->

<?php include_once 'cart-footer.php'; ?>