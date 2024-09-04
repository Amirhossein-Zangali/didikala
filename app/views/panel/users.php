<?php

use didikala\models\Category;
use didikala\models\Favorite;
use didikala\models\Order;
use didikala\models\OrderItem;
use didikala\models\Permission;
use didikala\models\Product;
use didikala\models\User;
use didikala\models\UserPermission;

require_once "../app/bootstrap.php";

if (User::isUserLogin()) {
    if (User::isUser()) {
        redirect('panel/');
    }
} else {
    redirect('pages/login');
}
if (!User::canManageUser())
    redirect('panel/');

$user_detail = false;
if (isset($data['user']))
    $user_detail = $data['user'];

$page_count = User::getPageCount();

$page = $data['page'] ?? 1;
$offset = $data['offset'] ?? 0;

$search = $data['search'] ?? false;
$field = $data['field'] ?? false;

$user = User::where('id', $_SESSION['user_id'])->first();

include '../app/views/inc/header.php';
?>

    <!-- Start main-content -->
    <main class="main-content dt-sl mt-4 mb-3">
        <div class="container main-container">
            <div class="row">

                <?php include_once 'panel-sidebar.php' ?>
                <?php if (!$user_detail): ?>
                    <!-- Start Content -->
                    <div class="col-xl-9 col-lg-8 col-md-8 col-sm-12">
                        <div class="row">
                            <div class="col-12">
                                <div class="section-title text-sm-title title-wide mb-1 no-after-title-wide dt-sl mb-2 px-res-1">
                                    <h2>کاربر ها</h2>
                                    <h6>جست و جو در</h6>
                                    <form method="post" class="d-flex flex-column">
                                        <select class="form-control" name="field">
                                            <option <?= $field == 'id' ? 'selected' : '' ?> value="id">شناسه</option>
                                            <option <?= $field == 'name' ? 'selected' : '' ?> value="name">نام</option>
                                            <option <?= $field == 'username' ? 'selected' : '' ?> value="username">نام
                                                کاربری
                                            </option>
                                            <option <?= $field == 'email' ? 'selected' : '' ?> value="email">ایمیل
                                            </option>
                                            <option <?= $field == 'phone' ? 'selected' : '' ?> value="phone">موبایل
                                            </option>
                                            <option <?= $field == 'role' ? 'selected' : '' ?> value="role">نقش</option>
                                        </select>
                                        <input name="search" value="<?= $search ?? '' ?>" type="text" class="form-control mt-2" placeholder="جست و جو">
                                        <button class="form-control btn-primary mt-2" type="submit">جست و جو</button>
                                    </form>
                                </div>
                                <div class="profile-section dt-sl">
                                    <?php
                                    if ($search && $field)
                                        $users = User::searchByField($field, $search);
                                    else
                                        $users = User::getAllUsers(User::$itemPerPage, $offset);
                                    if ($users->count() > 0) :?>
                                        <div class="table-responsive">
                                            <table class="table table-order">
                                                <thead>
                                                <tr>
                                                    <th><?= $users->count() ?></th>
                                                    <th>نام</th>
                                                    <th>نام کاربری</th>
                                                    <th>ایمیل</th>
                                                    <th>شماره موبایل</th>
                                                    <th>تعداد سفارش ها</th>
                                                    <th>نقش</th>
                                                    <th>جزییات</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php foreach ($users as $index => $user) : ?>
                                                    <tr>
                                                        <td><?= $offset + $index + 1 ?></td>
                                                        <td><?= $user->name ?></td>
                                                        <td><?= $user->username ?></td>
                                                        <td><?= $user->email ?></td>
                                                        <td><?= $user->phone ?? 'ثبت نشده' ?></td>
                                                        <td><?= User::getOrderCount($user->id) ?></td>
                                                        <td><?= User::getRoll($user->id, 1) ?></td>
                                                        <td class="details-link">
                                                            <form method="post" action="/panel/users">
                                                                <button name="id" value="<?= $user->id ?>" class="btn"
                                                                        type="submit"><i
                                                                            class="mdi mdi-chevron-left"></i>
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
                                        <div class="alert alert-danger">کاربری وجود ندارد.</div>
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
                                    <a href="/panel/users/" class="profile-navbar-btn-back">بازگشت</a>
                                </div>
                            </div>
                            <div class="col-md-10 col-sm-12 mx-auto">
                                <?php if (isset($data['err'])) : ?>
                                    <div class="alert alert-danger"><?= $data['err'] ?></div>
                                <?php endif; ?>
                                <?php if (isset($data['pass'])) : ?>
                                    <div class="alert alert-success"><?= $data['pass'] ?></div>
                                <?php endif; ?>
                                <div class="px-3 px-res-0">
                                    <div class="form-ui additional-info dt-sl dt-sn pt-4">
                                        <form method="post">
                                            <div class="form-row-title">
                                                <h3>نام</h3>
                                            </div>
                                            <div class="form-row">
                                                <input disabled value="<?= $user_detail->name ?? '' ?>" type="text"
                                                       class="input-ui pr-2" placeholder="نام خود را وارد نمایید">
                                            </div>
                                            <div class="form-row-title">
                                                <h3>نام کاربری</h3>
                                            </div>
                                            <div class="form-row">
                                                <input disabled value="<?= $user_detail->username ?? '' ?>" type="text"
                                                       class="input-ui pr-2"
                                                       placeholder="نام کاربری خود را وارد نمایید">
                                            </div>
                                            <div class="form-row-title">
                                                <h3 class="d-inline-block">ایمیل</h3>
                                                <span class="text-<?= $user_detail->email_verify > 0 ? 'success' : 'danger' ?>">(<?= $user_detail->email_verify > 0 ? 'فعال شده' : 'فعال نشده' ?>)</span>
                                            </div>
                                            <div class="form-row">
                                                <input disabled value="<?= $user_detail->email ?? '' ?>" type="email"
                                                       class="input-ui pl-2 text-left dir-ltr"
                                                       placeholder="ایمیل خود را وارد نمایید">
                                            </div>
                                            <div class="form-row-title">
                                                <h3>شماره موبایل</h3>
                                            </div>
                                            <div class="form-row">
                                                <input disabled
                                                       value="<?= $user_detail->phone != 0 ? $user_detail->phone : '' ?>"
                                                       type="text" class="input-ui pl-2 text-left dir-ltr"
                                                       placeholder="09151234567">
                                            </div>
                                            <div class="form-row-title">
                                                <h3>آدرس</h3>
                                            </div>
                                            <div class="form-row">
                                                <input disabled value="<?= $user->address ?? '' ?>" type="text"
                                                       class="input-ui pl-2 text-left dir-ltr"
                                                       placeholder="استان/شهر/آدرس">
                                            </div>
                                            <div class="form-row-title">
                                                <h3>کد پستی</h3>
                                            </div>
                                            <div class="form-row">
                                                <input disabled value="<?= $user->post_code ?? '' ?>" type="text"
                                                       class="input-ui pl-2 text-left dir-ltr" placeholder="9999999999">
                                            </div>
                                            <div class="form-row-title">
                                                <h3>تعداد سفارش ها</h3>
                                            </div>
                                            <div class="form-row">
                                                <input disabled value="<?= User::getOrderCount($user->id) ?>"
                                                       type="text" class="input-ui pl-2 text-left dir-ltr"
                                                       placeholder="09151234567">
                                            </div>
                                            <?php if (User::canManagePermission()) : ?>
                                                <div class="form-row-title">
                                                    <h3>نقش</h3>
                                                </div>
                                                <div class="form-row">
                                                    <select name="role" class="form-control">
                                                        <option <?= User::getRoll($user_detail->id) == 'user' ? 'selected' : '' ?>
                                                                value="user">کاربر
                                                        </option>
                                                        <option <?= User::getRoll($user_detail->id) == 'writer' ? 'selected' : '' ?>
                                                                value="writer">نویسنده
                                                        </option>
                                                        <option <?= User::getRoll($user_detail->id) == 'admin' ? 'selected' : '' ?>
                                                                value="admin">ادمین
                                                        </option>
                                                    </select>
                                                </div>
                                                <?php if (!User::isUserUser($user_detail->id)) : ?>
                                                    <div class="form-row-title">
                                                        <h3>اجازه ها</h3>
                                                    </div>
                                                    <div class="form-row">
                                                        <?php $permissions = Permission::getPermissions();
                                                        $user_permissions_id = userPermission::getUserPermissionsId($user_detail->id);
                                                        foreach ($permissions as $permission) : ?>
                                                            <input <?= in_array($permission->id, $user_permissions_id) ? 'checked' : '' ?>
                                                                    class="form-check mx-1"
                                                                    value="<?= $permission->id ?>"
                                                                    id="<?= $permission->name ?>" type="checkbox"
                                                                    name="permission[]">
                                                            <label style="margin-left: 20px"
                                                                   for="<?= $permission->name ?>"><?= str_replace('_', ' ', $permission->name) ?></label>
                                                        <?php endforeach; ?>
                                                    </div>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                            <div class="dt-sl">
                                                <div class="form-row mt-3 justify-content-center">
                                                    <button name="update" value="<?= $user_detail->id ?>" type="submit"
                                                            class="btn-primary-cm btn-with-icon ml-2"><i
                                                                class="mdi mdi-account-circle-outline"></i>
                                                        ثبت اطلاعات کاربری
                                                    </button>
                                                    <button name="delete" value="<?= $user_detail->id ?>" type="submit"
                                                            class="btn-primary-cm btn-with-icon ml-2"><i
                                                                class="mdi mdi-delete"></i>
                                                        حذف کاربر
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