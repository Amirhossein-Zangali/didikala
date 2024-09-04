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

                <!-- Start main-content -->
                <main class="main-content dt-sl mt-4 mb-3">
                    <div class="container main-container">

                        <div class="row">
                            <div class="col-xl-4 col-lg-5 col-md-7 col-12 mx-auto">
                                <?php if (isset($data['err'])) : ?>
                                    <div class="alert alert-danger"><?= $data['err'] ?></div>
                                <?php endif; ?>
                                <div class="form-ui dt-sl dt-sn pt-4">
                                    <div class="section-title title-wide mb-1 no-after-title-wide">
                                        <h2 class="font-weight-bold">فعال سازی ایمیل</h2>
                                    </div>
                                    <div class="message-light">
                                        برای ایمیل <?= $user->email ?> کد فعال سازی ارسال گردید.
                                    </div>
                                    <form method="post">
                                        <div class="form-row-title">
                                            <h3>کد فعال سازی را وارد کنید</h3>
                                        </div>
                                        <div class="form-row">
                                            <div class="numbers-verify">
                                                <div class="lines-number-input">
                                                    <input name="code" type="text" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row mt-3">
                                            <button name="active" type="submit"
                                                    class="form-control btn-outline-primary">فعال سازی
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>
                </main>
                <!-- End main-content -->

            </div>
        </div>
    </main>
    <!-- End main-content -->

<?php include '../app/views/inc/footer.php'; ?>