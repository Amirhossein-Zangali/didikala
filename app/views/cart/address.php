<?php

use \didikala\models\Product;
use \didikala\models\Order;
use \didikala\models\User;

require_once "../app/bootstrap.php";
include_once 'cart-header.php';
$items = $data['items'];

$user = User::where('id', $_SESSION["user_id"])->first();
?>
    <!-- Start main-content -->
    <main class="main-content dt-sl mt-4 mb-3">
        <div class="container main-container">

            <div class="row">
                <div class="cart-page-content col-xl-9 col-lg-8 col-12 px-0">
                    <div class="section-title text-sm-title title-wide no-after-title-wide mb-0 px-res-1">
                        <h2>آدرس تحویل سفارش</h2>
                    </div>
                    <section class="page-content dt-sl">
                        <div class="address-section">
                            <div class="checkout-contact dt-sn rounded-0 px-0 pt-0 pb-0">
                                <?php if (User::haveAddress($user->id)) : ?>
                                <div class="checkout-contact-content">
                                    <ul class="checkout-contact-items">
                                        <li class="checkout-contact-item">
                                            گیرنده:
                                            <span class="full-name"><?= $user->name ?></span>
                                        </li>
                                        <li class="checkout-contact-item">
                                            <div class="checkout-contact-item-message">
                                                کد پستی:
                                                <span class="post-code"><?= $user->post_code ?></span>
                                            </div>
                                            <br>
                                            <span><?= $user->address ?></span>
                                        </li>
                                    </ul>
                                    <a href="/panel/" class="checkout-contact-location">تغییر آدرس</a>
                                    <div class="checkout-contact-badge">
                                        <i class="mdi mdi-check-bold"></i>
                                    </div>
                                </div>
                                <?php else : ?>
                                    <div class="checkout-contact-content">
                                        ابتدا در حساب کاربری خود آدرس ثبت کنید.
                                        <a href="/panel/" class="btn btn-primary">ثبت آدرس</a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </section>
                    <div class="table-responsive checkout-content dt-sl">
                        <div class="col-xl-12 col-lg-8 col-md-12 col-sm-12 mb-2 px-0">
                            <nav class="tab-cart-page">
                                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                    <a class="nav-item nav-link active w-100" id="nav-home-tab" data-toggle="tab"
                                       href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">سبد
                                        خرید</a>
                                </div>
                            </nav>
                        </div>

                        <?php flash('message'); ?>
                        <table class="table table-cart">
                            <tbody>

                            <?php foreach ($items as $item) :
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
                                            <del class="text-danger"><?= Product::getPrice($product->id) ?></del><span class="text-danger price">(<?= $product->getProfitPercent($product->id) ?>%)</span>
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
                    <div class="mt-5">
                        <a href="/cart/" class="float-right border-bottom-dt btn"><i class="mdi mdi-chevron-double-right"></i>بازگشت به سبد خرید</a>
                    </div>
                </div>
                <?php include_once 'cart-sidebar.php'; ?>
            </div>

        </div>
    </main>
    <!-- End main-content -->

<?php include_once 'cart-footer.php'; ?>