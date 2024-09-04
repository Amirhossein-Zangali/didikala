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
if (!User::canManageProduct())
    redirect('panel/');

$add_product = false;
$product_detail = false;
if (isset($data['product']))
    $product_detail = $data['product'];
if (isset($data['add']))
    $add_product = true;

$page = $data['page'] ?? 1;
$offset = $data['offset'] ?? 0;
$page_count = Product::getPageCount();

include '../app/views/inc/header.php';

$user = User::where('id', $_SESSION['user_id'])->first();
?>

    <!-- Start main-content -->
    <main class="main-content dt-sl mt-4 mb-3">
        <div class="container main-container">
            <div class="row">

                <?php include_once 'panel-sidebar.php' ?>
                <?php if (!$product_detail && !$add_product): ?>
                    <!-- Start Content -->
                    <div class="col-xl-9 col-lg-8 col-md-8 col-sm-12">
                        <div class="row">
                            <div class="col-12">
                                <div class="section-title text-sm-title title-wide mb-1 no-after-title-wide dt-sl mb-2 px-res-1">
                                    <h2 class="d-inline">محصول ها</h2>
                                    <form class="d-inline" method="post">
                                        <button name="id" value="0" class="btn btn-primary"
                                                type="submit">ایجاد
                                        </button>
                                    </form>
                                </div>
                                <div class="profile-section dt-sl">
                                    <?php
                                    $products = (new Product())->getSearchProducts(offset: $offset, limit: Product::$itemPerPage);
                                    if ($products->count() > 0) :?>
                                        <div class="table-responsive">
                                            <table class="table table-order">
                                                <thead>
                                                <tr>
                                                    <th><?= $products->count() ?></th>
                                                    <th>تصویر</th>
                                                    <th>عنوان</th>
                                                    <th>نویسنده</th>
                                                    <th>دسته بندی</th>
                                                    <th>قیمت</th>
                                                    <th>درصد تخفیف</th>
                                                    <th>تعداد فروش</th>
                                                    <th>تعداد</th>
                                                    <th>ویرایش</th>
                                                    <th>حذف</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php foreach ($products as $index => $product) : ?>
                                                    <tr>
                                                        <td><?= $offset + $index + 1 ?></td>
                                                        <td><img class="img-fluid" width="100"
                                                                 src="/public/<?= $product->thumbnail ?>" alt=""></td>
                                                        <td>
                                                            <a href="/product/detail/<?= $product->id ?>"><?= $product->title ?></a>
                                                        </td>
                                                        <td><?= User::getUser($product->user_id)->username ?></td>
                                                        <?php
                                                        $mainCategory = Product::getMainCategoryProduct($product->category_id);
                                                        $category = Product::getCategoryProduct($product->category_id);
                                                        ?>
                                                        <td><?= isset($mainCategory->title) ? $mainCategory->title . ' / ' : '' ?><?= $category->title ?? '' ?></td>
                                                        <td><?= Product::getPrice($product->id) ?></td>
                                                        <td><?= $product->discount_percent ?>%</td>
                                                        <td><?= $product->sale_count ?></td>
                                                        <td><?= $product->stock ?></td>
                                                        <td class="details-link">
                                                            <form method="post">
                                                                <button name="id" value="<?= $product->id ?>"
                                                                        class="btn btn-primary"
                                                                        type="submit">ویرایش
                                                                </button>
                                                            </form>
                                                        </td>
                                                        <td class="details-link">
                                                            <form method="post">
                                                                <button class="btn btn-danger" name="delete"
                                                                        value="<?= $product->id ?>" type="submit">حذف
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
                                                                <button name="page" value="<?= $i ?>" type="submit"
                                                                        class="btn <?= $i == $page ? 'btn-danger' : ''; ?>"><?= $i ?></button>
                                                            <?php endfor; ?>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    <?php else : ?>
                                        <div class="alert alert-danger">محصولی یافت نشده است.</div>
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
                                    <a href="/panel/products/" class="profile-navbar-btn-back">بازگشت</a>
                                </div>
                            </div>
                            <div class="col-md-10 col-sm-12 mx-auto">
                                <div class="px-3 px-res-0">
                                    <div class="form-ui additional-info dt-sl dt-sn pt-4">
                                        <form method="post" enctype="multipart/form-data">
                                            <div class="form-row-title">
                                                <h3>تصویر</h3>
                                            </div>
                                            <div class="form-row">
                                                <?php if ($product_detail) : ?>
                                                    <img class="img-fluid" width="200"
                                                         src="/public/<?= $product_detail->thumbnail ?>" alt="">
                                                <?php endif; ?>
                                                <input required name="thumbnail" class="form-control" type="file">
                                            </div>
                                            <div class="form-row-title">
                                                <h3>عنوان</h3>
                                            </div>
                                            <div class="form-row">
                                                <input required name="title" value="<?= $product_detail->title ?? '' ?>"
                                                       type="text"
                                                       class="input-ui pr-2" placeholder="عنوان را وارد نمایید">
                                            </div>
                                            <?php if ($product_detail) : ?>
                                                <div class="form-row-title">
                                                    <h3>نویسنده</h3>
                                                </div>
                                                <div class="form-row">
                                                    <input disabled required
                                                           value="<?= User::getUser($product_detail->user_id)->username ?>"
                                                           type="text" class="input-ui pr-2">
                                                </div>
                                            <?php endif; ?>
                                            <div class="form-row-title">
                                                <h3>توضیحات</h3>
                                            </div>
                                            <div class="form-row">
                                                <textarea required name="content" class="input-ui pr-2"
                                                          placeholder="توضیحات را وارد نمایید"><?= $product_detail->content ?? '' ?></textarea>
                                            </div>
                                            <div class="form-row-title">
                                                <h3>دسته بندی</h3>
                                            </div>
                                            <div class="form-row">
                                                <select class="form-control" name="category_id">
                                                    <?php $categories = Category::getAllCategories();
                                                    foreach ($categories as $category) : $mainCategory = Category::getMainCategory($category->id); ?>
                                                        <option <?= @$product_detail->category_id == $category->id ? 'selected' : '' ?>
                                                                value="<?= $category->id ?>"><?= isset($mainCategory->title) ? $mainCategory->title . ' / ' : '' ?> <?= $category->title ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="form-row-title">
                                                <h3>قیمت (ریال)</h3>
                                            </div>
                                            <div class="form-row">
                                                <input required name="price" value="<?= $product_detail->price ?? '' ?>"
                                                       type="number"
                                                       class="input-ui pr-2" placeholder="قیمت را وارد نمایید">
                                            </div>
                                            <div class="form-row-title">
                                                <h3>درصد تخفیف</h3>
                                            </div>
                                            <div class="form-row">
                                                <input required name="discount_percent"
                                                       value="<?= $product_detail->discount_percent ?? '' ?>"
                                                       type="number"
                                                       class="input-ui pr-2" placeholder="درصد تخفیف را وارد نمایید">
                                            </div>
                                            <?php if ($product_detail) : ?>
                                                <div class="form-row-title">
                                                    <h3>تعداد فروش</h3>
                                                </div>
                                                <div class="form-row">
                                                    <input disabled value="<?= $product_detail->sale_count ?>"
                                                           type="number"
                                                           class="input-ui pr-2">
                                                </div>
                                            <?php endif; ?>
                                            <div class="form-row-title">
                                                <h3>تعداد</h3>
                                            </div>
                                            <div class="form-row">
                                                <input required name="stock" value="<?= $product_detail->stock ?? '' ?>"
                                                       type="number"
                                                       class="input-ui pr-2" placeholder="تعداد را وارد نمایید">
                                            </div>
                                            <div class="dt-sl">
                                                <div class="form-row mt-3 justify-content-center">
                                                    <button name="update" value="<?= $product_detail->id ?? 0 ?>"
                                                            type="submit"
                                                            class="btn-primary-cm btn-with-icon ml-2"><i
                                                                class="mdi mdi-account-circle-outline"></i>
                                                        <?= $product_detail ? 'ویرایش' : 'ایجاد' ?> محصول
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                        <?php if ($product_detail) : ?>
                                            <form method="post" class="d-flex justify-content-center">
                                                <button class="btn-primary-cm btn-with-icon ml-2 text-center" name="delete"
                                                        value="<?= $product_detail->id ?>" type="submit"><i
                                                            class="mdi mdi-delete"></i>حذف
                                                </button>
                                            </form>
                                        <?php endif; ?>
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