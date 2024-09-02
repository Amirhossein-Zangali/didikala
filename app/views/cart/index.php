<?php

use \didikala\models\Product;
use \didikala\models\Order;

require_once "../app/bootstrap.php";

if (isset($data['items']))
    $items = $data['items'];
else
    $items = false;
if ($items) {
    if ($items->count() < 1)
        $items = false;
}
include '../app/views/inc/header.php';

?>

    <!-- Start main-content -->
    <main class="main-content dt-sl mt-4 mb-3">
        <div class="container main-container">
            <?php if ($items) : ?>
                <div class="row">
                    <div class="col-xl-9 col-lg-8 col-md-12 col-sm-12 mb-2 px-0">
                        <nav class="tab-cart-page">
                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                <a class="nav-item nav-link active w-100" id="nav-home-tab" data-toggle="tab"
                                   href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">سبد
                                    خرید</a>
                            </div>
                        </nav>
                    </div>
                    <div class="col-12">
                        <div class="tab-content" id="nav-tabContent">
                            <div class="tab-pane fade show active" id="nav-home" role="tabpanel"
                                 aria-labelledby="nav-home-tab">
                                <div class="row">
                                    <div class="col-xl-9 col-lg-8 col-12 px-0">
                                        <div class="table-responsive checkout-content dt-sl">
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
                                                                <h3 class="text-dark h5"><?= $product->title ?></h3>
                                                            </a>
                                                        </td>
                                                        <td class="d-flex mt-lg-5">
                                                            <form method="post" action="/cart/update/<?= $item->id ?>/<?= $product->id ?>" class="d-flex justify-content-between align-items-center">
                                                                <div>
                                                                    <label for="quantity">تعداد</label>
                                                                    <div class="number-input">
                                                                        <button type="button" onclick="this.parentNode.querySelector('input[type=number]').stepDown()"></button>
                                                                        <input class="quantity" id="quantity" min="1" max="<?= $product->stock ?>" name="quantity" value="<?= $item->quantity ?>" type="number">
                                                                        <button type="button" onclick="this.parentNode.querySelector('input[type=number]').stepUp()" class="plus"></button>
                                                                    </div>
                                                                </div>
                                                                <button type="submit" class="btn btn-primary mx-2" style="height: 40px">ویرایش</button>
                                                            </form>
                                                            <form method="post" action="/cart/delete/<?= $item->id ?>" class="d-flex justify-content-between align-items-center">
                                                                <button type="submit" class="btn btn-danger"
                                                                        style="height: 40px">حذف
                                                                </button>
                                                            </form>
                                                        </td>
                                                        <td>
                                                            <?php if (Product::hasDiscount($product->id)) : ?>
                                                                <del class="text-danger"><?= Product::getPrice($product->id) ?></del><span class="text-danger price">(<?= $product->getProfitPercent($product->id) ?>%)</span>
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
                                    <?php include_once 'cart-sidebar.php'; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="row">
                    <div class="col-12">
                        <div class="dt sl dt-sn pt-3 pb-5">
                            <div class="cart-page cart-empty">
                                <div class="circle-box-icon">
                                    <i class="mdi mdi-cart-remove"></i>
                                </div>
                                <p class="cart-empty-title">سبد خرید شما خالی است!</p>
                                <a href="/product" class="btn-primary-cm">خرید در دی دی کالا</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </main>
    <!-- End main-content -->

<?php include '../app/views/inc/footer.php'; ?>