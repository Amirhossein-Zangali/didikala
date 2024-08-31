<?php

use \didikala\models\Product;
use \didikala\models\Category;

require_once "../app/bootstrap.php";
include '../app/views/inc/header.php';
?>
    <!-- Start main-content -->
    <main class="main-content dt-sl m-0">
        <div class="container main-container">
            <div class="row">
                <!-- Start Content -->
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="dt-sl dt-sn px-0 search-amazing-tab">
                        <div class="ah-tab-content-wrapper dt-sl px-res-0">
                            <div class="ah-tab-content dt-sl" data-ah-tab-active="true">
                                <div class="row mb-3 mx-0 px-res-0">
                                    <?php foreach ($data['products'] as $product) : ?>
                                    <div class="col-lg-3 col-md-4 col-sm-6 col-12 px-10 mb-1 px-res-0">
                                        <div class="product-card mb-2 mx-res-0">
                                            <?php if (Product::hasDiscount($product->id)): ?>
                                            <div class="promotion-badge">
                                                فروش ویژه
                                            </div>
                                            <div class="product-head">
                                                <div class="discount">
                                                    <span><?= $product->discount_percent ?>%</span>
                                                </div>
                                                <?php else: ?>
                                                <div class="product-head">
                                                    <?php endif; ?>
                                                    <div class="rating-stars">
                                                        <?php
                                                        // TODO: show rating
                                                        ?>
                                                        <i class="mdi mdi-star active"></i>
                                                        <i class="mdi mdi-star active"></i>
                                                        <i class="mdi mdi-star active"></i>
                                                        <i class="mdi mdi-star active"></i>
                                                        <i class="mdi mdi-star active"></i>
                                                    </div>
                                                </div>
                                                <a class="product-thumb" href="/product/detail/<?= $product->id ?>">
                                                    <img src="/public/<?= $product->thumbnail ?>"
                                                         alt="Product Thumbnail">
                                                </a>
                                                <div class="product-card-body">
                                                    <h5 class="product-title">
                                                        <a href="product/<?= $product->id ?>"><?= $product->title ?></a>
                                                    </h5>
                                                    <a class="product-meta"
                                                       href="product/<?= $product->id ?>"><?= Category::getCategoryById($product->category_id)->title ?></a>
                                                    <?php if ($product->discount_percent > 0): ?>
                                                        <del class="text-danger"><?= Product::getPrice($product->id) ?></del>
                                                    <?php endif; ?>
                                                    <span class="product-price d-inline"><?= Product::getSalePrice($product->id) ?> تومان</span>
                                                </div>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <?php //TODO: pagination ?>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="pagination">
                                                <a href="#" class="prev"><i
                                                            class="mdi mdi-chevron-double-right"></i></a>
                                                <a href="#">1</a>
                                                <a href="#" class="active-page">2</a>
                                                <a href="#">3</a>
                                                <a href="#">4</a>
                                                <a href="#">...</a>
                                                <a href="#">7</a>
                                                <a href="#" class="next"><i class="mdi mdi-chevron-double-left"></i></a>
                                            </div>
                                        </div>
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