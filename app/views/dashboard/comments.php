<?php

use didikala\models\Comment;
use didikala\models\Favorite;
use didikala\models\Order;
use didikala\models\Product;
use didikala\models\User;

require_once "../app/bootstrap.php";

if (!User::isUserLogin())
    redirect('pages/login');

include '../app/views/inc/header.php';

$user = User::where('id', $_SESSION['user_id'])->first();
?>

    <!-- Start main-content -->
    <main class="main-content dt-sl mt-4 mb-3">
        <div class="container main-container">
            <div class="row">

                <?php include_once 'dashboard-sidebar.php' ?>

                <!-- Start Content -->
                <div class="col-xl-9 col-lg-8 col-md-8 col-sm-12">
                    <div class="row">
                        <div class="col-12">
                            <div class="section-title text-sm-title title-wide mb-1 no-after-title-wide dt-sl mb-2 px-res-1">
                                <h2>پرسش ها</h2>
                            </div>
                            <div class="dt-sl">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12">
                                        <?php $comments = Comment::getUserQuestions($user->id);
                                        if ($comments->count() > 0) :
                                            foreach ($comments as $comment) : $product = Product::where('id', $comment->product_id)->first(); ?>
                                                <div class="card-horizontal-product">
                                                    <div class="card-horizontal-product-thumb">
                                                        <a href="/product/detail/<?= $product->id ?>">
                                                            <img width="200" src="/public/<?= $product->thumbnail ?>"
                                                                 alt="">
                                                        </a>
                                                    </div>
                                                    <div class="card-horizontal-product-content">
                                                        <?php if ($comment->status == 0) : ?>
                                                            <button class="label-status-comment text-warning"
                                                                    style="background-color: #fff3cd">تایید نشده
                                                            </button>
                                                        <?php elseif ($comment->status == 1) : ?>
                                                            <button class="label-status-comment text-success"
                                                                    style="background-color: #d4edda">تایید شده
                                                            </button>
                                                        <?php elseif ($comment->status == -1) : ?>
                                                            <button class="label-status-comment text-danger"
                                                                    style="background-color: #f8d7da">رد شده
                                                            </button>
                                                        <?php endif; ?>
                                                        <div class="card-horizontal-comment-title">
                                                            <a href="/product/detail/<?= $product->id ?>">
                                                                <h3><?= $product->title ?></h3>
                                                            </a>
                                                        </div>
                                                        <div class="card-horizontal-comment">
                                                            <p><?= $comment->content ?></p>
                                                            <?php if (Comment::haveReply($comment->id)) : ?>
                                                                <h5>پاسخ :
                                                                    <?= Comment::getReply($comment->id)->content ?>
                                                                </h5>
                                                            <?php endif; ?>
                                                        </div>
                                                        <div class="card-horizontal-product-buttons">
                                                            <form method="post">
                                                                <button name="delete" value="<?= $comment->id ?>"
                                                                        class="btn btn-light">حذف
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach;
                                        else: ?>
                                            <div class="alert alert-danger">
                                                پرسشی ندارید.
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="section-title text-sm-title title-wide mb-1 no-after-title-wide dt-sl mb-2 px-res-1">
                                <h2>نظرات</h2>
                            </div>
                            <div class="dt-sl">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12">
                                        <?php $comments = Comment::getUserComments($user->id);
                                        if ($comments->count() > 0) :
                                            foreach ($comments as $comment) : $product = Product::where('id', $comment->product_id)->first(); ?>
                                                <div class="card-horizontal-product">
                                                    <div class="card-horizontal-product-thumb">
                                                        <a href="/product/detail/<?= $product->id ?>">
                                                            <img width="200" src="/public/<?= $product->thumbnail ?>"
                                                                 alt="">
                                                        </a>
                                                    </div>
                                                    <div class="card-horizontal-product-content">
                                                        <?php if ($comment->status == 0) : ?>
                                                            <button class="label-status-comment text-warning"
                                                                    style="background-color: #fff3cd">تایید نشده
                                                            </button>
                                                        <?php elseif ($comment->status == 1) : ?>
                                                            <button class="label-status-comment text-success"
                                                                    style="background-color: #d4edda">تایید شده
                                                            </button>
                                                        <?php elseif ($comment->status == -1) : ?>
                                                            <button class="label-status-comment text-danger"
                                                                    style="background-color: #f8d7da">رد شده
                                                            </button>
                                                        <?php endif; ?>
                                                        <div class="card-horizontal-comment-title">
                                                            <a href="/product/detail/<?= $product->id ?>">
                                                                <h3><?= $product->title ?></h3>
                                                            </a>
                                                        </div>
                                                        <div class="card-horizontal-comment">
                                                            <p><?= $comment->content ?></p>
                                                        </div>
                                                        <div class="card-horizontal-product-buttons">
                                                            <form method="post">
                                                                <button name="delete" value="<?= $comment->id ?>"
                                                                        class="btn btn-light">حذف
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach;
                                        else: ?>
                                            <div class="alert alert-danger">
                                                نظری ندارید.
                                            </div>
                                        <?php endif; ?>
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