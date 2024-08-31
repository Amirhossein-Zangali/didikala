<?php

use \didikala\models\Product;
use \didikala\models\Category;
use \didikala\models\User;
use \didikala\models\Favorite;

if (is_null($data['products']))
    redirect('product/?order=new');
$product = $data['products'];

if (isset($_GET['favorite'])){
    if ($_GET['favorite'] == 'true')
        Favorite::addFavorite($_SESSION['user_id'], $product->id);
    if ($_GET['favorite'] == 'false')
        Favorite::removeFavorite($_SESSION['user_id'], $product->id);
    redirect("product/detail/$product->id");
}
$user_favorite = Favorite::where(['user_id' => $_SESSION['user_id'], 'product_id' => $product->id])->exists();

require_once "../app/bootstrap.php";
include '../app/views/inc/header.php';
?>
        <!-- Start main-content -->
        <main class="main-content dt-sl mt-4 mb-3">
            <div class="container main-container">
                <!-- Start Product -->
                <div class="dt-sn mb-5 dt-sl">
                    <div class="row">
                        <!-- Product Gallery-->
                        <div class="col-lg-4 col-md-6 pb-5 ps-relative">
                            <!-- Product Options-->
                            <?php if (User::isUserLogin()): ?>
                            <ul class="gallery-options">
                                <li>
                                    <form method="get">
                                        <button name="favorite" value="<?= $user_favorite ? 'false' : 'true' ?>" type="submit" class="add-favorites"><i class="mdi mdi-heart <?= $user_favorite ? 'text-danger' : '' ?>"></i></button>
                                    </form>
                                    <span class="tooltip-option"><?= $user_favorite ? 'حذف از' : 'افزودن به' ?> علاقمندی</span>
                                </li>
                            </ul>
                            <?php endif;?>
                            <?php if (Product::hasDiscount($product->id)): ?>
                            <div class="promotion-badge">
                                فروش ویژه
                            </div>
                            <?php endif;?>
                            <div class="product-gallery">
                                <div class="product-carousel owl-carousel">
                                    <div class="item">
                                        <a class="gallery-item" href="/product/detail/<?= $product->id ?>"
                                            data-fancybox="gallery1" data-hash="one">
                                            <img src="/public/<?= $product->thumbnail ?>" alt="Product">
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Product Info -->
                        <div class="col-lg-8 col-md-6 pb-5">
                            <div class="product-info dt-sl">
                                <div class="product-title dt-sl">
                                    <h1><?= $product->title ?></h1>
                                </div>
                                <div class="description-product dt-sl mt-3 mb-3">
                                    <div class="container">
                                        <p><?= $product->content ?></p>
                                    </div>
                                </div>

                                <div class="section-title text-sm-title title-wide no-after-title-wide mb-0 dt-sl">
                                    <h2>کد محصول:<?= $product->id ?></h2>
                                </div>
                                <div class="section-title text-sm-title title-wide no-after-title-wide mb-0 dt-sl">

                                    <h2>قیمت :
                                        <?php if ($product->discount_percent > 0): ?>
                                            <del class="text-danger price"><?= Product::getPrice($product->id) ?></del>
                                        <?php endif; ?>
                                        <span class="price"><?= Product::getSalePrice($product->id) ?> تومان</span>
                                    </h2>
                                </div>
                                <div class="dt-sl mt-4">
                                    <a href="<?= User::isUserLogin() ? "/cart/add/$product->id" : "/pages/login" ?>" class="btn-primary-cm btn-with-icon">
                                        <img src="/public/assets/img/theme/shopping-cart.png" alt="">
                                        افزودن به سبد خرید
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="dt-sn mb-5 px-0 dt-sl pt-0">
                    <?php //TODO:comment section?>
                    <!-- Start tabs -->
                    <section class="tabs-product-info mb-3 dt-sl">
                        <div class="ah-tab-wrapper dt-sl">
                            <div class="ah-tab dt-sl">
                                <a class="ah-tab-item" href=""><i class="mdi mdi-comment-text-multiple-outline"></i>نظرات کاربران</a>
                            </div>
                        </div>
                        <div class="ah-tab-content-wrapper product-info px-4 dt-sl">
                            <div class="ah-tab-content comments-tab dt-sl" data-ah-tab-active="true">
                                <div class="dt-sl">
                                    <div class="row mb-4">
                                        <div class="col-md-6 col-sm-12">
                                            <div class="comments-summary-note">
                                                <span>شما هم می‌توانید در مورد این کالا نظر
                                                    بدهید.</span>
                                                <p>برای ثبت نظر، لازم است ابتدا وارد حساب کاربری خود
                                                    شوید.
                                                </p>
                                                <div class="dt-sl mt-2">
                                                    <a href="<?= User::isUserLogin() ? "/product/comment/$product->id" : "/pages/login" ?>" class="btn-primary-cm btn-with-icon">
                                                        <i class="mdi mdi-comment-text-outline"></i>
                                                        افزودن نظر جدید
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
    
                                    <div class="comments-area dt-sl">
                                        <div class="section-title text-sm-title title-wide no-after-title-wide mb-0 dt-sl">
                                            <h2>نظرات کاربران</h2>
                                            <p class="count-comment">123 نظر</p>
                                        </div>
                                        <ol class="comment-list">
                                            <!-- #comment-## -->
                                            <li>
                                                <div class="comment-body">
                                                    <div class="row">
                                                        <div class="col-md-3 col-sm-12">
                                                            <div class="message-light message-light--purchased">
                                                                خریدار این محصول</div>
                                                            <ul class="comments-user-shopping">
                                                                <li>
                                                                    <div class="cell">رنگ خریداری
                                                                        شده:</div>
                                                                    <div class="cell color-cell">
                                                                        <span class="shopping-color-value"
                                                                            style="background-color: #FFFFFF; border: 1px solid rgba(0, 0, 0, 0.25)"></span>سفید
                                                                    </div>
                                                                </li>
                                                                <li>
                                                                    <div class="cell">خریداری شده
                                                                        از:</div>
                                                                    <div class="cell seller-cell">
                                                                        <span class="o-text-blue">دیجی‌کالا</span>
                                                                    </div>
                                                                </li>
                                                            </ul>
                                                            <div class="message-light message-light--opinion-positive">
                                                                خرید این محصول را توصیه می‌کنم</div>
                                                        </div>
                                                        <div class="col-md-9 col-sm-12 comment-content">
                                                            <div class="comment-title">
                                                                لباسشویی سامسونگ
                                                            </div>
                                                            <div class="comment-author">
                                                                توسط مجید سجادی فرد در تاریخ ۵ مهر ۱۳۹۵
                                                            </div>
    
                                                            <p>لورم ایپسوم متن ساختگی</p>
    
                                                            <div class="footer">
                                                                <div class="comments-likes">
                                                                    آیا این نظر برایتان مفید بود؟
                                                                    <button class="btn-like" data-counter="۱۱">بله
                                                                    </button>
                                                                    <button class="btn-like" data-counter="۶">خیر
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                            <!-- #comment-## -->
                                            <li>
                                                <div class="comment-body">
                                                    <div class="row">
                                                        <div class="col-md-3 col-sm-12">
                                                            <div class="message-light message-light--purchased">
                                                                خریدار این محصول</div>
                                                            <ul class="comments-user-shopping">
                                                                <li>
                                                                    <div class="cell">رنگ خریداری
                                                                        شده:</div>
                                                                    <div class="cell color-cell">
                                                                        <span class="shopping-color-value"
                                                                            style="background-color: #FFFFFF; border: 1px solid rgba(0, 0, 0, 0.25)"></span>سفید
                                                                    </div>
                                                                </li>
                                                                <li>
                                                                    <div class="cell">خریداری شده
                                                                        از:</div>
                                                                    <div class="cell seller-cell">
                                                                        <span class="o-text-blue">دیجی‌کالا</span>
                                                                    </div>
                                                                </li>
                                                            </ul>
                                                            <div class="message-light message-light--opinion-positive">
                                                                خرید این محصول را توصیه می‌کنم</div>
                                                        </div>
                                                        <div class="col-md-9 col-sm-12 comment-content">
                                                            <div class="comment-title">
                                                                لباسشویی سامسونگ
                                                            </div>
                                                            <div class="comment-author">
                                                                توسط مجید سجادی فرد در تاریخ ۵ مهر ۱۳۹۵
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-4 col-sm-6 col-12">
                                                                    <div class="content-expert-evaluation-positive">
                                                                        <span>نقاط قوت</span>
                                                                        <ul>
                                                                            <li>دوربین‌های 4گانه پرقدرت
                                                                            </li>
                                                                            <li>باتری باظرفیت بالا</li>
                                                                            <li>حسگر اثرانگشت زیر قاب
                                                                                جلویی</li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4 col-sm-6 col-12">
                                                                    <div class="content-expert-evaluation-negative">
                                                                        <span>نقاط ضعف</span>
                                                                        <ul>
                                                                            <li>نرم‌افزار دوربین</li>
                                                                            <li>نبودن Nano SD در بازار
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <p>
                                                                بعد از چندین هفته بررسی تصمیم به خرید
                                                                این مدل از ماشین لباسشویی گرفتم ولی
                                                                متاسفانه نتونست انتظارات منو برآورده کنه
                                                                .
                                                                دو تا ایراد داره یکی اینکه حدودا تا 20
                                                                دقیقه اول شستشو یه صدایی شبیه به صدای
                                                                پمپ تخلیه همش به گوش میاد که رو مخه یکی
                                                                هم با اینکه خشک کنش تا 1400 دور در دقیقه
                                                                میچرخه، ولی اون طوری که دوستان تعریف
                                                                میکردن لباسها رو خشک نمیکنه .ضمنا برای
                                                                این صدایی که گفتم زنگ زدم نمایندگی اومدن
                                                                دیدن، وتعمیرکار گفتش که این صدا طبیعیه و
                                                                تا چند دقیقه اول شستشو عادیه.بدجوری خورد
                                                                تو ذوقم. اگه بیشتر پول میذاشتم میتونستم
                                                                یه مدل میان رده از مارکهای بوش یا آ ا گ
                                                                میخریدم که خیلی بهتر از جنس مونتاژی کره
                                                                ای هستش.
                                                            </p>
    
                                                            <div class="footer">
                                                                <div class="comments-likes">
                                                                    آیا این نظر برایتان مفید بود؟
                                                                    <button class="btn-like" data-counter="۱۱">بله
                                                                    </button>
                                                                    <button class="btn-like" data-counter="۶">خیر
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                            <!-- #comment-## -->
                                            <li>
                                                <div class="comment-body">
                                                    <div class="row">
                                                        <div class="col-md-3 col-sm-12">
                                                            <div class="message-light message-light--purchased">
                                                                خریدار این محصول</div>
                                                            <ul class="comments-user-shopping">
                                                                <li>
                                                                    <div class="cell">رنگ خریداری
                                                                        شده:</div>
                                                                    <div class="cell color-cell">
                                                                        <span class="shopping-color-value"
                                                                            style="background-color: #FFFFFF; border: 1px solid rgba(0, 0, 0, 0.25)"></span>سفید
                                                                    </div>
                                                                </li>
                                                                <li>
                                                                    <div class="cell">خریداری شده
                                                                        از:</div>
                                                                    <div class="cell seller-cell">
                                                                        <span class="o-text-blue">دیجی‌کالا</span>
                                                                    </div>
                                                                </li>
                                                            </ul>
                                                            <div class="message-light message-light--opinion-positive">
                                                                خرید این محصول را توصیه می‌کنم</div>
                                                        </div>
                                                        <div class="col-md-9 col-sm-12 comment-content">
                                                            <div class="comment-title">
                                                                لباسشویی سامسونگ
                                                            </div>
                                                            <div class="comment-author">
                                                                توسط مجید سجادی فرد در تاریخ ۵ مهر ۱۳۹۵
                                                            </div>
    
                                                            <p>لورم ایپسوم متن ساختگی</p>
    
                                                            <div class="footer">
                                                                <div class="comments-likes">
                                                                    آیا این نظر برایتان مفید بود؟
                                                                    <button class="btn-like" data-counter="۱۱">بله
                                                                    </button>
                                                                    <button class="btn-like" data-counter="۶">خیر
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                            <!-- #comment-## -->
                                            <li>
                                                <div class="comment-body">
                                                    <div class="row">
                                                        <div class="col-md-3 col-sm-12">
                                                            <div class="message-light message-light--purchased">
                                                                خریدار این محصول</div>
                                                            <ul class="comments-user-shopping">
                                                                <li>
                                                                    <div class="cell">رنگ خریداری
                                                                        شده:</div>
                                                                    <div class="cell color-cell">
                                                                        <span class="shopping-color-value"
                                                                            style="background-color: #FFFFFF; border: 1px solid rgba(0, 0, 0, 0.25)"></span>سفید
                                                                    </div>
                                                                </li>
                                                                <li>
                                                                    <div class="cell">خریداری شده
                                                                        از:</div>
                                                                    <div class="cell seller-cell">
                                                                        <span class="o-text-blue">دیجی‌کالا</span>
                                                                    </div>
                                                                </li>
                                                            </ul>
                                                            <div class="message-light message-light--opinion-positive">
                                                                خرید این محصول را توصیه می‌کنم</div>
                                                        </div>
                                                        <div class="col-md-9 col-sm-12 comment-content">
                                                            <div class="comment-title">
                                                                لباسشویی سامسونگ
                                                            </div>
                                                            <div class="comment-author">
                                                                توسط مجید سجادی فرد در تاریخ ۵ مهر ۱۳۹۵
                                                            </div>
    
                                                            <p>لورم ایپسوم متن ساختگی</p>
    
                                                            <div class="footer">
                                                                <div class="comments-likes">
                                                                    آیا این نظر برایتان مفید بود؟
                                                                    <button class="btn-like" data-counter="۱۱">بله
                                                                    </button>
                                                                    <button class="btn-like" data-counter="۶">خیر
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                            <!-- #comment-## -->
                                            <li>
                                                <div class="comment-body">
                                                    <div class="row">
                                                        <div class="col-md-3 col-sm-12">
                                                            <div class="message-light message-light--purchased">
                                                                خریدار این محصول</div>
                                                            <ul class="comments-user-shopping">
                                                                <li>
                                                                    <div class="cell">رنگ خریداری
                                                                        شده:</div>
                                                                    <div class="cell color-cell">
                                                                        <span class="shopping-color-value"
                                                                            style="background-color: #FFFFFF; border: 1px solid rgba(0, 0, 0, 0.25)"></span>سفید
                                                                    </div>
                                                                </li>
                                                                <li>
                                                                    <div class="cell">خریداری شده
                                                                        از:</div>
                                                                    <div class="cell seller-cell">
                                                                        <span class="o-text-blue">دیجی‌کالا</span>
                                                                    </div>
                                                                </li>
                                                            </ul>
                                                            <div class="message-light message-light--opinion-positive">
                                                                خرید این محصول را توصیه می‌کنم</div>
                                                        </div>
                                                        <div class="col-md-9 col-sm-12 comment-content">
                                                            <div class="comment-title">
                                                                لباسشویی سامسونگ
                                                            </div>
                                                            <div class="comment-author">
                                                                توسط مجید سجادی فرد در تاریخ ۵ مهر ۱۳۹۵
                                                            </div>
    
                                                            <p>لورم ایپسوم متن ساختگی</p>
    
                                                            <div class="footer">
                                                                <div class="comments-likes">
                                                                    آیا این نظر برایتان مفید بود؟
                                                                    <button class="btn-like" data-counter="۱۱">بله
                                                                    </button>
                                                                    <button class="btn-like" data-counter="۶">خیر
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        </ol>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <!-- End tabs -->
                </div>
                <!-- End Product -->

                <!-- Start Product-Slider -->
                <section class="slider-section dt-sl mb-5">
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="section-title text-sm-title title-wide no-after-title-wide">
                                <h2>محصولات مشابه</h2>
                            </div>
                        </div>

                        <!-- Start Product-Slider -->
                        <div class="col-12 px-res-0">
                            <div class="product-carousel carousel-md owl-carousel owl-theme">
                                <?php
                                $products = (new Product())->getCategoryPost($product->category_id);
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
                                            <a class="product-thumb" href="/product/<?= $product->id ?>">
                                                <img src="/public/<?= $product->thumbnail ?>" alt="Product Thumbnail">
                                            </a>
                                            <div class="product-card-body">
                                                <h5 class="product-title">
                                                    <a href="/product/<?= $product->id ?>"><?= $product->title ?></a>
                                                </h5>
                                                <a class="product-meta" href="/product/<?= $product->id ?>"><?= Category::getCategoryById($product->category_id)->title?></a>
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
