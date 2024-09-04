<?php

use didikala\models\Comment;
use didikala\models\Favorite;
use didikala\models\Order;
use didikala\models\Product;
use didikala\models\User;

require_once "../app/bootstrap.php";

if (User::isUserLogin()) {
    if (User::isUser()) {
        redirect('panel/');
    }
} else {
    redirect('pages/login');
}
if (!User::canManageQuestion())
    redirect('panel/');

$page_count = Comment::getQuestionPageCount();

$page = $data['page'] ?? 1;
$offset = $data['offset'] ?? 0;

include '../app/views/inc/header.php';

$user = User::where('id', $_SESSION['user_id'])->first();
?>

    <!-- Start main-content -->
    <main class="main-content dt-sl mt-4 mb-3">
        <div class="container main-container">
            <div class="row">

                <?php include_once 'panel-sidebar.php' ?>

                <!-- Start Content -->
                <div class="col-xl-9 col-lg-8 col-md-8 col-sm-12">
                    <div class="row">
                        <div class="col-12">
                            <div class="section-title text-sm-title title-wide mb-1 no-after-title-wide dt-sl mb-2 px-res-1">
                                <h2>پرسش ها</h2>
                            </div>
                            <div class="profile-section dt-sl">
                                <?php
                                if (User::isUserWriter()) {
                                    $comments = Comment::getWriterQuestions($_SESSION['user_id'], Comment::$itemPerPage, $offset);
                                }
                                if (User::isUserAdmin()) {
                                    $comments = Comment::getAllQuestions(Comment::$itemPerPage, $offset);
                                }
                                if ($comments->count() > 0) :?>
                                    <div class="table-responsive">
                                        <table class="table table-order">
                                            <thead>
                                            <tr>
                                                <th><?= $comments->count() ?></th>
                                                <th>کاربر</th>
                                                <th>محصول</th>
                                                <th>پرسش</th>
                                                <th>پاسخ</th>
                                                <th>تایید</th>
                                                <th>حذف</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php foreach ($comments as $index => $comment) : ?>
                                                <tr>
                                                    <td><?= $offset + $index + 1 ?></td>
                                                    <td><?= User::getUser($comment->user_id)->username ?></td>
                                                    <td><a href="/product/detail/<?= $comment->product_id ?>">محصول</a>
                                                    </td>
                                                    <td><?= $comment->content ?></td>
                                                    <?php if (Comment::haveReply($comment->id)) : ?>
                                                        <td>
                                                            <?= Comment::getReply($comment->id)->content ?>
                                                            <form class="d-inline" method="post" action="/product/question/<?= $comment->product_id ?>">
                                                                <input name="panel" type="hidden">
                                                                <button class="btn btn-primary" name="reply" value="<?= $comment->id ?>" type="submit" >پاسخ</button>
                                                            </form>
                                                        </td>
                                                    <?php else : ?>
                                                        <td>
                                                            <form method="post" action="/product/question/<?= $comment->product_id ?>">
                                                                <input name="panel" type="hidden">
                                                                <button class="btn btn-primary" name="reply" value="<?= $comment->id ?>" type="submit" >پاسخ</button>
                                                            </form>
                                                        </td>
                                                    <?php endif; ?>
                                                    <td>
                                                        <?php if ($comment->status == 0) : ?>
                                                            <form method="post">
                                                                <input name="update" value="<?= $comment->id ?>"
                                                                       type="hidden">
                                                                <button class="btn btn-success" name="status" value="1"
                                                                        type="submit">تایید
                                                                </button>
                                                                <button class="btn btn-danger" name="status" value="-1"
                                                                        type="submit">رد
                                                                </button>
                                                            </form>
                                                        <?php else : ?>
                                                            <form method="post">
                                                                <input name="update" value="<?= $comment->id ?>"
                                                                       type="hidden">
                                                                <?php if ($comment->status == 1) : ?>
                                                                    <button class="btn btn-success" name="status"
                                                                            value="-1" type="submit">تایید شده
                                                                    </button>
                                                                <?php else: ?>
                                                                    <button class="btn btn-danger" name="status"
                                                                            value="1" type="submit">رد شده
                                                                    </button>
                                                                <?php endif; ?>
                                                            </form>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <form method="post">
                                                            <button class="btn btn-danger" name="delete"
                                                                    value="<?= $comment->id ?>" type="submit">حذف
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                            </tbody>
                                        </table>
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

                                <?php else : ?>
                                    <div class="alert alert-danger">پرسشی یافت نشده است.</div>
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