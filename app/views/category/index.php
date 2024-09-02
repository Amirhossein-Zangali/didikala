<?php

use \didikala\models\Product;
use \didikala\models\Category;

$products = $data['products'];
$page_count = Product::getPageCount();
$page = $data['page'];
if ($page > $page_count)
    redirect("product/?order=new&&page=$page_count");

Product::$offset = $data['offset'];

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
                                    <?php foreach ($products as $product) : ?>
                                    <div class="col-lg-3 col-md-4 col-sm-6 col-12 px-10 mb-1 px-res-0">
                                        <div class="product-card mb-2 mx-res-0">
                                            <?php if (Product::hasDiscount($product->id)): ?>
                                            <div class="promotion-badge">
                                                فروش ویژه
                                            </div>
                                            <div class="product-head">
                                                <div class="discount">
                                                    <span><?= $product->getProfitPercent($product->id) ?>%</span>
                                                </div>
                                                <?php else: ?>
                                                <div class="product-head">
                                                    <?php endif; ?>
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
                                                    <?php if ($product->getProfitPercent($product->id) > 0): ?>
                                                        <del class="text-danger"><?= Product::getPrice($product->id) ?></del>
                                                    <?php endif; ?>
                                                    <span class="product-price d-inline"><?= Product::getSalePrice($product->id) ?> تومان</span>
                                                </div>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <?php if ($page_count > 1) : ?>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="pagination paginations">
                                                <form method="post">
                                                    <?php for ($i = 1; $i <= $page_count; $i++) : ?>
                                                        <button name="page" value="<?= $i ?>" type="submit" class="btn <?= $i == $page ? 'btn-danger' : ''; ?>"><?= $i ?></button>
                                                    <?php endfor; ?>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endif; ?>
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