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
                                <h2>علاقمندی ها</h2>
                            </div>
                            <div class="dt-sl">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="profile-section dt-sl">
                                            <?php $favorites = Favorite::getFavorites($user->id, 0);
                                            if ($favorites->count() > 0) : ?>
                                                <ul class="d-flex flex-wrap justify-content-start align-items-center">
                                                    <?php foreach (Favorite::getFavorites($user->id) as $product) : ?>
                                                        <li class="list-group-item d-block" style="border: none">
                                                            <a class="text-dark"
                                                               href="/product/detail/<?= $product->id ?>">
                                                                <img width="100" class="img-fluid"
                                                                     src="/public/<?= $product->thumbnail ?>" alt="">
                                                                <span class="mx-3"><?= $product->title ?></span>
                                                            </a>
                                                        </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            <?php else : ?>
                                                <div class="alert alert-danger">لیست علاقه مندی خالی است.</div
                                            <?php endif; ?>
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