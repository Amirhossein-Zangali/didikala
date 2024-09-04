<?php

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
                                <h2>آدرس ها</h2>
                            </div>
                            <div class="dt-sl">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12">
                                        <?php if (isset($data['err'])) : ?>
                                            <div class="alert alert-danger"><?= $data['err'] ?></div>
                                        <?php endif; ?>
                                        <div class="card-horizontal-address">
                                            <div class="card-horizontal-address-data">
                                                <form method="post">
                                                    <label class="h5" for="address">
                                                        <i class="mdi mdi-map-marker"></i>
                                                        آدرس :
                                                    </label>
                                                    <input class="form-control" name="address" required id="address"
                                                           type="text" value="<?= $user->address ?? '' ?>"
                                                           placeholder="استان/شهر/آدرس">
                                                    <br>
                                                    <label class="h5" for="post_code">
                                                        <i class="mdi mdi-email-outline"></i>
                                                        کدپستی :
                                                    </label>
                                                    <input class="form-control" name="post_code" required id="post_code"
                                                           type="text" value="<?= $user->post_code ?? '' ?>"
                                                               placeholder="9999999999">

                                                    <button class="form-control mt-2 btn-outline-primary" name="update"
                                                            value="<?= $user->id ?>" type="submit">
                                                        <?= $user->address ? 'ویرایش' : 'ثبت' ?>
                                                    </button>
                                                </form>
                                            </div>
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