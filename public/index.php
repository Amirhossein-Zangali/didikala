<?php
use \didishop\models\Product;
use \didishop\models\Category;
include '../app/views/inc/header.php';
?>
    <!-- Start main-content -->
    <main class="main-content dt-sl mt-4 mb-3">
        <div class="container main-container">
            <!-- Start Main-Slider -->
            <div class="row mb-5">
                <div class="col-xl-12 col-lg-12 col-12 order-1 order-lg-2">
                    <!-- Start main-slider -->
                    <section id="main-slider" class="main-slider carousel slide carousel-fade card" data-ride="carousel">
                        <ol class="carousel-indicators">
                            <li data-target="#main-slider" data-slide-to="0" class="active"></li>
                            <?php for ($i = 1; $i < 3; $i++) :?>
                                <li data-target="#main-slider" data-slide-to="<?= $i ?>"></li>
                            <?php endfor;?>
                        </ol>
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <a class="main-slider-slide" href="product/"
                                   style="background-image: url(./assets/img/main-slider/1.jpg)">
                                </a>
                            </div>
                            <div class="carousel-item">
                                <a class="main-slider-slide" href="product/"
                                   style="background-image: url(./assets/img/main-slider/2.jpg)">
                                </a>
                            </div>
                            <div class="carousel-item">
                                <a class="main-slider-slide" href="product/"
                                   style="background-image: url(./assets/img/main-slider/3.jpg)">
                                </a>
                            </div>
                        </div>
                        <a class="carousel-control-prev" href="#main-slider" role="button" data-slide="prev">
                            <i class="mdi mdi-chevron-right"></i>
                        </a>
                        <a class="carousel-control-next" href="#main-slider" data-slide="next">
                            <i class="mdi mdi-chevron-left"></i>
                        </a>
                    </section>
                    <!-- End main-slider -->
                </div>
            </div>
            <!-- End Main-Slider -->

            <!-- Start Product-Slider -->
            <div class="row">
                <div class="col-xl-12">
                    <section class="slider-section dt-sl mb-5">
                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="section-title text-sm-title title-wide no-after-title-wide">
                                    <h2>پر فروش ترین ها</h2>
                                    <a href="product/">مشاهده همه</a>
                                </div>
                            </div>

                            <!-- Start Product-Slider -->
                            <div class="col-12 px-res-0">
                                <div class="product-carousel carousel-md owl-carousel owl-theme">
                                    <?php
                                    $products = (new Product())->getTopSaleProducts();
                                    foreach ($products as $product) :
                                    ?>
                                    <div class="item">
                                        <div class="product-card">
                                            <div class="product-head">
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
                                                <?php if (Product::hasDiscount($product->id)): ?>
                                                <div class="discount">
                                                    <span><?= $product->discount_percent ?>%</span>
                                                </div>
                                                <?php endif;?>
                                            </div>
                                            <a class="product-thumb" href="product/<?= $product->id ?>">
                                                <img src="./<?= $product->thumbnail ?>" alt="Product Thumbnail">
                                            </a>
                                            <div class="product-card-body">
                                                <h5 class="product-title">
                                                    <a href="product/<?= $product->id ?>"><?= $product->title ?></a>
                                                </h5>
                                                <a class="product-meta" href="product/<?= $product->id ?>"><?= Category::getCategoryById($product->category_id)->title?></a>
                                                <?php if ($product->discount_percent > 0):?>
                                                <del class="text-danger"><?= Product::getPrice($product->id) ?></del>
                                                <?php endif;?>
                                                <span class="product-price d-inline"><?= Product::getSalePrice($product->id) ?> تومان</span>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endforeach;?>
                                </div>
                            </div>
                            <!-- End Product-Slider -->

                        </div>
                    </section>
                </div>
            </div>
            <!-- End Product-Slider -->

            <!-- Start Category-Section -->
            <div class="row mt-3 mb-5">
                <div class="col-12">
                    <div class="category-section dt-sn dt-sl">
                        <div class="category-section-title dt-sl">
                            <h3>دسته بندی ها</h3>
                        </div>
                        <div class="category-section-slider dt-sl">
                            <div class="category-slider owl-carousel">
                                <?php $categories = (new Category())->getCategories();
                                foreach ($categories as $category) :?>
                                <div class="item">
                                    <a href="category/<?= $category->id ?>" class="promotion-category">
                                        <img src="./assets/img/category/notebook-computer.png" alt="">
                                        <h4 class="promotion-category-name"><?= $category->title ?></h4>
                                        <h6 class="promotion-category-quantity"><?= Product::getCountCategory($category->id) ?> کالا</h6>
                                    </a>
                                </div>
                                <?php endforeach;?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Category-Section -->

            <!-- Start Product-Slider -->
            <section class="slider-section dt-sl mb-5">
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="section-title text-sm-title title-wide no-after-title-wide">
                            <h2>فروش ویژه ها</h2>
                            <a href="product/">مشاهده همه</a>
                        </div>
                    </div>

                    <!-- Start Product-Slider -->
                    <div class="col-12 px-res-0">
                        <div class="product-carousel carousel-md owl-carousel owl-theme">
                            <?php
                            $products = (new Product())->getTopDiscountProducts();
                            foreach ($products as $product) :
                                ?>
                                <div class="item">
                                    <div class="product-card">
                                        <div class="product-head">
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
                                            <?php if (Product::hasDiscount($product->id)): ?>
                                                <div class="discount">
                                                    <span><?= $product->discount_percent ?>%</span>
                                                </div>
                                            <?php endif;?>
                                        </div>
                                        <a class="product-thumb" href="product/<?= $product->id ?>">
                                            <img src="./<?= $product->thumbnail ?>" alt="Product Thumbnail">
                                        </a>
                                        <div class="product-card-body">
                                            <h5 class="product-title">
                                                <a href="product/<?= $product->id ?>"><?= $product->title ?></a>
                                            </h5>
                                            <a class="product-meta" href="product/<?= $product->id ?>"><?= Category::getCategoryById($product->category_id)->title?></a>
                                            <?php if ($product->discount_percent > 0):?>
                                                <del class="text-danger"><?= Product::getPrice($product->id) ?></del>
                                            <?php endif;?>
                                            <span class="product-price d-inline"><?= Product::getSalePrice($product->id) ?> تومان</span>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach;?>
                        </div>
                    </div>
                    <!-- End Product-Slider -->

                </div>
            </section>
            <!-- End Product-Slider -->

            <!-- Start Product-Slider -->
            <section class="slider-section dt-sl mb-5">
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="section-title text-sm-title title-wide no-after-title-wide">
                            <h2>جدید ترین ها</h2>
                            <a href="product/">مشاهده همه</a>
                        </div>
                    </div>

                    <!-- Start Product-Slider -->
                    <div class="col-12 px-res-0">
                        <div class="product-carousel carousel-md owl-carousel owl-theme">
                            <?php
                            $products = (new Product())->getProducts();
                            foreach ($products as $product) :
                                ?>
                                <div class="item">
                                    <div class="product-card">
                                        <div class="product-head">
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
                                            <?php if (Product::hasDiscount($product->id)): ?>
                                                <div class="discount">
                                                    <span><?= $product->discount_percent ?>%</span>
                                                </div>
                                            <?php endif;?>
                                        </div>
                                        <a class="product-thumb" href="product/<?= $product->id ?>">
                                            <img src="./<?= $product->thumbnail ?>" alt="Product Thumbnail">
                                        </a>
                                        <div class="product-card-body">
                                            <h5 class="product-title">
                                                <a href="product/<?= $product->id ?>"><?= $product->title ?></a>
                                            </h5>
                                            <a class="product-meta" href="product/<?= $product->id ?>"><?= Category::getCategoryById($product->category_id)->title?></a>
                                            <?php if ($product->discount_percent > 0):?>
                                                <del class="text-danger"><?= Product::getPrice($product->id) ?></del>
                                            <?php endif;?>
                                            <span class="product-price d-inline"><?= Product::getSalePrice($product->id) ?> تومان</span>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach;?>
                        </div>
                    </div>
                    <!-- End Product-Slider -->

                </div>
            </section>
            <!-- End Product-Slider -->
        </div>
    </main>
    <!-- End main-content -->
<?php include '../app/views/inc/footer.php'; ?>