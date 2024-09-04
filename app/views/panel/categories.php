<?php

use didikala\models\Category;
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
if (!User::canManageCategory())
    redirect('panel/');

$category_detail = false;
$add_category = false;
if (isset($data['category']))
    $category_detail = $data['category'];
if (isset($data['add']))
    $add_category = true;

include '../app/views/inc/header.php';

$user = User::where('id', $_SESSION['user_id'])->first();
?>

    <!-- Start main-content -->
    <main class="main-content dt-sl mt-4 mb-3">
        <div class="container main-container">
            <div class="row">

                <?php include_once 'panel-sidebar.php' ?>
                <?php if (!$category_detail && !$add_category): ?>
                <!-- Start Content -->
                <div class="col-xl-9 col-lg-8 col-md-8 col-sm-12">
                    <div class="row">
                        <div class="col-12">
                            <div class="section-title text-sm-title title-wide mb-1 no-after-title-wide dt-sl mb-2 px-res-1">
                                <h2 class="d-inline">دسته بندی ها</h2>
                                <form class="d-inline" method="post" action="/panel/categories">
                                    <button name="id" value="0" class="btn btn-primary"
                                            type="submit">ایجاد
                                    </button>
                                </form>
                            </div>
                            <div class="profile-section dt-sl">
                                <?php
                                $categories = Category::getAllCategories();
                                if ($categories->count() > 0) :?>
                                    <div class="table-responsive">
                                        <table class="table table-order">
                                            <thead>
                                            <tr>
                                                <th><?= $categories->count() ?></th>
                                                <th>عنوان</th>
                                                <th>زیر دسته بندی</th>
                                                <th>ویرایش</th>
                                                <th>حذف</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php foreach ($categories as $index => $category) : ?>
                                                <tr>
                                                    <td><?= $index + 1 ?></td>
                                                    <td><a href="/category/<?= $category->id ?>"><?= $category->title ?></a></td>
                                                    <td><?= Category::getMainCategory($category->id)->title ?? 'اصلی' ?></td>
                                                    <td class="details-link">
                                                        <form method="post">
                                                            <button name="id" value="<?= $category->id ?>" class="btn btn-primary"
                                                                    type="submit">ویرایش
                                                            </button>
                                                        </form>
                                                    </td>
                                                    <td class="details-link">
                                                        <form method="post">
                                                            <button class="btn btn-danger" name="delete"
                                                                    value="<?= $category->id ?>" type="submit">حذف
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php else : ?>
                                    <div class="alert alert-danger">کامنتی یافت نشده است.</div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Content -->
                <?php else : ?>
                    <!-- Start Content -->
                    <div class="col-xl-9 col-lg-8 col-md-8 col-sm-12">
                        <div class="row">
                            <div class="col-12">
                                <div class="profile-navbar">
                                    <a href="/panel/categories/" class="profile-navbar-btn-back">بازگشت</a>
                                </div>
                            </div>
                            <div class="col-md-10 col-sm-12 mx-auto">
                                <div class="px-3 px-res-0">
                                    <div class="form-ui additional-info dt-sl dt-sn pt-4">
                                        <form method="post">
                                            <div class="form-row-title">
                                                <h3>عنوان</h3>
                                            </div>
                                            <div class="form-row">
                                                <input required name="title" value="<?= $category_detail->title ?? '' ?>" type="text"
                                                       class="input-ui pr-2" placeholder="عنوان را وارد نمایید">
                                            </div>
                                            <div class="form-row-title">
                                                <h3>زیر دسته بندی</h3>
                                            </div>
                                            <div class="form-row">
                                                <div class="form-row">
                                                    <select class="form-control" name="sub_cat">
                                                        <option value="0">اصلی</option>
                                                    <?php $categories = Category::getAllMainCategory();
                                                    foreach ($categories as $main_category) : ?>
                                                        <option <?= $category_detail->sub_cat == $main_category->id ? 'selected' : '' ?> value="<?= $main_category->id ?>"><?= $main_category->title ?></option>
                                                    <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="dt-sl">
                                                <div class="form-row mt-3 justify-content-center">
                                                    <button name="update" value="<?= $category_detail->id ?? 0 ?>" type="submit"
                                                            class="btn-primary-cm btn-with-icon ml-2"><i
                                                                class="mdi mdi-account-circle-outline"></i>
                                                        <?= $category_detail ? 'ویرایش' : 'ایجاد' ?> دسته بندی
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Content -->
                <?php endif; ?>

            </div>
        </div>
    </main>
    <!-- End main-content -->

<?php include '../app/views/inc/footer.php'; ?>