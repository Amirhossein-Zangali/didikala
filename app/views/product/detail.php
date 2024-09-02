<?php

use \didikala\models\Product;
use \didikala\models\Category;
use \didikala\models\User;
use \didikala\models\Favorite;
use \didikala\models\Comment;

if (is_null($data['products']))
    redirect('product/?order=new');
$product = $data['products'];

if (isset($_GET['favorite'])) {
    if ($_GET['favorite'] == 'true')
        Favorite::addFavorite($_SESSION['user_id'], $product->id);
    if ($_GET['favorite'] == 'false')
        Favorite::removeFavorite($_SESSION['user_id'], $product->id);
    redirect("product/detail/$product->id");
}
if (isset($_SESSION['user_id']))
    $user_favorite = Favorite::where(['user_id' => $_SESSION['user_id'], 'product_id' => $product->id])->exists();

require_once "../app/bootstrap.php";
include '../app/views/inc/header.php';
?>
<!-- Start main-content -->
<main class="main-content dt-sl mt-4 mb-3">
    <div class="container main-container">
        <?php flash('comment_success'); ?>
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
                                    <button name="favorite" value="<?= $user_favorite ? 'false' : 'true' ?>"
                                            type="submit" class="add-favorites"><i
                                                class="mdi mdi-heart <?= $user_favorite ? 'text-danger' : '' ?>"></i>
                                    </button>
                                </form>
                                <span class="tooltip-option"><?= $user_favorite ? 'حذف از' : 'افزودن به' ?> علاقمندی</span>
                            </li>
                        </ul>
                    <?php endif; ?>
                    <?php if (Product::hasDiscount($product->id)): ?>
                        <div class="promotion-badge">
                            فروش ویژه
                        </div>
                    <?php endif; ?>
                    <div class="product-gallery">
                        <div class="product-carousel">
                            <div class="item active">
                                <a class="gallery-item" href="/product/detail/<?= $product->id ?>">
                                    <img class="img-fluid" src="/public/<?= $product->thumbnail ?>" alt="Product">
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
                                <?php if ($product->getProfitPercent($product->id) > 0): ?>
                                    <del class="text-danger price"><?= Product::getPrice($product->id) ?></del><span class="text-danger price">(<?= $product->getProfitPercent($product->id) ?>%)</span>
                                <?php endif; ?>
                                <span class="price"><?= Product::getSalePrice($product->id) ?> تومان</span>
                            </h2>
                        </div>
                        <div class="dt-sl mt-4">
                            <?php if ($product->stock > 0): ?>
                            <a href="<?= User::isUserLogin() ? "/cart/add/$product->id" : "/pages/login" ?>" class="btn-primary-cm btn-with-icon">
                                <img src="/public/assets/img/theme/shopping-cart.png" alt="">
                                افزودن به سبد خرید
                            </a>
                            <?php else : ?>
                            <a class="btn-primary-cm bg-secondary text-white btn-with-icon" style="cursor: pointer">
                                <img src="/public/assets/img/theme/shopping-cart.png" alt="">
                                محصول موجود نیست
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="dt-sn mb-5 px-0 dt-sl pt-0">
            <!-- Start tabs -->
            <section class="tabs-product-info mb-3 dt-sl">
                <div class="ah-tab-wrapper dt-sl">
                    <div class="ah-tab dt-sl">
                        <a class="ah-tab-item"><i class="mdi mdi-comment-text-multiple-outline"></i>نظرات کاربران</a>
                        <a class="ah-tab-item"><i class="mdi mdi-comment-question-outline"></i>پرسش و پاسخ</a>
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
                                            <a href="<?= User::isUserLogin() ? "/product/comment/$product->id" : "/pages/login" ?>"
                                               class="btn-primary-cm btn-with-icon">
                                                <i class="mdi mdi-comment-text-outline"></i>
                                                افزودن نظر جدید
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="comments-area dt-sl">
                                <?php if (Comment::getProductCommentCount($product->id) > 0): ?>
                                    <div class="section-title text-sm-title title-wide no-after-title-wide mb-0 dt-sl">
                                        <h2>نظرات کاربران</h2>
                                        <p class="count-comment"><?= Comment::getProductCommentCount($product->id) ?>
                                            نظر</p>
                                    </div>

                                    <ol class="comment-list">
                                        <!-- #comment-## -->
                                        <?php
                                        $comments = Comment::getProductComments($product->id);
                                        foreach ($comments as $comment):
                                            $user_id = $comment->user_id;
                                            $user = User::where('id', $user_id)->first();
                                            ?>
                                            <li>
                                                <div class="comment-body">
                                                    <div class="row">
                                                        <div class="col-md-12 col-sm-12 comment-content">
                                                            <?php if ($comment->recommendation == 1) : ?>
                                                                <div class="message-light message-light--opinion-positive float-left">
                                                                    خرید این محصول را توصیه می‌کنم
                                                                </div>
                                                            <?php elseif ($comment->recommendation == -1) : ?>
                                                                <div class="message-light message-light--opinion-positive message-danger--opinion-positive float-left">
                                                                    خرید این محصول را توصیه نمی‌کنم
                                                                </div>
                                                            <?php endif; ?>

                                                            <div class="comment-title">
                                                                <?= $product->title ?>
                                                            </div>
                                                            <div class="comment-author">
                                                                توسط <?= $user->name ?> در
                                                                تاریخ <?= convert_date($comment->created_at) ?>
                                                            </div>
                                                            <div class="row">
                                                                <?php if (!empty($comment->strengths)) : ?>
                                                                    <div class="col-md-4 col-sm-6 col-12">
                                                                        <div class="content-expert-evaluation-positive">
                                                                            <span>نقاط قوت</span>
                                                                            <ul>
                                                                                <?php $strengths = explode('/', $comment->strengths);
                                                                                foreach ($strengths as $strength): ?>
                                                                                    <li><?= $strength ?></li>
                                                                                <?php endforeach; ?>
                                                                            </ul>
                                                                        </div>
                                                                    </div>
                                                                <?php endif; ?>
                                                                <?php if (!empty($comment->weaknesses)) : ?>
                                                                    <div class="col-md-4 col-sm-6 col-12">
                                                                        <div class="content-expert-evaluation-negative">
                                                                            <span>نقاط ضعف</span>
                                                                            <ul>
                                                                                <?php $weaknesses = explode('/', $comment->weaknesses);
                                                                                foreach ($weaknesses as $weakness): ?>
                                                                                    <li><?= $weakness ?></li>
                                                                                <?php endforeach; ?>
                                                                            </ul>
                                                                        </div>
                                                                    </div>
                                                                <?php endif; ?>
                                                            </div>
                                                            <p><?= $comment->content ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        <?php endforeach; ?>
                                    </ol>
                                <?php else: ?>
                                    <div class="alert alert-danger">
                                        نظری برای این محصول ثیت نشده است!
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="ah-tab-content dt-sl">
                            <div class="comments-summary-note mb-4">
                                <span>شما هم می‌توانید در مورد این کالا پرسش مطرح کنید.</span>
                                <p>برای ثبت پرسش، لازم است ابتدا وارد حساب کاربری خود
                                    شوید.
                                </p>
                                <div class="dt-sl mt-2">
                                    <a href="<?= User::isUserLogin() ? "/product/question/$product->id" : "/pages/login" ?>"
                                       class="btn-primary-cm btn-with-icon">
                                        <i class="mdi mdi-comment-text-outline"></i>
                                        افزودن پرسش جدید
                                    </a>
                                </div>
                            </div>
                            <div class="comments-area dt-sl">
                                <?php if (Comment::getProductQuestionsCount($product->id) > 0): ?>
                                    <div class="section-title text-sm-title title-wide no-after-title-wide mt-5 mb-0 dt-sl">
                                        <h2>پرسش ها و پاسخ ها</h2>
                                        <p class="count-comment"><?= Comment::getProductQuestionsCount($product->id) ?>
                                            پرسش</p>
                                    </div>

                                    <ol class="comment-list">
                                        <!-- #comment-## -->
                                        <?php
                                        $comments = Comment::getProductQuestions($product->id);
                                        foreach ($comments as $comment):
                                            $user_id = $comment->user_id;
                                            $user = User::where('id', $user_id)->first();
                                            ?>
                                            <li>
                                                <div class="comment-body">
                                                    <div class="comment-author">
                                                        <span class="icon-comment">?</span>
                                                        <cite class="fn"><?= $user->name ?></cite>
                                                        <span class="says">گفت:</span>
                                                        <div class="commentmetadata">
                                                            <a>
                                                                <?= convert_date($comment->created_at, 'd-F-Y در H:i') ?>
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <p><?= $comment->content ?></p>
                                                    <?php if (User::isWriter(User::isUserLogin()) || User::isAdmin(User::isUserLogin())) : ?>
                                                    <div class="reply">
                                                        <form method="post" action="/product/question/<?= $product->id ?>">
                                                            <button class="comment-reply-link btn" name="reply" value="<?= $comment->id ?>" type="submit" >پاسخ</button>
                                                        </form>
                                                    </div>
                                                    <?php endif; ?>
                                                </div>
                                                <?php $replies = Comment::getReplyProductQuestions($comment->product_id, $comment->id);
                                                 if ($replies->count() > 0) :?>
                                                <ol class="children">
                                                    <?php foreach ($replies as $reply) :
                                                        $user_id = $reply->user_id;
                                                        $user = User::where('id', $user_id)->first();?>
                                                    <li>
                                                        <div class="comment-body">
                                                            <div class="comment-author">
                                                            <span class="icon-comment mdi mdi-lightbulb-on-outline"></span>
                                                                <cite class="fn"><?= $user->name ?></cite> <span
                                                                        class="says">گفت:</span>
                                                                <div class="commentmetadata">
                                                                    <a>
                                                                        <?= convert_date($reply->created_at, 'd-F-Y در H:i') ?>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                            <p><?= $reply->content ?></p>
                                                        </div>
                                                    </li>
                                                    <?php endforeach; ?>
                                                </ol>
                                                <?php endif; ?>
                                            </li>
                                        <?php endforeach; ?>
                                    </ol>
                                <?php else: ?>
                                    <div class="alert alert-danger mt-4">
                                        پرسشی برای این محصول ثیت نشده است!
                                    </div>
                                <?php endif; ?>
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
                        $products = (new Product())->getCategoryProducts($product->category_id);
                        foreach ($products as $product) :
                            ?>
                            <div class="item">
                                <div class="product-card">
                                    <div class="product-head">
                                        <?php if (Product::hasDiscount($product->id)): ?>
                                            <div class="discount">
                                                <span><?= $product->getProfitPercent($product->id) ?>%</span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <a class="product-thumb" href="/product/detail/<?= $product->id ?>">
                                        <img src="/public/<?= $product->thumbnail ?>" alt="Product Thumbnail">
                                    </a>
                                    <div class="product-card-body">
                                        <h5 class="product-title">
                                            <a href="/product/<?= $product->id ?>"><?= mb_substr($product->title, 0, 40) . '...' ?></a>
                                        </h5>
                                        <a class="product-meta"
                                           href="/product/<?= $product->id ?>"><?= Category::getCategoryById($product->category_id)->title ?></a>
                                        <?php if ($product->getProfitPercent($product->id) > 0): ?>
                                            <del class="text-danger"><?= Product::getPrice($product->id) ?></del>
                                        <?php endif; ?>
                                        <span class="product-price d-inline"><?= Product::getSalePrice($product->id) ?> تومان</span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
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
