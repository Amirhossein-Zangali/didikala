<?php

use \didikala\models\Order;

$url = $_SERVER['REDIRECT_URL'];
?>
<div class="col-xl-3 col-lg-4 col-12 w-res-sidebar sticky-sidebar">
    <div class="dt-sn mb-2">
        <ul class="checkout-summary-summary">
            <li>
                <span>مبلغ کل</span><span><?= Order::getSubTotal($item->order_id); ?> تومان</span>
            </li>
            <?php if (Order::getProfit($item->order_id)) : ?>
            <li class="checkout-summary-discount">
                <span>سود شما از خرید</span><span><span>(<?= Order::getProfitPercent($item->order_id) ?>٪) </span><?= Order::getProfit($item->order_id) ?> تومان</span>
            </li>
            <?php endif; ?>
            <li class="checkout-summary-discount">
                <span>هزینه ارسال</span><span>رایگان</span>
            </li>
        </ul>
        <div class="checkout-summary-devider">
            <div></div>
        </div>
        <div class="checkout-summary-content">
            <div class="checkout-summary-price-title">مبلغ قابل پرداخت:</div>
            <div class="checkout-summary-price-value">
                <span class="checkout-summary-price-value-amount"><?= Order::getTotal($item->order_id); ?></span>
                تومان
            </div>
            <?php if (strstr($url, 'address')): ?>
                <a href="/cart/pay/" class="mb-2 d-block">
                    <button class="btn-primary-cm btn-with-icon w-100 text-center pr-0">
                        <i class="mdi mdi-arrow-left"></i>
                        ادامه ثبت سفارش
                    </button>
                </a>
            <?php elseif (strstr($url, 'pay')): ?>
                <?php
                $price = (Order::getTotal($item->order_id, false)) * 10;
                $merchant = "zibal";
                $url = "http://didikala.local/cart/complete";
                $trackId = Order::getTrackId($price, $merchant, $url);
                if (is_numeric($trackId))
                    $payUrl = Order::getPayUrl($trackId);

                if (isset($payUrl)):
                ?>
                <a href="<?= $payUrl ?>" class="mb-2 d-block">
                    <button class="btn-primary-cm btn-with-icon w-100 text-center pr-0">
                        <i class="mdi mdi-arrow-left"></i>
                        پرداخت و ثبت نهایی
                    </button>
                </a>
                <?php else : ?>
                    <form method="post">
                        <button type="submit" name="err" value="<?= $trackId ?>" class="btn-primary-cm btn-with-icon w-100 text-center pr-0">
                            <i class="mdi mdi-arrow-left"></i>
                            پرداخت و ثبت نهایی
                        </button>
                    </form>
                <?php endif; ?>
            <?php elseif ($url == '/public/cart/' || $url == '/public/cart'): ?>
                <a href="/cart/address/" class="mb-2 d-block">
                    <button class="btn-primary-cm btn-with-icon w-100 text-center pr-0">
                        <i class="mdi mdi-arrow-left"></i>
                        ادامه ثبت سفارش
                    </button>
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>