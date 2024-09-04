<?php

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
                        <div class="col-md-10 col-sm-12 mx-auto">
                            <?php if (isset($data['err'])) : ?>
                                <div class="alert alert-danger"><?= $data['err'] ?></div>
                            <?php endif; ?>
                            <?php if (isset($data['pass'])) : ?>
                                <div class="alert alert-success"><?= $data['pass'] ?></div>
                            <?php endif; ?>
                            <div class="px-3 px-res-0">
                                <div class="section-title text-sm-title title-wide mb-1 no-after-title-wide dt-sl mb-2 px-res-1">
                                    <h2>اطلاعات شخصی</h2>
                                </div>
                                <div class="form-ui additional-info dt-sl dt-sn pt-4">
                                    <form method="post">
                                        <div class="form-row-title">
                                            <h3>نام</h3>
                                        </div>
                                        <div class="form-row">
                                            <input required name="name" value="<?= $user->name ?? '' ?>" type="text" class="input-ui pr-2" placeholder="نام خود را وارد نمایید">
                                        </div>
                                        <div class="form-row-title">
                                            <h3>نام کاربری</h3>
                                        </div>
                                        <div class="form-row">
                                            <input required name="username" value="<?= $user->username ?? '' ?>" type="text" class="input-ui pr-2" placeholder="نام کاربری خود را وارد نمایید">
                                        </div>
                                        <div class="form-row-title">
                                            <h3>ایمیل</h3>
                                        </div>
                                        <div class="form-row">
                                            <input required name="email" value="<?= $user->email ?? '' ?>" type="email" class="input-ui pl-2 text-left dir-ltr" placeholder="ایمیل خود را وارد نمایید">
                                        </div>
                                        <div class="form-row-title">
                                            <h3>شماره موبایل</h3>
                                        </div>
                                        <div class="form-row">
                                            <input required name="phone" value="<?= $user->phone != 0 ? $user->phone : '' ?>" type="text" class="input-ui pl-2 text-left dir-ltr" placeholder="09151234567">
                                        </div>
                                        <hr>
                                        <div class="form-row-title">
                                            <h2>تغییر رمز عبور</h2><h3> (اگر نمی خواهید رمز عبور را تغییر دهید، مقادیر زیر را خالی بگذارید.)</h3>
                                        </div>
                                        <div class="form-row-title">
                                            <h3>رمز عبور</h3>
                                        </div>
                                        <div class="form-row">
                                            <input name="password" type="text" class="input-ui pl-2 text-left dir-ltr" placeholder="رمز عبور خود را وارد نمایید">
                                        </div>

                                        <div class="form-row-title">
                                            <h3>رمز عبور جدید</h3>
                                        </div>
                                        <div class="form-row">
                                            <input name="new_password" type="text" class="input-ui pl-2 text-left dir-ltr" placeholder="رمز عبور جدید خود را وارد نمایید">
                                        </div>

                                        <div class="dt-sl">
                                            <div class="form-row mt-3 justify-content-center">
                                                <button name="update" value="<?= $user->id ?>" type="submit" class="btn-primary-cm btn-with-icon ml-2"><i class="mdi mdi-account-circle-outline"></i>
                                                    ثبت اطلاعات کاربری
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

            </div>
        </div>
    </main>
    <!-- End main-content -->

<?php include '../app/views/inc/footer.php'; ?>