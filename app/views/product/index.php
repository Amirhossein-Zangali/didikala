<?php

use \didikala\models\Product;
use \didikala\models\Category;
if (!isset($_GET['order']) && !strstr($_GET['url'], 'detail'))
    redirect('product/?order=new');

require_once "../app/bootstrap.php";
include '../app/views/inc/header.php';
?>
    <!-- Start main-content -->
    <main class="main-content dt-sl mt-4 mb-3">
        <div class="container main-container">

            <div class="row">
                <?php
                if (isset($data[0])) {
                    $search = $data[0][0];
                    $category_id = $data[0][1];
                    $price_start = $data[0][2];
                    $price_end = $data[0][3];
                    $stock = $data[0][4];
                }
                ?>
                <!-- Start Sidebar -->
                <div class="col-lg-3 col-md-12 col-sm-12 sticky-sidebar">
                    <div class="dt-sn mb-3">
                        <form method="post">
                            <div class="col-12">
                                <div class="section-title text-sm-title title-wide mb-1 no-after-title-wide">
                                    <h2>فیلتر محصولات</h2>
                                </div>
                            </div>
                            <div class="col-12 mb-3">
                                <div class="widget-search">
                                    <input value="<?= @$search ?>" type="text" name="search"
                                           placeholder="نام محصول مورد نظر را بنویسید...">
                                    <button class="btn-search-widget">
                                        <img src="/public/assets/img/theme/search.png" alt="">
                                    </button>
                                </div>
                            </div>
                            <div class="col-12 filter-product mb-3">
                                <div class="accordion" id="accordionExample">
                                    <div class="card">
                                        <div class="card-header" id="headingOne">
                                            <h2 class="mb-0">
                                                <button class="btn btn-block text-right collapsed" type="button"
                                                        data-toggle="collapse" data-target="#collapseOne"
                                                        aria-expanded="false" aria-controls="collapseOne">
                                                    دسته بندی
                                                    <i class="mdi mdi-chevron-down"></i>
                                                </button>
                                            </h2>
                                        </div>
                                        <div id="collapseOne" class="collapse" aria-labelledby="headingOne"
                                             data-parent="#accordionExample">
                                            <div class="card-body">
                                                <div class="custom-control custom-checkbox">
                                                    <input name="category"
                                                           value="0" <?= @$category_id == 0 ? 'checked' : '' ?>
                                                           type="radio" class="custom-control-input"
                                                           id="customCheck1">
                                                    <label class="custom-control-label"
                                                           for="customCheck1">همه</label>
                                                </div>
                                                <?php $categories = (new Category)->getCategories();
                                                foreach ($categories as $category) : ?>
                                                    <div class="custom-control custom-checkbox">
                                                        <input name="category"
                                                               value="<?= $category->id ?>" <?= $category->id == @$category_id ? 'checked' : '' ?>
                                                               type="radio" class="custom-control-input"
                                                               id="customCheck<?= $category->id ?>">
                                                        <label class="custom-control-label"
                                                               for="customCheck<?= $category->id ?>"><?= $category->title ?></label>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mb-4">
                                <div class="section-title text-sm-title title-wide no-after-title-wide mb-1">
                                    <h2>فیلتر براساس قیمت :</h2>
                                </div>
                                <div class="mt-2 mb-2 text-center pt-2">
                                    <span>قیمت از: </span>
                                    <input value="<?= @$price_start !== 0 ? $price_start : '' ?>" name="price_start"
                                           class="fo" type="number">
                                    <span class="example-val" id="slider-non-linear-step-value"></span> تومان
                                </div>
                                <div class="mt-2 mb-2 text-center pt-2">
                                    <span>قیمت تا: </span>
                                    <input value="<?= @$price_end !== 0 ? $price_end : '' ?>" name="price_end"
                                           class="fo" type="number">
                                    <span class="example-val" id="slider-non-linear-step-value"></span> تومان
                                </div>
                            </div>
                            <div class="col-12 mb-3">
                                <div class="parent-switcher">
                                    <label class="ui-statusswitcher">
                                        <input <?= @$stock ? 'checked' : '' ?> name="stock" type="checkbox"
                                                                               id="switcher-1">
                                        <span class="ui-statusswitcher-slider">
                                                <span class="ui-statusswitcher-slider-toggle"></span>
                                            </span>
                                    </label>
                                    <label class="label-switcher" for="switcher-1">فقط کالاهای موجود</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <button class="btn btn-info btn-block" type="submit">
                                    فیلتر
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- End Sidebar -->

                <!-- Start Content -->
                <div class="col-lg-9 col-md-12 col-sm-12 search-card-res">
                    <div class="dt-sl dt-sn px-0 search-amazing-tab">
                        <div class="ah-tab-wrapper dt-sl">
                            <div class="ah-tab dt-sl">
                                <form method="get" action="/product/">
                                    <button name="order" value="new" class="ah-tab-item"
                                            data-ah-tab-active="<?= @$_GET['order'] == 'new' ? 'true' : 'false' ?>">جدید
                                        ترین
                                    </button>
                                    <button name="order" value="top_seller" class="ah-tab-item"
                                            data-ah-tab-active="<?= @$_GET['order'] == 'top_seller' ? 'true' : 'false' ?>">
                                        پر فروش ترین
                                    </button>
                                    <button name="order" value="top_discount" class="ah-tab-item"
                                            data-ah-tab-active="<?= @$_GET['order'] == 'top_discount' ? 'true' : 'false' ?>">
                                        فروش ویژه
                                    </button>
                                    <button name="order" value="low_price" class="ah-tab-item"
                                            data-ah-tab-active="<?= @$_GET['order'] == 'low_price' ? 'true' : 'false' ?>">
                                        ارزان ترین
                                    </button>
                                    <button name="order" value="high_price" class="ah-tab-item"
                                            data-ah-tab-active="<?= @$_GET['order'] == 'high_price' ? 'true' : 'false' ?>">
                                        گران ترین
                                    </button>
                                </form>
                            </div>
                        </div>
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